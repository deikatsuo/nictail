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
		$finder=$this->container->get('symfony.finder');
		$fsystem=$this->container->get('symfony.filesystem');
		if($fsystem->exists($this->path)) {
			$finder->files()->in($this->path)->name($this->file_name);
			foreach ($finder as $file) {
				$this->content=nl2br($file->getContents());
			}
		}
	}
	public function show() {
		return new Response($this->content);
	}
}
?>