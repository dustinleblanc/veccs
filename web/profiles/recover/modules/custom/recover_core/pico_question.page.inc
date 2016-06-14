<?php

/**
 * @file
 * Contains pico_question.page.inc.
 *
 * Page callback for PICO Question entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for PICO Question templates.
 *
 * Default template: pico_question.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_pico_question(array &$variables) {
  // Fetch PicoQuestion Entity Object.
  $pico_question = $variables['elements']['#pico_question'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
