<?php

namespace Drupal\karmayo_society;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Society user points entity entity.
 *
 * @see \Drupal\karmayo_society\Entity\SocietyUserPointsEntity.
 */
class SocietyUserPointsEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\karmayo_society\Entity\SocietyUserPointsEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished society user points entity entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published society user points entity entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit society user points entity entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete society user points entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add society user points entity entities');
  }


}
