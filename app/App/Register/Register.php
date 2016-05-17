<?php
namespace App\Register;

use System\Core\App;
use System\Core\Country;
Use GeoIp2\Database\Reader;

Class Register Extends App {
	public $render=[];
	public function index() {
		$vCountryIso="";
		try {
			$readIp=new Reader(_root."/GeoLite2-Country.mmdb");
			$vCountry=$readIp->Country($_SERVER['REMOTE_ADDR']);
			$vCountryIso=$vCountry->country->isoCode;
		}
		catch (\GeoIp2\Exception\AddressNotFoundException $e) {
			//Error atau IP tidak terdaftar didatabase
		}

		$this->render=[
			'title'			=> 'Daftar akun baru',
			'country'		=> new \System\Core\Country(),
			'vCountryIso'	=> $vCountryIso
		];
		$this->container->get('app.asset-manager')->addTo('Register',['register.js']);
		return $this;
	}

	public function config() {
		return [
			'path'		=>	__DIR__,
			'template'	=>	'register.twig'
		];
	}

	public function render() {
		return $this->render;
	}
}
?>
