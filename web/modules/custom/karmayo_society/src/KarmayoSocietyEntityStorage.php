<?php

namespace Drupal\karmayo_society;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class KarmayoSocietyEntityStorage extends SqlContentEntityStorage implements KarmayoSocietyEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(KarmayoSocietyEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {karmayo_society_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {karmayo_society_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

}
