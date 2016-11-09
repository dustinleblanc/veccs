<?php

/**
 * @file
 */

/**
 *
 */
class CoChairManageDomainsCest {
  /**
   * @var Faker\Generator
   */
  protected $faker;

  /**
   * @param \AcceptanceTester $I
   */
  public function _before(AcceptanceTester $I) {
    $this->faker = Faker\Factory::create();
  }

  /**
   *
   */
  public function _after(AcceptanceTester $I) {
  }

  /**
   * @param \AcceptanceTester $I
   */
  public function testCoChairCanManageDomains(AcceptanceTester $I) {
    $term = $this->faker->text(60);
    $I->loginAs('co_chair');
    $I->amOnPage('/admin/structure/taxonomy/manage/domain/add');
    $I->fillField('#edit-name-0-value', $term);
    $I->click('#edit-submit');
    $I->see("Created new term {$term}.");
    $I->logout();
  }

  /**
   * @param \AcceptanceTester $I
   */
  public function testAuthenticatedCantManageDomains(AcceptanceTester $I) {
    $I->loginAs('authenticated');
    $I->amOnPage('/admin/structure/taxonomy/manage/domain/add');
    $I->see('Access Denied');
  }

}
