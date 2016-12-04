<?php
namespace System\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollection;
use App\PageEvent\PageEvent;


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
		$this->container->setParameter('template_dir',_root.'/template');
		// $this->container->compile();
		$container=$this->container;

		// handler
		$this->matcher=$container->get('symfony.matcher');
		$this->resolver=$container->get('symfony.resolver');

		$this->matcher->getContext()->fromRequest($request);
		$path=$request->getPathInfo();
		try {
			if(substr($request->getPathInfo(),-1) == '/' && $path != '/') {
				$path=$this->removeSlashEnd($path);
			}
			$request->attributes->add($this->matcher->match($path));
		      	$controller = $this->resolver->getController($request);
		         	$arguments = $this->resolver->getArguments($request, $controller);

		          	$controllerAlias=$controller;
			$controllerAlias[1]='setContainer';

            			$response=call_user_func_array($controllerAlias, [$container]);
            			$send_response=$response->appExec($controller,$arguments);
		}
		catch (\Exception $e) {
			$event=new PageEvent($e);
			$event->container=$container;
			return $event->index();
		}

		return $send_response;
	}
}
?>
