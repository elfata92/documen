<?php if(!isset($_SESSION)){session_start();}include "sys_koneksi.php"; include "sys_config.php";include "resources/fungsi.php";include "resources/fungsi_doc.php";
// if(isset($_SESSION["group_name"]))
	// {
		// if($_SESSION["group_id"]<>1)
		// {
			// exit ;
		// }
	// }else
	// {
		// exit;
	// }

// ini_set('display_errors', 'on');
$judul="Master Variabel";
if(isset($_POST['editval'])){foreach($_POST as $key=>$value) {$$key=$value;}$column=antiInjections($column);$id=antiInjections($id);$editval=addslashes($editval); $sql="UPDATE ".$db_host2.".master_variabel SET $column='".$editval."' WHERE var_id=$id";  $quer=mysql_query($sql); if($quer){echo "<font color=green><b>Berhasil</b></font> Mengedit data menjadi<font color=green><b> $editval</b></font>";}else{echo "<font color=red><b>Gagal </b></font>Mengedit data menjadi <font color=red><b>$editval</b></font>";}exit; }

if(isset($_GET["simpan"]))
{
	foreach($_POST as $key=>$value) {$$key=$value; }
	if($var_nomor==""){echo "<font color=red>Nomor Variabel tidak boleh Kosong</font>";exit;}
	if($mode=='add')
	{
		$sql_cek="SELECT var_nomor FROM $db_host2.master_variabel WHERE var_nomor='$var_nomor'";
		$jumlah=mysql_num_rows(mysql_query($sql_cek));
		//echo $jumlah."<br>";
		if($jumlah==1)
		{
			echo "<font color=red>Variabel <b>".$var_nomor."</b> Sudah Ada<br>Silahkan gunakan Nomor Variabel Lain</font>";			
		}else
		{
			$sql_tambah="INSERT INTO $db_host2.master_variabel (var_nomor) values  ('$var_nomor')";
			$query_tambah=mysql_query($sql_tambah);
			$id= mysql_insert_id();
			 foreach ($_POST as $key => $value)
			 {
				if($key<>"mode")
				{
					$sql_update="UPDATE $db_host2.master_variabel SET $key='$value' WHERE var_id=$id";
					mysql_query($sql_update);
				}
			 }
			
		}
	}else
	{
		echo "Edit";	
	}
	exit;
}
if(isset($_GET["cek_daftar_field"]))
{
	$tabel=$_POST["tabel"];
	$sql_field=" SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".$db_host."' AND TABLE_NAME = '$tabel';  "; 
	 echo '<option value="">.::Pilih ::.</option>'; 					 
	 echo '<option value="DATA">DATA (Khusus Field Non SIPP)</option>'; 					 
	$res_field=mysql_query($sql_field); 
	while($rfield=mysql_fetch_array($res_field))
	{
	 echo "<option value=".$rfield['COLUMN_NAME'].">".$rfield['COLUMN_NAME']."</option>"; 
	}
	exit;
}
?>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo @$judul;?> - <?php echo $nama_aplikasi;?> <?php echo $NamaPN;?> </title><link rel="shortcut icon" href="favicon.ico" type="image/png">
<head> 
<link rel="stylesheet" type="text/css" href="resources/jqueryui/themes/icon.css"><link href="resources/vanilla-dataTables/vanilla-dataTables.min.css" rel="stylesheet" type="text/css">
<script src="resources/vanilla-dataTables/vanilla-dataTables.min.js" type="text/javascript"></script><link rel="stylesheet" href="resources/css/w3.css">  
</head>
<body>
<?php include("_menu_master.php")?>
<h4 align="center">MASTER VARIABEL </h4>	 
<div class="w3-container" >
<a onclick="tambah_variabel()"  href="#" class="w3-btn w3-purple w3-padding-small">+ Tambah Variabel</a> 
<?php 
$nama_tabel="master_variabel";
$nama_field="var_id#var_nomor#var_keterangan#var_jenis#var_sumber_sipp#var_model#var_tabel#var_field#var_urutan#var_sql_data#var_fungsi_nama#var_default_data#var_cek_sidang#var_pihak_ke";
$nama_judul="No#Nomor#Keterangan#Jenis#Sumber SIPP#Model#Tabel#Field#Urutan#SQL#Fungsi#Default Data#Cek Sidang#Pihak Ke";
$filter="";
$order_by=" ORDER BY cast(var_nomor  as unsigned) ASC ";
$limit="";
//$filter="var_id#var_nama#var_sumber_sipp";
echo  tampilkan_data_variabel($nama_tabel, $nama_field, $nama_judul, $filter, $order_by, $limit, $db_host2);
?> 
</div> 


