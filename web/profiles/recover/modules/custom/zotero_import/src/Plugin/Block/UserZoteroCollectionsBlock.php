<?php

namespace Drupal\zotero_import\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\user\Entity\User;
use DustinLeblanc\Zotero\Client;
use GuzzleHttp\Psr7\Response;

/**
 * Provides a 'Zotero Collections' block.
 *
 * @Block(
 *  id = "user_zotero_collections_block",
 *  admin_label = @Translation("Zotero Collections"),
 * )
 */
class UserZoteroCollectionsBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return \Drupal::formBuilder()->getForm('\Drupal\zotero_import\Form\CollectionBlockForm');
  }
}
