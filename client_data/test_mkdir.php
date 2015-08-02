<?php
//$dirRoot = "http://ljs93kr.cafe24.com/mapzip/client_data";
//$dir_name = $dirRoot."/client_1";

$dir_name = "./client_3";
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