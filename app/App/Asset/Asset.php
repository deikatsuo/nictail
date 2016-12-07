<?php
namespace App\Asset;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use System\Core\App;

Class Asset Extends App {
	protected $app;
	protected $file_name;
	protected $content;
	protected $current_template='blank.twig';
	public function indexPage(Request $request) {
		$this->app=ucfirst($request->query->get('app'));
		$this->file_name=$request->query->get('source');
		$this->path=__ROOT__.'/template/src';
		if(!empty($this->app)) {
			$this->path=__ROOT__.'/app/App/'.$this->app.'/template/src';
		}
		if(strrchr($this->file_name, '.') == '.css') {
			header("Content-type: text/css");
		}
		if(strrchr($this->file_name, '.') == '.js') {
			header("Content-Type: application/javascript");
		}
		if(strrchr($this->file_name, '.') == '.png') {
			header("Content-Type: image/png");
		}
		$this->readfile();
		return $this;
	}
	public function readfile() {
		$fsystem=$this->container->get('symfony.filesystem');
		if($fsystem->exists($this->path.'/'.$this->file_name)) {
			$this->render=[
				'data'	=> file_get_contents($this->path.'/'.$this->file_name)
			];
		}
	}

	public function config() {
		return [
			'path'		=>	__DIR__,
			'template'	=>	$this->current_template
		];
	}

	public function render() {
		return $this->render;
	}
}
?>