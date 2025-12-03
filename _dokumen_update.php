<?php   include "sys_koneksi.php";
	foreach($_POST as $key=>$value) {$$key=$value;} 
	$DATA=str_replace('"',"''",$DATA);
	$DATA=mysql_escape_string($DATA);
	$DATA=str_replace('\n',chr(13),$DATA);
	$cek="SELECT DATA  FROM  ".$db_host2.".data_teks WHERE perkara_id=$perkara_id AND var_nomor='$var_nomor' ";
	$query=mysql_query($cek);
	if(mysql_num_rows($query)==1)
	{
		$sql="UPDATE  ".$db_host2.".data_teks SET DATA='$DATA' WHERE  perkara_id=$perkara_id AND var_nomor='$var_nomor'";
	}else		
	{
		$sql="INSERT INTO   ".$db_host2.".data_teks (var_nomor, perkara_id, DATA) values ('$var_nomor', $perkara_id,'".$DATA."')";
		 
	}
	//echo '<br>'.$sql_simpan.'<br>';
	$simpan=mysql_query($sql);
	if($simpan){echo "<font color=green>Sukses merubah Data menjadi<b><br>".str_replace(chr(13), "<br>",$DATA)."</b></font>";} 
 
?> 