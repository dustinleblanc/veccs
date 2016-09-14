<?php

namespace Drupal\zotero_import;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Research Attachment entities.
 *
 * @ingroup zotero_import
 */
class ResearchAttachmentEntityListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Research Attachment ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\zotero_import\Entity\ResearchAttachmentEntity */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.research_attachment_entity.edit_form', array(
          'research_attachment_entity' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
