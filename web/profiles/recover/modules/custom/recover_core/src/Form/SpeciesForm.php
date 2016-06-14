<?php

/**
 * @file
 * Contains \Drupal\recover_core\Form\SpeciesForm.
 */

namespace Drupal\recover_core\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Species edit forms.
 *
 * @ingroup recover_core
 */
class SpeciesForm extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\recover_core\Entity\Species */
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
        drupal_set_message($this->t('Created the %label Species.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Species.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.species.canonical', ['species' => $entity->id()]);
  }

}
