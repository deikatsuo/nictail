<?php
namespace App\PageEvent;

use Symfony\Component\HttpFoundation\Response;


Class PageEvent {
	public $container;
	protected $catch;

	public function __construct($e){
		$this->catch=$e;
	}
	public function index() {

		return $this;
	}
	public function show() {
		return new Response('Test page');
	}
}
?>
