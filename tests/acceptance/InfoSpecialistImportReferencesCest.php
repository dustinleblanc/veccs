<?php


use Step\Acceptance\InfoSpecialist;

class InfoSpecialistImportReferencesCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function testInfoSpecialistCanImportResearchReferenceFromPersonalLibrary(InfoSpecialist $I)
    {
      $I->amOnPage('/dashboard');
      $I->click('#edit-fetch-all');
    }
}
