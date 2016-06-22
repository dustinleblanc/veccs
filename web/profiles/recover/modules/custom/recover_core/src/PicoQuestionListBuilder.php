<?php

/**
 * @file
 * Contains \Drupal\recover_core\PicoQuestionListBuilder.
 */

namespace Drupal\recover_core;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of PICO Question entities.
 *
 * @ingroup recover_core
 */
class PicoQuestionListBuilder extends EntityListBuilder {
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
    /* @var $entity \Drupal\recover_core\Entity\PicoQuestion */
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.pico_question.canonical', array(
          'pico_question' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
