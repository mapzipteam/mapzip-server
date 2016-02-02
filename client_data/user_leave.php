<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");
include("client_rmdir.php");
include("../user-leave-module.php");

// if($_POST['from_forserver'] === 'ok'){
//     $value['target_id'] = $_POST['target_id'];
// }

    
$value = json_decode(file_get_contents('php://input'), true);

$to_client = array();

$to_client['state_log'] = "";
$to_client['state'] = NON_KNOWN_ERROR;
    
    if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
    //echo "connnection error!\n";
        $to_client['state_log'] .= "connect mysql fail...\n";
        $to_client['state'] = LEAVE_FAIL_SERIOUS;
    }
    else{
        $to_client['state_log'] .= "connect mysql success!\n";
    }

    $user_leave = new MapzipUserLeave($conn);

    $user_leave->setTargetId($value['target_id']);
    $to_client = $user_leave->execute();


    
    echo(json_encode($to_client));

    

?>