<?php 

namespace Drupal\shoutbox\Controller;
use Drupal\Component\Render\FormattableMarkup;
use Symfony\Component\HttpFoundation\Response;

class ShoutboxController {
  public function createAdminPage() {
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
}
