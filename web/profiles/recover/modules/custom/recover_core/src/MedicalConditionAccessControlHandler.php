<?php

/**
 * @file
 * Contains \Drupal\recover_core\MedicalConditionAccessControlHandler.
 */

namespace Drupal\recover_core;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Medical Condition entity.
 *
 * @see \Drupal\recover_core\Entity\MedicalCondition.
 */
class MedicalConditionAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\recover_core\MedicalConditionInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished medical condition entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published medical condition entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit medical condition entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete medical condition entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add medical condition entities');
  }

}
