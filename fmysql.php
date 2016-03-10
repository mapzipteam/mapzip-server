<?php

function connect_mysqli($ip,$user,$password,$db){
	if(!$conn = mysqli_connect($ip,$user,$password)){
		echo "mysql 연결실패<br />";
	}
	//mysql_query("SET NAMES UTF8");
	if(!mysqli_select_db($conn,$db)){
		echo "db 선택 실패<br />";
	}
	
	return $conn;
}

/*
	서버 -> 클라이언트 로 보내는 데이터의 형식을 만들어주는 모듈

	Example #1
	$to_client = new MapzipResponse($value['debug_mode'], $value['build_version']);
	$to_client->setFields(key, value);
	$to_client->setDebugs(key, value);
	echo json_encode($to_client->build());

*/

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

class MapzipLogHelper{
	private $conn;
	function __construct($db_connection){
		$this->conn = $db_connection;
	}
}



?>