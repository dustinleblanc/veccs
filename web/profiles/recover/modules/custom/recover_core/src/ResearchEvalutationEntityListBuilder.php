<?php

namespace Drupal\recover_core;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Research Evaluation entities.
 *
 * @ingroup recover_core
 */
class ResearchEvalutationEntityListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Research Evaluation ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\recover_core\Entity\ResearchEvalutationEntity */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.research_evalutation_entity.edit_form', array(
          'research_evalutation_entity' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
