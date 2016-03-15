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
	}

	$user_id = $value['user_id'];
	$map_id = $value['map_id'];
	$title = $value['title'];
	$category = $value['category'];
	$hash_tag = $value['hash_tag'];

	$sql = "UPDATE ".CLIENT_TABLE." SET title = '".$title."', category = ".$category.", hash_tag = '".$hash_tag."' where user_id = '{$user_id}' and map_id = ".$map_id;

	if(!$result = mysqli_query($conn,$sql)){
		$to_client->setDebugs("update client table error", $sql);
		$to_client->setFields("state", MAP_SETTING_FAIL);
	}else{
		$to_client->setFields("state", MAP_SETTING_SUCCESS);
	}


	$sql = "SELECT * FROM ".CLIENT_TABLE." WHERE user_id = '{$user_id}'";

	$mapmeta_info_object = array();

	if(!$result = mysqli_query($conn,$sql)){
		$to_client['state']=SQL_QUERY_ERROR;
		$to_client['state_log'] = $sql;
	}else{
		while($row = mysqli_fetch_assoc($result)){
			if($row['type']==CLIENT_TYPE_MAPMETA){
				$map_object = new Map_Metainfo;
				$map_object->map_id = $row['map_id'];
				$map_object->title = $row['title'];
				$map_object->category = $row['category'];			
				$map_object->hash_tag = $row['hash_tag'];
				array_push($mapmeta_info_object, $map_object);

			}
		}
		$to_client->setFields("mapmeta_info", $mapmeta_info_object);
	}
	
	echo json_encode($to_client->build());
}else{
	// old client version code
	$to_client['state'] = NON_KNOWN_ERROR;
	$to_client['mapmeta_info']=array();

	if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
		$to_client['state']=DB_CONNECTION_ERROR;
	}
	else{
//echo "connect success!\n";
	}

	$user_id = $value['userid'];
	$map_id = $value['map_id'];
	$title = $value['title'];
	$category = $value['category'];
	$hash_tag = $value['hash_tag'];

	$sql = "UPDATE ".CLIENT_TABLE." SET title = '".$title."', category = ".$category.", hash_tag = '".$hash_tag."' where user_id = '{$user_id}' and map_id = ".$map_id;

	if(!$result = mysqli_query($conn,$sql)){
		$to_client['state']=$sql;
	}


	$sql = "SELECT * FROM ".CLIENT_TABLE." WHERE user_id = '{$user_id}'";

	if(!$result = mysqli_query($conn,$sql)){
		$to_client['state']=SQL_QUERY_ERROR;
		$to_client['state_log'] = $sql;
	}
	while($row = mysqli_fetch_assoc($result)){
		if($row['type']==CLIENT_TYPE_MAPMETA){
			$map_object = new Map_Metainfo;
			$map_object->map_id = $row['map_id'];
			$map_object->title = $row['title'];
			$map_object->category = $row['category'];			
			$map_object->hash_tag = $row['hash_tag'];
			array_push($to_client['mapmeta_info'],$map_object);

		}
	}
	echo json_encode($to_client);
}


class Map_Metainfo{
	public $map_id, $title, $category, $hash_tag;

}



?>