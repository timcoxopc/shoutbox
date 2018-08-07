<?php 

namespace Drupal\shoutbox\Controller;

use Symfony\Component\HttpFoundation\Response;

class ShoutboxController {
	public function create_page() {
		return new Response('Test');
	}
}
