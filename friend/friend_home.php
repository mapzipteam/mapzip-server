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

$sql = "SELECT * FROM ".REVIEW_TABLE.$target_id;

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


	
	echo json_encode($to_client);

?>