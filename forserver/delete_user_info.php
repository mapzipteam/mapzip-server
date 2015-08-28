<?php
include("../fmysql.php");
include("../mapzip-mysql-define.php");

if(!$conn = connect_mysqli(MYSQL_IP,MAIN_DB,DB_PASSWORD,USE_DB)){
	//echo "connnection error!\n";
	echo "connect mysql fail..<br>";
}
else{
	echo "connect mysql success!<br>";
}

$sql = "SELECT * FROM ".USER_TABLE;

if(!$result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
	echo "user table query fail..<br>";
}

if(empty($_GET['userid'])){
	echo "empty request<br>";
	while($row = mysqli_fetch_assoc($result)){
		$sql = "DELETE FROM ".USER_TABLE." WHERE userid = '{$row['userid']}' LIMIT 1";
		if($var = mysqli_query($conn,$sql)){
			echo "SUCCESS : delete {$row['userid']} from user_info table ..<br>";
		}
		else{
			echo "FAIL : delete {$row['userid']} from user_info table ..<br>";
		}
		$sql = "DROP TABLE ".CLIENT_TABLE.$row['userid'];
		if($var = mysqli_query($conn,$sql)){
		//echo "query fail...\n";
			echo "SUCCESS : drop table {$row['userid']} from mz_client...<br>";
		}
		else{
			echo "FAIL : drop table {$row['userid']} from mz_client..<br>";
		}
		$sql = "DROP TABLE ".REVIEW_TABLE.$row['userid'];
		if($var = mysqli_query($conn,$sql)){
		//echo "query fail...\n";
			echo "SUCCESS : drop table {$row['userid']} from mz_review...<br>";
		}
		else{
			echo "FAIL : drop table {$row['userid']} from mz_review..<br>";
		}

	}
}
else{
	echo "non empty request<br>";
	echo "userid = {$_GET['userid']}<br>";
	$sql = "DELETE FROM ".USER_TABLE." WHERE userid = '{$_GET['userid']}' LIMIT 1";
	if($result = mysqli_query($conn,$sql)){
		echo "SUCCESS : delete {$_GET['userid']} from user_info table ..<br>";
	}
	else{
		echo "FAIL : delete {$_GET['userid']} from user_info table ..<br>";
	}
	$sql = "DROP TABLE ".CLIENT_TABLE.$_GET['userid'];
	if($result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
		echo "SUCCESS : drop table {$_GET['userid']} from mz_client...<br>";
	}
	else{
		echo "FAIL : drop table {$_GET['userid']} from mz_client..<br>";
	}
	$sql = "DROP TABLE ".REVIEW_TABLE.$_GET['userid'];
	if($result = mysqli_query($conn,$sql)){
	//echo "query fail...\n";
		echo "SUCCESS : drop table {$_GET['userid']} from mz_review...<br>";
	}
	else{
		echo "FAIL : drop table {$_GET['userid']} from mz_review..<br>";
	}

}






?>