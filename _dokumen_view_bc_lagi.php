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
if(isset($_GET["id_sidang"]))
{
	$id_sidang=$_GET["id_sidang"];
}else
{
	$id_sidang=1;
}
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
 	//echo "----".$sql."----<br>";
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
	$var_model=$h_info["var_model"];
	$var_sumber_sipp=$h_info["var_sumber_sipp"];
	$var_sql_data=$h_info["var_sql_data"];
	$var_tabel=$h_info["var_tabel"];
	$var_field=$h_info["var_field"];
	$var_cek_sidang=$h_info["var_cek_sidang"];
	$var_fungsi_nama=$h_info["var_fungsi_nama"];
	if($h_info["var_model"]<>"tanya_jawab")
	{
		$isi="";
		$isi=isi_variabel($variabelnya, $perkara_id, $id_sidang, $var_model, $var_sumber_sipp, $var_sql_data, $var_tabel, $var_field, $var_cek_sidang, $var_fungsi_nama,$sebutan_pihak1,$sebutan_pihak2,$gugatan_permohonan,$var_keterangan,$db_host2);	
		
		//echo "<br> Variabel ".$variabelnya. " Data ".mysql_num_rows
		if($isi=="")
		{
			$default_data=$h_info["var_default_data"];
			
			$variabel_default_data = variabel_dokumen($default_data);
			$jml_variabel_default_data= count($variabel_default_data);$no_default_data=0;
			for ($variabel_posisi_default_data = 0; $variabel_posisi_default_data < $jml_variabel_default_data; $variabel_posisi_default_data++) 
			{
				$no++; 
				$variabelnya_default_data=$variabel_default_data[$variabel_posisi_default_data]; 
				$sql_default_data="select * from ".$db_host2.".master_variabel where var_nomor='$variabelnya_default_data'";
				//echo "----".$sql."----<br>";
				$query_default_data=mysql_query($sql_default_data);
				 
				/////JIKA BELUM ADA VARIABEL
				 
				/////JIKA BELUM ADA VARIABEL
				
				while($h_info_default_data=mysql_fetch_array($query_default_data))
				{ 	$var_keterangan_default_data=$h_info_default_data["var_keterangan"];
					$var_keterangan_default_data=str_replace("#0046#",$sebutan_pihak1,$var_keterangan_default_data);
					$var_keterangan_default_data=str_replace("#0047#",$sebutan_pihak2,$var_keterangan_default_data); 
					$var_keterangan_default_data=str_replace("#0053#",$gugatan_permohonan,$var_keterangan_default_data); 
					$var_model_default_data=$h_info_default_data["var_model"];
					$var_sumber_sipp_default_data=$h_info_default_data["var_sumber_sipp"];
					$var_sql_data_default_data=$h_info_default_data["var_sql_data"];
					$var_tabel_default_data=$h_info_default_data["var_tabel"];
					$var_field_default_data=$h_info_default_data["var_field"];
					$var_cek_sidang_default_data=$h_info_default_data["var_cek_sidang"];
					$var_fungsi_nama_default_data=$h_info_default_data["var_fungsi_nama"];
					$isi_default_data=isi_variabel($variabelnya_default_data, $perkara_id, $id_sidang, $var_model_default_data, $var_sumber_sipp_default_data, $var_sql_data_default_data, $var_tabel_default_data, $var_field_default_data, $var_cek_sidang_default_data, $var_fungsi_nama_default_data,$sebutan_pihak1,$sebutan_pihak2,$gugatan_permohonan,$var_keterangan_default_data,$db_host2);
					///
					$default_data=str_replace("#".$variabelnya_default_data."#",$isi_default_data, $default_data);
				}
			}	
			$isi=$default_data;
		}
		//jenis_inputan
		 
		 if(!empty($h_info["var_tabel"])){$fungsi="onchange=edit_isi('".$variabelnya."',this.value)";}
		

		echo "<tr><td valign='top'>".@$var_keterangan."</td><td valign='top'>";
		
		if($h_info["var_jenis"]=='textarea')		
		{
			$tinggi=strlen($isi)/60;
			//$tinggi=6;
			?>
			<textarea <?php echo @$fungsi?> style="width:750px;min-height:50px" rows="<?php echo $tinggi?>" id="<?php echo $h_info['var_nomor']?>" name="<?php echo $h_info['var_nomor']?>"><?php echo stripslashes($isi)?></textarea>
			<?php 
		}else
		{	?>
			 <input <?php echo @$fungsi?> style="width:750px" id="<?php echo $h_info['var_nomor']?>" name="<?php echo $h_info['var_nomor']?>" value="<?php echo stripslashes($isi)?>">
		<?php 
		}
		//jenis_inputan
		echo "</td></tr>";	
	}else
	{
		echo "<tr><td valign='top'>".@$var_keterangan."</td><td valign='top' id='tanya_jawab_".$h_info["var_nomor"]."' style='background:#fff'>"; 
		echo '<div style="display:none">';
		echo '<textarea   style="width:750px;min-height:100px"  id="'.$h_info['var_nomor'].'" name="'.$h_info['var_nomor'].'"></textarea><br>';
		echo "</div>";
		$pihak_ke=$h_info["var_pihak_ke"];
		tanya_jawab($perkara_id, $id_sidang,  $h_info["var_nomor"],$db_host2,$pihak_ke)	;
		echo "</td></tr>";	
	}		
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
<script> 
function tambah_baris(nomor,id, perkara_id, sidang_id, variabel) {
	var nomor_baru=nomor+1;
    var table = document.getElementById("tanyajawab_"+variabel);
    var row = table.insertRow(-1);
    var cell1 = row.insertCell(0); 
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
	cell1.setAttribute("height", "30"); 
    cell1.innerHTML = "<div id='data_id"+id+"' ketid"+id+">"+nomor_baru+"</div>"; 
    cell2.innerHTML = "<div id='q_tanya"+id+"'   contenteditable='' class='tanya' onBlur=Edit_Detail(this.innerHTML,'pertanyaan',"+id+","+variabel+")></div>";
    cell3.innerHTML = '<div id=q_jawab'+id+'  onkeydown="return myKeyPress(event,'+nomor_baru+',this,'+id+', '+perkara_id+', '+sidang_id+', '+variabel+')"  contenteditable="" class="jawab" onBlur=Edit_Detail(this.innerHTML,"jawaban",'+id+','+variabel+')></div>';
}


