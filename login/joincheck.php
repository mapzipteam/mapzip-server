<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");
include("../mapzip-imgdir-module.php");

//echo "id:".$user_id." pw:".$user_pw." name:".$username." from server\n";

$value = json_decode(file_get_contents('php://input'), true);

if($value['build_version'] >= BUILD_VERSION_EMERALD){
	$to_client = new MapzipResponse($value['debug_mode'], $value['build_version']);

	$user_id = $value['user_id'];
	$user_pw = $value['user_pw'];
	$user_name = $value['user_name'];

	if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
		$to_client->setFields("state", DB_CONNECTION_ERROR);
		$to_client->setDebugs("DB connection error", DB_CONNECTION_ERROR);
	
	}
	else{
	//echo "connect success!\n";
	}
	$sql = "SELECT * FROM ".USER_TABLE;

	if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
		$to_client->setFields("state", SQL_QUERY_ERROR);
		$to_client->setDebugs("select user_table query error", $sql);
	}

	if(joinOK($user_id,$result)){
		$sql = "INSERT INTO ".USER_TABLE." (userid, username, userpw, created) VALUES ('".$user_id."','".$user_name."','".$user_pw."',now())";
		if(mysqli_query($conn,$sql)){
		
			$sql = "INSERT INTO ".CLIENT_TABLE." (user_id, map_id, type, title, category, hash_tag, created) VALUES ('{$user_id}', 1, 1, '나만의 지도1',1,'#해#쉬#태#그#맛집',now())";
			if(!mysqli_query($conn,$sql)){
				$to_client->setDebugs('insert client table error', $sql);
			}
			$sql = "INSERT INTO ".CLIENT_TABLE." (user_id, map_id, type, title, category, hash_tag, created) VALUES ('{$user_id}', 2, 1, '나만의 지도2',1,'#해#쉬#태#그#맛집',now())";
			if(!mysqli_query($conn,$sql)){
				$to_client->setDebugs('insert client table error', $sql);
			}	

			$sql = "INSERT INTO ".GCM_TABLE." (user_id, type) VALUES ('{$value['userid']}', 1);";
			if(!mysqli_query($conn,$sql)){
				$to_client->setDebugs('insert gcm table error', $sql);
			}  

			$imgdir_manager = new MapzipImageDir();
			$dir_name = "../client_data/client_".$value['user_id'];
			$imgdir_manager->setClientDirPath($dir_name);
		
			$to_client->setFields('state_imgdir', $imgdir_manager->executeClientDir());
			$to_client->setFields('state', JOIN_SUCCESS);
		

			echo json_encode($to_client->build());
		

		}
		else{
		//echo "insert fail...\n";
			$to_client->setFields('state', JOIN_FAIL_INSERT_ERRPR);
			$to_client->setDebugs('insert user table error', $sql);
		
			echo json_encode($to_client->build());
		
		}
	

	}
	else{
	//echo("That id already exists in server\n");
		$to_client->setFields('state', JOIN_FAIL_ALREADY_ERROR);
		$to_client->setDebugs('join check error', "That id already exists in server");
	
		echo json_encode($to_client->build());
	
	}
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