<?php

namespace Drupal\karmayo_pledge;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Karmayo Pledge entities.
 *
 * @ingroup karmayo_pledge
 */
class KarmayoPledgeEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Karmayo Pledge ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\karmayo_pledge\Entity\KarmayoPledgeEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.karmayo_pledge.edit_form',
      ['karmayo_pledge' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
