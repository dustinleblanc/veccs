<?php

/**
 * @file
 */

use Faker\Factory;

/**
 *
 */
class ChairManagePicoQuestionCest {
  /**
   * @var Faker\Generator
   */
  protected $faker;

  /**
   * @param \AcceptanceTester $I
   */
  public function _before(\AcceptanceTester $I) {
    $this->faker = Factory::create();
    $I->loginAs('co_chair');
  }

  /**
   * @param \AcceptanceTester $I
   */
  public function _after(\AcceptanceTester $I) {
    $I->logout();
  }

  /**
   * @param \AcceptanceTester $I
   */
  public function testCoChairCanManageSpecies(AcceptanceTester $I) {
    $species = $this->faker->text(30);
    $I->amOnPage('/admin/structure/species/add');
    $I->fillField('#edit-name-0-value', $species);
    $I->click('#edit-submit');
    $I->see("Created the {$species} Species.");
  }
}
