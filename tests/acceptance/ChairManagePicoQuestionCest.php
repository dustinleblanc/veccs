<?php


use Step\Acceptance\CoChair;

class ChairManagePicoQuestionCest
{
  /**
   * @var Faker\Generator
   */
  protected $faker;

  public function _before(CoChair $I)
    {
      $this->faker = Faker\Factory::create();
    }

    public function _after(CoChair $I)
    {
    }

    // tests
    public function testCoChairCanManageSpecies(CoChair $I)
    {
        $species = $this->faker->text(60);
        $I->loginAsCoChair();
        $I->amOnPage('/admin/structure/species/add');
        $I->fillField('#edit-name-0-value', $species);
        $I->click('#edit-submit');
        $I->see("Created the {$species} Species.");
    }
}
