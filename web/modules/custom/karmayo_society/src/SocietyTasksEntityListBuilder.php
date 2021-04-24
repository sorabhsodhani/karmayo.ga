<?php

namespace Drupal\karmayo_society;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Karmayo Society Tasks Entity entities.
 *
 * @ingroup karmayo_society
 */
class SocietyTasksEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Karmayo Society Tasks Entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\karmayo_society\Entity\SocietyTasksEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.karmayo_society_tasks.edit_form',
      ['karmayo_society_tasks' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
