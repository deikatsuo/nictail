<?php
namespace App\Register;

use System\Core\App;

Class Register Extends App {
	public $render=[];
	public function index() {
		$this->render=[
			'title'		=> 'Daftar akun baru'
		];
		return $this;
	}

	public function config() {
		return [
			'path'		=>	__DIR__,
			'template'	=>	'register.twig'
		];
	}

	public function render() {
		return $this->render;
	}
}
?>
