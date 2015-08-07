<?php

include("fmysql.php");
include("mapzip-login-define.php");
include("../mapzip-state-define.php");

$value = json_decode(file_get_contents('php://input'), true);

$to_client = array('state'=>NON_KNOWN_ERROR,'login'=>0,'username'=>"");

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
	$to_client['state']=LOGIN_SUCCESS;
	$to_client['login']=1;
	$to_client['username']=$username;
	
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

?>