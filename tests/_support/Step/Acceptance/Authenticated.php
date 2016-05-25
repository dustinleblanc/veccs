<?php
namespace Step\Acceptance;

class Authenticated extends \AcceptanceTester
{

    public function loginAsAuthenticated()
    {
        $I = $this;
        $I->login('authenticated', 'test');
    }

}