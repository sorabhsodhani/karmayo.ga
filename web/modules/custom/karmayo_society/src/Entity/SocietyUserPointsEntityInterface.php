<?php

namespace Drupal\karmayo_society\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Society user points entity entities.
 *
 * @ingroup karmayo_society
 */
interface SocietyUserPointsEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Society user points entity name.
   *
   * @return string
   *   Name of the Society user points entity.
   */
  public function getName();

  /**
   * Sets the Society user points entity name.
   *
   * @param string $name
   *   The Society user points entity name.
   *
   * @return \Drupal\karmayo_society\Entity\SocietyUserPointsEntityInterface
   *   The called Society user points entity entity.
   */
  public function setName($name);

  /**
   * Gets the Society user points entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Society user points entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Society user points entity creation timestamp.
   *
   * @param int $timestamp
   *   The Society user points entity creation timestamp.
   *
   * @return \Drupal\karmayo_society\Entity\SocietyUserPointsEntityInterface
   *   The called Society user points entity entity.
   */
  public function setCreatedTime($timestamp);

}
