<?php
if(!_permision) {
	exit;
}
include(_root."/vendor/autoload.php");

use Symfony\Component\ClassLoader\ClassLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;
use Symfony\Component\HttpKernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use System\Core\Core;
use System\Core\AppInit;

$coreDir=_root.'/core';
$appDir=_root.'/app';
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


$request=Request::createFromGlobals();
$container = new ContainerBuilder();
$appInit=new AppInit($container, _root.'/app');
$appInit->runServices();

$app=new Core($container);
$handle=$app->handle($appInit->getRoutes(), $request);
$handle->show()->send();
?>
