<?php

namespace Drupal\zotero_import\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Research Attachment entities.
 *
 * @ingroup zotero_import
 */
interface ResearchAttachmentEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Research Attachment name.
   *
   * @return string
   *   Name of the Research Attachment.
   */
  public function getName();

  /**
   * Sets the Research Attachment name.
   *
   * @param string $name
   *   The Research Attachment name.
   *
   * @return \Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface
   *   The called Research Attachment entity.
   */
  public function setName($name);

  /**
   * Gets the Research Attachment creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Research Attachment.
   */
  public function getCreatedTime();

  /**
   * Sets the Research Attachment creation timestamp.
   *
   * @param int $timestamp
   *   The Research Attachment creation timestamp.
   *
   * @return \Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface
   *   The called Research Attachment entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Research Attachment published status indicator.
   *
   * Unpublished Research Attachment are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Research Attachment is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Research Attachment.
   *
   * @param bool $published
   *   TRUE to set this Research Attachment to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface
   *   The called Research Attachment entity.
   */
  public function setPublished($published);

}
