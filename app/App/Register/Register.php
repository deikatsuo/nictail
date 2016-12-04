<?php
namespace App\Register;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use System\Core\App;
use System\Core\Country;
Use GeoIp2\Database\Reader;

Class Register Extends App {
	public $render=[];
	public $current_template='register.twig';

	//-- Main Page --//
	public function indexPage() {
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
		$this->container->get('app.asset-manager')->addFrom('Register',['register.css','register.js']);
		$this->container->get('app.asset-manager')->add(['/uikit/css/components/datepicker.gradient.min.css','/uikit/js/components/datepicker.min.js']);
		//$this->container->get('app.asset-manager')->importJs(['https://www.google.com/recaptcha/api.js']);
		return $this;
	}

	//-- Login Page --//
	public function loginPage() {
		$this->current_template="login.twig";
		$this->render=[
			'title'			=> 'Masuk akun'
		];
		$this->container->get('app.asset-manager')->addFrom('Register',['register.css']);
		return $this;
	}

	//-- Action Page --//
	public function processPage(Request $request) {
		$this->current_template="blank.twig";
		$process=$this->container->get('app.register-process');

		// Recaptcha
		$recaptcha=$request->request->get('g-recaptcha-response');
		$secretKey = "6LeNTPYSAAAAAJwBm3kZ_WZq5ci0dM6tYF1c0Lve";
        
		$data = array(
		            'secret' => $secretKey,
		            'response' => $recaptcha
		        );
		// Recaptcha verify
		$verify = curl_init();
		curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
		curl_setopt($verify, CURLOPT_POST, true);
		curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
		$response = json_decode(curl_exec($verify));

		if($response->success) {
			$process->request($request->request->all());
			$this->render=[
				'data'		=> $process->result()
			];
		}
		else {
			$this->render=[
				'data'		=> json_encode(array('success' => false,'recaptcha' => false))
			];
		}
		return $this;
	}

	public function config() {
		return [
			'path'		=>	__DIR__,
			'template'	=>	$this->current_template
		];
	}

	public function render() {
		return $this->render;
	}
}
?>
