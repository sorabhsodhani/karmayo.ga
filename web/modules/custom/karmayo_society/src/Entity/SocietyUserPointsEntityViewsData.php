<?php

namespace Drupal\karmayo_society\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Society user points entity entities.
 */
class SocietyUserPointsEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
