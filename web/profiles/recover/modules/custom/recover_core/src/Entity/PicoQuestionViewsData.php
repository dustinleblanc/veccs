<?php

/**
 * @file
 * Contains \Drupal\recover_core\Entity\PicoQuestion.
 */

namespace Drupal\recover_core\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for PICO Question entities.
 */
class PicoQuestionViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['pico_question']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('PICO Question'),
      'help' => $this->t('The PICO Question ID.'),
    );

    return $data;
  }

}
