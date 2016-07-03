<?php
namespace System\Core;

Class User {
	protected $first_name,$last_name;
	protected $email;
	protected $password;
	protected $birthday;
	protected $country;
	protected $gender;

	// Form options
	private $options=[
		'first_name'	=> [
			'max'	=> 15,
			'min'	=> 3,
			'regex'	=> '/^[a-zA-Z]*$/'
		],
		'last_name'		=> [
			'min'	=> 3,
			'max'	=> 30,
			'regex'	=> '/^[a-zA-Z ]*$/'
		],
		'email'			=> [
			'type'	=> 'email'
		],
		'password'		=> [
			'min'	=> 8,
			'max'	=> 16,
			'regex'	=> '/^[a-zA-Z0-9]*$/'
		],
		'repassword'	=> [
			'equal'	=> 'password'
		],
		'birthday'		=> [
			'type'	=> 'date'
		],
		'country'		=> [
			'type'	=> 'country'
		],
		'gender'		=> [
			'choice'	=> ['m','f']
		]
	];
	protected $result=[
		'success'	=> false,
		'error'		=> []
	];
	public function create($data) {
		$this->validate($data);
	}
	private function validate($data) {
		$validator=new Validator($data, $this->options);
		if(count($validator->getError()) > 0) {
			return $this->result['error']=$validator->getError();
		}
		$this->result['success']=true;
		$this->result['successmessage']='Selamat! Akun kamu telah berhasil dibuat. Selanjutnya verifikasi email kamu';
		return true;
	}
	public function getLog() {
		return json_encode($this->result);
	}
}
?>
