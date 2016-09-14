<?php

namespace Drupal\zotero_import\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Research Reference entities.
 *
 * @ingroup zotero_import
 */
interface ResearchReferenceEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Research Reference type.
   *
   * @return string
   *   The Research Reference type.
   */
  public function getType();

  /**
   * Gets the Research Reference title.
   *
   * @return string
   *   Title of the Research Reference.
   */
  public function getTitle();

  /**
   * Sets the Research Reference title.
   *
   * @param string $title
   *   The Research Reference title.
   *
   * @return \Drupal\zotero_import\Entity\ResearchReferenceEntityInterface
   *   The called Research Reference entity.
   */
  public function setTitle($title);

  /**
   * Gets the Research Reference creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Research Reference.
   */
  public function getCreatedTime();

  /**
   * Sets the Research Reference creation timestamp.
   *
   * @param int $timestamp
   *   The Research Reference creation timestamp.
   *
   * @return \Drupal\zotero_import\Entity\ResearchReferenceEntityInterface
   *   The called Research Reference entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Research Reference published status indicator.
   *
   * Unpublished Research Reference are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Research Reference is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Research Reference.
   *
   * @param bool $published
   *   TRUE to set this Research Reference to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\zotero_import\Entity\ResearchReferenceEntityInterface
   *   The called Research Reference entity.
   */
  public function setPublished($published);

}
