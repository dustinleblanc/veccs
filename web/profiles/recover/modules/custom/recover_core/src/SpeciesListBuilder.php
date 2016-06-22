<?php

/**
 * @file
 * Contains \Drupal\recover_core\SpeciesListBuilder.
 */

namespace Drupal\recover_core;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Species entities.
 *
 * @ingroup recover_core
 */
class SpeciesListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\recover_core\Entity\Species */
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.species.canonical', array(
          'species' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
