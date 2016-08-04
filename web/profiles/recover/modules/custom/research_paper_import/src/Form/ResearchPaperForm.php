<?php

namespace Drupal\research_paper_import\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Research paper edit forms.
 *
 * @ingroup research_paper_import
 */
class ResearchPaperForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\research_paper_import\Entity\ResearchPaper */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Research paper.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Research paper.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.research_paper.canonical', ['research_paper' => $entity->id()]);
  }

}
