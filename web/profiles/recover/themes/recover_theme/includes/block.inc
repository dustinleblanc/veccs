<?php
/**
 * @file
 * Theme and preprocess functions for blocks.
 */
/**
 * Implements hook_preprocess_block().
 */
function recover_theme_preprocess_block(&$variables) {// Add id to template.
  if (isset($variables['elements']['#id'])) {
    $variables['id'] = str_replace('_', '-', $variables['elements']['#id']);
  }
  else {
    $variables['id'] = '';
  }

  // Check for BlockContent.
  if ($variables['elements']['#configuration']['provider'] != 'block_content' || empty($variables['elements']['content']['#block_content'])) {
    return;
  }

  // Get the block bundle.
  $block_content = $variables['elements']['content']['#block_content'];

  // Add bundle to template.
  $variables['bundle'] = $block_content->bundle();
}
