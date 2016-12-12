<?php
namespace Drupal\zotero_import\Importer;


class Importer {
  protected $contents;
  /**
   * @var \DustinLeblanc\Zotero\Client
   */
  protected $client;
  protected $prefix;
  /**
   * @var Response Response object returned from Zotero API.
   */
  private $response;

  /**
   * Fetch the entire library of the current user.
   * @return array
   */
  public function fetchGroups() {
    $zotero_user_id = $this->loadCurrentUser()
                           ->get('field_zotero_user_id')->value;
    $this->setResponse($this->client->get("users/{$zotero_user_id}/groups"));
    return $this->convertGroupsResponse();
  }

  /**
   * @param Request $request
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function fetchGroupItems(Request $request) {
    $id = $request->query->get('group_id');
    $this->setPrefix("/groups/$id");
    $this->fetchItems($this->client);

    $items            = $this->convertItemsResponse($this->getResponse());
    $renderable_items = [
      '#theme' => 'zotero_collection',
      '#type' => 'element',
      'elements' => $items,
    ];
    $markup           = \Drupal::service('renderer')->render($renderable_items);
    $response         = new AjaxResponse();
    return $response->addCommand(new ReplaceCommand('#zotero-groups', $markup));
  }

  /**
   * Fetch the entire library of the current user.
   * @return array
   */
  public function fetchLibrary() {
    $id = $this->loadCurrentUser()->get('field_zotero_user_id')->value;
    $this->setPrefix("/users/{$id}");
    $this->fetchItems($this->client);
    return $this->convertItemsResponse($this->getResponse());
  }

  /**
   * Create a Research Reference Entity from import data.
   *
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   */
  public function importItem(Request $request) {
    $data            = $request->query->get('item');

    $authors         = $this->extractAuthors($data['data']);
    $attachments = $this->client->get($data['links']['self']['href'] . '/children')->getBody()->getContents();

    $author_entities = array_map([$this, 'findOrCreateResearchAuthor'], $authors);
    // Once we've pulled the authors we don't want them clogging up the data.
    unset($data['creators']);

    $values                   = $this->fieldify($data['data'], $author_entities);
    $message                  = $this->createResearchReference($values, json_decode($attachments, TRUE));

    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand("#zotero-item-{$values['zoteroKey']} .zotero-item__import",
      $message));
    return $response;
  }

  /**
   * Retrieve Zotero API response.
   *
   * @return Response
   */
  public function getResponse() {
    return $this->response;
  }

  /**
   * Set the Zotero API response.
   *
   * @param Response $response
   *
   * @return ZoteroImportController
   */
  protected function setResponse(Response $response) {
    $this->response = $response;
    return $this;
  }

  /**
   * @param mixed $prefix
   *
   * @return ZoteroImportController
   */
  public function setPrefix($prefix) {
    $this->prefix = $prefix;
    return $this;
  }

  /**
   * @return mixed
   */
  public function getPrefix() {
    return $this->prefix;
  }

  /**
   * Fetch a User's entire Zotero library.
   *
   * @param Client $client
   * @param $zotero_user_id
   *
   * @return \Drupal\zotero_import\Controller\ZoteroImportController
   */
  private function fetchItems(Client $client) {
    $prefix = $this->getPrefix();
    return $this->setResponse($client->get("{$prefix}/items"));
  }

  /**
   * Retrieve the current user.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   */
  private function loadCurrentUser() {
    return User::load(\Drupal::currentUser()->id());
  }

  /**
   * Ensure that the loaded user has both an api key and user id set in their profile.
   *
   * @param User $user
   *
   * @return bool
   */
  private function validZoteroUser(User $user) {
    return !$user->get('field_zotero_user_id')
                 ->isEmpty() && !$user->get('field_zotero_api_key')->isEmpty();
  }

  /**
   * Convert API Response to a renderable array.
   *
   * @param Response $response
   *
   * @return array
   */
  private function convertItemsResponse(Response $response) {
    $this->contents = json_decode($response->getBody()->getContents(), TRUE);
    return array_filter($this->contents, function ($item) {
      // We only want to return top level items, we can retrieve children manually.
      if (!isset($item['data']['parentItem']) || $this->needsImport($item)) {
        return $item;
      }
    });
  }

  /**
   * Check if an item has already been imported.
   *
   * @param $item
   *
   * @return bool
   */
  private function needsImport($item) {
    return !empty(\Drupal::entityQuery('research_reference_entity')
                         ->condition('zoteroKey', $item['key'], '=')
                         ->execute()
    );
  }

  /**
   * Format incoming values from Zotero API for saving into database.
   *
   * @param array $values
   *
   * @param array $author_entities
   *
   * @return array
   */
  private function fieldify(array $values = [], array $author_entities = []) {
    $rekeyed_values = ['type' => 'pubmed'];
    foreach ($values as $key => $value) {
      $rekeyed_values['zotero' . ucfirst($key)] = $value;
    }
    $rekeyed_values['zoteroCreators'] = $author_entities;
    return $rekeyed_values;
  }

  /**
   * Extracts the author metadata of a Zotero item data set.
   *
   * @param array $data Data returned from Zotero API call.
   *
   * @return array
   */
  private function extractAuthors(array $data = []) {
    $authors = [];
    if (array_key_exists('creators', $data)) {
      $authors = $data['creators'];
    }
    return $authors;
  }

  /**
   * Retrieve an author by name if exists, create if not.
   *
   * @param array $author
   *
   * @return \Drupal\zotero_import\Entity\ResearchAuthor
   */
  private function findOrCreateResearchAuthor(array $author = []) {
    if (array_key_exists('firstName',
        $author) && array_key_exists('lastName', $author)
    ) {
      $full_name = $author['firstName'] . ' ' . $author['lastName'];
      $result    = \Drupal::entityQuery('research_author')
                          ->condition('name', $full_name, '=')
                          ->execute();
      if (!empty($result)) {
        return array_pop($result);
      }
      else {
        $values = [
          'name' => $full_name
        ];
        $author = ResearchAuthor::create($values);
        if ($author->validate()) {
          $author->save();
        }
        return $author;
      }
    }
    else {
      return ResearchAuthor::create([]);
    }
  }

  /**
   * Create a research reference.
   * @param array $values
   *
   * @return string
   */
  private function createResearchReference(array $values = [], array $children = []) {
    try {
      $entity = ResearchReferenceEntity::create($values);
      $entity->save();
      array_map([ResearchAttachmentEntity::class, 'create'], $children);
      $title = $entity->getTitle();
      drupal_set_message("{$title} imported!", 'status');
    } catch (EntityStorageException $e) {
      drupal_set_message($e->getMessage(), 'error');
    }
    return StatusMessages::renderMessages(NULL);
  }

  /**
   * Convert Groups API call to a renderable array.
   * @return array
   */
  private function convertGroupsResponse() {
    return array_map(
      function($data) {
        return [
          '#id' => $data['data']['id'],
          '#name' => $data['data']['name']
        ];
      },
      json_decode($this->getResponse()->getBody()->getContents(), TRUE)
    );
  }

  private function fetchChildren($id) {
    $prefix = $this->getPrefix();
    return json_decode(
      $this->client->get("{$prefix}/items/{$id}/children"),
      TRUE
    );
  }
}
