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
	
	$map_meta_info_object = array();

	$sql = "SELECT * FROM ".CLIENT_TABLE." WHERE user_id = '{$value['target_id']}'";

	if(!$result = mysqli_query($conn,$sql)){
		$to_client->setFields('state', SQL_QUERY_ERROR);
		$to_client->setDebugs("select client table fail", $sql);
	}else{
		while($row = mysqli_fetch_assoc($result)){
			if($row['type']==CLIENT_TYPE_MAPMETA){
				$map_object = new Map_Metainfo;
				$map_object->map_id = $row['map_id'];
				$map_object->title = $row['title'];
				$map_object->category = $row['category'];
				$map_object->created = $row['created'];
				$map_object->hash_tag = $row['hash_tag'];
				array_push($map_meta_info_object, $map_object);
			}
		}
		$to_client->setFields('mapmeta_info', $map_meta_info_object);

		$sql = "SELECT * FROM ".REVIEW_TABLE." WHERE user_id = '{$value['target_id']}'";
		if(!$result = mysqli_query($conn,$sql)){
			$to_client->setFields("state", SQL_QUERY_ERROR);
			$to_client->setDebugs("select review_table query error", $sql);
		}else{
			$gu_enroll_num_object = array();
			$is_gu_enroll=0;
			while($row = mysqli_fetch_assoc($result)){
				$is_gu_enroll=1;
				$gu_num = $row['gu_num'];
				$map_id = $row['map_id'];
				$gu_enroll_num_object["{$map_id}"]["{$gu_num}"]+=1;
			}
			if($is_gu_enroll){
				$gu_enroll_num_object['state'] = CLIENT_REVIEW_META_DOWN_SUCCESS;

			}
			else{
				$gu_enroll_num_object['state'] = CLIENT_REVIEW_META_DOWN_EMPTY;

			}
			$to_client->setFields("gu_enroll_num", $gu_enroll_num_object);

			$sql = "SELECT * FROM ".USER_TABLE." WHERE userid='{$value['target_id']}'";
			if(!$result = mysqli_query($conn,$sql)){
				$to_client->setFields('state', SQL_QUERY_ERROR);
				$to_client->setDebugs("select update_table query error", $sql);
			}else{
				$row = mysqli_fetch_assoc($result);
				$to_client->setFields('user_name', $row['username']);
				$to_client->setFields('state', FRIEND_HOME_SUCCESS);
			}
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

	$to_client['state'] = FRIEND_HOME_SUCCESS;
	$to_client['mapmeta_info']=array();


	$sql = "SELECT * FROM ".CLIENT_TABLE." WHERE user_id = '{$value['target_id']}'";

	if(!$result = mysqli_query($conn,$sql)){
		$to_client['state']=SQL_QUERY_ERROR;
	}
	while($row = mysqli_fetch_assoc($result)){
		if($row['type']==CLIENT_TYPE_MAPMETA){
			$map_object = new Map_Metainfo;
			$map_object->map_id = $row['map_id'];
			$map_object->title = $row['title'];
			$map_object->category = $row['category'];
			$map_object->created = $row['created'];
			$map_object->hash_tag = $row['hash_tag'];
			array_push($to_client['mapmeta_info'],$map_object);

		}

	}



	$sql = "SELECT * FROM ".REVIEW_TABLE." WHERE user_id = '{$value['target_id']}'";

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
	if($is_gu_enroll){
		$to_client['gu_enroll_num']['state'] = CLIENT_REVIEW_META_DOWN_SUCCESS;
		
	}
	else{
		$to_client['gu_enroll_num']['state'] = CLIENT_REVIEW_META_DOWN_EMPTY;
		
	}

	$sql = "SELECT * FROM ".USER_TABLE." WHERE userid='{$value['target_id']}'";
	if(!$result = mysqli_query($conn,$sql)){
		$to_client['state']= SQL_QUERY_ERROR;
	}

	$row = mysqli_fetch_assoc($result);
	$to_client['user_name']  = $row['username'];


	
	echo json_encode($to_client);
}



class Map_Metainfo{
	public $map_id, $title, $category, $hash_tag, $created;

}

?>