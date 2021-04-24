<?php

namespace Drupal\karmayo_pledge;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class KarmayoPledgeEntityStorage extends SqlContentEntityStorage implements KarmayoPledgeEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(KarmayoPledgeEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {karmayo_pledge_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {karmayo_pledge_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

}
