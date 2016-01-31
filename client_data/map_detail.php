<?php
include("../fmysql.php");
include("../mapzip-state-define.php");
include("../mapzip-mysql-define.php");

$value = json_decode(file_get_contents('php://input'), true);

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}

$sql = "SELECT * FROM ".REVIEW_TABLE." WHERE user_id = '{$value['userid']}'";


if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
	$to_client['state']=SQL_QUERY_ERROR;
}
else{
	// success
	$to_client['map_detail']=array();

	while($row = mysqli_fetch_assoc($result)){
		if($row['pid']==$value['store_id']){
			$map_object = new Map_Detail;
			$map_object->store_id = $row['pid'];
			$map_object->gu_num = $row['gu_num'];
			$map_object->map_id = $row['map_id'];
			$map_object->store_name = $row['store_name'];
			$map_object->store_address = $row['store_address'];
			$map_object->store_contact = $row['store_contact'];
			$map_object->review_emotion = $row['review_emotion'];
			$map_object->review_text = $row['review_text'];
			$map_object->image_num = $row['image_num'];
			array_push($to_client['map_detail'],$map_object);
		}
	}
	$to_client['state'] = CLIENT_REVIEW_DETAIL_DOWN_SUCCESS;
}


echo json_encode($to_client);

class Map_Detail{
	public $store_id, $gu_num, $store_address, $store_contact, $review_emotion, $review_text, $image_num;
}

?>