<?php

namespace Drupal\zotero_import;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface;

/**
 * Defines the storage handler class for Research attachment entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Research attachment entity entities.
 *
 * @ingroup zotero_import
 */
class ResearchAttachmentEntityStorage extends SqlContentEntityStorage implements ResearchAttachmentEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(ResearchAttachmentEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {research_attachment_entity_revision} WHERE id=:id ORDER BY vid',
      array(':id' => $entity->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {research_attachment_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      array(':uid' => $account->id())
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(ResearchAttachmentEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {research_attachment_entity_field_revision} WHERE id = :id AND default_langcode = 1', array(':id' => $entity->id()))
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('research_attachment_entity_revision')
      ->fields(array('langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED))
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
