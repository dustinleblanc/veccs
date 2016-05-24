<?php
namespace Step\Acceptance;

class CoChair extends \AcceptanceTester
{

    public function loginAsCoChair()
    {
        $I = $this;
        $I->amOnPage('/user/login');
        $I->fillField('//*[@id="edit-name"]', 'TestCoChair');
        $I->fillField('//*[@id="edit-pass"]', 'test');
        $I->click('//*[@id="edit-submit"]');
    }

}