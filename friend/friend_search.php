<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");

$value = json_decode(file_get_contents('php://input'), true);

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}

$sql = "SELECT * FROM ".USER_TABLE." WHERE userid = '{$value['friend_id']}'";
if(!$result = mysqli_query($conn,$sql)){
	$to_client['state']=SQL_QUERY_ERROR;
}
else{

	$row = mysqli_fetch_assoc($result);

	$to_client['friend_name'] = $row['username'];
	$to_client['total_review'] = $row['total_review'];
	if(is_friend($conn,$value)){
		// already enrolled friend
		$to_client['is_friend'] = 1;
	}
	else{
			// not enrolled friend
		$to_client['is_friend'] = 0;
	}

	
	$to_client['state'] = "success";
}
echo json_encode($to_client);


function is_friend($conn,$value){
	$sql = "SELECT * FROM ".CLIENT_TABLE.$value['user_id'];
	if(!$result = mysqli_query($conn,$sql)){
		$to_client['state']=SQL_QUERY_ERROR;
	}
	while($row = mysqli_fetch_assoc($result)){
		if($row['type'] == CLIENT_TYPE_FRIEND){
			if(strcmp($value['friend_id'],$row['friend_id'])==0){
				return true;
			}

		}
	}
	return false;
}

?>
