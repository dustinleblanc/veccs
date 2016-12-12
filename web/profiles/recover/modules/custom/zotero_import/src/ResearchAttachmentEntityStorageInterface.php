<?php

namespace Drupal\zotero_import;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface ResearchAttachmentEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Research attachment entity revision IDs for a specific Research attachment entity.
   *
   * @param \Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface $entity
   *   The Research attachment entity entity.
   *
   * @return int[]
   *   Research attachment entity revision IDs (in ascending order).
   */
  public function revisionIds(ResearchAttachmentEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Research attachment entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Research attachment entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface $entity
   *   The Research attachment entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(ResearchAttachmentEntityInterface $entity);

  /**
   * Unsets the language for all Research attachment entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
