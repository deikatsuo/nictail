<?php
if(!__PERMISSION__) {
	exit;
}

include(__ROOT__."/vendor/autoload.php");

use Symfony\Component\ClassLoader\ClassLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader as ServicesLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader as RoutesLoader;
use System\Core\EventGrabber;
use System\Core\Core;
use System\Core\DisMod;

$coreDir=__ROOT__.'/core';
$appDir=__ROOT__.'/app';
$coreAuto=new ClassLoader();
$coreAuto->setUseIncludePath(true);
//Daftarkan Core disini
$coreName=array();
$coreName=[
	'System'				=> $coreDir,
	'App'					=> $appDir,
];
$coreAuto->addPrefixes($coreName);
$coreAuto->register();

//Create request
$request=Request::createFromGlobals();

#Container  | All Services stored here
$container = new ContainerBuilder(new ParameterBag());
$system_services_loader=new ServicesLoader($container,new FileLocator(__ROOT__.'/app'));
$system_services_loader->load('services.yml');

//Event Dispatcher
$dispatcher=$container->get('symfony.dispatcher');
$dispatcher->addListener('connect.system.routing.load', function ($event){
	$e=$event->getResponse()->response();
});

$routes=new RoutesLoader(new FileLocator(__ROOT__.'/app'));

$routes=new DisMod($routes->load('routing.yml'));

//Dispatch event
$container->get('symfony.dispatcher')->dispatch('connect.system.routing.load',new EventGrabber($routes));

$app=new Core($container, $dispatcher);
$handle=$app->handle($routes->response(), $request);
$handle->show()->send();

?>
