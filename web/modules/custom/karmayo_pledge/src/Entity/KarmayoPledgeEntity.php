<?php

namespace Drupal\karmayo_pledge\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EditorialContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Karmayo Pledge entity.
 *
 * @ingroup karmayo_pledge
 *
 * @ContentEntityType(
 *   id = "karmayo_pledge",
 *   label = @Translation("Karmayo Pledge"),
 *   handlers = {
 *     "storage" = "Drupal\karmayo_pledge\KarmayoPledgeEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\karmayo_pledge\KarmayoPledgeEntityListBuilder",
 *     "views_data" = "Drupal\karmayo_pledge\Entity\KarmayoPledgeEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\karmayo_pledge\Form\KarmayoPledgeEntityForm",
 *       "add" = "Drupal\karmayo_pledge\Form\KarmayoPledgeEntityForm",
 *       "edit" = "Drupal\karmayo_pledge\Form\KarmayoPledgeEntityForm",
 *       "delete" = "Drupal\karmayo_pledge\Form\KarmayoPledgeEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\karmayo_pledge\KarmayoPledgeEntityHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\karmayo_pledge\KarmayoPledgeEntityAccessControlHandler",
 *   },
 *   base_table = "karmayo_pledge",
 *   revision_table = "karmayo_pledge_revision",
 *   revision_data_table = "karmayo_pledge_field_revision",
 *   translatable = FALSE,
 *   admin_permission = "administer karmayo pledge entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/karmayo_pledge/{karmayo_pledge}",
 *     "add-form" = "/admin/structure/karmayo_pledge/add",
 *     "edit-form" = "/admin/structure/karmayo_pledge/{karmayo_pledge}/edit",
 *     "delete-form" = "/admin/structure/karmayo_pledge/{karmayo_pledge}/delete",
 *     "version-history" = "/admin/structure/karmayo_pledge/{karmayo_pledge}/revisions",
 *     "revision" = "/admin/structure/karmayo_pledge/{karmayo_pledge}/revisions/{karmayo_pledge_revision}/view",
 *     "revision_revert" = "/admin/structure/karmayo_pledge/{karmayo_pledge}/revisions/{karmayo_pledge_revision}/revert",
 *     "revision_delete" = "/admin/structure/karmayo_pledge/{karmayo_pledge}/revisions/{karmayo_pledge_revision}/delete",
 *     "collection" = "/admin/structure/karmayo_pledge",
 *   },
 *   field_ui_base_route = "karmayo_pledge.settings"
 * )
 */
class KarmayoPledgeEntity extends EditorialContentEntityBase implements KarmayoPledgeEntityInterface {

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
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    // If no revision author has been set explicitly,
    // make the karmayo_pledge owner the revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
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
      ->setDescription(t('The user ID of author of the Karmayo Pledge entity.'))
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
      ->setDescription(t('The name of the Karmayo Pledge entity.'))
      ->setRevisionable(TRUE)
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
    
    $fields['activity_points'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Points Gained/Lost'))
      ->setDescription(t('The points gained or lost for the activity'))
      ->setDefaultValue(0)
      ->setRevisionable(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    
    $fields['activity_status'] = BaseFieldDefinition::create('list_integer')
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
    
    $fields['node_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Activity'))
      ->setDescription(t('The node ID of activity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'node')
      ->setSetting('handler', 'default:node')
      ->setSetting('handler_settings', [
        'target_bundles' => ['karma_activities' => 'karma_activities'],
        'auto_create' => FALSE,
      ])
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'string',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 2,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => 'Enter here activity title...',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
    
    
    
    
    $fields['status']->setDescription(t('A boolean indicating whether the Karmayo Pledge is published.'))
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
