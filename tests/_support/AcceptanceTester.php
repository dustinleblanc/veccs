<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

   /**
    * Define custom actions here
    */
    public function login($userName, $pass)
    {
        $I = $this;
        $I->amOnPage('/user/login');
        $I->fillField('//*[@id="edit-name"]', $userName);
        $I->fillField('//*[@id="edit-pass"]', $pass);
        $I->click('//*[@id="edit-submit"]');
    }

    public function logout()
    {
        $this->amOnPage('/user/logout');
    }
}
