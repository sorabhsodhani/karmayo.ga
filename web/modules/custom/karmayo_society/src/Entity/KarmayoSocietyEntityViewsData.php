<?php

namespace Drupal\karmayo_society\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Karmayo society entity entities.
 */
class KarmayoSocietyEntityViewsData extends EntityViewsData {

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
