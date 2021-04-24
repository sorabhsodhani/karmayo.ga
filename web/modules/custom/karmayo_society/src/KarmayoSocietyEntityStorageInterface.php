<?php

namespace Drupal\karmayo_society;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\karmayo_society\Entity\KarmayoSocietyEntityInterface;

/**
 * Defines the storage handler class for Karmayo society entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Karmayo society entity entities.
 *
 * @ingroup karmayo_society
 */
interface KarmayoSocietyEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Karmayo society entity revision IDs for a specific Karmayo society entity.
   *
   * @param \Drupal\karmayo_society\Entity\KarmayoSocietyEntityInterface $entity
   *   The Karmayo society entity entity.
   *
   * @return int[]
   *   Karmayo society entity revision IDs (in ascending order).
   */
  public function revisionIds(KarmayoSocietyEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Karmayo society entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Karmayo society entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

}
