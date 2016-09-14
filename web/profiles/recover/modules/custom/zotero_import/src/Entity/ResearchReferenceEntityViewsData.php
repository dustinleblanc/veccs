<?php

namespace Drupal\zotero_import\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Research Reference entities.
 */
class ResearchReferenceEntityViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['research_reference_entity']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Research Reference'),
      'help' => $this->t('The Research Reference ID.'),
    );

    return $data;
  }

}
