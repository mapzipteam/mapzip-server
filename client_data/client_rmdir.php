<?php

function removeDirectory($directory_name){
	foreach(glob("{$directory_name}/*") as $each_file){
		if(is_dir($each_file)){
			removeDirectory($each_file);
		}else{
			unlink($each_file);
		}
	}
	if(!rmdir($directory_name)){
		return 0;
	}
	else{
		return 1;
	}
}


?>