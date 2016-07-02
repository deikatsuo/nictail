<?php
namespace System\Core;

use Symfony\Component\HttpFoundation\Response;


Abstract Class App {
	public $container=[];
	public $extends=[
		'place_before_navbar_menu'			=> '',
		'place_before_left_menu'			=> ''
	];
	protected $current_app;
	protected $render_merge=[];

	public function __construct() {
		// ---
		$this->current_app=end(explode('/',$this->config()['path']));
	}

	public function show() {
		$twig=$this->container->get('twig.environment');
		$template=$this->config()['template'];

		$this->container->get('twig.loader')->addPath($this->config()['path'].'/template',$this->current_app);

		if($template == "blank.twig") {
			return new Response($twig->render($template,$this->prepare('blank')));
		}
		return new Response($twig->render('@'.$this->current_app.'/'.$template,$this->prepare()));
	}

	# @Return array
	private function prepare($blank="") {
		if(empty($blank)) {
			$this->container->get('app.asset-manager')->add(['global.css','/uikit/css/uikit.min.css','jquery-2.2.3.min.js','/uikit/js/uikit.min.js','global.js']);

			// Prepare
			$render=[
				'head_asset'	=>	$this->container->get('app.asset-manager')->load()
			];
			return array_merge($this->extends,$render,$this->render);
		}
		return $this->render;
	}

	abstract function config();
	abstract function render();
}
?>
