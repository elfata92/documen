<?php if(!isset($_SESSION)){session_start();} if(isset($_GET['aksi'])){if($_GET['aksi']=='keluar'){ session_unset();
        session_destroy();echo '<script>window.location = "index";	</script>';}} function arr2md5($arrinput){ $hasil=''; foreach($arrinput as $val){ if($hasil==''){ $hasil=md5($val); } else { $code=md5($val); for($hit=0;$hit<min(array(strlen($code),strlen($hasil)));$hit++){ $hasil[$hit]=chr(ord($hasil[$hit]) ^ ord($code[$hit])); } } } return(md5($hasil)); } function getPassword($pase){ $pass = arr2md5($pase); return $pass; }

foreach($_POST as $key=>$value) {$$key=$value;}
if(!isset($_POST["username"]))
{
	exit;
}
include "sys_koneksi.php";
$sql="SELECT  fullname  , username as nama_user , password  as kata_sandi, code_activation ,group_id  , group_name, hakim_id, panitera_id FROM v_users where username='$username'";
$quer=mysql_query($sql); 
$cek=''; 
$kata_sandi=''; 
while($h=mysql_fetch_array($quer)) { foreach($h as $key=>$value) {$$key=$value;} 
	$cek=arr2md5(array($code_activation,$password));
	
}
if($cek==$kata_sandi && $cek<>'')
{
	echo 'Berhasil';
	$_SESSION['username']=$nama_user;
	$_SESSION["fullname"]=$fullname;
	$_SESSION["group_name"]=$group_name;
	$_SESSION["group_id"]=$group_id;
	 
	if($group_id==10 OR $group_id==20)
	{
		$_SESSION["jabatan_id"]=$hakim_id;
	}else
	if($group_id==30 OR $group_id==430 OR $group_id==500 OR $group_id==1000 OR $group_id==1010)	
	{
		$_SESSION["jabatan_id"]=$panitera_id;
	}else
	{
		$_SESSION["jabatan_id"]="";
	}
}else
{
	echo 'Gagal';
}
?> 