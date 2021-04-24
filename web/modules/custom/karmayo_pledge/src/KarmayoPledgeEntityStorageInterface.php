<?php

namespace Drupal\karmayo_pledge;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\karmayo_pledge\Entity\KarmayoPledgeEntityInterface;

/**
 * Defines the storage handler class for Karmayo Pledge entities.
 *
 * This extends the base storage class, adding required special handling for
 * Karmayo Pledge entities.
 *
 * @ingroup karmayo_pledge
 */
interface KarmayoPledgeEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Karmayo Pledge revision IDs for a specific Karmayo Pledge.
   *
   * @param \Drupal\karmayo_pledge\Entity\KarmayoPledgeEntityInterface $entity
   *   The Karmayo Pledge entity.
   *
   * @return int[]
   *   Karmayo Pledge revision IDs (in ascending order).
   */
  public function revisionIds(KarmayoPledgeEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Karmayo Pledge author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Karmayo Pledge revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

}
