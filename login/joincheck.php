<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");

//echo "id:".$user_id." pw:".$user_pw." name:".$username." from server\n";

$value = json_decode(file_get_contents('php://input'), true);

$to_client = array('state'=>NON_KNOWN_ERROR,'login'=>0);


$user_id = $value['userid'];
$user_pw = $value['userpw'];
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
		//$sql = "CREATE TABLE ".
		

		//$sql = "CREATE TABLE mz_mapmeta_".$user_id." ( pid int(11) NOT NULL AUTO_INCREMENT, title varchar(255) NOT NULL, category int(11) NOT NULL, created datetime NOT NULL, PRIMARY KEY(pid));";
		//$sql = "CREATE TABLE ".CLIENT_TABLE.$user_id." ( pid int(11) NOT NULL AUTO_INCREMENT, type int(11) NOT NULL, title varchar(255) , category int(11) , hash_tag text , friend_id varchar(255),  created datetime NOT NULL, PRIMARY KEY(pid));";
		//mysqli_query($conn,$sql);
		
		$sql = "INSERT INTO ".CLIENT_TABLE." (user_id, map_id, type, title, category, hash_tag, created) VALUES ('{$user_id}', 1, 1, '나만의 지도1',1,'#해#쉬#태#그#맛집',now())";
		if(!mysqli_query($conn,$sql)){
			$to_client['state_log'] = $sql;
		}
		$sql = "INSERT INTO ".CLIENT_TABLE." (user_id, map_id, type, title, category, hash_tag, created) VALUES ('{$user_id}', 2, 1, '나만의 지도2',1,'#해#쉬#태#그#맛집',now())";
		mysqli_query($conn,$sql);

		//$sql = "CREATE TABLE ".REVIEW_TABLE.$user_id." (pid int(11) NOT NULL AUTO_INCREMENT, map_id int(11) NOT NULL, gu_num int(11) NOT NULL, store_x double, store_y double, store_name varchar(255), store_address text, store_contact text, review_emotion int(11), review_text text, image_num int(11), PRIMARY KEY(pid));";
		//mysqli_query($conn,$sql);

		$sql = "INSERT INTO ".GCM_TABLE." (user_id, type) VALUES ('{$value['userid']}', 1);";
		mysqli_query($conn,$sql);  
		
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