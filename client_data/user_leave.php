<?php
include("../fmysql.php");
include("../mapzip-state-define.php");
include("../mapzip-mysql-define.php");
include("./client_rmdir.php");

//$value = json_decode(file_get_contents('php://input'), true);

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}

$select_sql = "SELECT * FROM ".USER_TABLE;
if(!$result = mysqli_query($conn, $select_sql)){
	echo("select error");
}else{
	// start 
	while($row = mysqli_fetch_assoc($result)){
		$one_line = "userid : {$row['userid']}";
		echo($one_line."<br>");
	}
}

?>