<?php

$filepath = "./client_a_2_storename|123456|234567";
$filename = $filepath."/image0.jpg";

if(file_exists($filename)){
	echo "file exist!<br>";
	echo "i'll unlink that file!<br>";
	if(unlink($filename)){
		echo "file delete complete<br>";
	}
	else{
		echo "file delete fail<br>";
	}

}
else{
	echo "file no exist!<br>";
}
?>