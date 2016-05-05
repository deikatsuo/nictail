<?php
namespace System\Core;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollection;


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
		$this->container->compile();
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
            $config=[
				'container'	=> $container,
            ];
            $key=array_keys($config);
            for($i=0;$i<count($key);$i++) {
				if(array_key_exists($key[$i],$controller[0])) {
					$controller[0]->$key[$i]=$config[$key[$i]];
				}
			}
			
            return call_user_func_array($controller, $arguments);
		}
		catch (Routing\Exception\ResourceNotFoundException $e) {
			$response = new Response('Not Found', 404);
		} 
		catch (Exception $e) {
			$response = new Response('An error occurred', 500);
		}
	}
}
?>