function hapus_baris(o) 
{ 
     var p=o.parentNode.parentNode;
         p.parentNode.removeChild(p);
}
</script>
 
<script type="text/javascript">  
  function myKeyPress(e,nomor,o,id, perkara_id, sidang_id, variabel){
    var keynum; 
    if(window.event) {             
      keynum = e.keyCode;
    } else if(e.which){         
      keynum = e.which;
    }

    if(keynum==13)
	{ 
		var nomor_baru=nomor+1;
		Add_Detail(nomor, perkara_id, sidang_id,  variabel); 
		return false;
	}
	if(keynum==9)
	{
		//tambah_baris(nomor);
		var nomor_baru=nomor+1;
		document.getElementById('q_tanya'+nomor_baru).focus();
		return false;
	}
	
	if(keynum==113)
	{
		hapus_detail_tanya_jawab(id, variabel);
		hapus_baris(o);
	}
  }
</script>
<script> 
//detail tanya jawab 
 

function Add_Detail(nomor, perkara_id, sidang_id, variabel)
{  
	var xhr = new XMLHttpRequest(); 
	var urutan_pertanyaan=nomor+1;
	xhr.open("POST","_dokumen_view_proses_tanya_jawab", true); 
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
	xhr.onreadystatechange = function() { 
		if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) { 
			var pesan=parseInt(xhr.responseText);  
			//notifier.show('Pesan!', pesan, '', '',10000); 
				
			tambah_baris(nomor, pesan, perkara_id, sidang_id, variabel);	 
			
			document.getElementById('q_tanya'+pesan).focus();		
		}
	}
	xhr.send("add_detail=ya&urutan_pertanyaan="+encodeURIComponent(urutan_pertanyaan)+"&perkara_id="+encodeURIComponent(perkara_id)+"&sidang_id="+encodeURIComponent(sidang_id)+"&saksi_id="+encodeURIComponent(variabel)); 	
}

