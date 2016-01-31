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

$friend_table = FRIEND_TABLE;
$gcm_table = GCM_TABLE;
$target_id = $_GET['target_id'];

$sql = "SELECT {$friend_table}.from_id, {$gcm_table}.gcm_key FROM {$friend_table} INNER JOIN {$gcm_table} ON {$friend_table}.to_id = '{$target_id}' and {$friend_table}.from_id = {$gcm_table}.user_id";
if(!$result = mysqli_query($conn,$sql)){
	echo $sql;
}else{
	//print_r($result);
	echo("something");
	while($row = mysqli_fetch_assoc($result)){
		echo "from_id : ".$row['from_id']." gcm_key : ".$row['gcm_key']."<br>";
	}
}


?>