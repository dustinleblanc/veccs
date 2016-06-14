<?php

/**
 * @file
 * Contains \Drupal\recover_core\OutcomeListBuilder.
 */

namespace Drupal\recover_core;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Outcome entities.
 *
 * @ingroup recover_core
 */
class OutcomeListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Outcome ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\recover_core\Entity\Outcome */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.outcome.edit_form', array(
          'outcome' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
