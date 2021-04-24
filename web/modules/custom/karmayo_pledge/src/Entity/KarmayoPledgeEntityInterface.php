<?php

namespace Drupal\karmayo_pledge\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Karmayo Pledge entities.
 *
 * @ingroup karmayo_pledge
 */
interface KarmayoPledgeEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Karmayo Pledge name.
   *
   * @return string
   *   Name of the Karmayo Pledge.
   */
  public function getName();

  /**
   * Sets the Karmayo Pledge name.
   *
   * @param string $name
   *   The Karmayo Pledge name.
   *
   * @return \Drupal\karmayo_pledge\Entity\KarmayoPledgeEntityInterface
   *   The called Karmayo Pledge entity.
   */
  public function setName($name);

  /**
   * Gets the Karmayo Pledge creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Karmayo Pledge.
   */
  public function getCreatedTime();

  /**
   * Sets the Karmayo Pledge creation timestamp.
   *
   * @param int $timestamp
   *   The Karmayo Pledge creation timestamp.
   *
   * @return \Drupal\karmayo_pledge\Entity\KarmayoPledgeEntityInterface
   *   The called Karmayo Pledge entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Karmayo Pledge revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Karmayo Pledge revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\karmayo_pledge\Entity\KarmayoPledgeEntityInterface
   *   The called Karmayo Pledge entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Karmayo Pledge revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Karmayo Pledge revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\karmayo_pledge\Entity\KarmayoPledgeEntityInterface
   *   The called Karmayo Pledge entity.
   */
  public function setRevisionUserId($uid);

}
