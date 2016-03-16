<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");

define("MAP_SEARCH_TYPE_ALL", 0);
define("MAP_SEARCH_TYPE_HASH", 1);

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

		$more = $value['more'];
		$target_word = $value['target'];

		$contents_count = 6;
		$more *=$contents_count;

		$sql = "SELECT * FROM ".CLIENT_TABLE." WHERE user_id <> '{$value['user_id']}' ORDER BY created DESC";

		if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
			$to_client->setFields('state', SQL_QUERY_ERROR);
			$to_client->setDebugs('select client table fail', $sql);
		}else{
			
			$map_search_object = array();

			while($row = mysqli_fetch_assoc($result)){
				if($value['type'] == MAP_SEARCH_TYPE_ALL){
		// all search
					if($more>0){
						$more -=1;
						continue;
					}
					if($contents_count<=0){
						break;
					}
					$search_object = new Map_Search;
					$search_object->user_id = $row['user_id'];
					$search_object->category = $row['category'];
					$search_object->hash_tag = $row['hash_tag'];
					$sql2 = "SELECT userid, username FROM ".USER_TABLE." WHERE userid = '{$row['user_id']}'";
					$result2 = mysqli_query($conn,$sql2);
					$row2 = mysqli_fetch_assoc($result2);
					$search_object->user_name = $row2['username'];
					array_push($map_search_object, $search_object);

					$contents_count -= 1;	

				}else if($value['type'] == MAP_SEARCH_TYPE_HASH){
		// filter by hash
					if(strpos($row['hash_tag'], $target_word)==true){
			// 타겟해시가 포함되어 있다면
						if($more>0){
							$more -=1;
							continue;
						}
						if($contents_count<=0){
							break;
						}
						$search_object = new Map_Search;
						$search_object->user_id = $row['user_id'];
						$search_object->category = $row['category'];
						$search_object->hash_tag = $row['hash_tag'];
						$sql2 = "SELECT userid, username FROM ".USER_TABLE." WHERE userid = '{$row['user_id']}'";
						$result2 = mysqli_query($conn,$sql2);
						$row2 = mysqli_fetch_assoc($result2);
						$search_object->user_name = $row2['username'];
						array_push($map_search_object, $search_object);
						$contents_count -= 1;	
					}
					else{
						// continue;
					}	
				}else{
					// undifined type in value
				}
			}
			$to_client->setFields('map_search', $map_search_object);
			if($more>0){
				$to_client->setFields('state', MAP_SEARCH_NO_MORE);
			}
			else{
				$to_client->setFields('state', MAP_SEARCH_SUCCESS);
			}
		}	
	}
	echo json_encode($to_client->build());
}else{
	// old client version code
	$to_client = array('state'=>NON_KNOWN_ERROR);

	$more = $value['more'];
	$target_word = $value['target'];

	$contents_count = 6;
	$more *=$contents_count;

	if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
		$to_client['state']=DB_CONNECTION_ERROR;
	}

//$sql = "SELECT * FROM ".USER_TABLE;
	$sql = "SELECT * FROM ".CLIENT_TABLE." WHERE user_id <> '{$value['user_id']}' ORDER BY created DESC";

	if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
		$to_client['state']=SQL_QUERY_ERROR;
	}

	$to_client['map_search']=array();



	while($row = mysqli_fetch_assoc($result)){
		if($value['type'] == MAP_SEARCH_TYPE_ALL){
		// all search
			if($more>0){
				$more -=1;
				continue;
			}
			if($contents_count<=0){
				break;
			}
			$search_object = new Map_Search;
			$search_object->user_id = $row['user_id'];
			$search_object->category = $row['category'];
			$search_object->hash_tag = $row['hash_tag'];
			$sql2 = "SELECT userid, username FROM ".USER_TABLE." WHERE userid = '{$row['user_id']}'";
			$result2 = mysqli_query($conn,$sql2);
			$row2 = mysqli_fetch_assoc($result2);
			$search_object->user_name = $row2['username'];
			array_push($to_client['map_search'],$search_object);	

			$contents_count -= 1;	

		}else if($value['type'] == MAP_SEARCH_TYPE_HASH){
		// filter by hash
			if(strpos($row['hash_tag'], $target_word)==true){
			// 타겟해시가 포함되어 있다면
				if($more>0){
					$more -=1;
					continue;
				}
				if($contents_count<=0){
					break;
				}
				$search_object = new Map_Search;
				$search_object->user_id = $row['user_id'];
				$search_object->category = $row['category'];
				$search_object->hash_tag = $row['hash_tag'];
				$sql2 = "SELECT userid, username FROM ".USER_TABLE." WHERE userid = '{$row['user_id']}'";
				$result2 = mysqli_query($conn,$sql2);
				$row2 = mysqli_fetch_assoc($result2);
				$search_object->user_name = $row2['username'];
				array_push($to_client['map_search'],$search_object);	

				$contents_count -= 1;	
			}
			else{

			}	
		}else{

		}


	}
	if($more>0){
		$to_client['state'] = MAP_SEARCH_NO_MORE;
	}
	else{
		$to_client['state'] = MAP_SEARCH_SUCCESS;
	}

	echo json_encode($to_client);

}




class Map_Search{
	public $user_id, $user_name, $category, $hash_tag; 
}




?>