<?php
namespace App\Welcome;

use Symfony\Component\HttpFoundation\Response;
use System\Core\App;
use App\Register\Register;

Class Welcome Extends App {
	public function index() {
		return new Response('hai');
	}
}
?>
