<?php

namespace Drupal\shoutbox\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Xss;

class ShoutboxForm extends FormBase {

  public function getFormId() {
      return 'shoutbox_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $result = db_select('shoutbox', 's')
      ->fields('s', ['timestamp', 'name', 'message'])
      ->orderBy('timestamp', 'DESC')
      ->range(0, 10)
      ->execute();

    $headers = [
      'timestamp' => 'Timestamp',
      'name' => 'Name',
      'message' => 'Message'
    ];

    foreach ($result as $row) {
      $rows[] = [
        'timestamp' => date('d/m/Y', $row->timestamp),
        'name' => $row->name,
        'message' => $row->message,
      ];
    }
    
    $form['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
    ];

    $form['name'] = [
      '#type' => 'textfield', 
      '#title' => t('Name'), 
    ];

    $form['message'] = [
      '#type' => 'textarea', 
      '#title' => t('Message'),
    ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    /*
    if (strlen($element['#value']) <= 3) {
      form_error($element, t($element['#value'] . ' must have at least 3 characters.'));
    }
    */
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $connection = \Drupal::database();
    $result = $connection->insert('shoutbox')
      ->fields([
        'timestamp' => date('U'),
        'name' => Xss::filter($form_state->getValue('name')),
        'message' => Xss::filter($form_state->getValue('message')),
    ])
    ->execute();

    drupal_set_message('Your message has been saved');
  }
}