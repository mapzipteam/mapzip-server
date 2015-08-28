<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");

$value = json_decode(file_get_contents('php://input'), true);

$to_client = array('state'=>NON_KNOWN_ERROR);

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

$sql = "UPDATE ".CLIENT_TABLE.$user_id." SET title = '".$title."', category = ".$category.", hash_tag = '".$hash_tag."' where pid = ".$map_id;

if(!$result = mysqli_query($conn,$sql)){
	$to_client['state']=$sql;
}

UpdateUserInfoHashtag($conn,$user_id,$map_id,$hash_tag);


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
		$map_object->hash_tag = $row['hash_tag'];
		array_push($to_client['mapmeta_info'],$map_object);

	}
}
echo json_encode($to_client);




class Map_Metainfo{
	public $map_id, $title, $category, $hash_tag;

}

function UpdateUserInfoHashtag($conn,$user_id,$map_id,$hash_tag){
	$sql = "SELECT * FROM ".CLIENT_TABLE.$user_id;
	if(!$result = mysqli_query($conn,$sql)){
		$to_client['state']="update userinfo error";
	}
	while($row = mysqli_fetch_assoc($result)){
		if($row['type']==CLIENT_TYPE_MAPMETA){
			if($row['pid']!=$map_id){
				$new_hash = $new_hash.$row['hash_tag']."|";

			}
			else{
				$new_hash = $new_hash.$hash_tag."|";
			}
		}
	}

	$sql = "UPDATE ".USER_TABLE." SET hash_tag = '{$new_hash}' WHERE userid = '{$user_id}'";
	
	if(!$result = mysqli_query($conn,$sql)){
		$to_client['state']=$sql;
	}


}

?>