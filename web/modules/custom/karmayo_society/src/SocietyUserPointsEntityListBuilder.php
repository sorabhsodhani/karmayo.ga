<?php

namespace Drupal\karmayo_society;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Society user points entity entities.
 *
 * @ingroup karmayo_society
 */
class SocietyUserPointsEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Society user points entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\karmayo_society\Entity\SocietyUserPointsEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.karmayo_society_user_points.edit_form',
      ['karmayo_society_user_points' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
