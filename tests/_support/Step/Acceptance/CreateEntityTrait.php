<?php
namespace Codeception\Step;

trait CreateEntityTrait
{
    public function createEntity($entityName = '', $entityBasePath = '')
    {
      if (!$entityBasePath) {
        $entityBasePath = "admin/structure/";
      }
      $I = $this;
      $I->amOnPage("{$entityBasePath}{$entityName}/add");
      $I->mockAllFields();
    }

    public function mockAllFields()
    {
      
    }
}
