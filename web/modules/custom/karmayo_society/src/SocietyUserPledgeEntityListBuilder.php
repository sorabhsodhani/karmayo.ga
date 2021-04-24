<?php

namespace Drupal\karmayo_society;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Society user pledge entity entities.
 *
 * @ingroup karmayo_society
 */
class SocietyUserPledgeEntityListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Society user pledge entity ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\karmayo_society\Entity\SocietyUserPledgeEntity $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.karmayo_society_pledge.edit_form',
      ['karmayo_society_pledge' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
