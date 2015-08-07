<?php

$dir_name = "./client_".$_GET['userid'];
if(is_dir($dir_name)){
	echo "folder exist<br>";
}
else{
	echo "folder none<br>";
	if(@mkdir($dir_name,0777)){
		echo "${dir_name} directory created...";
	}
	else{
		echo "directory uncreated...";
	}
}


?>