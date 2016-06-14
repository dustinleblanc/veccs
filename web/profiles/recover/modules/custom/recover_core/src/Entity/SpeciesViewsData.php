<?php

/**
 * @file
 * Contains \Drupal\recover_core\Entity\Species.
 */

namespace Drupal\recover_core\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Species entities.
 */
class SpeciesViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['species']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Species'),
      'help' => $this->t('The Species ID.'),
    );

    return $data;
  }

}
