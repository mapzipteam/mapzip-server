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

$sql = "SELECT * FROM ".CLIENT_TABLE.$value['userid'];


if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
	$to_client['state']=$sql;
}
else{
	$to_client['friend_list'] = array();
	while($row = mysqli_fetch_assoc($result)){
		if($row['type'] == CLIENT_TYPE_FRIEND){
			// only friend info
			$friend_object = new Friend_Item;
			$sql_2 = "SELECT * FROM ".USER_TABLE." WHERE userid = '{$row['friend_id']}'";
			$result2 = mysqli_query($conn,$sql_2);
			$row2 = mysqli_fetch_assoc($result2);
			$friend_object->user_id = $row2['userid'];
			$friend_object->user_name = $row2['username'];
			$friend_object->total_review = $row2['total_review'];
			array_push($to_client['friend_list'], $friend_object);
		}

	}
	$to_client['state']="show success";
	echo json_encode($to_client);
}






class Friend_Item{
	public $user_id, $user_name, $total_review;
}

?>