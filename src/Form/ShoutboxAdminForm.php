<?php

namespace Drupal\shoutbox\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Xss;

class ShoutboxAdminForm extends FormBase {

  public function getFormId() {
      return 'shoutbox_admin_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    
    setMessage("Build form");

    $params = \Drupal::request()->get('keys');
    //kint($params);

    if(isset($params['delete'])) {
      drupal_set_message('Message has been deleted');
      $num_deleted = db_delete('shoutbox')
      ->condition('id', $params['delete'])
      ->execute();
    }

    $result = db_select('shoutbox', 's')
      ->fields('s', ['id', 'timestamp', 'name', 'message'])
      ->orderBy('timestamp', 'DESC')
      ->execute()
      ->fetchAll();

    $headers = [
      'timestamp' => 'Timestamp',
      'name' => 'Name',
      'message' => 'Message',
      'admin' => 'Admin'
    ];

    foreach ($result as $row) {
      $rows[] = [
        'timestamp' => date('d/m/Y', $row->timestamp),
        'name' => t('<strong>' . $row->name . '</strong>'),
        'message' => t('<p>' . $row->message . '</p>'),
        'delete' => t('<button formmethod="get" formaction="" type="submit" name="delete" value="' . $row->id . '">Delete</button>'),
      ];
    }

    $form['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    setMessage("Validate");
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    setMessage("Submit form");
    /*
    $connection = \Drupal::database();
    $result = $connection->insert('shoutbox')
      ->fields([
        'timestamp' => date('U'),
        'name' => Xss::filter($form_state->getValue('name')),
        'message' => Xss::filter($form_state->getValue('message')),
    ])
    ->execute();

    
    */
    drupal_set_message('Your message has been saved');
  }
}