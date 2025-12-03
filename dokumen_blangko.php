<?php if(!isset($_SESSION)){session_start();}
		include "sys_koneksi.php"; 
		include "sys_config.php";
		include "resources/fungsi.php";
		//include "resources/fungsi_dokumen.php";
		// ini_set('display_errors', 'on');
		// if(isset($_SESSION["group_name"]))
		// {
			// if($_SESSION["group_id"]>0)
			// {
				// exit ;
			// }
		// }else
		// {
			// exit;
		// }
?>
<?php 

if(isset($_GET["cek_ulang_variabel"]))
{
	$id=$_POST["id"]; 
	$filenya=$_POST["filenya"]; 
	//$id_template=$_POST["id_template"]; 
	$target_file="doc/".$filenya;
	
	//haus variavel
	$hapus=mysql_query("DELETE FROM ".$db_host2.".template_variabel WHERE id=$id ");
		if(!$hapus){echo "Error Menghapus Perkara Pengguna\n";}
	//haus variavel
	bacafile_dan_input_variabel($target_file,$db_host2,$id);
	echo tampilkan_variabel($id,$db_host2);
	exit;	
}

if(isset($_GET["list_variabel"]))
{
	$id=$_POST["id"]; 
	echo tampilkan_variabel($id,$db_host2);
	exit;	
}

if(isset($_GET["hapus_variabel_template"]))
{
	$id=$_POST["id"];
	$template_id=$_POST["template_id"];
	//echo $_POST["file_dokumen"];
	//hapus perkara pengguna
		$hapus=mysql_query("DELETE FROM ".$db_host2.".template_variabel WHERE id=$id ");
		if(!$hapus){echo "Error Menghapus Perkara Pengguna\n";}
	   
	
	if($hapus){echo "Hapus Data Berhasil<br>";}
	echo tampilkan_variabel($template_id,$db_host2);
	exit;
}
if(isset($_GET["hapus"]))
{
	$id=$_POST["id"];
	$file_dokumen=$_POST["file_dokumen"];
	//echo $_POST["file_dokumen"];
	//hapus perkara pengguna
		$hapus1=mysql_query("DELETE FROM ".$db_host2.".template_dokumen_jenis_perkara WHERE template_dokumen_id=$id ");
		if(!$hapus1){echo "Error Menghapus Perkara Pengguna\n";}
	//hapus perkara pengguna
	
	//hapus template variabel 
		$hapus2=mysql_query("DELETE FROM ".$db_host2.".template_variabel WHERE template_id=$id ");
		if(!$hapus2){echo "Error Menghapus template variabel\n";}
	//hapus template variabel 
		
	//hapus template dokumen   
		$hapus3=mysql_query("DELETE FROM ".$db_host2.".template_dokumen WHERE id=$id ");
		if(!$hapus3){echo "Error Menghapus template dokumen\n";}
	//hapus template dokumen   
	
	//hapus  dokumen   
		$hapus4=unlink('doc/'.$file_dokumen);
		if(!$hapus4){echo "Error Menghapus file dokumen\n";}
	//hapus  dokumen  
	
	if($hapus1 AND $hapus2 AND $hapus3 AND $hapus4){echo "Hapus Data Berhasil";}
	exit;
}
if(isset($_GET["lihat"]))
{
	$id=$_POST["id"];
	$sql="SELECT *  FROM ".$db_host2.".template_dokumen where id=$id"; 
	$query=mysql_query($sql)	; 
	while($h=mysql_fetch_array($query)) 
	{
		foreach($h as $key=>$value) {$$key=$value;}  
	}
	//cek perkara pengguna 
		//$sql_cek_pengguna="SELECT jenis_perkara_id  FROM ".$db_host2.".template_dokumen_jenis_perkara where template_dokumen_id=$id"; 
		$sql_cek_pengguna="SELECT 
							  id as jenis_perkara_id,
							  nama as nama_jenis_perkara,
									CASE 
									WHEN 	  
									  (SELECT 
										jenis_perkara_id 
									  FROM
										".$db_host2.".template_dokumen_jenis_perkara 
									  WHERE template_dokumen_id = $id
										AND jenis_perkara_id = v_jenis_perkara.id) IS NOT NULL
									THEN 'checked'				
									ELSE
										' '
									END 
							AS checked 
							FROM
							  v_jenis_perkara 
							WHERE alur_perkara_id = 15 
							  OR alur_perkara_id = 16 ORDER by alur_perkara_id asc, nama asc "; 
		$query_cek_pengguna=mysql_query($sql_cek_pengguna)	; 
		$perkara_pengguna='';
		while($h_cek_pengguna=mysql_fetch_array($query_cek_pengguna)) 
		{
			foreach($h_cek_pengguna as $key=>$value) {$$key=$value;}  
			$perkara_pengguna.=$jenis_perkara_id.'^'.$nama_jenis_perkara.'^'.$checked.'^#';
		}
	//cek perkara pengguna 
	$data=array(	
					'id'=>$id 
					,'kode'=>$kode
					,'nama'=>$nama
					,'jenis_blangko_id'=>$jenis_blangko_id 
					,'perkara_pengguna'=>$perkara_pengguna 
					);
			array_push($data, $data); 
		 		
		echo json_encode($data);
	exit;	
}

