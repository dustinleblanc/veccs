<?php

/**
 * @file
 * Contains research_attachment_entity.page.inc.
 *
 * Page callback for Research Attachment entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Research Attachment templates.
 *
 * Default template: research_attachment_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_research_attachment_entity(array &$variables) {
  // Fetch ResearchAttachmentEntity Entity Object.
  $research_attachment_entity = $variables['elements']['#research_attachment_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
