<?php
namespace Drupal\zotero_import\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AppendCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\system\MachineNameController;
use Drupal\user\Entity\User;
use Drupal\zotero_import\Entity\ResearchAuthor;
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
    $data   = $request->query->get('item')['data'];
    $author = $this->extractAuthors($data);
    $creators = ResearchAuthor::create();
    $values = $this->fieldify($data);
    $entity = ResearchReferenceEntity::create($values);
    if ($entity->validate()) {
      $entity->save();
      $message = '<p class="alert alert-success alert-dismissable" role="alert">Item successfully imported!</p>';
    }
    else {
      $message = '<p class="alert alert-danger alert-dismissable" role="alert">Unable to import!</p>';
    }
    $response = new AjaxResponse();
    $response->addCommand(new ReplaceCommand("#zotero-item-{$values['zoteroKey']} .zotero-item__import", $message));
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
                         ->condition('zoteroKey', $item['key'], '=')
                         ->execute()
    );
  }

  /**
   * Format incoming values from Zotero API for saving into database.
   *
   * @param array $values
   *
   * @return array
   */
  private function fieldify(array $values = []) {
    $rekeyed_values = ['type' => 'pubmed'];
    foreach ($values as $key => $value) {
      $rekeyed_values['zotero' . ucfirst($key)] = $value;
    }
    return $rekeyed_values;
  }

  /**
   * Extracts the author metadata of a Zotero item data set.
   *
   * @param $data
   *
   * @return array
   */
  private function extractAuthors($data) {

    return [];
  }
}
