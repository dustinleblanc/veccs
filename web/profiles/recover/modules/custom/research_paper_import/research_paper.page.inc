<?php

/**
 * @file
 * Contains research_paper.page.inc.
 *
 * Page callback for Research paper entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Research paper templates.
 *
 * Default template: research_paper.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_research_paper(array &$variables) {
  // Fetch ResearchPaper Entity Object.
  $research_paper = $variables['elements']['#research_paper'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
