<?php

$dir_name = "./".$_GET['store_id']."/".$_GET['something'];
if(is_dir($dir_name)){
	echo "folder exist<br>";
}
else{
	echo "folder none<br>";
	if(@mkdir($dir_name,0777)){
		echo "{$dir_name} directory created...";
	}
	else{
		echo "{$dir_name} uncreated...";
	}
}


?>