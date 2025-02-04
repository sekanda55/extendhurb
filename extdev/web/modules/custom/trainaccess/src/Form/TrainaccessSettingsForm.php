<?php

namespace Drupal\trainaccess\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class TrainaccessSettingsForm extends ConfigFormBase {

  /** 
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'trainaccess.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'trainaccess_admin_settings';
  }

  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $types = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();

    $ct = [];
    foreach($types as $type){
        $ct += array($type->id() => $type->label());
    }

    $form['ct_email'] = [
        '#type' => 'radios',
        '#title' => t('After create node ?'),
        '#options' => $ct,
        '#default_value' => $config->get('ct_email'),
        '#required' => TRUE,
      ];

      $form['recipient_email'] = [
        '#type' => 'email',
        '#title' => t('Recipient'),
        '#default_value' => $config->get('recipient_email'),
        '#required' => TRUE,
      ];

    $form['train_txt_email'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Message'),
      '#format' => 'basic_html',
      '#default_value' => $config->get('train_txt_email')['value'],
      '#required' => TRUE,
    ];   

    return parent::buildForm($form, $form_state);
  }

  /** 
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $this->config(static::SETTINGS)
    ->set('ct_email', $form_state->getValue('ct_email'))
    ->set('recipient_email', $form_state->getValue('recipient_email'))
    ->set('train_txt_email', $form_state->getValue('train_txt_email'))
    ->save();

    parent::submitForm($form, $form_state);
  }

}