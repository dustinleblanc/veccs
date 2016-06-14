<?php

/**
 * @file
 * Contains outcome.page.inc.
 *
 * Page callback for Outcome entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Outcome templates.
 *
 * Default template: outcome.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_outcome(array &$variables) {
  // Fetch Outcome Entity Object.
  $outcome = $variables['elements']['#outcome'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
