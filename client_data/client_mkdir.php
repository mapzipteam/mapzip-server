<?php
include("../mapzip-state-define.php");
include("../mapzip-imgdir-module.php");

$value = json_decode(file_get_contents('php://input'), true);


//$dir_name = "./client_".$value['userid']."_".$value['map_id']."_".$value['store_name']."|".$value['store_cx']."|".$value['store_cy'];
//$dir_name = "./client_".$value['userid']."_".$value['map_id']."_".$value['store_id'];
$imgdir_manager = new MapzipImageDir();
$dir_name = "./client_".$value['userid'];
$imgdir_manager->setClientDirPath($dir_name);
$imgdir_manager->setStoreDirPath($value['store_id']);
$to_client['state_imgdir'] = $imgdir_manager->executeStoreDir();
$to_client['state'] = $to_client['state_imgdir']['state_dir'];
echo json_encode($to_client);

?>