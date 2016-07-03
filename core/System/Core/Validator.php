<?php 
namespace System\Core;

/* Validator */
Class Validator {
	private $validate;
	private $options;
	private $error=[];
	/*
		@param:array data which need to validate
		@param:array options
	*/
	public function __construct($validate,$options) {
		$this->validate=$validate;
		$this->options=$options;
		$this	->checkEmpty()
				->checkMin()
				->checkMax()
				->checkEqual()
				->checkChoice()
				->checkRegex()
				->checkType();
	}
	/*
		Check if data is empty
	*/
	private function checkEmpty() {
		$keys=array_keys($this->options);
		for($i=0;$i<count($keys);$i++) {
			if(empty($this->validate[$keys[$i]])) {
				$this->error[$keys[$i]]['empty']=true;
				$this->validate[$keys[$i]]='';
			}
		}
		return $this;
	}
	// Option, min
	private function checkMin() {
		$keys=array_keys($this->options);
		for($i=0;$i<count($keys);$i++) {
			if($this->opt('min',$this->options[$keys[$i]])) {
				$min=$this->options[$keys[$i]]['min'];
				if(strlen($this->validate[$keys[$i]]) < $min) {
					$this->error[$keys[$i]]['min']=true;
				}
			}
		}
		return $this;
	}
	private function checkMax() {
		$keys=array_keys($this->options);
		for($i=0;$i<count($keys);$i++) {
			if($this->opt('max',$this->options[$keys[$i]])) {
				$max=$this->options[$keys[$i]]['max'];
				if(strlen($this->validate[$keys[$i]]) > $max) {
					$this->error[$keys[$i]]['max']=true;
				}
			}
		}
		return $this;
	}
	private function checkEqual() {
		$keys=array_keys($this->options);
		for($i=0;$i<count($keys);$i++) {
			if($this->opt('equal',$this->options[$keys[$i]])) {
				$equal=$this->options[$keys[$i]]['equal'];
				if($this->validate[$keys[$i]] !== $this->validate[$equal]) {
					$this->error[$keys[$i]]['equal']=$equal;
				}
			}
		}
		return $this;
	}
	private function checkChoice() {
		$keys=array_keys($this->options);
		for($i=0;$i<count($keys);$i++) {
			if($this->opt('choice',$this->options[$keys[$i]])) {
				$choice=$this->options[$keys[$i]]['choice'];
				$validate=$this->validate[$keys[$i]];
				$keys=$keys[$i];
			}
		}
		if(!in_array($validate, $choice)) {
			$this->error[$keys]['choice']='Invalid';
		}
		return $this;
	}
	private function checkRegex() {
		$keys=array_keys($this->options);
		for($i=0;$i<count($keys);$i++) {
			if($this->opt('regex',$this->options[$keys[$i]])) {
				$regex=$this->options[$keys[$i]]['regex'];
				if(!preg_match($regex, $this->validate[$keys[$i]])) {
					$this->error[$keys[$i]]['regex']='invalid';
				}
			}
		}
		return $this;
	}
	private function email($email) {
		$email=filter_var($email, FILTER_SANITIZE_EMAIL);
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}
	private function country($countryId) {
		$country=new Country();
		return $country->countryName($countryId);
	}
	private function date($date) {
		$date = \DateTime::createFromFormat('d-m-Y', $date);
		$date_errors = \DateTime::getLastErrors();
		if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
		    return false;
		}
		return true;
	}
	private function checkType() {
		$keys=array_keys($this->options);
		for($i=0;$i<count($keys);$i++) {
			if($this->opt('type',$this->options[$keys[$i]])) {
				if(!$this->{$this->options[$keys[$i]]['type']}($this->validate[$keys[$i]])) {
					$this->error[$keys[$i]]['type']='invalid';
				}
			}
		}
		return $this;
	}
	private function opt($opt, $check) {
		if(array_key_exists($opt, $check)) {
			return true;
		}
	}
	/*
		Get error message
		@return:array error message
	*/
	public function getError() {
		return $this->error;
	}
}
?>