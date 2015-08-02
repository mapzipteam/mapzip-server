<?php
include("fmysql.php");
include("mapzip-login-define.php");
$user_id = $_POST['userid'];
$user_pw = $_POST['userpw'];

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

if(loginOK($user_id,$user_pw,$result)){
	//echo("login success\n");
	echo "1";
}
else{
	//echo("login fail...\n");
	echo "0";
}

function loginOK($id,$pw,$result){
	while($row = mysqli_fetch_assoc($result)){
		if(strcmp($id,$row['userid'])==0 && strcmp($pw,$row['userpw'])==0){
			
			return true;
		}
	}
	return false;
}

?>