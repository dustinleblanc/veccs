<?php


use Step\Acceptance\Authenticated;
use Step\Acceptance\CoChair;

class CoChairManageDomainsCest
{

    public function _before(CoChair $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
        $I->logout();
    }

    // tests
    public function testCoChairCanManageDomains(CoChair $I)
    {
        $I->loginAsCoChair();
        $I->amOnPage('/admin/structure/taxonomy/manage/domain/add');
        $I->fillField('#edit-name-0-value', 'Basic Life Support');
        $I->click('#edit-submit');
        $I->see('Created new term Basic Life Support.');
    }

    public function testAuthenticatedCantManageDomains(Authenticated $I)
    {
        $I->loginAsAuthenticated();
        $I->amOnPage('/admin/structure/taxonomy/manage/domain/add');
        $I->seeResponseCodeIs(403);
    }
}
