<?php

namespace Drupal\recover_core\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Research Evaluation entities.
 */
class ResearchEvalutationEntityViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['research_evalutation_entity']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Research Evaluation'),
      'help' => $this->t('The Research Evaluation ID.'),
    );

    return $data;
  }

}
