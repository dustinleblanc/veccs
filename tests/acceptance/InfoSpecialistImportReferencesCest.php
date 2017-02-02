<?php

class InfoSpecialistImportReferencesCest {
    private $itemID;
    private $itemTitle;

    public function _before(AcceptanceTester $I) {
        $this->itemID = '5BPT7QEN';
        $this->itemTitle = 'Cardiopulmonary arrest in a cat as a result of a suspected anaphylactic reaction to an intravenously administered iodinated contrast agent';
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
        $I->amOnPage('/dashboard');
        $I->click('#edit-fetch-all');
        $I->waitForElementVisible('#zotero-collections', 30);
        $I->click("//*[@id=\"zotero-item-{$this->itemID}\"]/a");
        $I->waitForElementVisible(".alert-success");
        $I->see("{$this->itemTitle} imported!");
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
