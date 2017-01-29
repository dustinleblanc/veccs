<?php

namespace Drupal\zotero_import\Entity;

use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Url;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Research attachment entity entities.
 *
 * @ingroup zotero_import
 */
interface ResearchAttachmentEntityInterface extends RevisionableInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Research attachment entity name.
   *
   * @return string
   *   Name of the Research attachment entity.
   */
  public function getName();

  /**
   * Sets the Research attachment entity name.
   *
   * @param string $name
   *   The Research attachment entity name.
   *
   * @return \Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface
   *   The called Research attachment entity entity.
   */
  public function setName($name);

  /**
   * Gets the Research attachment entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Research attachment entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Research attachment entity creation timestamp.
   *
   * @param int $timestamp
   *   The Research attachment entity creation timestamp.
   *
   * @return \Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface
   *   The called Research attachment entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Research attachment entity published status indicator.
   *
   * Unpublished Research attachment entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Research attachment entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Research attachment entity.
   *
   * @param bool $published
   *   TRUE to set this Research attachment entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface
   *   The called Research attachment entity entity.
   */
  public function setPublished($published);

  /**
   * Gets the Research attachment entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Research attachment entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface
   *   The called Research attachment entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Research attachment entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionAuthor();

  /**
   * Sets the Research attachment entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface
   *   The called Research attachment entity entity.
   */
  public function setRevisionAuthorId($uid);

}
