<?php

namespace Drupal\karmayo_society;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Karmayo society entity entities.
 *
 * @ingroup karmayo_society
 */
class KarmayoSocietyEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Karmayo society entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\karmayo_society\Entity\KarmayoSocietyEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.karmayo_society.edit_form',
      ['karmayo_society' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
