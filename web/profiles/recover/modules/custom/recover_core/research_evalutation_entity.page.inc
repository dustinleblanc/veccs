<?php

/**
 * @file
 * Contains research_evalutation_entity.page.inc.
 *
 * Page callback for Research Evaluation entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Research Evaluation templates.
 *
 * Default template: research_evalutation_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_research_evalutation_entity(array &$variables) {
  // Fetch ResearchEvalutationEntity Entity Object.
  $research_evalutation_entity = $variables['elements']['#research_evalutation_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
