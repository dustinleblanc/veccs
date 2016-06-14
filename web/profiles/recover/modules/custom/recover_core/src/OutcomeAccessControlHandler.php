<?php

/**
 * @file
 * Contains \Drupal\recover_core\OutcomeAccessControlHandler.
 */

namespace Drupal\recover_core;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Outcome entity.
 *
 * @see \Drupal\recover_core\Entity\Outcome.
 */
class OutcomeAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\recover_core\OutcomeInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished outcome entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published outcome entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit outcome entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete outcome entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add outcome entities');
  }

}
