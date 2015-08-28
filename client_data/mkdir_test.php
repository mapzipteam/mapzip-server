<?php
include("../mapzip-state-define.php");

$value = json_decode(file_get_contents('php://input'), true);


$dir_name = "./client_".$value['userid']."_".$value['map_id']."_".$value['store_name']."|".$value['store_x']."|".$value['store_y'];
if(is_dir($dir_name)){
	$to_client['state']=CLIENT_REVIEW_IMAGE_MKDIR_EXIST;
}
else{
	
	if(@mkdir($dir_name,0777)){
		$to_client['state'] = CLIENT_REVIEW_IMAGE_MKDIR_SUCCESS;
	}
	else{
		$to_client['state'] = CLIENT_REVIEW_IMAGE_MKDIR_FAIL;
	}
}

echo json_encode($to_client);

?>