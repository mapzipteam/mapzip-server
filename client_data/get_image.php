
<?php 
$uploaddir = './'; 

$uploadfile = $uploaddir.basename($_FILES['userfile']['name']); 

if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)){
	print "성공적으로 업로드 되었습니다."; 

} 

//print_r($_FILES); 
else {
	print "파일 업로드 실패";

}
echo '자세한 디버깅 정보입니다:';
print_r($_FILES);
 
//print_r($_FILES); 

?> 
