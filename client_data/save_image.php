
<?php 
// include("../mapzip-state-define.php");

// if($_SERVER["REQUEST_METHOD"] == "POST"){

// 	// $uploaddir = "./client_{$_POST['userid']}_{$_POST['map_id']}_{$_POST['store_name']}|{$_POST['store_cx']}|{$_POST['store_cy']}"; 
// $uploaddir = "./client_{$_POST['userid']}_{$_POST['map_id']}_{$_POST['store_id']}"; 
// // $uploadfile = $uploaddir.basename($_FILES['userfile']['name']); 

// $uploadfile = $uploaddir."/".$_POST['image_name'].".jpg";

// if(file_exists($uploadfile)){
// 	if(unlink($uploadfile)){
// 		echo "file delete complete<br>";
// 		file_upload($uploadfile);
// 	}
// 	else{
// 		echo "file delete fail";
// 	}
// }
// else{
// 	file_upload($uploadfile);

// }
// }else{
// 	echo("not post request");
// }



// function file_upload($uploadfile){
// 	// if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
// 	// 	print "성공적으로 업로드 되었습니다.";
// 	// } 
// 	// //print_r($_FILES); 
// 	// else {
// 	// 	print "파일 업로드 실패 uploadfile : {$uploadfile}";

// 	// }
// 	// echo '자세한 디버깅 정보입니다:';
// 	// print_r($_FILES);
// 	//echo($image_string);
// 	if(file_put_contents($uploadfile, base64_decode($_POST['image_string']))){
// 		echo("upload success");
// 	}else{
// 		echo("upload fail..");
// 		print_r($_POST);
// 	}

// }

include("../fmysql.php");
include("../mapzip-state-define.php");



$value = json_decode(file_get_contents('php://input'), true);


if(($value['build_version'] >= BUILD_VERSION_GARNET) && ($value['build_version'] < BUILD_VERSION_GARNET_END)){
	$to_client = new MapzipResponse($value['debug_mode'], $value['build_version']);

	$uploaddir = "./client_{$value['user_id']}/store_{$value['store_id']}"; 
	$uploadfile = $uploaddir."/".$value['image_name'].".jpg";

	$to_client->setDebugs('uploaddir',$uploaddir);
	$to_client->setDebugs('uploadfile', $uploadfile);


	if(file_exists($uploadfile)){
		$to_client->setDebugs('file exists', 'true');
		if(unlink($uploadfile)){
			$to_client->setDebugs('unlink previous file', 'success');
			file_upload($uploadfile,$to_client,$value);
		}
		else{
			$to_client->setDebugs('unlink previous file', 'fail');
		}
	}
	else{
		$to_client->setDebugs('file exists', 'false');
		file_upload($uploadfile, $to_client, $value);

	}
	echo json_encode($to_client->build());
}else{
	$to_client['state_log'] = "default";

	$uploaddir = "./client_{$value['userid']}/store_{$value['store_id']}"; 

	$uploadfile = $uploaddir."/".$value['image_name'].".jpg";

	if(file_exists($uploadfile)){
		if(unlink($uploadfile)){
			$to_client['state_log'] .= "file delete success";
			file_upload($uploadfile, $to_client, $value);
		}
		else{
			
			$to_client['state_log'] .= "file delete fail";
		}
	}
	else{
		file_upload($uploadfile, $to_client, $value);

	}

	
	echo json_encode($to_client);
}


function file_upload($uploadfile,$to_client,$value){
	// if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
	// 	print "성공적으로 업로드 되었습니다.";
	// } 
	// //print_r($_FILES); 
	// else {
	// 	print "파일 업로드 실패 uploadfile : {$uploadfile}";

	// }
	// echo '자세한 디버깅 정보입니다:';
	// print_r($_FILES);
	//echo($image_string);
	if(($value['build_version'] >= BUILD_VERSION_GARNET) && ($value['build_version'] < BUILD_VERSION_GARNET_END)){
		if(file_put_contents($uploadfile, base64_decode($value['image_string']))){
			$to_client->setFields('state', CLIENT_REVIEW_IMAGE_ENROLL_SUCCESS);
			$to_client->setDebugs('image upload', 'success');
			return true;
		}else{
			$to_client->setDebugs('image upload', 'fail');
			$to_client->setFields('state', CLIENT_REVIEW_IMAGE_ENROLL_FAIL);
			return false;
		}
	}else{
		if(file_put_contents($uploadfile, base64_decode($value['image_string']))){
			$to_client['state_log'] .= "upload success";
		}else{
			$to_client['state_log'] .= "upload fail..";
			$to_client['state_log'] .= $value;
		}
	}
	


	

}




?> 
