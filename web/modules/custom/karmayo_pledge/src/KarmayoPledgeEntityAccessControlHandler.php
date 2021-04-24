<?php

namespace Drupal\karmayo_pledge;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Karmayo Pledge entity.
 *
 * @see \Drupal\karmayo_pledge\Entity\KarmayoPledgeEntity.
 */
class KarmayoPledgeEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\karmayo_pledge\Entity\KarmayoPledgeEntityInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished karmayo pledge entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published karmayo pledge entities');

      case 'update':

        $access = AccessResult::allowedIfHasPermission($account, 'edit karmayo pledge entities');
        if (!$access->isAllowed() && $account->hasPermission('edit own karmayo pledge entities')) {
          $access = $access->orIf(AccessResult::allowedIf($account->id() == $entity->getOwnerId())->cachePerUser()->addCacheableDependency($entity));
        }
        return $access;

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete karmayo pledge entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add karmayo pledge entities');
  }


}
