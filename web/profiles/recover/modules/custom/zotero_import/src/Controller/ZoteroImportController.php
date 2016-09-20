<?php
namespace Drupal\zotero_import\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AppendCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Drupal\zotero_import\Entity\ResearchReferenceEntity;
use DustinLeblanc\Zotero\Client;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Psr7\Response;

/**
 * Provides actions to view and import items from Zotero collections.
 *
 * Class ZoteroImportController
 * @package Drupal\zotero_import\Controller
 */
class ZoteroImportController extends ControllerBase {
  const FIELD_KEY = [
    'key' => 'field_zotero_key',
    'itemType' => '',
    'title' => '',
    'creators' => '',
    'abstractNote' => '',
    'publicationTitle' => '',
    'volume' => '',
    'issue' => '',
    'pages' => '',
    'date' => '',
    'series' => '',
    'seriesTitle' => '',
    'seriesText' => '',
    'journalAbbreviation' => '',
    'language' => '',
    'DOI' => '',
    'ISSN' => '',
    'shortTitle' => '',
    'url' => '',
    'accessDate' => '',
    'archive' => '',
    'archiveLocation' => '',
    'libraryCatalog' => '',
    'callNumber' => '',
    'rights' => '',
    'extra' => '',
    'tags' => '',
    'dateAdded' => '',
    'dateModified' => ''
  ];
  /**
   * @var Response Response object returned from Zotero API.
   */
  private $response;

  /**
   * Fetch the entire library of the current user.
   * @return array
   */
  public function fetchLibrary() {
    $user = $this->loadCurrentUser();
    $this->fetchItems(
      new Client(
        [
          'apiKey' => $user->get('field_zotero_api_key')->value
        ]
      ),
      $user->get('field_zotero_user_id')->value
    );
    return $this->convertResponse($this->getResponse());
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
    $values = $request->query->get('item');
//    $fields = $this->fieldify($values);
    $entity = new ResearchReferenceEntity([], 'research_reference_entity', 'pubmed');

    if ($entity->validate()) {
//      $entity->save();
      $message = '<span>Item successfully imported!</span>';
    }
    else {
      $message = '<span>Unable to import!</span>';
    }
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand("#zotero-item-{$values['key']} .zotero-item__import", $message));
    return $response;
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
   * Retrieve Zotero API response.
   *
   * @return Response
   */
  public function getResponse() {
    return $this->response;
  }

  /**
   * Fetch a User's entire Zotero library.
   *
   * @param Client $client
   * @param $zotero_user_id
   *
   * @return \Drupal\zotero_import\Controller\ZoteroImportController
   */
  private function fetchItems(Client $client, $zotero_user_id = '') {
    return $this->setResponse($client->get("users/{$zotero_user_id}/items"));
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
  private function convertResponse(Response $response) {
    $contents = json_decode($response->getBody()->getContents(), true);
    return array_filter($contents, function($item) {
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
   * @return bool
   */
  private function needsImport($item) {
    return !empty(\Drupal::entityQuery('research_reference_entity')
      ->condition('field_zotero_item_key', $item['key'], '=')
      ->execute()
    );
  }

  private function fieldify(array $values) {
//    $fields = [];
//    foreach ($values['data'] as $value) {
//      if (array_key_exists(array_keys($value), self::FIELD_KEY))
//      $fields[self::FIELD_KEY[array_keys($value)]] = $value
//    }
  }
}
