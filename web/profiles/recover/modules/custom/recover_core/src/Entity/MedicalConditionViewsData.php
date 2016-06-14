<?php

/**
 * @file
 * Contains \Drupal\recover_core\Entity\MedicalCondition.
 */

namespace Drupal\recover_core\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Medical Condition entities.
 */
class MedicalConditionViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['medical_condition']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Medical Condition'),
      'help' => $this->t('The Medical Condition ID.'),
    );

    return $data;
  }

}
