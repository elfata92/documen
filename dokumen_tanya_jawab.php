<?php 
//error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true); 
ini_set('display_startup_errors', true);
include "sys_koneksi.php";
include "sys_config.php";
if(isset($_SESSION["group_name"]))
	{ 
		exit;
	}
foreach($_POST as $key=>$value) {$$key=$value;}
//master tanya jawab
if(isset($_POST["Edit_M_Tanya"]))
{
	$isi=mysql_escape_string($isi);
	$sql="UPDATE ".$db_host2.".template_keterangan_saksi_m  SET $kolom='".$isi."' WHERE id=$id";
	$simpan=mysql_query($sql);
	if($simpan)
	{
		echo "Penyimpanan Berhasil";
	}else
	{
		echo "Penyimpanan Gagal";
	}	
	exit;
}
if(isset($_POST["simpan_m_tanya_jawab"]))
{
	$nama=mysql_escape_string($nama);
	$sql="INSERT INTO ".$db_host2.".template_keterangan_saksi_m  (nama, pihak_id)  values('".$nama."',".$pihak_id.")";
	$simpan=mysql_query($sql);
	$m_id= mysql_insert_id();
	$sql="INSERT INTO ".$db_host2.".template_keterangan_saksi_d  (m_id, urutan_pertanyaan)  values($m_id, 1)";
	$simpan=mysql_query($sql);
	if($simpan)
	{
		echo "Penyimpanan Berhasil";
	}else
	{
		echo "Penyimpanan Gagal";
	}	
	exit;
}
if(isset($_POST["hapus_m_tanya_jawab"]))
{
	$id=(int)$id;
	$sql="DELETE FROM ".$db_host2.".template_keterangan_saksi_m  WHERE id=$id";
	echo $sql."<br>";
	$simpan=mysql_query($sql);
	if($simpan)
	{
		echo "Penghapusan Berhasil";
	}else
	{
		echo "Penghapusan Gagal";
	}	
	$sql="DELETE FROM ".$db_host2.".template_keterangan_saksi_d  WHERE m_id=$id";
	echo $sql."<br>";
	$simpan=mysql_query($sql);
	if($simpan)
	{
		echo "Penghapusan Berhasil";
	}else
	{
		echo "Penghapusan Gagal";
	}	
	exit;
}
//master tanya jawab

//detail tanya jawab

