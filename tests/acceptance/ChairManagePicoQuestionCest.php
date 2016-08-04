<?php


use Step\Acceptance\CoChair;

class ChairManagePicoQuestionCest
{
  /**
   * @var Faker\Generator
   */
  protected $faker;

  public function _before(AcceptanceTester $I)
    {
      $this->faker = Faker\Factory::create();
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function testCoChairCanManageSpecies(CoChair $I)
    {
        $I->loginAsCoChair();
        $I->amOnPage('/admin/structure/species/add');
        $I->fillField('#edit-name-0-value', $this->faker->text(60));
        $I->click('#edit-submit');
        $I->seeResponseCodeIs(200);
    }
}