<!-- The Modal --> 
<div id="f_edit_blangko" name="f_edit_blangko" class="w3-modal" style="padding-top: 0px;">
	<div class="w3-modal-content w3-card-4" style="width:600px">
	  <header class="w3-container w3-teal"> 
		<span onclick="document.getElementById('f_edit_blangko').style.display='none'" 
		class="w3-button w3-display-topright">&times;</span>
		<h3 id="judul_modal">Variabel</h3>
	  </header>
	  <div class="w3-container" >  
		<form class="w3-container" id="f_variabel" name="f_variabel"> 
			<input class="w3-input w3-border" name="mode" id="mode" type="hidden">
			<p><label>Nomor</label><input class="w3-input w3-border" name="var_nomor" id="var_nomor"></p>
			<p><label>Nama</label><input class="w3-input w3-border" name="var_keterangan" id="var_keterangan"></p>
			<p><label>Bentuk Inputan</label>
				<select class="w3-select  w3-border" id="var_jenis" name="var_jenis">
					<option value=""  selected>.::Pilih Bentuk Inputan::.</option>
					<?php 
						$sql_jenis="SELECT * FROM ".$db_host2.".master_variabel_jenis WHERE status =1"; 
						$res_jenis=mysql_query($sql_jenis); 
						while($r_jenis=mysql_fetch_array($res_jenis))
						{
						 echo "<option value=".$r_jenis['nama'].">".$r_jenis['nama']."</option>"; 
						}
					?>
				</select>
			</p> 
			<p><label>Sumber Data</label>
				<select class="w3-select  w3-border" name="var_sumber_sipp">
					<option value=""   selected>Pilih Sumber Data</option>
					<option value="1">Data SIPP</option>
					<option value="0">Data Non SIPP</option> 
				</select>
			</p> 
			<p><label>Model</label>
			<span id="model">
				<select class="w3-select  w3-border" name="var_model" id="var_model" onchange="pilih_model_variabel(this.value)">
					<option value=""   selected>.::Pilih Model::.</option>
					<?php 
						$sql_model="SELECT * FROM ".$db_host2.".master_variabel_model WHERE status =1"; 
						$res_model=mysql_query($sql_model); 
						while($r=mysql_fetch_array($res_model))
						{
						 echo "<option value=".$r['var_model'].">".$r['keterangan']."</option>"; 
						}
					?>
				</select>
			</span> 
			</p> 
			<p><label>Tabel</label>
			 <select name="var_tabel" id="var_tabel" class="w3-select  w3-border" onchange="cek_daftar_field(this.value)">
			 <option value="">..::Pilih Tabel ::.</option>
					<?php 
						
						$sql_tabel=" SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$db_host2."' AND TABLE_NAME   LIKE 'data_%'  "; 
						 
						$res_tabel=mysql_query($sql_tabel); 
						while($r_tabel=mysql_fetch_array($res_tabel))
						{
						 echo "<option value=".$r_tabel['TABLE_NAME'].">".$r_tabel['TABLE_NAME']."</option>"; 
						}
						
						$sql_tabel_sipp=" SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = '".$db_host."' AND TABLE_NAME   LIKE 'perkara%'  "; 
						 
						$res_tabel_sipp=mysql_query($sql_tabel_sipp); 
						while($r_tabel_sipp=mysql_fetch_array($res_tabel_sipp))
						{
						 echo "<option value=".$r_tabel_sipp['TABLE_NAME'].">".$r_tabel_sipp['TABLE_NAME']."</option>"; 
						}
					?>
				</select>
			 
			</p> 
			<p><label>Field</label>
			 
				 
				 <select name="var_field" id="var_field" class="w3-select  w3-border" >
				 <?php  
						 echo '<option value="">.::Pilih Tabel::.</option>'; 
						 
					?>
				 </select>
				
			 
			</p> 
			<p><label>Urutan</label>
				<input class="w3-input w3-border" type="number" name="var_urutan" id="var_urutan">
			 </p> 
			<p><label>SQL Tampil</label>
				<textarea  class="w3-input w3-border"  id="var_sql_data" name="var_sql_data" rows="5"></textarea>
			 </p> 
			<p><label>Data Default</label>
				<textarea  class="w3-input w3-border"  id="var_default_data" name="var_default_data" rows="5"></textarea>
			 </p> 
			 
			<p><label>Nama Fungsi</label>
				<select class="w3-select  w3-border" id="var_fungsi_nama" name="var_fungsi_nama">
					<option value=""  selected>.::Pilih Fungsi::.</option>
					<?php 
						$sql_fungsi=" SELECT nama FROM ".$db_host2.".master_variabel_fungsi_nama WHERE status =1"; 
						 
						$res_fungsi=mysql_query($sql_fungsi); 
						while($r_fungsi=mysql_fetch_array($res_fungsi))
						{
						 echo "<option value=".$r_fungsi['nama'].">".$r_fungsi['nama']."</option>"; 
						}
					?>
				</select>
			</p> 
			<p><label>Cek Sidang</label>
				<select class="w3-select  w3-border" id="var_cek_sidang" name="var_cek_sidang">
					<option value=""  selected>Tidak</option>
					<option value="1">Ya</option>
				</select>
			 </p>
			<p><label>Pertanyaan untuk</label>
				<select class="w3-select  w3-border" id="var_pihak_ke" name="var_pihak_ke">
					<option value=""  selected>Tidak</option>
					<option value="1">Penggugat/ Pemohon</option>
					<option value="2">Saksi P</option>
					<option value="3">Saksi T</option>
				</select>
			 </p>
			<p><a onclick="kirim_post('dokumen_variabel?simpan=ya','f_variabel')" class="w3-button w3-red">Simpan</a>
			 </p>
		</form> 
				 
	</div>
  </div>
