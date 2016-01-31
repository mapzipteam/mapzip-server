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
	// success area
	$to_client['map_meta']=array();
	$data_num = 0;
	while($row = mysqli_fetch_assoc($result)){
		if($row['map_id']==$value['map_id']){
			$data_num+=1;
			$map_object = new Map_Meta;
			$map_object->map_id = $row['map_id'];
			$map_object->store_x = $row['store_x'];
			$map_object->store_y = $row['store_y'];
			$map_object->store_name = $row['store_name'];
			$map_object->store_id = $row['pid'];
			array_push($to_client['map_meta'],$map_object);
		}
	}
	if($data_num!=0){
		// something data in
		$to_client['state'] = CLIENT_REVIEW_META_DOWN_SUCCESS;
	}
	else{
		// there is nothing
		$to_client['state'] = CLIENT_REVIEW_META_DOWN_EMPTY;
	}
}



echo json_encode($to_client);

class Map_Meta{
	public $map_id, $store_x, $store_y, $store_name, $store_id;
}

?>