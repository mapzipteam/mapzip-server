<?php
include("../fmysql.php");
include("../mapzip-state-define.php");
include("../mapzip-mysql-define.php");
include("../mapzip-gcm-define.php");

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
	$flag_type = REVIEW_FLAG_TYPE_DEFAULT;
	$sql = "INSERT INTO ".REVIEW_TABLE." (user_id, map_id, gu_num, store_x, store_y, store_name, store_address, store_contact, review_emotion, positive_text, negative_text, review_text, image_num, flag_type, created, modified) VALUES ('{$user_id}', {$value['map_id']}, {$value['gu_num']}, {$value['store_x']}, {$value['store_y']}, '{$value['store_name']}','{$value['store_address']}','{$value['store_contact']}',{$value['review_emotion']},'{$value['positive_text']}','{$value['negative_text']}','{$value['review_text']}', {$value['image_num']}, {$flag_type}, now(), now())";
	if(!mysqli_query($conn,$sql)){
		// insert fail
		$to_client['state'] = $sql;
	}else{
		// insert success
		$sql = "UPDATE ".USER_TABLE." SET total_review = total_review+1 WHERE userid = '{$user_id}'";
		$result = mysqli_query($conn,$sql);
		
		$sql = "SELECT pid FROM ".REVIEW_TABLE." WHERE user_id = '{$user_id}' and store_x = {$value['store_x']} AND store_y = {$value['store_y']} AND store_name = '{$value['store_name']}'";
		$result = mysqli_query($conn,$sql);
		if($row = mysqli_fetch_assoc($result)){
			// match success
			$to_client['store_id']=$row['pid'];
			$to_client['state'] = CLIENT_REVIEW_DATA_ENROLL_SUCCESS;

			// gcm to this user's followers
			$headers = array(
        		'Content-Type:application/json'
            
    		);
			$arr = array();
			$arr['from_id'] = $value['userid'];
			$arr['from_name'] = $value['user_name'];
			
			$arr['gcm_type'] = WHEN_FRIEND_NEW_REVIEW;

			$ch = curl_init();
    		curl_setopt($ch, CURLOPT_URL, 'http://ljs93kr.cafe24.com/mapzip/gcm_push/gcm_push_each.php');
    		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    		curl_setopt($ch, CURLOPT_POST, true);
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arr));
    		$response = curl_exec($ch);
    		curl_close($ch);
    		$to_client['response'] = $response;
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
	$sql = "SELECT * FROM ".REVIEW_TABLE." WHERE user_id = '{$user_id}'";
	if(!$result = mysqli_query($conn,$sql)){
		$to_client['state'] = $sql;
	}
	while($row = mysqli_fetch_assoc($result)){
		if($row['map_id'] != $value['map_id']){
			continue;
		}
		if(strcmp($value['store_name'],$row['store_name'])==0 && strcmp($value['store_x'],$row['store_x'])==0 && strcmp($value['store_y'],$row['store_y'])==0){
			
			return false;
		}
	}
	return true;

}

?>