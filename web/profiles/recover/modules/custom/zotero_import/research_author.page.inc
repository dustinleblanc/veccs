<?php

/**
 * @file
 * Contains research_author.page.inc.
 *
 * Page callback for Research Author entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Research Author templates.
 *
 * Default template: research_author.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_research_author(array &$variables) {
  // Fetch ResearchAuthor Entity Object.
  $research_author = $variables['elements']['#research_author'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
