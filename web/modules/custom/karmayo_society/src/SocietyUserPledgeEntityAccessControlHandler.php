<?php

namespace Drupal\karmayo_society;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Society user pledge entity entity.
 *
 * @see \Drupal\karmayo_society\Entity\SocietyUserPledgeEntity.
 */
class SocietyUserPledgeEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\karmayo_society\Entity\SocietyUserPledgeEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished society user pledge entity entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published society user pledge entity entities');

      case 'update':
        $access = AccessResult::allowedIfHasPermission($account, 'edit society user pledge entity entities');
        if (!$access->isAllowed() && $account->hasPermission('edit own society user pledge entity entities')) {
          $access = $access->orIf(AccessResult::allowedIf($account->id() == $entity->getOwnerId())->cachePerUser()->addCacheableDependency($entity));
        }
        return $access;

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete society user pledge entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add society user pledge entity entities');
  }


}
