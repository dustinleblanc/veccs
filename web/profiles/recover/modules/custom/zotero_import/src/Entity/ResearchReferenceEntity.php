<?php

namespace Drupal\zotero_import\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Research Reference entity.
 *
 * @ingroup zotero_import
 *
 * @ContentEntityType(
 *   id = "research_reference_entity",
 *   label = @Translation("Research Reference"),
 *   bundle_label = @Translation("Research Reference type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\zotero_import\ResearchReferenceEntityListBuilder",
 *     "views_data" = "Drupal\zotero_import\Entity\ResearchReferenceEntityViewsData",
 *     "translation" = "Drupal\zotero_import\ResearchReferenceEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\zotero_import\Form\ResearchReferenceEntityForm",
 *       "add" = "Drupal\zotero_import\Form\ResearchReferenceEntityForm",
 *       "edit" = "Drupal\zotero_import\Form\ResearchReferenceEntityForm",
 *       "delete" = "Drupal\zotero_import\Form\ResearchReferenceEntityDeleteForm",
 *     },
 *     "access" = "Drupal\zotero_import\ResearchReferenceEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\zotero_import\ResearchReferenceEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "research_reference_entity",
 *   data_table = "research_reference_entity_field_data",
 *   translatable = TRUE,
  *   admin_permission = "administer research reference entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "title",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/research_references/research_reference_entity/{research_reference_entity}",
 *     "add-page" = "/research_references/research_reference_entity/add",
 *     "add-form" = "/research_references/research_reference_entity/add/{research_reference_entity_type}",
 *     "edit-form" = "/research_references/research_reference_entity/{research_reference_entity}/edit",
 *     "delete-form" = "/research_references/research_reference_entity/{research_reference_entity}/delete",
 *     "collection" = "/research_references/research_reference_entity",
 *   },
 *   bundle_entity_type = "research_reference_entity_type",
 *   field_ui_base_route = "entity.research_reference_entity_type.edit_form"
 * )
 */
class ResearchReferenceEntity extends ContentEntityBase implements ResearchReferenceEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->bundle();
  }

  /**
   * {@inheritdoc}
   */
  public function getTitle() {
    return $this->get('title')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('title', $title);
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
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Research Reference entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The title of the referenced research piece.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Research Reference is published.'))
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
