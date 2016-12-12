<?php

namespace Drupal\zotero_import\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface;

/**
 * Class ResearchAttachmentEntityController.
 *
 *  Returns responses for Research attachment entity routes.
 *
 * @package Drupal\zotero_import\Controller
 */
class ResearchAttachmentEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Research attachment entity  revision.
   *
   * @param int $research_attachment_entity_revision
   *   The Research attachment entity  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($research_attachment_entity_revision) {
    $research_attachment_entity = $this->entityManager()->getStorage('research_attachment_entity')->loadRevision($research_attachment_entity_revision);
    $view_builder = $this->entityManager()->getViewBuilder('research_attachment_entity');

    return $view_builder->view($research_attachment_entity);
  }

  /**
   * Page title callback for a Research attachment entity  revision.
   *
   * @param int $research_attachment_entity_revision
   *   The Research attachment entity  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($research_attachment_entity_revision) {
    $research_attachment_entity = $this->entityManager()->getStorage('research_attachment_entity')->loadRevision($research_attachment_entity_revision);
    return $this->t('Revision of %title from %date', array('%title' => $research_attachment_entity->label(), '%date' => format_date($research_attachment_entity->getRevisionCreationTime())));
  }

  /**
   * Generates an overview table of older revisions of a Research attachment entity .
   *
   * @param \Drupal\zotero_import\Entity\ResearchAttachmentEntityInterface $research_attachment_entity
   *   A Research attachment entity  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(ResearchAttachmentEntityInterface $research_attachment_entity) {
    $account = $this->currentUser();
    $langcode = $research_attachment_entity->language()->getId();
    $langname = $research_attachment_entity->language()->getName();
    $languages = $research_attachment_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $research_attachment_entity_storage = $this->entityManager()->getStorage('research_attachment_entity');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $research_attachment_entity->label()]) : $this->t('Revisions for %title', ['%title' => $research_attachment_entity->label()]);
    $header = array($this->t('Revision'), $this->t('Operations'));

    $revert_permission = (($account->hasPermission("revert all research attachment entity revisions") || $account->hasPermission('administer research attachment entity entities')));
    $delete_permission = (($account->hasPermission("delete all research attachment entity revisions") || $account->hasPermission('administer research attachment entity entities')));

    $rows = array();

    $vids = $research_attachment_entity_storage->revisionIds($research_attachment_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\zotero_import\ResearchAttachmentEntityInterface $revision */
      $revision = $research_attachment_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionAuthor(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->revision_timestamp->value, 'short');
        if ($vid != $research_attachment_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.research_attachment_entity.revision', ['research_attachment_entity' => $research_attachment_entity->id(), 'research_attachment_entity_revision' => $vid]));
        }
        else {
          $link = $research_attachment_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->revision_log_message->value, '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('research_attachment_entity.revision_revert_translation_confirm', ['research_attachment_entity' => $research_attachment_entity->id(), 'research_attachment_entity_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('research_attachment_entity.revision_revert_confirm', ['research_attachment_entity' => $research_attachment_entity->id(), 'research_attachment_entity_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('research_attachment_entity.revision_delete_confirm', ['research_attachment_entity' => $research_attachment_entity->id(), 'research_attachment_entity_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['research_attachment_entity_revisions_table'] = array(
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    );

    return $build;
  }

}
