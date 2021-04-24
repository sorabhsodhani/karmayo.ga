<?php

namespace Drupal\karmayo_society\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Society user points entity entity.
 *
 * @ingroup karmayo_society
 *
 * @ContentEntityType(
 *   id = "karmayo_society_user_points",
 *   label = @Translation("Society user points"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\karmayo_society\SocietyUserPointsEntityListBuilder",
 *     "views_data" = "Drupal\karmayo_society\Entity\SocietyUserPointsEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\karmayo_society\Form\SocietyUserPointsEntityForm",
 *       "add" = "Drupal\karmayo_society\Form\SocietyUserPointsEntityForm",
 *       "edit" = "Drupal\karmayo_society\Form\SocietyUserPointsEntityForm",
 *       "delete" = "Drupal\karmayo_society\Form\SocietyUserPointsEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\karmayo_society\SocietyUserPointsEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\karmayo_society\SocietyUserPointsEntityAccessControlHandler",
 *   },
 *   base_table = "karmayo_society_user_points",
 *   translatable = FALSE,
 *   admin_permission = "administer society user points entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/karmayo_society_user_points/{karmayo_society_user_points}",
 *     "add-form" = "/admin/structure/karmayo_society_user_points/add",
 *     "edit-form" = "/admin/structure/karmayo_society_user_points/{karmayo_society_user_points}/edit",
 *     "delete-form" = "/admin/structure/karmayo_society_user_points/{karmayo_society_user_points}/delete",
 *     "collection" = "/admin/structure/karmayo_society_user_points",
 *   },
 *   field_ui_base_route = "karmayo_society_user_points.settings"
 * )
 */
class SocietyUserPointsEntity extends ContentEntityBase implements SocietyUserPointsEntityInterface {

  use EntityChangedTrait;
  use EntityPublishedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    $weight = 0;
    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Society user points entity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => $weight++,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['society_points_societyid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Associated Society'))
      ->setDescription(t('The society ID to which the user points is to be mapped.'))
      ->setSetting('target_type', 'karmayo_society')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => $weight++,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['society_points_user_taskid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Associated User Task'))
      ->setDescription(t('The task ID to which the user points is to be mapped.'))
      ->setSetting('target_type', 'karmayo_society_pledge')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => $weight++
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['society_points_taskid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Associated Task'))
      ->setDescription(t('The task ID to which the user points is to be mapped.'))
      ->setSetting('target_type', 'karmayo_society_tasks')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => $weight++,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Society user points entity entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => $weight++,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
    
    $fields['society_user_points_amount'] = BaseFieldDefinition::create('decimal')
      ->setLabel(t('Points that has to be added or deducted'))
      ->setDescription(t('Points that has to be added or deducted'))
      ->setDefaultValue(0)
      ->setSettings(array(
        'precision' => 10,
        'scale' => 2,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'number_decimal',
        'weight' => $weight++,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'number',
        'weight' => -4,
      ))
      ->setRevisionable(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['society_user_points_balance'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Balance Points at this transaction'))
      ->setDescription(t('Balance Points at this transaction'))
      ->setDefaultValue(0)
      ->setSettings(array(
        'precision' => 10,
        'scale' => 2,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'number_decimal',
        'weight' => $weight++,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'number',
        'weight' => -4,
      ))
      ->setRevisionable(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    
    $fields['status']->setDescription(t('A boolean indicating whether the Society user points entity is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => $weight++,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
