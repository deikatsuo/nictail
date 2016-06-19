<?php
namespace App\Asset;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

Class Asset {
	protected $app;
	protected $file_name;
	protected $content;
	public $container;
	public function index(Request $request) {
		$this->app=ucfirst($request->query->get('app'));
		$this->file_name=$request->query->get('source');
		$this->path=_root.'/template/src';
		if(!empty($this->app)) {
			$this->path=_root.'/app/App/'.$this->app.'/template/src';
		}
		$this->readfile();
		return $this;
	}
	public function readfile() {
		$fsystem=$this->container->get('symfony.filesystem');
		if($fsystem->exists($this->path.'/'.$this->file_name)) {
			$this->content=file_get_contents($this->path.'/'.$this->file_name);
		}
		$file=$this->path.'/'.$this->file_name;
	}
	public function show() {
		if(strrchr($this->file_name, '.') == '.css') {
			header("Content-type: text/css");
		}
		if(strrchr($this->file_name, '.') == '.js') {
			header("Content-Type: application/javascript");
		}
		if(strrchr($this->file_name, '.') == '.png') {
			header("Content-Type: image/png");
		}
		return new Response($this->content);
	}
}
?>