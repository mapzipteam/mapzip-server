<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");

$value = json_decode(file_get_contents('php://input'), true);

if(($value['build_version'] >= BUILD_VERSION_GARNET) && ($value['build_version'] < BUILD_VERSION_GARNET_END)){
	$to_client = new MapzipResponse($value['debug_mode'], $value['build_version']);
	
	if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
		//echo "connnection error!\n";
		$to_client->setFields("state", DB_CONNECTION_ERROR);
		$to_client->setDebugs("DB connection error", DB_CONNECTION_ERROR);
	}
	else{
		//echo "connect success!\n";
		$sql = "SELECT * FROM ".USER_TABLE." WHERE userid = '{$value['friend_id']}'";
		if(!$result = mysqli_query($conn,$sql)){
			$to_client->setFields("state", SQL_QUERY_ERROR);
			$to_client->setDebugs("select user table friend id", $sql);
		}
		else{
			$row = mysqli_fetch_assoc($result);
			$to_client->setFields('friend_name', $row['username']);
			$to_client->setFields('total_review', $row['total_review']);
			if(is_friend($conn,$value)){
		// already enrolled friend
				$to_client->setFields('is_friend', true);
			}
			else{
			// not enrolled friend
				$to_client->setFields('is_friend', false);
			}
			$to_client->setFields('state', FRIEND_SEARCH_SUCCESS);
		}
		
	}
	echo json_encode($to_client->build());
}else{
	// old client version code
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
}




function is_friend($conn, $value, $to_client){
	$sql = "SELECT * FROM ".FRIEND_TABLE." WHERE from_id = '{$value['user_id']}'";
	if(!$result = mysqli_query($conn,$sql)){
		$to_client->setFields('state', SQL_QUERY_ERROR);
		$to_client->setDebugs('select friend table fail', $sql);
	}
	while($row = mysqli_fetch_assoc($result)){
		
		if(strcmp($value['friend_id'],$row['to_id'])==0){
			return true;
		}

		
	}
	return false;
}

?>
