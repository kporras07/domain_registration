<?php

/**
 * @file
 * Contains \Drupal\domain_registration\Form\DomainRegistrationAdminForm.
 */

namespace Drupal\domain_registration\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class DomainRegistrationAdminForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'domain_registration_admin_form';
  }

    /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('domain_registration.settings');

    foreach (Element::children($form) as $variable) {
      $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }
  

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {
    $options = array(
      0 => t('Allow only domains listed below to register'),
      1 => t('Prevent domains listed below from registering'),
    );
    $form['domain_registration_method'] = array(
      '#type' => 'radios',
      '#required' => TRUE,
      '#options' => $options,
      '#title' => t('Restriction Type'),
      '#default_value' => \Drupal::config('domain_registration.settings')->get('domain_registration_method'),
      '#description' => t('Choose which method you would like the domains list to operate. Only allow domains listed to register, or prevent domains listed from registering.'),
    );
    $form['domain_registration'] = array(
      '#type' => 'textarea',
      '#required' => TRUE,
      '#title' => t('Email domains'),
      '#default_value' => \Drupal::config('domain_registration.settings')->get('domain_registration'),
      '#description' => t('Enter the domains you wish to restrict registration. One entry per line in the format (e.g. something.com)'),
    );
    $form['domain_registration_message'] = array(
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => t('Error message'),
      '#default_value' => \Drupal::config('domain_registration.settings')->get('domain_registration_message'),
      '#description' => t('Enter the error message you want the user to see if the email address does not validate.'),
    );
    return parent::buildForm($form, $form_state);
  }
}
