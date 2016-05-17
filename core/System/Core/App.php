<?php
namespace System\Core;

use Symfony\Component\HttpFoundation\Response;


Abstract Class App {
	public $container=[];
	public $place=[
		'place_after_menu_navbar'		=> '',
		'place_after_left_menu'			=> ''
	];
	protected $current_app;
	protected $render_merge=[];

	public function __construct() {
		// ---
		$this->current_app=end(explode('/',$this->config()['path']));
	}

	public function show() {
		$twig=$this->container->get('twig.environment');
		
		$this->container->get('twig.loader')->addPath($this->config()['path'].'/template',$this->current_app);

		return new Response($twig->render('@'.$this->current_app.'/'.$this->config()['template'],$this->prepare()));
	}

	# @Return array
	private function prepare() {
		$this->container->get('app.asset-manager')->add(['global.css','materialize.min.css','jquery-2.2.3.min.js','materialize.min.js','global.js']);

		// Prepare
		$render=[
			'head_asset'	=>	$this->container->get('app.asset-manager')->load()
		];
		return array_merge($this->place,$render,$this->render);
	}

	abstract function config();
	abstract function render();
}
?>
