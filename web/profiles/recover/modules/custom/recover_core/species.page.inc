<?php

/**
 * @file
 * Contains species.page.inc.
 *
 * Page callback for Species entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Species templates.
 *
 * Default template: species.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_species(array &$variables) {
  // Fetch Species Entity Object.
  $species = $variables['elements']['#species'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
