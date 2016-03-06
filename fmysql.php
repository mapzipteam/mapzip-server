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
	$mrb = new MapzipResponse($value['debugmode']);
	$mrb->setFields(key, value);
	$mrb->setDebugs(key, value);
	echo json_encode(mrb->build());

*/

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

class MapzipLogHelper{
	private $conn;
	function __construct($db_connection){
		$this->conn = $db_connection;
	}
}



?>