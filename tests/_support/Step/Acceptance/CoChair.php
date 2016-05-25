<?php
namespace Step\Acceptance;

class CoChair extends \AcceptanceTester
{

    public function loginAsCoChair()
    {
        $I = $this;
        $I->login('TestCoChair', 'test');
    }

}