<?php
namespace Step\Acceptance;

class InfoSpecialist extends \AcceptanceTester
{

    public function loginAsInfoSpecialist()
    {
        $I = $this;
        $I->login('testinformation_specialistUser', 'test');
    }

}
