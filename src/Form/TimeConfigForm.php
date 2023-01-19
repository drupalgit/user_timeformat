<?php

namespace Drupal\user_timeformat\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure Time form settings.
 */
class TimeConfigForm extends ConfigFormBase {

  const SETTINGS = 'user_timeformat.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'user_timeformat_form';
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
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => $config->get('country'),
    ];
    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $config->get('city'),
    ];
    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Select timezone'),
      '#options' => [
        'America/Chicago' => $this->t('America/Chicago'),
        'America/New_York' => $this->t('America/New_York'),
        'Asia/Dubai' => $this->t('Asia/Dubai'),
        'Asia/Kolkata' => $this->t('Asia/Kolkata'),
        'Asia/Tokyo' => $this->t('Asia/Tokyo'),
        'Europe/Amsterdam' => $this->t('Europe/Amsterdam'),
        'Europe/Oslo' => $this->t('Europe/Oslo'),
        'Europe/London' => $this->t('Europe/London'),
      ],
      '#default_value' => $config->get('timezone'),
      "#empty_option" => $this->t('- Select -'),
    ];
    // Extra work. Date & time format settings.
    $form['time_date_format'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Date & time Format'),
      '#description' => $this->t('A user-defined date format. See the <a href="@manual_url">PHP manual </a> for available options.', ['@manual_url' => 'https://www.php.net/manual/en/datetime.format.php#refsect1-datetime.format-parameters']),
    ];
    $form['time_date_format']['time'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Time format'),
      '#default_value' => $config->get('time'),
    ];
    $form['time_date_format']['date'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Date format'),
      '#default_value' => $config->get('date'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config(static::SETTINGS)
      ->set('country', $form_state->getValue('country'))
      ->set('city', $form_state->getValue('city'))
      ->set('timezone', $form_state->getValue('timezone'))
      ->set('time', $form_state->getValue('time'))
      ->set('date', $form_state->getValue('date'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