if(isset($_POST["hapus_detail_tanya_jawab"]))
{
	 
	$sql="DELETE FROM ".$db_host2.".template_keterangan_saksi_d  WHERE id=$id";
	echo $sql."<br>";
	$simpan=mysql_query($sql);
	if($simpan)
	{
		echo "Penghapusan Berhasil";
	}else
	{
		echo "Penghapusan Gagal";
	}	
	exit;
}
if(isset($_POST["add_detail"]))
{
	$sql="INSERT INTO ".$db_host2.".template_keterangan_saksi_d  (m_id, urutan_pertanyaan)  values($m_id, $id)";
	$simpan=mysql_query($sql);
	echo mysql_insert_id();	
	exit;	
}
if(isset($_POST["edit_detail"]))
{
	//cek 
		$isi=mysql_escape_string(strip_tags($isi));
		$sql="UPDATE ".$db_host2.".template_keterangan_saksi_d SET $kolom='".$isi."' WHERE id=$id"; //echo $sql;
		$simpan=mysql_query($sql);
		if($simpan)
		{
			echo "Penyimpanan Berhasil";
		}else
		{
			echo "Penyimpanan Gagal";
		}
	 
	//kl tidak ada insert 
	
	exit;
}
if(isset($_POST["edit_detail_tanya_jawab"]))
{?>

<div class="w3-container">

<form id="f_tannya_jawab" name="f_tannya_jawab" >
<div id="divtanyajawab">
<table id="tanyajawab" class="w3-table" border="1">
<tbody>
<tr style="color: white; background-color: dimgray;">
<th width="5%">No.</th><th width="50%">Pertanyaan</th><th width="45%">Jawaban</th></tr>

<?php 
	$sql_info="SELECT a.*, b.nama  AS jumlah 
				FROM ".$db_host2.".template_keterangan_saksi_d a LEFT JOIN ".$db_host2.".template_keterangan_saksi_m b
				ON b.id =a.m_id where a.m_id=$id ORDER by urutan_pertanyaan asc";
			//	echo $sql_info;
	$quer_info=mysql_query($sql_info);
	$no=0;
	 while($perkara_info=mysql_fetch_array($quer_info)) 
	{
		foreach($perkara_info as $key=>$value) {$$key=$value;} 
		$no++; 
		//echo "Isi Disini";
?>


	<tr>
		<td height="30"><div id="data_id<?php echo $id?>"   ketid=""><?php echo $urutan_pertanyaan?></div></td> 
		<td height="30"><div id="q_tanya<?php echo $id?>"  onkeydown="return myKeyPress_kiri(event,this,<?php echo $id?>)" contenteditable="" class="tanya" onBlur="Edit_Detail(this.innerHTML,'pertanyaan',<?php echo $id?>)"><?php echo $pertanyaan?></div></td>
		<td height="30"><div id="q_jawab<?php echo $id?>" contenteditable="" class="jawab" onBlur="Edit_Detail(this.innerHTML,'jawaban',<?php echo $id?>)" onkeydown="return myKeyPress(event,<?php echo $urutan_pertanyaan?>,this,<?php echo $id?>)"><?php echo $jawaban?></div></td> 
	</tr>
<?php 
	} 
?>	
</tbody>
</table>
	<h3 style="color:red;"></h3>					
</div>
</form>
<div class="w3-panel w3-pale-red w3-display-container">
	<span onclick="this.parentElement.style.display='none'"
	class="w3-button w3-pale-red w3-large w3-display-topright">&times;</span>
	<h4>Perhatian!</h4>
	<p>Tombol <b>Enter</b>: pada Kolom  <b>Jawaban Paling Akhir</b>, untuk menambah  Baris <br>Tombol <b>F2</b> : untuk  <b>menghapus </b> Baris</p> 
</div>
</div>
<?php  	
	exit;
}
//detail tanya jawab
$judul="Tanya Jawab ";
?>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo @$judul;?> - <?php echo $nama_aplikasi;?> <?php echo $NamaPN;?> </title>
<link rel="shortcut icon" href="favicon.ico" type="image/png">
<link rel="stylesheet" href="resources/css/w3.css"> 
<style>tr:nth-child(odd){ background: #EFEFEF;}/* Define the background color for all the EVEN background rows */tr:nth-child(even){background: #fff;}</style>
<body> 
<?php include("_menu_master.php")?>
<div class="w3-container">
<h3>Jenis-Jenis Tanya Jawab : </h3>
<button class="w3-btn w3-green w3-hover-purple" onclick="m_tanya_jawab_tambah()">+ Tambah </button><br><br>
<table class="w3-table-all w3-hoverable w3-medium">
<thead>
<tr class="w3-light-grey"> 
	<th width="5%">No</th>
	<th width="40%">Nama</th>
	<th width="10%">Kepada</th>
	<th width="40%">Jumlah Tanya Jawab</th>
	<th width="5%">Hapus</th>
</tr>
 </thead>
 <?php 
$sql_info="SELECT b.id, b.`nama` as nama, COUNT(a.id) AS jumlah, b.pihak_id FROM ".$db_host2.".template_keterangan_saksi_m b LEFT JOIN ".$db_host2.".template_keterangan_saksi_d a
ON a.`m_id`= b.`id` GROUP BY b.`id`";
$quer_info=mysql_query($sql_info);
$no=0;
 while($perkara_info=mysql_fetch_array($quer_info)) 
{
	$no++;
	foreach($perkara_info as $key=>$value) {$$key=$value;} 
	echo "<tr><td>".$no."</td><td contenteditable title='Klik dan ubah untuk mengubah data'  onBlur=Edit_M_Tanya(this.innerHTML,".$id.",'nama')> ".$nama."</td><td contenteditable title='Klik dan ubah untuk mengubah data'  onBlur=Edit_M_Tanya(this.innerHTML,".$id.",'pihak_id')> ".$pihak_id."</td><td><a style='text-decoration:none' href='#' onclick='edit_detail_tanya_jawab(".$id.")' title='Detail Tanya Jawab'>".$jumlah."</td><td><a title='hapus' onclick='hapus_m_tanya_jawab(".$id.")'  href='#' class='w3-button w3-red  w3-ripple w3-padding-small w3-hover-purple'>x</a></td></tr>";
 

}
?>

</table>

<div class="w3-panel w3-pale-red w3-display-container">
	<span onclick="this.parentElement.style.display='none'"
	class="w3-button w3-pale-red w3-large w3-display-topright">&times;</span>
	<h4>Perhatian!</h4>
	<p>Kepada <br>1. Pertanyaan Kepada Pemohon/ Penggugat<br>2. Pertanyaan kepada Termohon/ Tergugat<br>3. Pertanyaan kepada Saksi<br>4. Pertanyaan kepada Calon Istri</p> 
	<p>Untuk <b>Menambah</b> jenis tanya jawab, pilih <b>Tambah </b><br>Untuk <b>mengedit</b> Nama Tanya Jawab, <b>ubah</b> pada  Nama <br>Untuk <b>Menghapus</b> Tanya Jawab klik pada <b>Baris</b> Tanya Jawab (Isi tanya jawab akan terhapus)</p> 
</div>
</div>
<hr>
<!-- The Modal Master--> 
<div id="m_tanya_jawab" class="w3-modal">
	<div class="w3-modal-content w3-card-4"  style="max-width:600px;">
	  <header class="w3-container w3-teal"> 
		<span onclick="document.getElementById('m_tanya_jawab').style.display='none'" 
		class="w3-button w3-display-topright">&times;</span>
		<h3 id="judul_modal">Tambah Tanya Jawab</h3>
	  </header>
	  <div class="w3-container" >  
			<p>
			 
				<input type="hidden" id="simpan_mode" name="simpan_mode">  
				Nama <br>
				<input id="m_nama" placeholder="isikan Nama Tanya Jawab" style="width:470px" name="m_nama"> 
				<br><br>
				Kepada
				<br>
				<select id="pihak_id" name="pihak_id">
					<option value=3>Saksi</option>
					<option value=1>Pemohon/ Penggugat</option>
					<option value=2>Termohon / Tergugat</option>
					<option value=4>Calon Istri</option>
				</select>
				<br>
				<br>
				<a class="w3-btn w3-blue w3-hover-green" onclick="simpan_baru_m_tanya_jawab()">Simpan</a><br><br>	
			 
				</p>
	</div>
  </div>
</div>
 <!-- The Modal Master-->	
<!-- The Modal Detail--> 
<div id="div_detail_tanya_jawab" class="w3-modal">
	<div class="w3-modal-content w3-card-4"  style="width:100%;top:0px">
		<header class="w3-container w3-teal"> 
		<span onclick="location.reload()" 
		class="w3-button w3-display-topright">&times;</span>
		<input type="hidden" id='master_id_tanya_jawab'>
		<h4 id="judul_modal_detail">Detail Tanya Jawab</h4>
		</header>
		<div class="w3-container" id="detail_tanya_jawab_isi"></div>
	</div>
</div>
 <!-- The Modal Detail-->	
<script>
function m_tanya_jawab_tambah()
{
	document.getElementById('m_tanya_jawab').style="display:block";
	document.getElementById('m_nama').focus();
}
function tambah_baris(nomor,id) {
	var nomor_baru=nomor+1;
    var table = document.getElementById("tanyajawab");
    var row = table.insertRow(-1);
    var cell1 = row.insertCell(0); 
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
	cell1.setAttribute("height", "30"); 
    cell1.innerHTML = "<div id='data_id"+id+"' ketid"+id+">"+nomor_baru+"</div>"; 
    cell2.innerHTML = "<div id='q_tanya"+id+"'  onkeydown='return myKeyPress_kiri(event,this, "+id+")'  contenteditable='' class='tanya' onBlur=Edit_Detail(this.innerHTML,'pertanyaan',"+id+")></div>";
    cell3.innerHTML = '<div id=q_jawab'+id+'  onkeydown="return myKeyPress(event,'+nomor_baru+',this,'+id+')"  contenteditable="" class="jawab" onBlur=Edit_Detail(this.innerHTML,"jawaban",'+id+')></div>';
}


function hapus_baris(o) {
     //no clue what to put here?
     var p=o.parentNode.parentNode;
         p.parentNode.removeChild(p);
    }
</script>
 
<script type="text/javascript">
  function myKeyPress_kiri(e,o,id){
    var keynum;
	var m_id=document.getElementById('master_id_tanya_jawab').value;
    if(window.event) { // IE                    
      keynum = e.keyCode;
    } else if(e.which){ // Netscape/Firefox/Opera                   
      keynum = e.which;
    } 
	
	if(keynum==113)
	{
		hapus_detail_tanya_jawab(m_id,id);
		hapus_baris(o);
	}
	if(keynum==13)
	{ 
		return false;
	}
  }
  function myKeyPress(e,nomor,o,id){
    var keynum;
	var m_id=document.getElementById('master_id_tanya_jawab').value;
    if(window.event) { // IE                    
      keynum = e.keyCode;
    } else if(e.which){ // Netscape/Firefox/Opera                   
      keynum = e.which;
    }

    if(keynum==13)
	{
		
		var nomor_baru=nomor+1;
		Add_Detail(m_id,nomor, nomor_baru);
		 	
		 
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
		hapus_detail_tanya_jawab(m_id,id);
		hapus_baris(o);
	}
  }
</script>
<script>
//master tanya jawab
function Edit_M_Tanya(isi,id,kolom) 
{
	 
		var xhr = new XMLHttpRequest(); 
		xhr.open("POST", 'dokumen_tanya_jawab', true); 
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
		xhr.onreadystatechange = function() { 
			if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) { 
			 var pesan=xhr.responseText;  
				 notifier.show('Pesan!', pesan, '', '',10000); 
				 // location.reload();
			}
		}
		xhr.send("Edit_M_Tanya=ya&id="+encodeURIComponent(id)+"&kolom="+encodeURIComponent(kolom)+"&isi="+encodeURIComponent(isi)); 	
		
	 
}
function hapus_m_tanya_jawab(id) 
{
	var conf = confirm("Apakah anda yakin akan menghapus data ini?");
	if (conf == true) 
	{
		var xhr = new XMLHttpRequest(); 
		xhr.open("POST", 'dokumen_tanya_jawab', true); 
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
		xhr.onreadystatechange = function() { 
			if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) { 
			 var pesan=xhr.responseText;  
				 //notifier.show('Pesan!', pesan, '', '',10000); 
				  location.reload();
			}
		}
		xhr.send("hapus_m_tanya_jawab=ya&id="+encodeURIComponent(id)); 	
		
	}
}
function simpan_baru_m_tanya_jawab()
{
	var m_nama=document.getElementById('m_nama').value;
	var pihak_id=document.getElementById('pihak_id').value;
	var xhr = new XMLHttpRequest(); 
	xhr.open("POST","dokumen_tanya_jawab", true); 
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function() {//Call a function when the state changes.
		if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
			//document.getElementById("pesan_kirim").style="display.block" ;
			 
			 var pesan=xhr.responseText;  
				 location.reload(); 
		}
	}
	xhr.send("simpan_m_tanya_jawab=ya&nama="+m_nama+"&pihak_id="+pihak_id); 
}
//master tanya jawab

