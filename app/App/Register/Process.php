<?php
namespace App\Register;

use System\Core\User;

Class Process {
	protected $request;
	public function __construct() {
		//--
	}
	public function request($request) {
		$this->request=$request;
		return $this;
	}
	public function result() {
		$user=new User();
		$user->create($this->request);
		return $user->getLog();
	}
}
?>