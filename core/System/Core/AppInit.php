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
	protected $apps_loaded=[];
	protected $apps_config=[];
	protected $loaded=0;
	protected $load_services=[];
	protected $hasservices=[];
	protected $hasparameters=[];
	protected $routes=[];
	protected $hasroutes=[];
	protected $k_m=['execute','lang','services','parameters'];
	protected $lang;
	protected $execute, $services, $parameters = [];
	
	public function __construct(ContainerBuilder $container, $dir) {
		$this->container=$container;
		$yaml=new Parser();
		$this->dir=$dir;
		$run=$yaml->parse(file_get_contents($this->dir.'/app_config.yml'));
		for($i=0;$i<count($run);$i++) {
			if(array_key_exists($this->k_m[$i], $run)) {
				$this->{$this->k_m[$i]}=$run[$this->k_m[$i]];
			}
		}
		$this->apps=str_replace(' ','',explode(',',$this->execute));
		for($i=0;$i<count($this->apps);$i++) {
			if(file_exists($this->dir.'/'.$this->apps[$i].'/config.yml')) {
				$this->loaded++;
				array_push($this->apps_loaded,$this->apps[$i]);
				$this->apps_config[$this->apps[$i]]=$yaml->parse(file_get_contents($this->dir.'/'.$this->apps[$i].'/config.yml'));
				if(array_key_exists('services',$this->apps_config[$this->apps[$i]])) {
					array_push($this->hasservices,$this->apps[$i]);
				}
				if(array_key_exists('parameters',$this->apps_config[$this->apps[$i]])) {
					array_push($this->hasparameters,$this->apps[$i]);
				}
				if(array_key_exists('routes',$this->apps_config[$this->apps[$i]])) {
					array_push($this->hasroutes,$this->apps[$i]);
				}
			}
		}
	}
	
	public function runServices() {
		$services=[];
		$parameters=[];
		for($i=0;$i<count($this->hasservices);$i++) {
			$services=array_merge($services,$this->apps_config[$this->hasservices[$i]]['services']);
		}
		for($i=0;$i<count($this->hasparameters);$i++) {
			$parameters=array_merge($parameters,$this->apps_config[$this->hasparameters[$i]]['parameters']);
		}
		$this->load_services['services']=array_merge($this->services,$services);
		$this->load_services['parameters']=array_merge($this->parameters,$parameters);
		$services=new AppServicesLoader($this->container,$this->load_services);
		return $services->servicesLoader();
	}
	
	/* Routers
	 * 
	 * @return RouteCollections
	*/
	public function getRoutes() {
		for($i=0;$i<count($this->hasroutes);$i++) {
			$this->routes=array_merge($this->routes,$this->apps_config[$this->hasroutes[$i]]['routes']);
		}
		$routes=new AppRoutesLoader($this->routes);
		return $routes->routesLoader();
	}
}
?>
