<?php
use Codeception\Scenario;
use Drupal\user\Entity\User;

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
class AcceptanceTester extends \Codeception\Actor {
  use _generated\AcceptanceTesterActions;

  /**
   * @var User
   */
  protected $user;

  /**
   * @var string
   */
  protected $userName;

  /**
   * AcceptanceTester constructor.
   *
   * @param \Codeception\Scenario $scenario
   */
  public function __construct(Scenario $scenario) {
    parent::__construct($scenario);
    $dotenv = new \Dotenv\Dotenv(dirname(__DIR__, 2));
    $dotenv->load();

  }

  /**
   * Login as a test user.
   *
   * @param string $role
   */
  public function loginAs($role = '') {
    $I = $this;
    $I->setUserName("test{$role}User");
    $I->amOnPage('/user/login');
    $I->fillField('//*[@id="edit-name"]', $this->userName);
    $I->fillField('//*[@id="edit-pass"]', 'test');
    $I->click('//*[@id="edit-submit"]');
    $I->setUser($this->fetchUser($this->getUserName()));
  }

  public function logout() {
    $this->amOnPage('/user/logout');
  }

  /**
   * @param string $userName
   *
   * @return AcceptanceTester
   */
  public function setUserName($userName) {
    $this->userName = $userName;
    return $this;
  }

  /**
   * @return string
   */
  public function getUserName() {
    return $this->userName;
  }

  /**
   * @param string $userName
   *
   * @return User
   */
  public function fetchUser($userName = '') {
    $result = \Drupal::entityQuery('user')
                     ->condition('name', $userName, '=')
                     ->execute();

    return User::load(array_pop($result));
  }

  public function haveFields(array $fields) {
    array_map(function ($value, $key) {
      return $this->user->set($key, $value);
    }, $fields, array_keys($fields));
    $this->user->save();
  }

  /**
   * @param User $user
   *
   * @return AcceptanceTester
   */
  public function setUser(User $user) {
    $this->user = $user;
    return $this;
  }

  /**
   * @return \Drupal\user\Entity\User
   */
  public function getUser() {
    return $this->user;
  }
}
