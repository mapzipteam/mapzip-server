<?php

include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");

$value = json_decode(file_get_contents('php://input'), true);

if(($value['build_version'] >= BUILD_VERSION_GARNET) && ($value['build_version'] < BUILD_VERSION_GARNET_END)){
	$to_client = new MapzipResponse($value['debug_mode'], $value['build_version']);
	
	if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
		$to_client->setFields("state", DB_CONNECTION_ERROR);
		$to_client->setDebugs("DB connection error", DB_CONNECTION_ERROR);
	}
	else{
		// db connection success


		$sql = "SELECT * FROM ".UPDATE_TABLE;

		if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
			$to_client->setFields('state', SQL_QUERY_ERROR);
			$to_client->setDebugs('select update table fail', $sql);
		}else{

			$row = mysqli_fetch_assoc($result);
			$to_client->setFields('version', $row['version']);
			$to_client->setFields('contents', $row['contents']);
			$to_client->setFields('state', PATCH_NOTE_GET_SUCCESS);
		}
	}
	echo json_encode($to_client->build());
}else{
	$to_client = array('state'=>NON_KNOWN_ERROR);

	if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
		$to_client['state']=DB_CONNECTION_ERROR;
	}
	else{
//echo "connect success!\n";
	}

	$sql = "SELECT * FROM ".UPDATE_TABLE;

	if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
		$to_client['state']=SQL_QUERY_ERROR;
	}

	$row = mysqli_fetch_assoc($result);

	$to_client['version'] = $row['version'];
	$to_client['contents'] = $row['contents'];

	echo json_encode($to_client);

}



?>