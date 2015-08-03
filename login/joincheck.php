<?php
include("fmysql.php");
include("mapzip-login-define.php");
$user_id = $_POST['userid'];
$user_pw = $_POST['userpw'];
$user_name = $_POST['username'];
//echo "id:".$user_id." pw:".$user_pw." name:".$username." from server\n";

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
}
else{
//echo "connect success!\n";
}
$sql = "SELECT * FROM ".USER_TABLE;

if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
}

if(joinOK($user_id,$result)){
	$sql = "INSERT INTO ".USER_TABLE." (userid, username, userpw, created) VALUES ('".$user_id."','".$user_name."','".$user_pw."',now())";
	if(mysqli_query($conn,$sql)){
		//echo("join success!\n");
		echo "1";

	}
	else{
		//echo "insert fail...\n";
		echo "0";
	}
	

}
else{
	//echo("That id already exists in server\n");
	echo "0";
}





function joinOK($id,$result){
	while($row = mysqli_fetch_assoc($result)){
		if(strcmp($id,$row['userid'])==0){
			
			return false;
		}
	}
	return true;
}

?>