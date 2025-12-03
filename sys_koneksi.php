<?php 
$hosta = "bG9jYWxob3N0";
$user_hosta = "cm9vdA==";
$password_hosta = "VGVtODI0";
$db_hosta = "c2lwcA==";
$db_host2a = "a29uc2VwcQ==";//konsepq
//$db_host2a = "ZG9rdW1lbg==";//dokumen
$url_sippa = "bG9jYWxob3N0L3NpcHA=";
$mulai_wajib_upload_bas_put = "2018-03-12"; 

$host=base64_decode($hosta);
$user_host=base64_decode($user_hosta);
$password_host=base64_decode($password_hosta);
$db_host=base64_decode($db_hosta);
$db_host2=base64_decode($db_host2a);
$url_sipp=base64_decode($url_sippa);


$koneksi=mysql_connect($host,$user_host,$password_host);
$koneksi2=mysql_connect($host,$user_host,$password_host);
mysql_select_db($db_host,$koneksi);
?>