</div>
 <!-- The Modal -->	
	
 <script>
var table = new DataTable(".table"); 
</script> 	
<script>

function cek_daftar_field(tabel)
{
	if(tabel==="")
	{
		return true;
	}else
	{ 
	minAjax({
		url:"dokumen_variabel?cek_daftar_field", type:"POST", 
		data:{tabel:tabel}
		,success: function(data){
			document.getElementById('var_field').innerHTML =data;
			}
			}
			); 
	}
	 		
}	
function tambah_variabel()
{
	document.getElementById('f_edit_blangko').style.display='block';
	document.getElementById('mode').value='add';
	document.getElementById('var_nomor').focus();
}	
function jenis_tanya_jawab()
{
	document.getElementById('var_tabel').disabled =true;
	document.getElementById('var_field').disabled =true;
	document.getElementById('var_urutan').disabled =false;
	document.getElementById('var_sql_data').disabled =true;
	document.getElementById('var_default_data').disabled =true;
	document.getElementById('var_fungsi_nama').disabled =true;
	document.getElementById('var_cek_sidang').disabled =true;
	document.getElementById('var_pihak_ke').disabled =false;
}	
function jenis_text()
{
	document.getElementById('var_tabel').disabled =false;
	document.getElementById('var_field').disabled =false;
	document.getElementById('var_urutan').disabled =false;
	document.getElementById('var_sql_data').disabled =false;
	document.getElementById('var_default_data').disabled =false;
	document.getElementById('var_fungsi_nama').disabled =false;
	document.getElementById('var_cek_sidang').disabled =false;
	document.getElementById('var_pihak_ke').disabled =true;			
}	
function pilih_model_variabel(jenis)
{
	if(jenis=='tanya_jawab')
	{
		return jenis_tanya_jawab();
	}else
	if(jenis=='text')
	{
		return  jenis_text();
	}else
	if(jenis=='sql')
	{
		return  jenis_text();
	}else
	{
		notifier.show('Pesan!', 'Belum Diset', '', '', 4000);
	}		
			
}

function serialize(form) 
{ 
    var field, l, s = [];
    if (typeof form == 'object' && form.nodeName == "FORM") {
        var len = form.elements.length;
        for (var i=0; i<len; i++) {
            field = form.elements[i];
            if (field.name && !field.disabled && field.type != 'file' && field.type != 'reset' && field.type != 'submit' && field.type != 'button') {
                if (field.type == 'select-multiple') {
                    l = form.elements[i].options.length; 
                    for (var j=0; j<l; j++) {
                        if(field.options[j].selected)
                            s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.options[j].value);
                    }
                } else if ((field.type != 'checkbox' && field.type != 'radio') || field.checked) {
                    s[s.length] = encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value);
                }
            }
        }
    }
    return s.join('&').replace(/%20/g, '+');
}

function kirim_post(url, frm)
{ 
	 
	var xhr = new XMLHttpRequest();
	var data=serialize(f_variabel);  
	xhr.open("POST",url, true); 
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function() {//Call a function when the state changes.
		if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
			 var data=xhr.responseText;  
			 data=data.replace(/\r?\n|\r/g, "");
			notifier.show('Pesan!', data, '', '', 4000);
			//document.getElementById("pesan_kirim").style="display.block" ;
		 location.reload();
			
			
		}
	}
	xhr.send(data); 
}; 

</script> 	
<script src="resources/minAjax.js" type="text/javascript"></script><link rel="stylesheet" href="resources/notifier/css/notifier.min.css"> <script src="resources/notifier/js/notifier.min.js" type="text/javascript"></script>
<script>
function Edit(editableObj,column,id) 
{
	minAjax({
		url:"dokumen_variabel?edit", type:"POST", 
		data:{column:column, editval:editableObj, id:id }
		,success: function(data){
			notifier.show('Pesan!', data, '', '', 4000);
			}
			}
			); }
</script>
</body>
</html>    