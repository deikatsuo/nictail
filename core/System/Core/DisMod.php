<?php
namespace System\Core;

Class DisMod {
	protected $response;
	public function __construct($response) {
		$this->response=$response;
		return $this;
	}

	#Return the responses
	public function response() {
		return $this->response;
	}

	#Convert response to array
	public function toArray() {
		return (array) $this->response;
	}

	#Modifiy response valua.. Identified by array  index name
	public function set($set, $nset) {
		$this->response[$set]=$nset;
	}

}
?>