//detail tanya jawab 

function edit_detail_tanya_jawab(id)
{ 
	var xhr = new XMLHttpRequest(); 
	xhr.open("POST","dokumen_tanya_jawab", true); 
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function() {//Call a function when the state changes.
		if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
			//document.getElementById("pesan_kirim").style="display.block" ;
			 document.getElementById('div_detail_tanya_jawab').style="display:block; padding-top: 0px";
			 var pesan=xhr.responseText;  
			 document.getElementById('detail_tanya_jawab_isi').innerHTML=pesan;
			 document.getElementById('master_id_tanya_jawab').value=id;
				// location.reload(); 
		}
	}
	xhr.send("edit_detail_tanya_jawab=ya&id="+id); 
}


function Add_Detail(m_id, nomor, nomor_baru)
{ 
	var m_id=document.getElementById('master_id_tanya_jawab').value;
	var xhr = new XMLHttpRequest(); 
	xhr.open("POST","dokumen_tanya_jawab", true); 
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function() {//Call a function when the state changes.
		if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
			//document.getElementById("pesan_kirim").style="display.block" ;
			
			 var pesan=parseInt(xhr.responseText);  
			 //notifier.show('Pesan!', pesan, '', '',10000); 
				
			tambah_baris(nomor, pesan);	 
			var nomor_baru=nomor+1;
			document.getElementById('q_tanya'+pesan).focus();		
		}
	}
	xhr.send("add_detail=ya&id="+encodeURIComponent(nomor_baru)+"&m_id="+encodeURIComponent(m_id)); 	
}

