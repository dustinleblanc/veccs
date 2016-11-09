<?php

use VCR\VCR;

class InfoSpecialistImportReferencesCest {
  public function _before(AcceptanceTester $I) {
    $I->loginAs('information_specialist');
    $I->haveFields([
      'field_zotero_user_id' => getenv('TEST_ZOTERO_USER_ID'),
      'field_zotero_api_key' => getenv('TEST_ZOTERO_API_KEY')
    ]);
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
    $I->click('//*[@id="zotero-item-5BPT7QEN"]/a');
    $I->waitForElementVisible('//*[@id="success-zotero-5BPT7QEN"]');
    $I->see('Item successfully imported!');

    VCR::eject();
    VCR::turnOff();
  }
}
