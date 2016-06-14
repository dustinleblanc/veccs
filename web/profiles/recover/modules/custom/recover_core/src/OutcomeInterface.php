<?php

/**
 * @file
 * Contains \Drupal\recover_core\OutcomeInterface.
 */

namespace Drupal\recover_core;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Outcome entities.
 *
 * @ingroup recover_core
 */
interface OutcomeInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.
  /**
   * Gets the Outcome name.
   *
   * @return string
   *   Name of the Outcome.
   */
  public function getName();

  /**
   * Sets the Outcome name.
   *
   * @param string $name
   *   The Outcome name.
   *
   * @return \Drupal\recover_core\OutcomeInterface
   *   The called Outcome entity.
   */
  public function setName($name);

  /**
   * Gets the Outcome creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Outcome.
   */
  public function getCreatedTime();

  /**
   * Sets the Outcome creation timestamp.
   *
   * @param int $timestamp
   *   The Outcome creation timestamp.
   *
   * @return \Drupal\recover_core\OutcomeInterface
   *   The called Outcome entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Outcome published status indicator.
   *
   * Unpublished Outcome are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Outcome is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Outcome.
   *
   * @param bool $published
   *   TRUE to set this Outcome to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\recover_core\OutcomeInterface
   *   The called Outcome entity.
   */
  public function setPublished($published);

}
