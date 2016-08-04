<?php

namespace Drupal\research_paper_import;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Research paper entity.
 *
 * @see \Drupal\research_paper_import\Entity\ResearchPaper.
 */
class ResearchPaperAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\research_paper_import\Entity\ResearchPaperInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished research paper entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published research paper entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit research paper entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete research paper entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add research paper entities');
  }

}
