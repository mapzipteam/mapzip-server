<?php

include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");

$value = json_decode(file_get_contents('php://input'), true);

$to_client = array('state'=>NON_KNOWN_ERROR);

$user_id = $value['userid'];
$user_pw = $value['userpw'];


if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}

mysql_query("set session character_set_connection=utf8;");
mysql_query("set session character_set_results=utf8;");
mysql_query("set session character_set_client=utf8;");

$sql = "SELECT * FROM ".USER_TABLE;


if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
	$to_client['state']=SQL_QUERY_ERROR;
}



if($username = loginOK($user_id,$user_pw,$result)){
	//echo("login success\n");a
	//echo "1".iconv("utf-8","euc-kr",$username);
	
	$to_client['username']=$username;
	$to_client['state'] = LOGIN_SUCCESS;
	$to_client['mapmeta_info']=array();


	$sql = "SELECT * FROM ".CLIENT_TABLE.$user_id;

	if(!$result = mysqli_query($conn,$sql)){
		$to_client['state']=SQL_QUERY_ERROR;
	}
	while($row = mysqli_fetch_assoc($result)){
		if($row['type']==CLIENT_TYPE_MAPMETA){
			$map_object = new Map_Metainfo;
			$map_object->map_id = $row['pid'];
			$map_object->title = $row['title'];
			$map_object->category = $row['category'];
			$map_object->created = $row['created'];
			$map_object->hash_tag = $row['hash_tag'];
			array_push($to_client['mapmeta_info'],$map_object);

		}
		 
	}
	$sql = "SELECT * FROM ".REVIEW_TABLE.$user_id;

	if(!$result = mysqli_query($conn,$sql)){
		$to_client['state']=SQL_QUERY_ERROR;
	}

	$to_client['gu_enroll_num'] = array();
	$is_gu_enroll=0;
	while($row = mysqli_fetch_assoc($result)){
		$is_gu_enroll=1;
		$gu_num = $row['gu_num'];
		$map_id = $row['map_id'];
		$to_client['gu_enroll_num']["{$map_id}"]["{$gu_num}"]+=1;
	}
	$sql = "UPDATE ".GCM_TABLE." SET gcm_key = '{$value['gcm_key']}' WHERE user_id = '{$value['userid']}';";
	if(!mysqli_query($conn,$sql)){
		$to_client['state'] = USER_GCM_UPDATE_FAIL;
	}
	if($is_gu_enroll){
		$to_client['gu_enroll_num']['state'] = CLIENT_REVIEW_META_DOWN_SUCCESS;
		
	}
	else{
		$to_client['gu_enroll_num']['state'] = CLIENT_REVIEW_META_DOWN_EMPTY;
		
	}


	
	echo json_encode($to_client);
	
}
else{
	//echo("login fail...\n");
	if($to_client['state']==NON_KNOWN_ERROR){
		// 그전에는 오류가 없었다는 의미이니까 로그인 오류를 넣으면 된다
		$to_client['state']=LOGIN_FAIL;
	}
	else{
		// 그전에 오류가 있는 경우이므로 그대로 출력하면 된다
	}
	$to_client['login']=0;
	echo json_encode($to_client);
	
}

function loginOK($id,$pw,$result){
	while($row = mysqli_fetch_assoc($result)){
		if(strcmp($id,$row['userid'])==0 && strcmp($pw,$row['userpw'])==0){
			
			return $row['username'];
		}
	}
	return false;
}

class Map_Metainfo{
	public $map_id, $title, $category, $hash_tag, $created;

}



?>