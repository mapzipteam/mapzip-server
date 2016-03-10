<?php
$value = json_decode(file_get_contents('php://input'), true);

class MapzipResponse{
	private $mDebugMode;
	private $mBuildVersion;
	private $response;
	
	// 디버그 모드를 설정해 주어야 한다
	function __construct($debug_mode, $build_version){
		$this->mDebugMode = $debug_mode;
		$this->mBuildVersion = $build_version;

		$this->response['headers'] = array();
		$this->response['headers']['build_version'] = $build_version;
		$this->response['fields'] = array();
		if($this->mDebugMode){
			// when debug mode requeset
			$this->response['headers']['debug_mode'] = true;
			$this->response['debugs'] = array();
		}
		else{
			// when release mode request
			
			$this->response['headers']['debug_mode'] = false;
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

define("BUILD_VERSION_EMERALD", 5);

$mrb = new MapzipResponse($value['debug_mode'], $value['build_version']);
$mrb->setFields("key1", "value1");
$mrb->setFields("key2", "value2");
$mrb->setDebugs("key1", "value1");
echo json_encode($mrb->build());
?>