<?
$ary = "UTF-8"; 
$sCharset = mb_detect_encoding("값", $ary);
echo "1 ".$sCharset;

$ary = "EUC-KR";
$sCharset = mb_detect_encoding("값", $ary);
echo "2 ".$sCharset;

$ary = "ASCII";
$sCharset = mb_detect_encoding("값", $ary);
echo "3 ".$sCharset;

$ary = "JIS";
$sCharset = mb_detect_encoding("값", $ary);
echo "4 ".$sCharset;

$ary = "eucjp-win"; 
$sCharset = mb_detect_encoding("값", $ary);
echo "5 ".$sCharset;

$ary = "sjis-win";
$sCharset = mb_detect_encoding("값", $ary);
echo "6 ".$sCharset;

$ary = "euc-jp"; 
$sCharset = mb_detect_encoding("값", $ary);
echo "7 ".$sCharset;

?>