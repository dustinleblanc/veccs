<?php

/**
 * @file
 * Contains \Drupal\recover_core\PicoQuestionAccessControlHandler.
 */

namespace Drupal\recover_core;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the PICO Question entity.
 *
 * @see \Drupal\recover_core\Entity\PicoQuestion.
 */
class PicoQuestionAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\recover_core\PicoQuestionInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished pico question entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published pico question entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit pico question entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete pico question entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add pico question entities');
  }

}
