<?php

namespace Drupal\zotero_import\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Research Author entities.
 */
class ResearchAuthorViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['research_author']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Research Author'),
      'help' => $this->t('The Research Author ID.'),
    );

    return $data;
  }

}
