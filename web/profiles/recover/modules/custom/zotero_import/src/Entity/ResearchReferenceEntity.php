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

    $fields['zotero_key'] = BaseFieldDefinition::create('string')
                                               ->setLabel(t('Zotero Item Key'))
                                               ->setDescription(t('The unique identifier in Zotero.'));

    $fields['zotero_version'] = BaseFieldDefinition::create('integer')
                                                   ->setLabel(t('Zotero Item Version'))
                                                   ->setDescription(t('The version of a Zotero item.'));

    $fields['zotero_item_type'] = BaseFieldDefinition::create('string')
                                                     ->setLabel(t('Zotero Item Type'))
                                                     ->setDescription(t('The type of Zotero item.'));

    $fields['zotero_creators'] = BaseFieldDefinition::create('entity_reference')
                                                    ->setLabel(t('Zotero Item Creators'))
                                                    ->setDescription(t('The creators of Zotero item.'))
                                                    ->setSettings([
                                                      'target_type' => 'research_author',
                                                      'default_value' => 0,
                                                    ]);

    $fields['zotero_abstract_note'] = BaseFieldDefinition::create('string')
                                                         ->setLabel(t('Zotero Abstract Note'))
                                                         ->setDescription(t('The abstract note of a Zotero item.'));

    $fields['zotero_publication_title'] = BaseFieldDefinition::create('string')
                                                             ->setLabel(t('Zotero Publication Title'))
                                                             ->setDescription(t('The publication title of a Zotero item.'));

    $fields['zotero_volume'] = BaseFieldDefinition::create('string')
                                                  ->setLabel(t('Zotero Publication Volume'))
                                                  ->setDescription(t('The publication volume of a Zotero item.'));

    $fields['zotero_issue'] = BaseFieldDefinition::create('string')
                                                 ->setLabel(t('Zotero Publication Issue'))
                                                 ->setDescription(t('The publication issue of a Zotero item.'));

    $fields['zotero_pages'] = BaseFieldDefinition::create('string')
                                                 ->setLabel(t('Zotero Publication Pages'))
                                                 ->setDescription(t('The publication pages of a Zotero item.'));

    $fields['zotero_date'] = BaseFieldDefinition::create('string')
                                                ->setLabel(t('Zotero Publication Date'))
                                                ->setDescription(t('The publication date of a Zotero item.'));

    $fields['zotero_series'] = BaseFieldDefinition::create('string')
                                                  ->setLabel(t('Zotero Publication Series'))
                                                  ->setDescription(t('The publication series of a Zotero item.'));

    $fields['zotero_series_title'] = BaseFieldDefinition::create('string')
                                                        ->setLabel(t('Zotero Publication Series Title'))
                                                        ->setDescription(t('The publication series title of a Zotero item.'));

    $fields['zotero_series_text'] = BaseFieldDefinition::create('string')
                                                       ->setLabel(t('Zotero Publication Series Text'))
                                                       ->setDescription(t('The publication series text of a Zotero item.'));

    $fields['zotero_journal_abbreviation'] = BaseFieldDefinition::create('string')
                                                                ->setLabel(t('Zotero Journal Abbreviation'))
                                                                ->setDescription(t('The abbeviated journal name of a Zotero item.'));

    $fields['zotero_language'] = BaseFieldDefinition::create('string')
                                                    ->setLabel(t('Zotero Language'))
                                                    ->setDescription(t('The original language of a Zotero item.'));

    $fields['zotero_doi'] = BaseFieldDefinition::create('string')
                                               ->setLabel(t('Zotero DOI'))
                                               ->setDescription(t('The DOI code of a Zotero item.'));

    $fields['zotero_issn'] = BaseFieldDefinition::create('string')
                                                ->setLabel(t('Zotero ISSN'))
                                                ->setDescription(t('The ISSN of a Zotero item.'));

    $fields['zotero_short_title'] = BaseFieldDefinition::create('string')
                                                       ->setLabel(t('Zotero Short Title'))
                                                       ->setDescription(t('The shortened title of a Zotero item.'));

    $fields['zotero_url'] = BaseFieldDefinition::create('string')
                                               ->setLabel(t('Zotero URL'))
                                               ->setDescription(t('The URL of a Zotero item (if the publication has one).'));

    $fields['zotero_access_date'] = BaseFieldDefinition::create('string')
                                                       ->setLabel(t('Zotero Access Date'))
                                                       ->setDescription(t('The date a Zotero item was originally accessed.'));

    $fields['zotero_archive'] = BaseFieldDefinition::create('string')
                                                   ->setLabel(t('Zotero Archive'))
                                                   ->setDescription(t('The archive of a Zotero item.'));

    $fields['zotero_archive_location'] = BaseFieldDefinition::create('string')
                                                            ->setLabel(t('Zotero Archive Location'))
                                                            ->setDescription(t('The archive location of a Zotero item.'));

    $fields['zotero_library_catalog'] = BaseFieldDefinition::create('string')
                                                           ->setLabel(t('Zotero Library Catalog'))
                                                           ->setDescription(t('The library catalog of a Zotero item.'));

    $fields['zotero_call_number'] = BaseFieldDefinition::create('string')
                                                       ->setLabel(t('Zotero Call Number'))
                                                       ->setDescription(t('The call number of a Zotero item.'));

    $fields['zotero_rights'] = BaseFieldDefinition::create('string')
                                                  ->setLabel(t('Zotero Rights'))
                                                  ->setDescription(t('The rights associated with a Zotero item.'));

    $fields['zotero_extra'] = BaseFieldDefinition::create('string')
                                                 ->setLabel(t('Zotero Extra'))
                                                 ->setDescription(t('The extra data attached to a Zotero item that doesn\'t fit other fields.'));

    $fields['zotero_tags'] = BaseFieldDefinition::create('entity_reference')
                                                ->setLabel(t('Zotero Item Creators'))
                                                ->setDescription(t('The creators of Zotero item.'))
                                                ->setSettings([
                                                  'target_type' => 'taxonomy_term',
                                                  'target_bundle' => 'tags',
                                                  'default_value' => 0,
                                                ]);
    $fields['zotero_collections'] = BaseFieldDefinition::create('map')
                                                       ->setLabel(t('Zotero Collections'))
                                                       ->setDescription(t('The Zotero collections that an item is part of.'));

    $fields['zotero_relations'] = BaseFieldDefinition::create('map')
                                                     ->setLabel(t('Zotero Relations'))
                                                     ->setDescription(t('The related items to a Zotero item.'));

    $fields['zotero_date_added'] = BaseFieldDefinition::create('string')
                                                      ->setLabel(t('Zotero Date Added'))
                                                      ->setDescription(t('The date an item was added to Zotero library.'));

    $fields['zotero_date_modified'] = BaseFieldDefinition::create('string')
                                                         ->setLabel(t('Zotero Date Modified'))
                                                         ->setDescription(t('The last modified date of a Zotero item (before imported).'));
    return $fields;
  }

}
