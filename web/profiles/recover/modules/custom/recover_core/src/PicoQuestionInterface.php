<?php

/**
 * @file
 * Contains \Drupal\recover_core\PicoQuestionInterface.
 */

namespace Drupal\recover_core;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining PICO Question entities.
 *
 * @ingroup recover_core
 */
interface PicoQuestionInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.
  /**
   * Gets the PICO Question name.
   *
   * @return string
   *   Name of the PICO Question.
   */
  public function getName();

  /**
   * Sets the PICO Question name.
   *
   * @param string $name
   *   The PICO Question name.
   *
   * @return \Drupal\recover_core\PicoQuestionInterface
   *   The called PICO Question entity.
   */
  public function setName($name);

  /**
   * Gets the PICO Question creation timestamp.
   *
   * @return int
   *   Creation timestamp of the PICO Question.
   */
  public function getCreatedTime();

  /**
   * Sets the PICO Question creation timestamp.
   *
   * @param int $timestamp
   *   The PICO Question creation timestamp.
   *
   * @return \Drupal\recover_core\PicoQuestionInterface
   *   The called PICO Question entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the PICO Question published status indicator.
   *
   * Unpublished PICO Question are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the PICO Question is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a PICO Question.
   *
   * @param bool $published
   *   TRUE to set this PICO Question to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\recover_core\PicoQuestionInterface
   *   The called PICO Question entity.
   */
  public function setPublished($published);

}