function Edit_Detail(isi, kolom, id)
{ 
	var m_id=document.getElementById('master_id_tanya_jawab').value;
	var xhr = new XMLHttpRequest(); 
	xhr.open("POST","dokumen_tanya_jawab", true); 
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function() {//Call a function when the state changes.
		if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
			//document.getElementById("pesan_kirim").style="display.block" ;
			
			 var pesan=xhr.responseText;  
			 // notifier.show('Pesan!', pesan, '', '',10000); 
				// location.reload(); 
		}
	}
	xhr.send("edit_detail=ya&id="+encodeURIComponent(id)+"&m_id="+encodeURIComponent(m_id)+"&kolom="+encodeURIComponent(kolom)+"&isi="+encodeURIComponent(isi)); 	
}
function hapus_detail_tanya_jawab(m_id, id)
{  
	var xhr = new XMLHttpRequest(); 
	xhr.open("POST","dokumen_tanya_jawab", true); 
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function() {//Call a function when the state changes.
		if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
			//document.getElementById("pesan_kirim").style="display.block" ;
			
			 var pesan=xhr.responseText;  
			  notifier.show('Pesan!', pesan, '', '',10000); 
				// location.reload(); 
		}
	}
	xhr.send("hapus_detail_tanya_jawab=ya&id="+encodeURIComponent(id)); 	
}
//detail tanya jawab
</script>
 <link rel="stylesheet" href="resources/notifier/css/notifier.min.css">  
<script src="resources/notifier/js/notifier.min.js" type="text/javascript"></script> 