<?php

/**
 * @file
 * Contains medical_condition.page.inc.
 *
 * Page callback for Medical Condition entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Medical Condition templates.
 *
 * Default template: medical_condition.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_medical_condition(array &$variables) {
  // Fetch MedicalCondition Entity Object.
  $medical_condition = $variables['elements']['#medical_condition'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
