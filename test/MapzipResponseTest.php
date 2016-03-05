<?php
class MapzipResponse{
	private $mDebugMode;
	private $response;
	
	// 디버그 모드를 설정해 주어야 한다
	function __construct($debugmode){
		$this->mDebugMode = $debugmode;
		if($this->mDebugMode){
			// when debug mode requeset
			$this->response['headers'] = array();
			$this->response['headers']['debugmode'] = true;
			$this->response['fields'] = array();
			$this->response['debugs'] = array();
		}
		else{
			// when release mode request
			$this->response['headers'] = array();
			$this->response['headers']['debugmode'] = false;
			$this->response['fields'] = array();
		}
	}

	function setHeaders($headerkey, $headervalue){
		$this->response['headers']["{$headerkey}"] = $headervalue;
	}

	function setFields($fieldkey, $fieldvalue){
		$this->response['fields']["{$fieldkey}"] = $fieldvalue;
	}

	function setDebugs($debugkey, $debugvalue){
		if($this->mDebugMode){
			array_push($this->response['debugs'],"{$debugkey} : {$debugvalue}");
		}else{
			// ignore
		}
	}

	function build(){
		return $this->response;
	}
}

$mrb = new MapzipResponse(true);
$mrb->setFields("key1", "value1");
$mrb->setFields("key2", "value2");
$mrb->setDebugs("key1", "value1");
echo json_encode($mrb->build());
?>