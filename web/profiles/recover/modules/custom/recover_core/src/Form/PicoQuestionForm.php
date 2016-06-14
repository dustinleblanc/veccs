<?php

/**
 * @file
 * Contains \Drupal\recover_core\Form\PicoQuestionForm.
 */

namespace Drupal\recover_core\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for PICO Question edit forms.
 *
 * @ingroup recover_core
 */
class PicoQuestionForm extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\recover_core\Entity\PicoQuestion */
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
        drupal_set_message($this->t('Created the %label PICO Question.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label PICO Question.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.pico_question.canonical', ['pico_question' => $entity->id()]);
  }

}
