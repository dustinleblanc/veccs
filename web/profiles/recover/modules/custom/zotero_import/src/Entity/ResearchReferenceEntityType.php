<?php

namespace Drupal\zotero_import\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Research Reference type entity.
 *
 * @ConfigEntityType(
 *   id = "research_reference_entity_type",
 *   label = @Translation("Research Reference type"),
 *   handlers = {
 *     "list_builder" = "Drupal\zotero_import\ResearchReferenceEntityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\zotero_import\Form\ResearchReferenceEntityTypeForm",
 *       "edit" = "Drupal\zotero_import\Form\ResearchReferenceEntityTypeForm",
 *       "delete" = "Drupal\zotero_import\Form\ResearchReferenceEntityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\zotero_import\ResearchReferenceEntityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "research_reference_entity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "research_reference_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/research_references/research_reference_entity_type/{research_reference_entity_type}",
 *     "add-form" = "/admin/structure/research_references/research_reference_entity_type/add",
 *     "edit-form" = "/admin/structure/research_references/research_reference_entity_type/{research_reference_entity_type}/edit",
 *     "delete-form" = "/admin/structure/research_references/research_reference_entity_type/{research_reference_entity_type}/delete",
 *     "collection" = "/admin/structure/research_references/research_reference_entity_type"
 *   }
 * )
 */
class ResearchReferenceEntityType extends ConfigEntityBundleBase implements ResearchReferenceEntityTypeInterface {

  /**
   * The Research Reference type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Research Reference type label.
   *
   * @var string
   */
  protected $label;

}
