

<?php

include("fmysql.php");
include("mapzip-login-define.php");

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	$to_client['state']=DB_CONNECTION_ERROR;
}
else{
//echo "connect success!\n";
}

$user_id = $_GET['userid'];

$sql = "SELECT * FROM ".MAP_META_TABLE.$user_id;

$to_client['state']=200;

$to_client['mapmeta_info']=array();

$result = mysqli_query($conn,$sql);
while($row = mysqli_fetch_assoc($result)){
	$map_object = new Map_Metainfo;
	$map_object->title = $row['title'];
	$map_object->category = $row['category'];
	$map_object->created = $row['created'];
	array_push($to_client['mapmeta_info'],$map_object); 
}

echo json_encode($to_client);


class Map_Metainfo{
	public $title, $category, $created;


}

?>

