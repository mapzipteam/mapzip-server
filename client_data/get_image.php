
<?php 

include("../mapzip-state-define.php");
$user_id = $_POST['userid'];
$map_id = $_POST['map_id'];


$uploaddir = "./client_{$_POST['userid']}_{$_POST['map_id']}_{$_POST['store_name']}|{$_POST['store_x']}|{$_POST['store_y']}"; 

// $uploadfile = $uploaddir.basename($_FILES['userfile']['name']); 

$uploadfile = $uploaddir."/".$_POST['image_name'].".jpg"; 


if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
	print "성공적으로 업로드 되었습니다."; 

} 

//print_r($_FILES); 
else {
	print "파일 업로드 실패 uploadfile : {$uploadfile}";

}
echo '자세한 디버깅 정보입니다:';
print_r($_FILES);
 
//print_r($_FILES); 

?> 