function Edit_Detail(isi, kolom, id, variabel)
{  
	var xhr = new XMLHttpRequest(); 
	xhr.open("POST","_dokumen_view_proses_tanya_jawab", true); 
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function() {//Call a function when the state changes.
		if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
			//document.getElementById("pesan_kirim").style="display.block" ;
			
			 var pesan=xhr.responseText;  
			 // notifier.show('Pesan!', pesan, '', '',10000);  
			  proses_tanya_jawab_ke_textarea(variabel);
		}
	}
	xhr.send("edit_detail=ya&id="+encodeURIComponent(id)+"&kolom="+encodeURIComponent(kolom)+"&isi="+encodeURIComponent(isi)); 	
}
function hapus_detail_tanya_jawab(id,variabel)
{  
var conf = confirm("Apakah anda yakin akan menghapus tanya jawab ini?");
if (conf == true) 
{	
	var xhr = new XMLHttpRequest(); 
	xhr.open("POST","_dokumen_view_proses_tanya_jawab", true); 
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function() {//Call a function when the state changes.
		if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
			//document.getElementById("pesan_kirim").style="display.block" ;
			
			 var pesan=xhr.responseText;  
			//  notifier.show('Pesan!', pesan, '', '',10000); 
			  proses_tanya_jawab_ke_textarea(variabel);
				// location.reload(); 
		}
	}
	xhr.send("hapus_detail_tanya_jawab=ya&id="+encodeURIComponent(id)); 	
}
}
function proses_pilih_template(templat_tanya_jawab,variabel)
{  
	var conf = confirm("Apakah anda yakin akan memilih template, karena semua tanya jawab terhadap saksi ini akan diganti baru?");
	if (conf == true) 
	{
		var xhr = new XMLHttpRequest(); 
		xhr.open("POST","_dokumen_view_proses_tanya_jawab", true); 
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		xhr.onreadystatechange = function() {//Call a function when the state changes.
			if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
				//document.getElementById("pesan_kirim").style="display.block" ;
				
				 var pesan=xhr.responseText;  
				 // notifier.show('Pesan!', pesan, '', '',10000); 
				document.getElementById('detail_'+variabel).innerHTML=pesan;
				proses_tanya_jawab_ke_textarea(variabel);
			}
		}
		xhr.send("ganti_template_tanya_jawab=ya&templat_tanya_jawab="+encodeURIComponent(templat_tanya_jawab)+"&saksi_id="+encodeURIComponent(variabel)+"&perkara_id="+encodeURIComponent(<?php echo $perkara_id?>)+"&sidang_id="+encodeURIComponent(<?php echo $id_sidang?>)); 
	}	
}
function proses_tanya_jawab_ke_textarea(variabel)
{  
	 
		var xhr = new XMLHttpRequest(); 
		xhr.open("POST","_dokumen_view_proses_tanya_jawab", true); 
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		xhr.onreadystatechange = function() {//Call a function when the state changes.
			if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
				//document.getElementById("pesan_kirim").style="display.block" ;
				
				 var pesan=xhr.responseText;  
				 var pesan=pesan.trim();  
				 // notifier.show('Pesan!', pesan, '', '',10000); 
				document.getElementById(variabel).value=pesan;
			}
		}
		xhr.send("proses_tanya_jawab_ke_textarea=ya&saksi_id="+encodeURIComponent(variabel)+"&perkara_id="+encodeURIComponent(<?php echo $perkara_id?>)+"&sidang_id="+encodeURIComponent(<?php echo $id_sidang?>)); 
	 	
}
//detail tanya jawab
</script>
</body>
</html>