if(isset($_GET["edit_simpan"]))
{
	foreach($_POST as $key=>$value) {$$key=$value;}
	$sql_simpan="UPDATE  ".$db_host2.".template_dokumen SET nama='$template_dokumen_nama', jenis_blangko_id=$jenis_blangko_id WHERE id=$id_blangko";
	//echo '<br>'.$sql_simpan.'<br>';
	$simpan=mysql_query($sql_simpan);
	if($simpan){echo "Update Data Berhasil";}
	
	//hapus dulu
	mysql_query("DELETE FROM ".$db_host2.".template_dokumen_jenis_perkara WHERE template_dokumen_id=$id_blangko");
	//hapus dulu
	
	//SIMPAN JENIS PERKARA  
	 $jumlah_data=0;
	 $jumlah_data= count(@$_POST['pilih_jenis']);
	 if(!$jumlah_data==0)
	 { 
		//echo "<br>"; 
		for($i=0;$i<$jumlah_data;$i++)
		{ 
			$jenis_perkara_id = $_POST['pilih_jenis'][$i];  
			mysql_query("INSERT INTO ".$db_host2.".template_dokumen_jenis_perkara (template_dokumen_id,jenis_perkara_id)values ($id_blangko,$jenis_perkara_id)"); 
		}
	}
	//SIMPAN JENIS PERKARA 
	//
	exit;
}
	
if(isset($_GET["upload_ulang"]))
{
	foreach($_POST as $key=>$value) {$$key=$value;}
	 
	echo "YES";
	$target_dir = "doc/";
	$nama_file= basename($_FILES["fileToUpload1"]["name"]);
	$nama_file_tanpa_ekstensi=str_replace(".rtf","",$nama_file);
	$target_file = $target_dir . basename($_FILES["fileToUpload1"]["name"]);
	$file_sudah_ada=$target_dir . $kode.'.rtf';
	echo $nama_file_tanpa_ekstensi;
	$uploadOk = 1;
	$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	 
	if($FileType != "rtf") {
		echo "<b>Pastikan Dokumen jenisnya rtf</b><br>Untuk mengulangi Proses <a href='dokumen_blangko'> <b>Silahkan Kembali</a><br>"; 
		$uploadOk = 0;
		exit;
	}else
	{
		if (move_uploaded_file($_FILES["fileToUpload1"]["tmp_name"], $file_sudah_ada)) 
		{
			echo "File ". basename( $_FILES["fileToUpload1"]["name"]). " Sudah Terupload."; 
			 //baca dan simpan variabel ke tabel master variabel
		 
		}else
		{
			echo "Upload Ggagal";
		}
		lempar('dokumen_blangko');
	}
	exit;
}	
	
