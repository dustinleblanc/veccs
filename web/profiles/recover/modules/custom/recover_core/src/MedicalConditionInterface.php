<?php

/**
 * @file
 * Contains \Drupal\recover_core\MedicalConditionInterface.
 */

namespace Drupal\recover_core;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Medical Condition entities.
 *
 * @ingroup recover_core
 */
interface MedicalConditionInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.
  /**
   * Gets the Medical Condition name.
   *
   * @return string
   *   Name of the Medical Condition.
   */
  public function getName();

  /**
   * Sets the Medical Condition name.
   *
   * @param string $name
   *   The Medical Condition name.
   *
   * @return \Drupal\recover_core\MedicalConditionInterface
   *   The called Medical Condition entity.
   */
  public function setName($name);

  /**
   * Gets the Medical Condition creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Medical Condition.
   */
  public function getCreatedTime();

  /**
   * Sets the Medical Condition creation timestamp.
   *
   * @param int $timestamp
   *   The Medical Condition creation timestamp.
   *
   * @return \Drupal\recover_core\MedicalConditionInterface
   *   The called Medical Condition entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Medical Condition published status indicator.
   *
   * Unpublished Medical Condition are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Medical Condition is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Medical Condition.
   *
   * @param bool $published
   *   TRUE to set this Medical Condition to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\recover_core\MedicalConditionInterface
   *   The called Medical Condition entity.
   */
  public function setPublished($published);

}
