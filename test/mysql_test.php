<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");

$to_client['state'] = NON_KNOWN_ERROR;
    
if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
    //echo "connnection error!\n";
    $to_client['state_log'] .= "connect mysql fail...\n";
    $to_client['state'] = LEAVE_FAIL_SERIOUS;
}
else{
    $to_client['state_log'] .= "connect mysql success!\n";
}
$sql = "SELECT count(*) as count_mem FROM ".USER_TABLE;
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
echo("count mem : ".$row['count_mem']);
?>