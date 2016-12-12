<?php

namespace Drupal\zotero_import\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\RevisionableContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Research Attachment entity.
 *
 * @ingroup zotero_import
 *
 * @ContentEntityType(
 *   id = "research_attachment_entity",
 *   label = @Translation("Research Attachment"),
 *   handlers = {
 *     "storage" = "Drupal\zotero_import\ResearchAttachmentEntityStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\zotero_import\ResearchAttachmentEntityListBuilder",
 *     "views_data" = "Drupal\zotero_import\Entity\ResearchAttachmentEntityViewsData",
 *     "translation" = "Drupal\zotero_import\ResearchAttachmentEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\zotero_import\Form\ResearchAttachmentEntityForm",
 *       "add" = "Drupal\zotero_import\Form\ResearchAttachmentEntityForm",
 *       "edit" = "Drupal\zotero_import\Form\ResearchAttachmentEntityForm",
 *       "delete" = "Drupal\zotero_import\Form\ResearchAttachmentEntityDeleteForm",
 *     },
 *     "access" = "Drupal\zotero_import\ResearchAttachmentEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\zotero_import\ResearchAttachmentEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "research_attachment_entity",
 *   data_table = "research_attachment_entity_field_data",
 *   revision_table = "research_attachment_entity_revision",
 *   revision_data_table = "research_attachment_entity_field_revision",
 *   translatable = TRUE,
  *   admin_permission = "administer research attachment entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/research_attachment_entity/{research_attachment_entity}",
 *     "add-form" = "/admin/structure/research_attachment_entity/add",
 *     "edit-form" = "/admin/structure/research_attachment_entity/{research_attachment_entity}/edit",
 *     "delete-form" = "/admin/structure/research_attachment_entity/{research_attachment_entity}/delete",
 *     "version-history" = "/admin/structure/research_attachment_entity/{research_attachment_entity}/revisions",
 *     "revision" = "/admin/structure/research_attachment_entity/{research_attachment_entity}/revisions/{research_attachment_entity_revision}/view",
 *     "revision_revert" = "/admin/structure/research_attachment_entity/{research_attachment_entity}/revisions/{research_attachment_entity_revision}/revert",
 *     "translation_revert" = "/admin/structure/research_attachment_entity/{research_attachment_entity}/revisions/{research_attachment_entity_revision}/revert/{langcode}",
 *     "revision_delete" = "/admin/structure/research_attachment_entity/{research_attachment_entity}/revisions/{research_attachment_entity_revision}/delete",
 *     "collection" = "/admin/structure/research_attachment_entity",
 *   },
 *   field_ui_base_route = "research_attachment_entity.settings"
 * )
 */
class ResearchAttachmentEntity extends RevisionableContentEntityBase implements ResearchAttachmentEntityInterface {

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
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);

    foreach (array_keys($this->getTranslationLanguages()) as $langcode) {
      $translation = $this->getTranslation($langcode);

      // If no owner has been set explicitly, make the anonymous user the owner.
      if (!$translation->getOwner()) {
        $translation->setOwnerId(0);
      }
    }

    // If no revision author has been set explicitly, make the research_attachment_entity owner the
    // revision author.
    if (!$this->getRevisionAuthor()) {
      $this->setRevisionAuthorId($this->getOwnerId());
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
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionCreationTime() {
    return $this->get('revision_timestamp')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionCreationTime($timestamp) {
    $this->set('revision_timestamp', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRevisionAuthor() {
    return $this->get('revision_uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setRevisionAuthorId($uid) {
    $this->set('revision_uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Research Attachment entity.'))
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

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Research Attachment entity.'))
      ->setRevisionable(TRUE)
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
      ->setDescription(t('A boolean indicating whether the Research Attachment is published.'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['revision_timestamp'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Revision timestamp'))
      ->setDescription(t('The time that the current revision was created.'))
      ->setQueryable(FALSE)
      ->setRevisionable(TRUE);

    $fields['revision_uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Revision user ID'))
      ->setDescription(t('The user ID of the author of the current revision.'))
      ->setSetting('target_type', 'user')
      ->setQueryable(FALSE)
      ->setRevisionable(TRUE);

    $fields['revision_translation_affected'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Revision translation affected'))
      ->setDescription(t('Indicates if the last edit of a translation belongs to current revision.'))
      ->setReadOnly(TRUE)
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
