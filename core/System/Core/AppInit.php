<?php
namespace System\Core;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use System\Core\AppServicesLoader;
use System\Core\AppRoutesLoader;

Class AppInit {
	protected $container;
	protected $dir;
	protected $apps=[];
	protected $appsloaded=[];
	protected $config=[];
	protected $loaded=0;
	protected $services=[];
	protected $mainservices=[];
	protected $hasservices=[];
	protected $mainparameters=[];
	protected $hasparameters=[];
	protected $routes=[];
	protected $hasroutes=[];
	
	public function __construct(ContainerBuilder $container, $dir) {
		$this->container=$container;
		$yaml=new Parser();
		$this->dir=$dir;
		$run=$yaml->parse(file_get_contents($this->dir.'/run.yml'));
		if(array_key_exists('services',$run)) {
			$this->mainservices=$run['services'];
		}
		if(array_key_exists('parameters',$run)) {
			$this->mainparameters=$run['parameters'];
		}
		$this->apps=str_replace(' ','',explode(',',$run['execute']));
		for($i=0;$i<count($this->apps);$i++) {
			if(file_exists($this->dir.'/'.$this->apps[$i].'/config.yml')) {
				$this->loaded++;
				array_push($this->appsloaded,$this->apps[$i]);
				$this->config[$this->apps[$i]]=$yaml->parse(file_get_contents($this->dir.'/'.$this->apps[$i].'/config.yml'));
				if(array_key_exists('services',$this->config[$this->apps[$i]])) {
					array_push($this->hasservices,$this->apps[$i]);
				}
				if(array_key_exists('parameters',$this->config[$this->apps[$i]])) {
					array_push($this->hasparameters,$this->apps[$i]);
				}
				if(array_key_exists('routes',$this->config[$this->apps[$i]])) {
					array_push($this->hasroutes,$this->apps[$i]);
				}
			}
		}
	}
	
	public function runServices() {
		$services=[];
		$parameters=[];
		for($i=0;$i<count($this->hasservices);$i++) {
			$services=array_merge($services,$this->config[$this->hasservices[$i]]['services']);
		}
		for($i=0;$i<count($this->hasparameters);$i++) {
			$parameters=array_merge($parameters,$this->config[$this->hasparameters[$i]]['parameters']);
		}
		$this->services['services']=array_merge($this->mainservices,$services);
		$this->services['parameters']=array_merge($this->mainparameters,$parameters);
		$services=new AppServicesLoader($this->container,$this->services);
		return $services->servicesLoader();
	}
	
	/* Routers
	 * 
	 * @return RouteCollections
	*/
	public function getRoutes() {
		for($i=0;$i<count($this->hasroutes);$i++) {
			$this->routes=array_merge($this->routes,$this->config[$this->hasroutes[$i]]['routes']);
		}
		$routes=new AppRoutesLoader($this->routes);
		return $routes->routesLoader();
	}
}
?>
