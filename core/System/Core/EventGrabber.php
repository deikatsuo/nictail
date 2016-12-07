<?php
namespace System\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\Event;
use System\Core\DisMod;

Class EventGrabber Extends Event {
	private $response;
	private $request;

	public function __construct(DisMod $response=null, Request $request=null) {
		$this->response=$response;
		$this->request=$request;
	}

	public function getResponse() {
		return $this->response;
	}	

	public function getRequest() {
		return $this->request;
	}
}
?>