<?php
namespace System\Core;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

Class DBase {
	private $config=[
		'dbname'	=> 'mydb',
		'user'		=> 'nictail',
		'password'	=> '1234',
		'host'		=> 'localhost',
		'driver'		=> 'pdo_mysql'
	];
	private $connect;

	public function __construct() {
		$config=new Configuration();
		$this->connect=DriverManager::getConnection($this->config, $config);
	}
	public function connect() {
		return $this->connect;
	}
}
?>