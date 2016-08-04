<?php

namespace Drupal\research_paper_import\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Research paper entities.
 */
class ResearchPaperViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['research_paper']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Research paper'),
      'help' => $this->t('The Research paper ID.'),
    );

    return $data;
  }

}
