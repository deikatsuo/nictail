<?php
namespace System\Core;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait AppContainer {
	#Container
	protected $container;
	public function setContainer(ContainerInterface $container) {
		$this->container=$container;
		return $this;
	}
}
?>