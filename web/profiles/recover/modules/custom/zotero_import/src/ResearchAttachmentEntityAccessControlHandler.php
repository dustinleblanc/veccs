<?php

namespace Drupal\zotero_import;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Research attachment entity entity.
 *
 * @see \Drupal\zotero_import\Entity\ResearchAttachmentEntity.
 */
class ResearchAttachmentEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished research attachment entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published research attachment entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit research attachment entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete research attachment entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add research attachment entity entities');
  }

}
