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

$to_client['state'] = FRIEND_HOME_SUCCESS;
$to_client['mapmeta_info']=array();


	$sql = "SELECT * FROM ".CLIENT_TABLE.$value['target_id'];

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



$sql = "SELECT * FROM ".REVIEW_TABLE.$value['target_id'];

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

	class Map_Metainfo{
	public $map_id, $title, $category, $hash_tag, $created;

}

?>