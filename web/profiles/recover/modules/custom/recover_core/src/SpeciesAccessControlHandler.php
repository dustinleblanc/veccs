<?php

/**
 * @file
 * Contains \Drupal\recover_core\SpeciesAccessControlHandler.
 */

namespace Drupal\recover_core;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Species entity.
 *
 * @see \Drupal\recover_core\Entity\Species.
 */
class SpeciesAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\recover_core\SpeciesInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished species entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published species entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit species entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete species entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add species entities');
  }

}
