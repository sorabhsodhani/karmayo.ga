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
 * Defines the Karmayo Society Tasks Entity entity.
 *
 * @ingroup karmayo_society
 *
 * @ContentEntityType(
 *   id = "karmayo_society_tasks",
 *   label = @Translation("Karmayo Society Tasks"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\karmayo_society\SocietyTasksEntityListBuilder",
 *     "views_data" = "Drupal\karmayo_society\Entity\SocietyTasksEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\karmayo_society\Form\SocietyTasksEntityForm",
 *       "add" = "Drupal\karmayo_society\Form\SocietyTasksEntityForm",
 *       "edit" = "Drupal\karmayo_society\Form\SocietyTasksEntityForm",
 *       "delete" = "Drupal\karmayo_society\Form\SocietyTasksEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\karmayo_society\SocietyTasksEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\karmayo_society\SocietyTasksEntityAccessControlHandler",
 *   },
 *   base_table = "karmayo_society_tasks",
 *   translatable = FALSE,
 *   admin_permission = "administer karmayo society tasks entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/karmayo_society_tasks/{karmayo_society_tasks}",
 *     "add-form" = "/admin/structure/karmayo_society_tasks/add",
 *     "edit-form" = "/admin/structure/karmayo_society_tasks/{karmayo_society_tasks}/edit",
 *     "delete-form" = "/admin/structure/karmayo_society_tasks/{karmayo_society_tasks}/delete",
 *     "collection" = "/admin/structure/karmayo_society_tasks",
 *   },
 *   field_ui_base_route = "karmayo_society_tasks.settings"
 * )
 */
class SocietyTasksEntity extends ContentEntityBase implements SocietyTasksEntityInterface {

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

    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Karmayo Society Tasks Entity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
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
      ->setDescription(t('The name of the Karmayo Society Tasks Entity entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);
    
    $fields['society_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Associated Society'))
      ->setDescription(t('The society ID to which the task is to be mapped.'))
      ->setSetting('target_type', 'karmayo_society')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
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
    
    $fields['society_task_pointfactor'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Point Factor for the task'))
      ->setDescription(t('The point factor for the activity'))
      ->setDefaultValue(0)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['society_task_start_date'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Task Date'))
      ->setDescription(t('Task Date'))
      ->setSettings([
        'datetime_type' => 'date',
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'datetime_default',
        'settings' => [
          'format_type' => 'medium',
        ],
        'weight' => -9,
      ])
      ->setDisplayOptions('form', [
        'type' => 'datetime_default',
        'weight' => -9,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['status']->setDescription(t('A boolean indicating whether the Karmayo Society Tasks Entity is published.'))
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
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
