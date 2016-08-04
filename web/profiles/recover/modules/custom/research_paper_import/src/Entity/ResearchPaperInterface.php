<?php

namespace Drupal\research_paper_import\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Research paper entities.
 *
 * @ingroup research_paper_import
 */
interface ResearchPaperInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Research paper name.
   *
   * @return string
   *   Name of the Research paper.
   */
  public function getName();

  /**
   * Sets the Research paper name.
   *
   * @param string $name
   *   The Research paper name.
   *
   * @return \Drupal\research_paper_import\Entity\ResearchPaperInterface
   *   The called Research paper entity.
   */
  public function setName($name);

  /**
   * Gets the Research paper creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Research paper.
   */
  public function getCreatedTime();

  /**
   * Sets the Research paper creation timestamp.
   *
   * @param int $timestamp
   *   The Research paper creation timestamp.
   *
   * @return \Drupal\research_paper_import\Entity\ResearchPaperInterface
   *   The called Research paper entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Research paper published status indicator.
   *
   * Unpublished Research paper are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Research paper is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Research paper.
   *
   * @param bool $published
   *   TRUE to set this Research paper to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\research_paper_import\Entity\ResearchPaperInterface
   *   The called Research paper entity.
   */
  public function setPublished($published);

}
