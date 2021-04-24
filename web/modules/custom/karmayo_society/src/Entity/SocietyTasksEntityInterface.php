<?php

namespace Drupal\karmayo_society\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Karmayo Society Tasks Entity entities.
 *
 * @ingroup karmayo_society
 */
interface SocietyTasksEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Karmayo Society Tasks Entity name.
   *
   * @return string
   *   Name of the Karmayo Society Tasks Entity.
   */
  public function getName();

  /**
   * Sets the Karmayo Society Tasks Entity name.
   *
   * @param string $name
   *   The Karmayo Society Tasks Entity name.
   *
   * @return \Drupal\karmayo_society\Entity\SocietyTasksEntityInterface
   *   The called Karmayo Society Tasks Entity entity.
   */
  public function setName($name);

  /**
   * Gets the Karmayo Society Tasks Entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Karmayo Society Tasks Entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Karmayo Society Tasks Entity creation timestamp.
   *
   * @param int $timestamp
   *   The Karmayo Society Tasks Entity creation timestamp.
   *
   * @return \Drupal\karmayo_society\Entity\SocietyTasksEntityInterface
   *   The called Karmayo Society Tasks Entity entity.
   */
  public function setCreatedTime($timestamp);

}
