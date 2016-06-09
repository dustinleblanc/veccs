<?php
namespace Step\Acceptance;

class CoChair extends \AcceptanceTester
{

    public function loginAsCoChair()
    {
        $I = $this;
        $I->login('testco_chairUser', 'test');
    }

}