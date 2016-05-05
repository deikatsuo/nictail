<?php
namespace System\Core;

use Symfony\Component\HttpFoundation\Response;

Abstract Class App {
	public $container=[];
	public $place=[
		'place_after_menu_navbar'		=> '',
		'place_after_left_menu'			=> ''
	];

	public function __construct() {
		// ---
	}

	public function show() {
		$twig=$this->container->get('twig.environment');
		$current_app=end(explode('/',$this->config()['path']));
		$this->container->get('twig.loader')->addPath($this->config()['path'].'/template',$current_app);

		return new Response($twig->render('@'.$current_app.'/'.$this->config()['template'],$this->prepare()));
	}

	# @Return array
	private function prepare() {
		$asset=$this->container->get('app.asset-manager');
		$asset->add(['pure.css','jquery-2.2.3.min.js']);
		$asset->addTo('register',['pure.css','jquery-2.2.3.min.js']);

		// Prepare
		$render=[
			'head_asset'	=>	$asset->load()
		];
		return array_merge($this->place,$render,$this->render);
	}

	abstract function config();
	abstract function render();
}
?>
