<?php

namespace Drupal\recover_core\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Research Evaluation entity.
 *
 * @ingroup recover_core
 *
 * @ContentEntityType(
 *   id = "research_evalutation_entity",
 *   label = @Translation("Research Evaluation"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\recover_core\ResearchEvalutationEntityListBuilder",
 *     "views_data" = "Drupal\recover_core\Entity\ResearchEvalutationEntityViewsData",
 *     "translation" = "Drupal\recover_core\ResearchEvalutationEntityTranslationHandler",
 *
 *     "form" = {
 *       "default" = "Drupal\recover_core\Form\ResearchEvalutationEntityForm",
 *       "add" = "Drupal\recover_core\Form\ResearchEvalutationEntityForm",
 *       "edit" = "Drupal\recover_core\Form\ResearchEvalutationEntityForm",
 *       "delete" = "Drupal\recover_core\Form\ResearchEvalutationEntityDeleteForm",
 *     },
 *     "access" = "Drupal\recover_core\ResearchEvalutationEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\recover_core\ResearchEvalutationEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "research_evalutation_entity",
 *   data_table = "research_evalutation_entity_field_data",
 *   translatable = TRUE,
  *   admin_permission = "administer research evaluation entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/evaluations/research_evalutation_entity/{research_evalutation_entity}",
 *     "add-form" = "/evaluations/research_evalutation_entity/add",
 *     "edit-form" = "/evaluations/research_evalutation_entity/{research_evalutation_entity}/edit",
 *     "delete-form" = "/evaluations/research_evalutation_entity/{research_evalutation_entity}/delete",
 *     "collection" = "/evaluations/research_evalutation_entity",
 *   },
 *   field_ui_base_route = "research_evalutation_entity.settings"
 * )
 */
class ResearchEvalutationEntity extends ContentEntityBase implements ResearchEvalutationEntityInterface {

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
      ->setDescription(t('The user ID of author of the Research Evaluation entity.'))
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
      ->setDescription(t('The name of the Research Evaluation entity.'))
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
      ->setDescription(t('A boolean indicating whether the Research Evaluation is published.'))
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
