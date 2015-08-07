<?php
$value = array('apple','banana');
print_r($value."<br>");

echo json_encode($value)."<br>";

$value['apple'] = 2;

$evalue = json_encode($value);
echo $evalue."<br>";



$value = array('apple'=>"",'banana'=>"",'state'=>"");
echo json_encode($value);

$value['apple']=2;

//echo json_encode($value);
?>