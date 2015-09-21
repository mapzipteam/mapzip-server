<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");

//$value = json_decode(file_get_contents('php://input'), true);

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}

$sql = "SELECT * FROM ".USER_TABLE." WHERE userid = '{$_GET['friend_id']}'";
if(!$result = mysqli_query($conn,$sql)){
	$to_client['state']=SQL_QUERY_ERROR;
	echo "error";
}

echo "?";

// echo $result['username'];

$row = mysqli_fetch_assoc($result);
echo $row['username'];

?>