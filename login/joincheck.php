<?php
include("fmysql.php");
include("mapzip-login-define.php");
include("../mapzip-state-define.php");

//echo "id:".$user_id." pw:".$user_pw." name:".$username." from server\n";

$value = json_decode(file_get_contents('php://input'), true);

$to_client = array('state'=>NON_KNOWN_ERROR,'login'=>0,'username'=>"");


$user_id = $value['userid'];
$user_pw = $vaule['userpw'];
$user_name = $value['username'];

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}
$sql = "SELECT * FROM ".USER_TABLE;

if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
	$to_client['state']=SQL_QUERY_ERROR;
}

if(joinOK($user_id,$result)){
	$sql = "INSERT INTO ".USER_TABLE." (userid, username, userpw, created) VALUES ('".$user_id."','".$user_name."','".$user_pw."',now())";
	if(mysqli_query($conn,$sql)){
		//echo("join success!\n");
		$to_client['state']=JOIN_SUCCESS;
		$to_client['join']=1;
		
		echo json_encode($to_client);
		

	}
	else{
		//echo "insert fail...\n";
		$to_client['state']=JOIN_FAIL_INSERT_ERRPR;
		$to_client['join']=0;
		
		echo json_encode($to_client);
		
	}
	

}
else{
	//echo("That id already exists in server\n");
	$to_client['state']=JOIN_FAIL_ALREADY_ERROR;
	$to_client['join']=0;
	echo json_encode($to_client);
	
}





function joinOK($id,$result){
	while($row = mysqli_fetch_assoc($result)){
		if(strcmp($id,$row['userid'])==0){
			
			return false;
		}
	}
	return true;
}

?>