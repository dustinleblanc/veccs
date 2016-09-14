<?php

namespace Drupal\zotero_import\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Research Reference edit forms.
 *
 * @ingroup zotero_import
 */
class ResearchReferenceEntityForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\zotero_import\Entity\ResearchReferenceEntity */
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
        drupal_set_message($this->t('Created the %label Research Reference.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Research Reference.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.research_reference_entity.canonical', ['research_reference_entity' => $entity->id()]);
  }

}
