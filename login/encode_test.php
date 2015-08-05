

<?php

include("fmysql.php");
include("mapzip-login-define.php");
//$user_id = $_POST['userid'];
//$user_pw = $_POST['userpw'];

$user_id = $_GET['userid'];
$user_pw = $_GET['userpw'];

echo "2<br>";

echo $user_id." ".$userpw."<br>";

echo mb_detect_encoding($user_id, 'UTF-8');;

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	echo "connnection error!\n";
}
else{
echo "connect success!\n";
}

mysql_query("set session character_set_connection=utf8;");
mysql_query("set session character_set_results=utf8;");
mysql_query("set session character_set_client=utf8;");

$sql = "SELECT * FROM ".USER_TABLE;

if(!$result = mysqli_query($conn,$sql)){
	echo "query fail...\n";
}



if($username = loginOK($user_id,$user_pw,$result)){
	echo("login success\n");
	echo "1".iconv("euckr","utf8",$username)."<br>";
	echo "1".$username."<br>";
	
}
else{
	echo("login fail...\n");
	echo "0";
}

function loginOK($id,$pw,$result){
	while($row = mysqli_fetch_assoc($result)){
		if(strcmp($id,$row['userid'])==0 && strcmp($pw,$row['userpw'])==0){
			
			return $row['username'];
		}
	}
	return false;
}

?>

