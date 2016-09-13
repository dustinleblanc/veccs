<?php

namespace Drupal\recover_core;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Research Evaluation entity.
 *
 * @see \Drupal\recover_core\Entity\ResearchEvalutationEntity.
 */
class ResearchEvalutationEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\recover_core\Entity\ResearchEvalutationEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished research evaluation entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published research evaluation entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit research evaluation entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete research evaluation entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add research evaluation entities');
  }

}
