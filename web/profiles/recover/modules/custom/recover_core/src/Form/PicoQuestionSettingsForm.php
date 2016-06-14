<?php

/**
 * @file
 * Contains \Drupal\recover_core\Form\PicoQuestionSettingsForm.
 */

namespace Drupal\recover_core\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class PicoQuestionSettingsForm.
 *
 * @package Drupal\recover_core\Form
 *
 * @ingroup recover_core
 */
class PicoQuestionSettingsForm extends FormBase {
  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'PicoQuestion_settings';
  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Empty implementation of the abstract submit class.
  }


  /**
   * Defines the settings form for PICO Question entities.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   Form definition array.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['PicoQuestion_settings']['#markup'] = 'Settings form for PICO Question entities. Manage field settings here.';
    return $form;
  }

}
