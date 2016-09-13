<?php

namespace Drupal\recover_core\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Research Evaluation entities.
 *
 * @ingroup recover_core
 */
interface ResearchEvalutationEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Research Evaluation name.
   *
   * @return string
   *   Name of the Research Evaluation.
   */
  public function getName();

  /**
   * Sets the Research Evaluation name.
   *
   * @param string $name
   *   The Research Evaluation name.
   *
   * @return \Drupal\recover_core\Entity\ResearchEvalutationEntityInterface
   *   The called Research Evaluation entity.
   */
  public function setName($name);

  /**
   * Gets the Research Evaluation creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Research Evaluation.
   */
  public function getCreatedTime();

  /**
   * Sets the Research Evaluation creation timestamp.
   *
   * @param int $timestamp
   *   The Research Evaluation creation timestamp.
   *
   * @return \Drupal\recover_core\Entity\ResearchEvalutationEntityInterface
   *   The called Research Evaluation entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Research Evaluation published status indicator.
   *
   * Unpublished Research Evaluation are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Research Evaluation is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Research Evaluation.
   *
   * @param bool $published
   *   TRUE to set this Research Evaluation to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\recover_core\Entity\ResearchEvalutationEntityInterface
   *   The called Research Evaluation entity.
   */
  public function setPublished($published);

}
