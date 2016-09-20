<?php

namespace Drupal\zotero_import\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Research Author entities.
 *
 * @ingroup zotero_import
 */
interface ResearchAuthorInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Research Author name.
   *
   * @return string
   *   Name of the Research Author.
   */
  public function getName();

  /**
   * Sets the Research Author name.
   *
   * @param string $name
   *   The Research Author name.
   *
   * @return \Drupal\zotero_import\Entity\ResearchAuthorInterface
   *   The called Research Author entity.
   */
  public function setName($name);

  /**
   * Gets the Research Author creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Research Author.
   */
  public function getCreatedTime();

  /**
   * Sets the Research Author creation timestamp.
   *
   * @param int $timestamp
   *   The Research Author creation timestamp.
   *
   * @return \Drupal\zotero_import\Entity\ResearchAuthorInterface
   *   The called Research Author entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Research Author published status indicator.
   *
   * Unpublished Research Author are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Research Author is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Research Author.
   *
   * @param bool $published
   *   TRUE to set this Research Author to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\zotero_import\Entity\ResearchAuthorInterface
   *   The called Research Author entity.
   */
  public function setPublished($published);

}
