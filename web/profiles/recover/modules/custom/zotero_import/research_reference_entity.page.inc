<?php

/**
 * @file
 * Contains research_reference_entity.page.inc.
 *
 * Page callback for Research Reference entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Research Reference templates.
 *
 * Default template: research_reference_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_research_reference_entity(array &$variables) {
  // Fetch ResearchReferenceEntity Entity Object.
  $research_reference_entity = $variables['elements']['#research_reference_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
