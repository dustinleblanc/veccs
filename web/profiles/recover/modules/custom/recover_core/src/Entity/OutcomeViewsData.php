<?php

/**
 * @file
 * Contains \Drupal\recover_core\Entity\Outcome.
 */

namespace Drupal\recover_core\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Outcome entities.
 */
class OutcomeViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['outcome']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Outcome'),
      'help' => $this->t('The Outcome ID.'),
    );

    return $data;
  }

}
