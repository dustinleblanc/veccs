<?php

namespace Drupal\zotero_import;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Research Reference entity.
 *
 * @see \Drupal\zotero_import\Entity\ResearchReferenceEntity.
 */
class ResearchReferenceEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\zotero_import\Entity\ResearchReferenceEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished research reference entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published research reference entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit research reference entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete research reference entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add research reference entities');
  }

}