if(isset($_GET["simpan"]))
{
	foreach($_POST as $key=>$value) {$$key=$value;}
	//echo $jenis_blangko_id;	
	
	
	//SIMPAN TEMPLATE 
	
	//cek_id_terakhir
		$sql_id_template_terakhir="SELECT max(id) as id_template_terakhir FROM ".$db_host2.".template_dokumen ORDER BY id ASC limit 1";
		echo $sql_id_template_terakhir;
		$query_template_terakhir=mysql_query($sql_id_template_terakhir)	; 
		while($h_template_terakhir=mysql_fetch_array($query_template_terakhir)) 
		{
			$idnya_template_terakhir=$h_template_terakhir['id_template_terakhir']+1;
			$id_template=$idnya_template_terakhir;
		}
		echo "<br>".$id_template; 
		//cek_id_terakhir
			$target_dir = "doc/";
			$nama_file= basename($_FILES["fileToUpload"]["name"]);
			$nama_file_tanpa_ekstensi=str_replace(".rtf","",$nama_file);
			$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
			$uploadOk = 1;
	$sql_template_db="SELECT  id FROM ".$db_host2.".template_dokumen WHERE nama ='$template_dokumen_nama' OR kode='$nama_file_tanpa_ekstensi'";
	echo "<br>".$sql_template_db."<br>";
	$jumlah_template_db= mysql_num_rows(mysql_query($sql_template_db));
	
			
	if($jumlah_template_db==0)
	{
		$sql_simpan="INSERT INTO ".$db_host2.".template_dokumen 
							(id, kode, nama, jenis_blangko_id)
							VALUES 
							($idnya_template_terakhir,  '$nama_file_tanpa_ekstensi', '$template_dokumen_nama',  $jenis_blangko_id )";
		//echo '<br>'.$sql_simpan.'<br>';
		$simpan=mysql_query($sql_simpan);
		if($simpan)
		{
			//UPLOAD
			$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
			 
			if (file_exists($target_file)) 
			{
				echo "Dokumen dengan Nama : <b>[ ".$nama_file." ] <font color=red>Sudah Pernah Terupload</font></b><br>
					 Untuk mendownload File Tersebut <a href='doc/".$nama_file."'> <b>Klik Disini</b></a><br>
					 Untuk mengulangi Proses <a href='dokumen_blangko'> <b>Silahkan Kembali</a><br>"; 
				exit;
			} 
			if($FileType != "rtf") {
				echo "<b>Pastikan Dokumen jenisnya rtf</b><br>Untuk mengulangi Proses <a href='dokumen_blangko'> <b>Silahkan Kembali</a><br>"; 
				$uploadOk = 0;
				exit;
			}else
			{
				 $uploadOk = 1;
			} 
			if ($uploadOk == 0) {
				echo "File Tidak Terupload."; 
				exit;
			} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					echo "File ". basename( $_FILES["fileToUpload"]["name"]). " Sudah Terupload."; 
					 //baca dan simpan variabel ke tabel master variabel
					 
					 //baca dan simpan variabel ke tabel master variabel
					 
					
					//SIMPAN JENIS PERKARA 
					 $jumlah_data=0;
					 $jumlah_data= count(@$_POST['pilih_jenis']);
					 if(!$jumlah_data==0)
					 { 
						echo "<br>"; 
						for($i=0;$i<$jumlah_data;$i++)
						{
							 
							$jenis_perkara_id = $_POST['pilih_jenis'][$i]; 
							  
							mysql_query("INSERT INTO ".$db_host2.".template_dokumen_jenis_perkara (template_dokumen_id,jenis_perkara_id)values ($id_template,$jenis_perkara_id)");
							 
							
						}
					}
					//SIMPAN JENIS PERKARA 
				} else {
					echo "Error Upload<br>Untuk mengulangi Proses <a href='dokumen_blangko'> <b>Silahkan Kembali</a><br>"; 
					exit;
				}
			}
			//UPLOAD
		}else
		{
			echo "Error Menyimpan  <br><br> 
					 Untuk mengulangi Proses <a href='dokumen_blangko'> <b>Silahkan Kembali</a><br>";	
		}
		
	}else
	{
		echo " Nama Dokumen : <b>[ ".$template_dokumen_nama." ] <font color=red>Sudah Ada</font></b><br> 
					 Untuk mengulangi Proses <a href='dokumen_blangko'> <b>Silahkan Kembali</a><br>"; 
				exit;
	}		
		//echo '<br>'.$sql_template.'<br>';
		 
			
		 
	//SIMPAN TEMPLATE 
	echo "  <br><br> 
					<a href='dokumen_blangko'> <b>Kembali</a><br>";
	
	exit;
}
?>
<html>
<head>
	<link rel="stylesheet" href="resources/jqueryui/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="resources/jqueryui/themes/icon.css">
	<script src="resources/jqueryui/jquery.min.js"></script>
	<script src="resources/jqueryui/jquery.easyui.min.js"></script>
	<link rel="stylesheet" href="resources/css/w3.css">  
