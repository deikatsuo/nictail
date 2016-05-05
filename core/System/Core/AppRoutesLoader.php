<?php
namespace System\Core;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Loader\YamlFileLoader;

Class AppRoutesLoader Extends YamlFileLoader {
	private $routes=[];
	public function __construct($routes) {
		$this->routes=$routes;
	}
	public function routesLoader() {
		
		$collection = new RouteCollection();
		
		foreach ($this->routes as $name => $config) {
            $this->validate($config, $name, null);
            $this->parseRoute($collection, $name, $config, null);
        }
		return $collection;
	}
}
?>
