<?php 
//error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true); 
ini_set('display_startup_errors', true);
include "sys_koneksi.php";
include "sys_config.php";
$judul="Pencetak Dokumen ";
?>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo @$judul;?> - <?php echo $nama_aplikasi;?> <?php echo $NamaPN;?> </title>
<link rel="shortcut icon" href="favicon.ico" type="image/png">
<link rel="stylesheet" href="resources/css/w3.css">
<style>tr:nth-child(odd){ background: #b8d1f3;}/* Define the background color for all the EVEN background rows */tr:nth-child(even){background: #dae5f4;}</style>
<script type="text/javascript" src="resources/js/dokumen.js"></script>
<body>
<?php  
include "resources/fungsi_doc.php"; 
//cek GET
$template_id=$_GET['template_id'];
$perkara_id=$_GET['perkara_id'];
//cek GET

//SEBUTAN PIHAK DAN SURAT
$sql_info="SELECT alur_perkara_id, jenis_perkara_id, jenis_perkara_nama FROM perkara WHERE perkara_id=".$perkara_id;$quer_info=mysql_query($sql_info); while($perkara_info=mysql_fetch_array($quer_info)) 
{
	foreach($perkara_info as $key=>$value) {$$key=$value;} 
	if($alur_perkara_id==15)
	{
	 $sebutan_pihak1="Penggugat";
	 $sebutan_pihak2="Tergugat";
	 $gugatan_permohonan="Gugatan";
	 if($jenis_perkara_nama=="Cerai Talak")
	 {
		 $sebutan_pihak1="Pemohon";
			$sebutan_pihak2="Termohon";
	 $gugatan_permohonan="Permohonan";
	 }
		 
	}else
	{
	  $sebutan_pihak1="Pemohon";
	  $sebutan_pihak2="Termohon";
	 $gugatan_permohonan="Permohonan";
	}
}
//SEBUTAN PIHAK DAN SURAT	


//TEMPLATE YANG DIGUNAKAN 
$sql_dokumen="SELECT kode FROM ".$db_host2.".template_dokumen WHERE id=".$template_id;$rtf="";$query_template=mysql_query($sql_dokumen);while($template_info=mysql_fetch_array($query_template)) {$kode_dokumen=$template_info["kode"].".rtf"; }
//TEMPLATE YANG DIGUNAKAN 

//BUKA TEMPLATE 
$rtf=file_get_contents("doc/".$kode_dokumen);
$variabel = variabel_dokumen($rtf);
$jml_variabel= count($variabel);$no=0;
//echo $jml_variabel."<br>"; 
//BUKA TEMPLATE 

echo "<form name='frm_cetak' id='frm_cetak' action='_dokumen_cetak' method=POST>";
echo "<input type='hidden' name='template_id' value='".$template_id."'>";
echo "<input type='hidden' name='perkara_id' id='perkara_id' value='".$perkara_id."'>";
echo "<center><b>File Blangko : ".$kode_dokumen."</b></center>";
echo "<table align=center><tr><th>Keterangan</th><th>Data</th></tr>";
for ($variabel_posisi = 0; $variabel_posisi < $jml_variabel; $variabel_posisi++) 
{
	$no++; 
	$variabelnya=$variabel[$variabel_posisi]; 
	$sql="select * from ".$db_host2.".master_variabel where var_nomor='$variabelnya'";
 //	echo "----".$sql."----";
	$query=mysql_query($sql);
	
	/////JIKA BELUM ADA VARIABEL
	if(mysql_num_rows($query)==0)
	{ 
		echo "<tr><td colspan=2 class=w3-text-red>Nomor Variabel ".$variabelnya." Belum Tersedia</td></tr>";
	}
	/////JIKA BELUM ADA VARIABEL
	
	while($h_info=mysql_fetch_array($query))
	{ 
		$var_keterangan=$h_info["var_keterangan"];
		$var_keterangan=str_replace("#0046#",$sebutan_pihak1,$var_keterangan);
		$var_keterangan=str_replace("#0047#",$sebutan_pihak2,$var_keterangan); 
		$var_keterangan=str_replace("#0053#",$gugatan_permohonan,$var_keterangan); 
		
		$isi="";
		if($h_info["var_model"]=='sql' AND $h_info["var_sumber_sipp"]==1)		
		{
			$sql_isi=$h_info["var_sql_data"];
		//	echo "<br>".$sql_isi ."<br>";
			$sql_isi=str_replace('#perkara_id#',$perkara_id,$sql_isi);
			$sql_isi=str_replace('#sebutan_pihak1#',$sebutan_pihak1,$sql_isi);
			$sql_isi=str_replace('#sebutan_pihak2#',$sebutan_pihak2,$sql_isi);
			$var_keterangan=str_replace("#0046#",$sebutan_pihak1,$var_keterangan);
			$var_keterangan=str_replace("#0047#",$sebutan_pihak2,$var_keterangan); 
			$var_keterangan=str_replace("#0053#",$gugatan_permohonan,$var_keterangan); 
			$fungsi="";
		//	$sql_isi=str_replace()
		}else
		if(!empty($h_info["var_tabel"]) AND $h_info["var_sumber_sipp"]==0 )		
		{
			$tabel= $db_host2.".".$h_info["var_tabel"];
			$sql_isi="SELECT DATA  from $tabel WHERE perkara_id=$perkara_id AND var_nomor='".$variabelnya."'";
		} 
		if(!empty($h_info["var_tabel"]) AND $h_info["var_sumber_sipp"]==1 )		
		{
			$tabel=$h_info["var_tabel"];
			$sql_isi="SELECT ".$h_info['var_field']." AS DATA from $tabel WHERE perkara_id=$perkara_id";
			$fungsi="";
		}
		else
		{
			$isi="";
			$fungsi="";
		}			
	//	echo "<br>----" .$sql_isi. "----<br>" ;
		$query_isi=mysql_query($sql_isi);
		
		while($h_info_isi=mysql_fetch_array($query_isi))
		{
		//isi
			$isi=@$h_info_isi['DATA'];
			$isi=str_replace("<<"," ",$isi);
			$isi=cleanHtmlTagSpecialnya($isi);
			if($h_info['var_fungsi_nama']=='hijriah')
			{ 
				$isi=trim(convertToHijriah($isi));
			}
			if($h_info['var_fungsi_nama']=='tanggal_indonesia')
			{ 
				$isi=trim(convertKeTglIndo($isi));
			}
			if($h_info['var_fungsi_nama']=='format_uang')
			{ 
				$isi=trim(format_uang($isi));
			}
			if($h_info['var_fungsi_nama']=='huruf_besar_awal_kata')
			{ 
				$isi=trim(huruf_besar_awal_kata($isi));
			}
			
		//isi 	
		}	
		//echo "<br> Variabel ".$variabelnya. " Data ".mysql_num_rows
		if(mysql_num_rows($query_isi)==0){$isi=$h_info["var_default_data"];}
		//jenis_inputan
		 
		 if($h_info["var_sumber_sipp"]<>1){$fungsi="onchange=edit_isi('".$variabelnya."',this.value)";}
		

		echo "<tr><td valign='top'>".@$var_keterangan."</td><td valign='top'>";
		
		if($h_info["var_jenis"]=='textarea')		
		{
			$tinggi=strlen($isi)/60;
			//$tinggi=6;
			?>
			<textarea <?php echo @$fungsi?> style="width:750px" rows="<?php echo $tinggi?>" id="<?php echo $h_info['var_nama']?>" name="<?php echo $h_info['var_nomor']?>"><?php echo stripslashes($isi)?></textarea>
			<?php 
		}else
		{	?>
			 <input <?php echo @$fungsi?> style="width:750px" id="<?php echo $h_info['var_nama']?>" name="<?php echo $h_info['var_nomor']?>" value="<?php echo stripslashes($isi)?>">
		<?php 
		}
		//jenis_inputan
		echo "</td></tr>";	
		
	}
	  
	 
}

echo "</table>" ;
echo "<center>"; 
echo '<div  id="loader" class="loader" style="display:none"></div>';
echo '<div  id="success"   style="display:none"></div><br>';
$nama_form='frm_cetak';
$tujuan='_dokumen_cetak';
echo "<input class='w3-btn w3-green' onclick=kirim_post('".$tujuan."') type=button  value=Cetak> ";
echo "<a href='perkara_detail?perkara_id=".$perkara_id."' class='w3-btn w3-red'><< Kembali</a> </center>" ;
echo "</form><br><br><br><br>" ;
?> 
<style>.loader{border:16px solid #f3f3f3;border-radius:50%;border-top:16px solid blue;border-right:16px solid green;border-bottom:16px solid red;width:60px;height:60px;-webkit-animation:spin 2s linear infinite;animation:spin 2s linear infinite}@-webkit-keyframes spin{0%{-webkit-transform:rotate(0deg)}100%{-webkit-transform:rotate(360deg)}}@keyframes spin{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}table,td{border:0}</style>
 <link rel="stylesheet" href="resources/notifier/css/notifier.min.css">  
<script src="resources/notifier/js/notifier.min.js" type="text/javascript"></script> 
</body>
</html>