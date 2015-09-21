<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");
include("../mapzip-state-define.php");

$value = json_decode(file_get_contents('php://input'), true);

$to_client = array('state'=>NON_KNOWN_ERROR);

$more = $value['more'];
$target_word = $value['target'];

$contents_count = 3;
$more *=$contents_count;

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}

$sql = "SELECT * FROM ".USER_TABLE;

if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
	$to_client['state']=SQL_QUERY_ERROR;
}

$to_client['map_search']=array();



while($row = mysqli_fetch_assoc($result)){
	if(strpos($row['hash_tag'], $target_word)==true){
		// 타겟해시가 포함되어 있다면
		if($more>0){
			$more -=1;
			continue;
		}
		if($contents_count<=0){
			break;
		}
		$search_object = SearchInClient($conn,$row['userid'],$row['username'],$target_word);
		array_push($to_client['map_search'],$search_object);

		$contents_count -= 1;


	}
	else{

	}

}
if($more>0){
	$to_client['state'] = MAP_SEARCH_NO_MORE;
}
else{
	$to_client['state'] = MAP_SEARCH_SUCCESS;
}

echo json_encode($to_client);

function SearchInClient($conn,$user_id,$user_name,$target_word){
	

	$sql = "SELECT * FROM ".CLIENT_TABLE.$user_id;
	if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
		$to_client['state']=$sql;
	}

	while($row = mysqli_fetch_assoc($result)){
		if($row['type']==CLIENT_TYPE_MAPMETA){
			if(strpos($row['hash_tag'], $target_word)==true){
				$search_object = new Map_Search;
				$search_object->user_id = $user_id;
				$search_object->user_name = $user_name;
				$search_object->category = $row['category'];			
				$search_object->hash_tag = $row['hash_tag'];
				return $search_object;
			}
				
		}
	}

}

class Map_Search{
	public $user_id, $user_name, $category, $hash_tag; 
}




?>