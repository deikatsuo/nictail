<?php
namespace System\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use App\PageEvent\PageEvent;
use System\Core\EventGrabber;
use System\Core\DisMod;

Class Core {
	protected $matcher;
	protected $resolver;
	protected $container;

	public function __construct(ContainerBuilder $container) {
		$this->container=$container;
	}

	public function removeSlashEnd($path) {
		$path=substr($path,0,-1);
		if(substr($path,-1) == '/') {
			return $this->removeSlashEnd($path);
		}
		return $path;
	}

	public function handle(RouteCollection $routes, Request $request) {
		$this->container->setParameter('routes',$routes);
		$this->container->setParameter('template_dir',__ROOT__.'/template');

		// handler
		$this->matcher=$this->container->get('symfony.matcher');
		$this->resolver=$this->container->get('symfony.resolver');

		$this->matcher->getContext()->fromRequest($request);
		$path=$request->getPathInfo();

	         	try {
	         		if(substr($request->getPathInfo(),-1) == '/' && $path != '/') {
				$path=$this->removeSlashEnd($path);
			}

			$request->attributes->add($this->matcher->match($path));

			#controller
		      	$controller = $this->resolver->getController($request);
		         	$arguments = $this->resolver->getArguments($request, $controller);

		         	#load services from application
		         	$app=$controller[0]->getAppName();
		         	$app_services_loader=new YamlFileLoader($this->container,new FileLocator(__ROOT__.'/app/App/'.$app));
		         	$app_services_loader->load('services.yml');
		         	
		         	if($controller[0]->getImport()) {
		         		$app_services=$controller[0]->getImport();
		         		for($i=0;$i<count($app_services);$i++) {
		         			$app_services_loader->load('../'.$app_services[$i].'/services.yml');
		         		}
		         	}

			$controllerAlias=$controller;
			$controllerAlias[1]='setContainer';

	            		$response=call_user_func_array($controllerAlias, [$this->container]);
	            		$response->appExec($controller,$arguments);
		}
		catch (\Exception $e) {
			$event=new PageEvent($e);
			$event->container=$this->container;
			return $event->index();
		}
		$this->container->get('symfony.dispatcher')->dispatch('connect.system.appload', new EventGrabber(new DisMod($response), $request));
		return $response;
	}
}
?>
