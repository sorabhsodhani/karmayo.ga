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
 * Defines the Society user pledge entity entity.
 *
 * @ingroup karmayo_society
 *
 * @ContentEntityType(
 *   id = "karmayo_society_pledge",
 *   label = @Translation("Society user pledge"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\karmayo_society\SocietyUserPledgeEntityListBuilder",
 *     "views_data" = "Drupal\karmayo_society\Entity\SocietyUserPledgeEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\karmayo_society\Form\SocietyUserPledgeEntityForm",
 *       "add" = "Drupal\karmayo_society\Form\SocietyUserPledgeEntityForm",
 *       "edit" = "Drupal\karmayo_society\Form\SocietyUserPledgeEntityForm",
 *       "delete" = "Drupal\karmayo_society\Form\SocietyUserPledgeEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\karmayo_society\SocietyUserPledgeEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\karmayo_society\SocietyUserPledgeEntityAccessControlHandler",
 *   },
 *   base_table = "karmayo_society_pledge",
 *   translatable = FALSE,
 *   admin_permission = "administer society user pledge entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/karmayo_society_pledge/{karmayo_society_pledge}",
 *     "add-form" = "/admin/structure/karmayo_society_pledge/add",
 *     "edit-form" = "/admin/structure/karmayo_society_pledge/{karmayo_society_pledge}/edit",
 *     "delete-form" = "/admin/structure/karmayo_society_pledge/{karmayo_society_pledge}/delete",
 *     "collection" = "/admin/structure/karmayo_society_pledge",
 *   },
 *   field_ui_base_route = "karmayo_society_pledge.settings"
 * )
 */
class SocietyUserPledgeEntity extends ContentEntityBase implements SocietyUserPledgeEntityInterface {

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
      ->setDescription(t('The user ID of author of the Society user pledge entity entity.'))
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
    
    $fields['society_pledge_user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User linked to the task'))
      ->setDescription(t('User linked to the task.'))
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
      ->setDescription(t('The name of the Society user pledge entity entity.'))
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

    $fields['society_pledge_societyid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Associated Society'))
      ->setDescription(t('The society ID to which the user points is to be mapped.'))
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
    
    $fields['society_pledge_taskid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Associated Society'))
      ->setDescription(t('The society ID to which the user points is to be mapped.'))
      ->setSetting('target_type', 'karmayo_society_tasks')
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
    
    $fields['society_activity_points'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('User points at stake'))
      ->setDescription(t('User points at stake'))
      ->setDefaultValue(0)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    $fields['society_activity_status'] = BaseFieldDefinition::create('list_integer')
      ->setLabel(t('User activity status'))
      ->setDescription(t('The current status of activity user picked.'))
      ->setDefaultValue(0)
      ->setRevisionable(TRUE)
      ->setSettings([
        'allowed_values' => [
          0 => 'Picked',
          1 => 'Pledged',
          2 => 'Performed',
          3 => 'Expired'
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'list_default',
        'weight' => 6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 6,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
    
    $fields['status']->setDescription(t('A boolean indicating whether the Society user pledge entity is published.'))
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