</head>
<body> 
<?php include("_menu_master.php")?>
<style>.modal{display:none;position:fixed;z-index:1;padding-top:10px;left:0;top:0;width:100%;height:100%;overflow:auto;background-color:#000;background-color:rgba(0,0,0,.4)}.modal-content{position:relative;background-color:#fefefe;margin:auto;padding:0;border:1px solid #888;width:830px;box-shadow:0 4px 8px 0 rgba(0,0,0,.2),0 6px 20px 0 rgba(0,0,0,.19);-webkit-animation-name:animatetop;-webkit-animation-duration:.4s;animation-name:animatetop;animation-duration:.4s}@-webkit-keyframes animatetop{from{top:-300px;opacity:0}to{top:0;opacity:1}}@keyframes animatetop{from{top:-300px;opacity:0}to{top:0;opacity:1}}.close{color:#fff;float:right;font-size:20px;font-weight:700}.close:focus,.close:hover{color:#000;text-decoration:none;cursor:pointer}.modal-footer,.modal-header{padding:2px 16px;background-color:#008C4B;color:#fff}.modal-body{padding:2px 16px;text-align:center}
.btn {
    margin: 0;
    font-weight: 300;
    color: white;
    border-radius: 3px;
    border: 0 none;
    padding: 8px 20px;background-color: #007640;
}
.btn-danger {
    background-color: #dd4b39;
    border-color: #d73925;
}
.btn-warning {
    background-color: #f39c12;
border-color: #e08e0b;}
.btn-primary {
    color: #fff;
    background-color: #337ab7;
    border-color: #2e6da4;
}
</style>
			
			<div id="peringatan" class="modal">
				<div class="modal-content">  
					<div class="modal-header"> 
						<span class="close" onclick="tutup_modal()">&times;</span> 
						<h3 id="modal_header"></h3> 
					</div>
					<div class="modal-body">
						<div id="input_data">	
						<form action="dokumen_blangko?simpan=ok" id="formx" name="formx" method="post" enctype="multipart/form-data">
						<input type="hidden" name='id_blangko' id='id_blangko'>
						<table cellpadding="5">
							<tr>
								<td>Jenis Blangko</td>
								<td>
									<select name="jenis_blangko_id" id="jenis_blangko_id" required>
									<option value="" disabled selected>.::: Pilih Jenis Blangko :::.</option>
										<?php 
											$sql_jenis_blangko=" SELECT * FROM ".$db_host2.".jenis_blangko   
														order by jenis_blangko_parent_id asc, jenis_blangko_id asc "; 
											$res_jenis_blangko=mysql_query($sql_jenis_blangko); 
											while($row_jenis_blangko=mysql_fetch_array($res_jenis_blangko))
											{
												 echo "<option value=".$row_jenis_blangko['jenis_blangko_id']."> ".$row_jenis_blangko['jenis_blangko_nama']."</option>";
														
											}
										?>
										
									</select>
								</td>
							</tr>
							<tr id="file_dokumen_upload">
								<td>File Dokumen</td>
								<td> <input onchange='nama_file_pilihan(this.value)' type="file" style="width:650px;"  name="fileToUpload" id="fileToUpload" required accept=".rtf"></td>
							</tr>
							<tr>
								<td>Keterangan</td>
								<td> <input  style="width:650px;" id="template_dokumen_nama" name="template_dokumen_nama"  required placeholder="Isikan Nama Blangko"></td>
							</tr>
							<tr id="file_dokumen_edit">
								<td valign=top>File Dokumen</td>
								<td  id="isi_dokumen_edit"> Nama File </td>
							</tr>
							<tr>
								<td valign="top">Perkara Pemakai</td>
								<td> 
								<div id="pemakai" class="easyui-panel" title="" style="width:650px;height:250px;padding:5px;">
									 
										 
									
									<table>
										<tr>
											<td align=left><input  type="checkbox"   onClick="handleChange(this)" id="pilih_semua"></td>
											<td colspan="2"><b>Pilih Semua Jenis Perkara</b></td>
										</tr>
										<?php 
											$sql_jenis_perkara=" SELECT * FROM v_jenis_perkara where alur_perkara_id=15 OR alur_perkara_id=16
														order by alur_perkara_id asc, nama asc "; 
											$res_jenis_perkara=mysql_query($sql_jenis_perkara); 
											while($row__jenis_perkara=mysql_fetch_array($res_jenis_perkara))
											{?>
											<tr>
												<td> </td>
												<td><input type="checkbox" name="pilih_jenis[]" id="cek" value="<?php echo $row__jenis_perkara['id'] ?>"></td>
												<td><?php echo $row__jenis_perkara['nama'] ?></td>
											</tr>
												 
											<?php 			
											}
										?>
									</table>
									</div> 
								</td>
							</tr>
						</table>	
						 <input value="Simpan" id="tmbl_simpan" type="submit" style="margin: 0; font-weight: 300; color: white; background-color: #007640; padding: 8px 0; border-radius: 0 0 0 0; -moz-border-radius: 0 0 0 0; -webkit-border-radius: 0 0 0 0; -o-border-radius: 0 0 0 0; border: 0 none; padding-top: 10px; padding-bottom: 10px; padding-left: 15px; padding-right: 15px;">
						 <a href="#" onClick="edit_simpan()"  id="tmbl_edit" style="margin: 0; font-weight: 300; color: white; background-color: #007640; padding: 8px 0; border-radius: 0 0 0 0; -moz-border-radius: 0 0 0 0; -webkit-border-radius: 0 0 0 0; -o-border-radius: 0 0 0 0; border: 0 none; padding-top: 10px; padding-bottom: 10px; padding-left: 15px; padding-right: 15px;">Edit</a>
						 <a href="#" onClick="hapus()"  id="tmbl_hapus" style="margin: 0; font-weight: 300; color: white; background-color: #A4493F; padding: 8px 0; border-radius: 0 0 0 0; -moz-border-radius: 0 0 0 0; -webkit-border-radius: 0 0 0 0; -o-border-radius: 0 0 0 0; border: 0 none; padding-top: 10px; padding-bottom: 10px; padding-left: 15px; padding-right: 15px;">Hapus</a> <p></p> 
					</form>
					</div> 
					<div id="upload_ulang">
						<p></p>
						<form id="form_upload_ulang" name="form_upload_ulang" method="post" enctype="multipart/form-data" action="dokumen_blangko?upload_ulang=ya">
						<input name="id_template_ganti" id="id_template_ganti" type="hidden">
						<input name="kode" id="kode" type="hidden">
						<input  type="file" style="width:650px;"  name="fileToUpload1" id="fileToUpload1" required accept=".rtf">
						<input type="submit"   class="btn btn-primary"  value="Upload"> 
						</form>
						<a href='#' onclick='kembali_pilih()'>[ <font color='red'><b>< Kembali</b></font> ] </a><br><br>
					</div>
					<div id="daftar_variabel">
						Daftar Variabel 
					</div>
				</div>  
					 
						 
					</div>  
				</div>
				<p></p><p></p><p></p><p></p>
			</div>
<center>		
<a href="perdata"  class="easyui-linkbutton" data-options="iconCls:'icon-back'"><font color="#114599">Kembali Ke Daftar Perkara</font></a>    
<a href="dokumen_variabel"  class="easyui-linkbutton" data-options="iconCls:'icon-sum'"><font color="#114599">Daftar Master Variabel</font></a> 
</center>			
<br>
 <h2 align="center"> DAFTAR DOKUMEN BLANGKO </h2>
	 <div style="padding:5px 0;">
		<a href="#" onclick="tambah_baru()" class="easyui-linkbutton" data-options="iconCls:'icon-edit'"><font color="#017A01">Tambah</font></a>  
	</div>
	 
	<div class="easyui-accordion" style="width:100%;">
		  
 
		 
		  
		 
			 <?php 
				function get_blangko_tree($parent_id)
				{
				include 'sys_koneksi.php'; 
				$menu="";
				$sqlquery=" SELECT * FROM ".$db_host2.".jenis_blangko where jenis_blangko_parent_id='".$parent_id."' 
														order by jenis_blangko_parent_id asc, jenis_blangko_id asc "; 
				$res=mysql_query($sqlquery);
				$icon='icon-add';
				while($row=mysql_fetch_array($res))
				{
				$menu.='<div title="'.$row['jenis_blangko_nama'].'"  style="overflow:auto;padding:10px;">
						 
						<ul class="easyui-tree">
						';
						$sqlquery1=" SELECT * FROM ".$db_host2.".template_dokumen where jenis_blangko_id=".$row['jenis_blangko_id']."  order by nama asc "; 
						//echo $sqlquery1;
							$res1=mysql_query($sqlquery1);
							while($row1=mysql_fetch_array($res1))
							{
								$tambahan=$row1['nama'] ;
								$menu.= "<li><a href=# onclick=edit(".$row1['id'].")><span>  ".$row1['nama']." </span></a> </li>";
								 
							}	
				$menu.="</ul>";		
				$menu.=''.get_blangko_tree($row['jenis_blangko_id']).'';
				$menu.="</div>";
				 
				
				
				}
				return $menu;}?>
<?php echo get_blangko_tree(0);?>
						 
			 
			 
		</div>  
	<style>body{font-family:verdana,helvetica,arial,sans-serif;padding:20px;font-size:12px;margin:0}td{font-family:verdana,helvetica,arial,sans-serif;font-size:12px}h2{font-size:18px;font-weight:bold;margin:0}.demo-info{padding:0 0 12px 0}.demo-tip{display:none}.label-top{display:block;height:22px;line-height:22px;vertical-align:middle}a{text-decoration:none}</style>

	<script>
				function tutup_modal() 
				{ 
					document.getElementById("peringatan").style.display = 'none'; 
				}
				function tambah_baru()
				{
						awal_inputan() ;
					 document.getElementById("modal_header").innerHTML="Tambah Blangko Dokumen";  
					 document.getElementById("peringatan").style.display = 'block'; 
					 document.getElementById("jenis_blangko_id").focus();  
					 
					 
				}
				function edit(id)
				{
					document.getElementById("input_data").style.display = 'block'; 
					document.getElementById("upload_ulang").style.display = 'none'; 
					document.getElementById("daftar_variabel").style.display = 'none'; 
					document.getElementById("modal_header").innerHTML="Silahkan Tunggu....   ";  
					document.getElementById("id_blangko").value=id;  
					document.getElementById("id_template_ganti").value=id;  
					
					var xhr = new XMLHttpRequest(); 
					xhr.open("POST", "dokumen_blangko?lihat=ya", true); 
					xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

					xhr.onreadystatechange = function() 
					{ 
						if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
							 
							myObj = JSON.parse(xhr.responseText);
							document.getElementById("jenis_blangko_id").value=myObj.jenis_blangko_id;  
							document.getElementById("template_dokumen_nama").value=myObj.nama;  
							document.getElementById("kode").value=myObj.kode;   
							document.getElementById("isi_dokumen_edit").innerHTML="<span id='file_nya'>"+myObj.kode+".rtf</span>  <br><br><a href='doc/"+myObj.kode+".rtf'>  [<b> Unduh Blangko </b>]</a>   <br><br>  <a href='#' onClick='upload_ulang()'>  [<b> Ganti Blangko </b>]</a>  -> Apabila Blangko Akan Diganti Dengan Yang Baru      ";  
							
							var code_nya1='';
							var perkara_pengguna=myObj.perkara_pengguna;
							var res=perkara_pengguna.split("#");
							for( var i = 0; i < res.length-1; i++ ) 
							{
								var code=res[i];
								var res1=code.split("^");
								var code_nya1=code_nya1+'<tr>  <td> </td> <td><input type="checkbox" name="pilih_jenis[]" id="cek" value="'+res1[0]+'" '+res1[2]+'></td> <td>'+res1[1]+'</td>';
							}
							 //
							document.getElementById("pemakai").innerHTML='<table> <tr> <td align=left><input  type="checkbox"   onClick="handleChange(this)" id="pilih_semua"></td> <td colspan="2"><b>Pilih Semua Jenis Perkara</b></td> </tr>   '+code_nya1+'</table>';  
							
							document.getElementById("modal_header").innerHTML="Edit Blangko Dokumen   ";  
							document.getElementById("peringatan").style.display = 'block'; 
							 
							document.getElementById("tmbl_simpan").style.visibility = 'hidden'; 
							document.getElementById("tmbl_edit").style.visibility = 'visible'; 
							document.getElementById("tmbl_hapus").style.visibility = 'visible'; 
							document.getElementById("file_dokumen_edit").style.visibility = 'visible'; 
							document.getElementById("file_dokumen_upload").style.visibility = 'hidden';
							 //
						}
					}
					xhr.send("id="+encodeURIComponent(id)); 
					 
					 
				} 
				function handleChange(cb) {
					var status_cek=document.getElementById("cek").checked ;
					var allCB = document.querySelectorAll("input[id='cek']");
					if(status_cek==false)
					{ 
						for(var i=0; i< allCB.length; i++)
						{
							allCB[i].checked=true;
						}
					}else
					{
						for(var i=0; i< allCB.length; i++)
						{
							allCB[i].checked=false;
						}
					}
				}
				function awal_inputan() 
				{
					document.getElementById("jenis_blangko_id").value='';
					document.getElementById("template_dokumen_nama").value='';
					document.getElementById("fileToUpload").value=''; 
					document.getElementById("pilih_semua").checked=false;
					var allCB = document.querySelectorAll("input[id='cek']");
					for(var i=0; i< allCB.length; i++)
					{
						allCB[i].checked=false;
						
					}
					document.getElementById("tmbl_simpan").style.visibility = 'visible'; 
					document.getElementById("tmbl_edit").style.visibility = 'hidden'; 
					document.getElementById("tmbl_hapus").style.visibility = 'hidden'; 
					document.getElementById("file_dokumen_edit").style.visibility = 'hidden'; 
					document.getElementById("file_dokumen_upload").style.visibility = 'visible'; 
					document.getElementById("isi_dokumen_edit").innerHTML='';
					
					document.getElementById("input_data").style.display = 'block'; 
					document.getElementById("upload_ulang").style.display = 'none'; 
					document.getElementById("daftar_variabel").style.display = 'none'; 
				}
				function hapus() 
				{
					var txt;
					var id=document.getElementById('id_blangko').value;
					var file_nya=document.getElementById('file_nya').innerHTML;
					var r = confirm("Apakah Anda Yakin Akan Menghapus Blangko ini? \nApabila memang blangko masih diperlukan silahkan UNDUH terlebih dahulu!!");
					if (r == true) {
						var xhr = new XMLHttpRequest(); 
						xhr.open("POST", "dokumen_blangko?hapus=ya", true); 
						xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						xhr.onreadystatechange = function() 
						{ 
							if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
								 
								//myObj = JSON.parse(xhr.responseText);
								//document.getElementById("jenis_blangko_id").value=myObj.jenis_blangko_id;  
								 alert(xhr.responseText) ;
								 window.location = "dokumen_blangko";	
								 //
							}
						}
						xhr.send("id="+encodeURIComponent(id) 
						+"&file_dokumen="+encodeURIComponent(file_nya)
						); 
						
						
					}  
					 
				}
				function edit_simpan()
				{ 
					var form = document.formx; 
					var dataString = $(form).serialize();


					$.ajax({
						type:'POST',
						url:'dokumen_blangko?edit_simpan=yes',
						data: dataString,
						success: function(data){
							alert(data);
							location.reload(); 
						}
					});
					return false; 
				}
				function upload_ulang()
				{ 
					document.getElementById("modal_header").innerHTML="Ganti Blangko File : "+document.getElementById("file_nya").innerHTML;  
					document.getElementById("input_data").style.display = 'none'; 
					document.getElementById("upload_ulang").style.display = 'block'; 
					document.getElementById("daftar_variabel").style.display = 'none'; 
					document.getElementById("fileToUpload1").value = ''; 
				}
				function kembali_pilih()
				{ 
					document.getElementById("modal_header").innerHTML="Ganti Blangko File : "+document.getElementById("file_nya").innerHTML;  
					document.getElementById("input_data").style.display = 'block'; 
					document.getElementById("upload_ulang").style.display = 'none'; 
					document.getElementById("daftar_variabel").style.display = 'none'; 
				}
				 
				
				function list_variabel()
				{ 
					var id=document.getElementById('id_blangko').value;
					var xhr = new XMLHttpRequest(); 
						xhr.open("POST", "dokumen_blangko?list_variabel=ya", true); 
						xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						xhr.onreadystatechange = function() 
						{ 
							if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
								 
								//myObj = JSON.parse(xhr.responseText);
								document.getElementById("daftar_variabel").innerHTML=xhr.responseText;  
								document.getElementById("modal_header").innerHTML="Daftar Variabel : "+document.getElementById("template_dokumen_nama").value;  
								document.getElementById("input_data").style.display = 'none'; 
								document.getElementById("upload_ulang").style.display = 'none'; 
								document.getElementById("daftar_variabel").style.display = 'block';  
								// alert(xhr.responseText) ;
								// window.location = "dokumen_blangko";	
								 //
							}
						}
						xhr.send("id="+encodeURIComponent(id) 
						);
				}
				
				function hapus_variabel_template(id,template_id) 
				{ 
					var r = confirm("Apakah Anda Yakin Akan Menghapus Variabel ini? ");
					if (r == true) {
						var xhr = new XMLHttpRequest(); 
						xhr.open("POST", "dokumen_blangko?hapus_variabel_template=ya", true); 
						xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						xhr.onreadystatechange = function() 
						{ 
							if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
								 
								//myObj = JSON.parse(xhr.responseText);
								//document.getElementById("jenis_blangko_id").value=myObj.jenis_blangko_id;  
								document.getElementById("daftar_variabel").innerHTML=xhr.responseText;  
								 //
							}
						}
						xhr.send("id="+encodeURIComponent(id) 
						+"&template_id="+encodeURIComponent(template_id)
						); 
						
						
					}  
					 
				}
				
				function cek_ulang_variabel() 
				{ 
					var filenya=document.getElementById('file_nya').innerHTML; 
					var id=document.getElementById('id_blangko').value;
					var r = confirm("Apakah Anda Yakin Cek Ulang Variabel  Menghapus Variabel ini?\Proses ini akan menghapus terlebih dahulu variabel tersimpan di database dan Mengisi Variabel Berdasarkan Dokumen ");
					if (r == true) {
						var xhr = new XMLHttpRequest(); 
						xhr.open("POST", "dokumen_blangko?cek_ulang_variabel=ya", true); 
						xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						xhr.onreadystatechange = function() 
						{ 
							if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
								 
								//myObj = JSON.parse(xhr.responseText);
								//document.getElementById("jenis_blangko_id").value=myObj.jenis_blangko_id;  
								document.getElementById("daftar_variabel").innerHTML=xhr.responseText;  
								 //
							}
						}
						xhr.send("id="+encodeURIComponent(id) 
						+"&filenya="+encodeURIComponent(filenya)
						); 
						
						
					}  
					 
				}
				function nama_file_pilihan(file) 
				{ 
					var nama_file=file.split(".rtf") ;
					var  n = nama_file[0].length;
					var  nama_file1 = nama_file[0];
					//alert(n);
					//var nama_file1=nama_file.split(":") ;
					//var nama_file2=nama_file1[1] ;
					
					//var nama_file2=nama_file1.replace("C:\fakepath\","");
					document.getElementById("template_dokumen_nama").value=nama_file1.substr(12, n);
					 
				}
			</script>
</body>
</html>    