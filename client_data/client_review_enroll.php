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

$user_id = $value['userid'];

if(Enroll_OK($conn,$user_id,$value)){
	// enroll ok
	$sql = "INSERT INTO ".REVIEW_TABLE.$user_id." (map_id, gu_num, store_x, store_y, store_name, store_address, store_contact, review_emotion, review_text, image_num) VALUES ({$value['map_id']}, {$value['gu_num']}, {$value['store_x']}, {$value['store_y']}, '{$value['store_name']}','{$value['store_address']}','{$value['store_contact']}',{$value['review_emotion']},'{$value['review_text']}', {$value['image_num']})";
	if(!mysqli_query($conn,$sql)){
		// insert fail
		$to_client['state'] = $sql;
	}else{
		// insert success
		$sql = "SELECT pid FROM ".REVIEW_TABLE.$user_id." WHERE store_x = {$value['store_x']} AND store_y = {$value['store_y']} AND store_name = '{$value['store_name']}'";
		$result = mysqli_query($conn,$sql);
		if($row = mysqli_fetch_assoc($result)){
			// match success
			$to_client['store_id']=$row['pid'];
			$to_client['state'] = CLIENT_REVIEW_DATA_ENROLL_SUCCESS;
		}
		else{
			// match fail
			$to_client['state'] = $sql;

		}
		
	}
}
else{
	$to_client['state'] = CLIENT_REVIEW_DATA_ENROLL_EXIST;
}




echo json_encode($to_client);


function Enroll_OK($conn,$user_id,$value){
	// $sql = "SELECT * FROM ".REVIEW_TABLE.$user_id;
	// if(!$result = mysqli_query($conn,$sql)){
	// 	$to_client['state'] = $sql;
	// }
	// while($row = mysqli_fetch_assoc($result)){
	// 	if(strcmp($value['store_name'],$row['store_name'])==0 && strcmp($value['store_x'],$row['store_x'])==0 && strcmp($value['store_y'],$row['store_y'])==0){
			
	// 		return false;
	// 	}
	// }
	return true;

}

?>