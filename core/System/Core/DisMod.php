<?php
namespace System\Core;

Class DisMod {
	protected $response=[];
	protected $bag=[];
	public function __construct($response) {
		$this->response=$response;
		return $this;
	}

	#Return the responses
	public function response($key=null) {
		if($key) {
			return $this->{$key};
		}
		else {
			return $this->response;
		}
	}

	#Convert response to array
	public function toArray() {
		return (array) $this->response;
	}


	private $sar=[];
	#Modifiy response value.. Identified by array  index name
	public function set($set, $nset) {
		#Remove spaces
		$set=str_replace(' ', '', $set);

		$this->sar=$nset;
		if(strpos($set, ':') && ($set[0] != ':') && ($set[strlen($set)-1] != ':')) {
			#Extract string
			$expd=explode(':',$set);

			$this->arrTpr($expd);
		}
		else {
			$this->{$set}=$nset;
		}
	}

	# 
	private function arrTpr($arr) {
		$this->sar=[$arr[count($arr)-1] => $this->sar];
		unset($arr[count($arr)-1]);
		if(count($arr) > 0) {
			if(count($arr) == 1) {
				isset($this->{$arr[0]}) ? $this->{$arr[0]}=array_replace_recursive($this->{$arr[0]}, $this->sar) : $this->{$arr[0]}=$this->sar;
				unset($this->sar);
			}
			else {
				return $this->arrTpr($arr);
			}
		}

	}
}
?>