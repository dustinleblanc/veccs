<?php

use VCR\VCR;

class InfoSpecialistImportReferencesCest {
  private $itemID;

  public function _before(AcceptanceTester $I) {
    $this->itemID = '5BPT7QEN';
    $I->loginAs('information_specialist');

    $I->haveFields([
      'field_zotero_user_id' => getenv('TEST_ZOTERO_USER_ID'),
      'field_zotero_api_key' => getenv('TEST_ZOTERO_API_KEY')
    ]);
    $this->ensureItemNotInDb($this->itemID);
  }

  public function _after(AcceptanceTester $I) {
  }

  /**
   * @param \AcceptanceTester $I
   */
  public function testInfoSpecialistImportReferencePersonalLibrary(AcceptanceTester $I) {
    VCR::turnOn();
    VCR::insertCassette('acceptance_info_specialist_fetch_library');

    $I->amOnPage('/dashboard');
    $I->click('#edit-fetch-all');
    $I->waitForElementVisible('#zotero-collections', 30);
    $I->click("//*[@id=\"zotero-item-{$this->itemID}\"]/a");
    $I->waitForElementVisible("//*[@id=\"success-zotero-{$this->itemID}\"]");
    $I->see('Item successfully imported!');

    VCR::eject();
    VCR::turnOff();
  }

  protected function ensureItemNotInDb($id) {
    $result = \Drupal::entityQuery('research_reference_entity')
      ->condition('zoteroKey', $id, '=')
      ->execute();
    if (!empty($result)) {
      $item = \Drupal\zotero_import\Entity\ResearchReferenceEntity::load(array_pop($result));
      $item->delete();
    }
  }
}
