<?php

/**
 * @file
 * Contains \Drupal\recover_core\SpeciesInterface.
 */

namespace Drupal\recover_core;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Species entities.
 *
 * @ingroup recover_core
 */
interface SpeciesInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.
  /**
   * Gets the Species name.
   *
   * @return string
   *   Name of the Species.
   */
  public function getName();

  /**
   * Sets the Species name.
   *
   * @param string $name
   *   The Species name.
   *
   * @return \Drupal\recover_core\SpeciesInterface
   *   The called Species entity.
   */
  public function setName($name);

  /**
   * Gets the Species creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Species.
   */
  public function getCreatedTime();

  /**
   * Sets the Species creation timestamp.
   *
   * @param int $timestamp
   *   The Species creation timestamp.
   *
   * @return \Drupal\recover_core\SpeciesInterface
   *   The called Species entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Species published status indicator.
   *
   * Unpublished Species are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Species is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Species.
   *
   * @param bool $published
   *   TRUE to set this Species to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\recover_core\SpeciesInterface
   *   The called Species entity.
   */
  public function setPublished($published);

}
