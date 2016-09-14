<?php

namespace Drupal\zotero_import\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Research Attachment entities.
 */
class ResearchAttachmentEntityViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['research_attachment_entity']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Research Attachment'),
      'help' => $this->t('The Research Attachment ID.'),
    );

    return $data;
  }

}
