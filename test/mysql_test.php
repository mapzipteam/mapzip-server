<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
    //echo "connnection error!\n";
    $to_client['state_log'] .= "connect mysql fail...\n";
    
}
else{
    $to_client['state_log'] .= "connect mysql success!\n";
}

$client_table = CLIENT_TABLE.$_GET['user_id'];
$gcm_table = GCM_TABLE;

$sql = "SELECT {$client_table}.friend_id, {$gcm_table}.gcm_key FROM {$client_table} INNER JOIN {$gcm_table} ON {$client_table}.type = 2 and {$client_table}.friend_id = {$gcm_table}.user_id";
if(!$result = mysqli_query($conn,$sql)){
	echo $sql;
}else{
	//print_r($result);
	while($row = mysqli_fetch_assoc($result)){
		echo "friend_id : ".$row['friend_id']." gcm_key : ".$row['gcm_key']."<br>";
	}
}


?>