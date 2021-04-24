<?php

namespace Drupal\karmayo_society\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Karmayo society entity entities.
 *
 * @ingroup karmayo_society
 */
interface KarmayoSocietyEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Karmayo society entity name.
   *
   * @return string
   *   Name of the Karmayo society entity.
   */
  public function getName();

  /**
   * Sets the Karmayo society entity name.
   *
   * @param string $name
   *   The Karmayo society entity name.
   *
   * @return \Drupal\karmayo_society\Entity\KarmayoSocietyEntityInterface
   *   The called Karmayo society entity entity.
   */
  public function setName($name);

  /**
   * Gets the Karmayo society entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Karmayo society entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Karmayo society entity creation timestamp.
   *
   * @param int $timestamp
   *   The Karmayo society entity creation timestamp.
   *
   * @return \Drupal\karmayo_society\Entity\KarmayoSocietyEntityInterface
   *   The called Karmayo society entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Karmayo society entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Karmayo society entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\karmayo_society\Entity\KarmayoSocietyEntityInterface
   *   The called Karmayo society entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Karmayo society entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Karmayo society entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\karmayo_society\Entity\KarmayoSocietyEntityInterface
   *   The called Karmayo society entity entity.
   */
  public function setRevisionUserId($uid);

}
