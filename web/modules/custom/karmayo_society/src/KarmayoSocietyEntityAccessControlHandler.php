<?php

namespace Drupal\karmayo_society;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Karmayo society entity entity.
 *
 * @see \Drupal\karmayo_society\Entity\KarmayoSocietyEntity.
 */
class KarmayoSocietyEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\karmayo_society\Entity\KarmayoSocietyEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished karmayo society entity entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published karmayo society entity entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit karmayo society entity entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete karmayo society entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add karmayo society entity entities');
  }


}
