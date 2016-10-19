<?php

namespace Drupal\zotero_import\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
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
 *     "label" = "zoteroTitle",
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
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
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
    return $this->get('zoteroTitle')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setTitle($title) {
    $this->set('zoteroTitle', $title);
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

    $fields['zoteroTitle'] = BaseFieldDefinition::create('string')
                                                ->setLabel(t('Title'))
                                                ->setDescription(t('The title of the referenced research piece.'))
                                                ->setSettings(array(
                                                  'max_length' => 256,
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

    $fields['zoteroKey'] = BaseFieldDefinition::create('string')
                                              ->setLabel(t('Zotero Item Key'))
                                              ->setDescription(t('The unique identifier in Zotero.'))
                                              ->setSettings(array(
                                                'max_length' => 50,
                                                'text_processing' => 0,
                                              ))
                                              ->setDefaultValue('')
                                              ->setDisplayOptions('view', array(
                                                'label' => 'inline',
                                                'type' => 'string',
                                                'weight' => -4,
                                              ))
                                              ->setDisplayOptions('form', array(
                                                'type' => 'string_textfield',
                                              ))
                                              ->setDisplayConfigurable('form', TRUE)
                                              ->setDisplayConfigurable('view', TRUE);

    $fields['zoteroVersion'] = BaseFieldDefinition::create('integer')
                                                  ->setLabel(t('Zotero Item Version'))
                                                  ->setDescription(t('The version of a Zotero item.'))
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

    $fields['zoteroItemType'] = BaseFieldDefinition::create('string')
                                                   ->setLabel(t('Zotero Item Type'))
                                                   ->setDescription(t('The type of Zotero item.'))
                                                   ->setSettings(array(
                                                     'max_length' => 256,
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

    $fields['zoteroCreators'] = BaseFieldDefinition::create('entity_reference')
                                                   ->setLabel(t('Zotero Item Creators'))
                                                   ->setDescription(t('The creators of the Zotero item.'))
                                                   ->setRevisionable(TRUE)
                                                   ->setSetting('target_type', 'research_author')
                                                   ->setSetting('handler', 'default')
                                                   ->setTranslatable(TRUE)
                                                   ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
                                                   ->setDisplayOptions('view', array(
                                                     'label' => 'above',
                                                     'type' => 'author',
                                                     'weight' => 0,
                                                   ))
                                                   ->setDisplayOptions('form', array(
                                                     'type' => 'entity_reference_autocomplete',
                                                     'settings' => array(
                                                       'match_operator' => 'CONTAINS',
                                                       'size' => '60',
                                                       'autocomplete_type' => 'tags',
                                                       'placeholder' => '',
                                                     ),
                                                   ))
                                                   ->setDisplayConfigurable('form', TRUE)
                                                   ->setDisplayConfigurable('view', TRUE);

    $fields['zoteroAbstractNote'] = BaseFieldDefinition::create('string')
                                                       ->setLabel(t('Zotero Abstract Note'))
                                                       ->setDescription(t('The abstract note of a Zotero item.'))
                                                       ->setSettings(array(
                                                         'max_length' => 5000,
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

    $fields['zoteroPublicationTitle'] = BaseFieldDefinition::create('string')
                                                           ->setLabel(t('Zotero Publication Title'))
                                                           ->setDescription(t('The publication title of a Zotero item.'))
                                                           ->setSettings(array(
                                                             'max_length' => 256,
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

    $fields['zoteroVolume'] = BaseFieldDefinition::create('string')
                                                 ->setLabel(t('Zotero Publication Volume'))
                                                 ->setDescription(t('The publication volume of a Zotero item.'))
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

    $fields['zoteroIssue'] = BaseFieldDefinition::create('string')
                                                ->setLabel(t('Zotero Publication Issue'))
                                                ->setDescription(t('The publication issue of a Zotero item.'))
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

    $fields['zoteroPages'] = BaseFieldDefinition::create('string')
                                                ->setLabel(t('Zotero Publication Pages'))
                                                ->setDescription(t('The publication pages of a Zotero item.'))
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

    $fields['zoteroDate'] = BaseFieldDefinition::create('string')
                                               ->setLabel(t('Zotero Publication Date'))
                                               ->setDescription(t('The publication date of a Zotero item.'))
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

    $fields['zoteroSeries'] = BaseFieldDefinition::create('string')
                                                 ->setLabel(t('Zotero Publication Series'))
                                                 ->setDescription(t('The publication series of a Zotero item.'))
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

    $fields['zoteroSeriesTitle'] = BaseFieldDefinition::create('string')
                                                      ->setLabel(t('Zotero Publication Series Title'))
                                                      ->setDescription(t('The publication series title of a Zotero item.'))
                                                      ->setSettings(array(
                                                        'max_length' => 256,
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

    $fields['zoteroSeriesText'] = BaseFieldDefinition::create('string')
                                                     ->setLabel(t('Zotero Publication Series Text'))
                                                     ->setDescription(t('The publication series text of a Zotero item.'))
                                                     ->setSettings(array(
                                                       'max_length' => 5000,
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

    $fields['zoteroJournalAbbreviation'] = BaseFieldDefinition::create('string')
                                                              ->setLabel(t('Zotero Journal Abbreviation'))
                                                              ->setDescription(t('The abbeviated journal name of a Zotero item.'))
                                                              ->setSettings(array(
                                                                'max_length' => 256,
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

    $fields['zoteroLanguage'] = BaseFieldDefinition::create('string')
                                                   ->setLabel(t('Zotero Language'))
                                                   ->setDescription(t('The original language of a Zotero item.'))
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

    $fields['zoteroDOI'] = BaseFieldDefinition::create('string')
                                              ->setLabel(t('Zotero DOI'))
                                              ->setDescription(t('The DOI code of a Zotero item.'))
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

    $fields['zoteroISSN'] = BaseFieldDefinition::create('string')
                                               ->setLabel(t('Zotero ISSN'))
                                               ->setDescription(t('The ISSN of a Zotero item.'))
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

    $fields['zoteroShortTitle'] = BaseFieldDefinition::create('string')
                                                     ->setLabel(t('Zotero Short Title'))
                                                     ->setDescription(t('The shortened title of a Zotero item.'))
                                                     ->setSettings(array(
                                                       'max_length' => 256,
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

    $fields['zoteroUrl'] = BaseFieldDefinition::create('string')
                                              ->setLabel(t('Zotero URL'))
                                              ->setDescription(t('The URL of a Zotero item (if the publication has one).'))
                                              ->setSettings(array(
                                                'max_length' => 512,
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

    $fields['zoteroAccessDate'] = BaseFieldDefinition::create('string')
                                                     ->setLabel(t('Zotero Access Date'))
                                                     ->setDescription(t('The date a Zotero item was originally accessed.'))
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

    $fields['zoteroArchive'] = BaseFieldDefinition::create('string')
                                                  ->setLabel(t('Zotero Archive'))
                                                  ->setDescription(t('The archive of a Zotero item.'))
                                                  ->setSettings(array(
                                                    'max_length' => 256,
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

    $fields['zoteroArchiveLocation'] = BaseFieldDefinition::create('string')
                                                          ->setLabel(t('Zotero Archive Location'))
                                                          ->setDescription(t('The archive location of a Zotero item.'))
                                                          ->setSettings(array(
                                                            'max_length' => 256,
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

    $fields['zoteroLibraryCatalog'] = BaseFieldDefinition::create('string')
                                                         ->setLabel(t('Zotero Library Catalog'))
                                                         ->setDescription(t('The library catalog of a Zotero item.'))
                                                         ->setSettings(array(
                                                           'max_length' => 256,
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

    $fields['zoteroCallNumber'] = BaseFieldDefinition::create('string')
                                                     ->setLabel(t('Zotero Call Number'))
                                                     ->setDescription(t('The call number of a Zotero item.'))
                                                     ->setSettings(array(
                                                       'max_length' => 256,
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

    $fields['zoteroRights'] = BaseFieldDefinition::create('string')
                                                 ->setLabel(t('Zotero Rights'))
                                                 ->setDescription(t('The rights associated with a Zotero item.'))
                                                 ->setSettings(array(
                                                   'max_length' => 256,
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

    $fields['zoteroExtra'] = BaseFieldDefinition::create('string')
                                                ->setLabel(t('Zotero Extra'))
                                                ->setDescription(t('The extra data attached to a Zotero item that doesn\'t fit other fields.'))
                                                ->setSettings(array(
                                                  'max_length' => 256,
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

    $fields['zoteroTags'] = BaseFieldDefinition::create('entity_reference')
                                               ->setLabel(t('Zotero Item Creators'))
                                               ->setDescription(t('The creators of Zotero item.'))
                                               ->setSettings([
                                                 'target_type' => 'taxonomy_term',
                                                 'target_bundle' => 'tags',
                                                 'default_value' => 0,
                                               ])
                                               ->setSettings(array(
                                                 'max_length' => 256,
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
    $fields['zoteroCollections'] = BaseFieldDefinition::create('map')
                                                      ->setLabel(t('Zotero Collections'))
                                                      ->setDescription(t('The Zotero collections that an item is part of.'))
                                                      ->setSettings(array(
                                                        'max_length' => 256,
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

    $fields['zoteroRelations'] = BaseFieldDefinition::create('map')
                                                    ->setLabel(t('Zotero Relations'))
                                                    ->setDescription(t('The related items to a Zotero item.'))
                                                    ->setSettings(array(
                                                      'max_length' => 256,
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

    $fields['zoteroDateAdded'] = BaseFieldDefinition::create('string')
                                                    ->setLabel(t('Zotero Date Added'))
                                                    ->setDescription(t('The date an item was added to Zotero library.'))
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

    $fields['zoteroDateModified'] = BaseFieldDefinition::create('string')
                                                       ->setLabel(t('Zotero Date Modified'))
                                                       ->setDescription(t('The last modified date of a Zotero item (before imported).'))
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
    return $fields;
  }
}
