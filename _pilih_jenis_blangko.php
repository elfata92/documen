<?php 
if(!isset($_SESSION)){session_start();}
		include "sys_koneksi.php"; include "sys_config.php";include "resources/fungsi.php";
foreach($_GET as $key=>$value) {$$key=$value; }

?>
<form action="cetak">
<input value="<?php echo $tglsidang?>" id="tglsidang" name="tglsidang" type="hidden">
<input value="<?php echo $perkara_id?>" id="perkara_id" name="perkara_id" type="hidden">
<input value="<?php echo $jenis_blangko_id?>" id="jenis_blangko_id" name="jenis_blangko_id" type="hidden">
<input value="<?php echo $jenis_perkara_id?>" id="jenis_perkara_id" name="jenis_perkara_id" type="hidden">
<input value="<?php echo $id_sidang?>" id="id_sidang" name="id_sidang" type="hidden"> 
<p>
	</p>
<div class="cssTable" id="tablePerkara">
<center>
	 <h2>DOKUMEN YANG DIGUNAKAN</h2>
						<select autofocus name="template_id" id="template_id" style="width:50%"  size="20" required>
						<?php //	<option value="-1" disabled>DOKUMEN YANG DIGUNAKAN</option> ?>
						<?php 
						$sql="SELECT 
								a.template_dokumen_id
								,b.`kode`
								,b.`nama`
							FROM konsepq.template_dokumen_jenis_perkara AS a
							LEFT JOIN konsepq.template_dokumen AS b
							ON b.id=a.template_dokumen_id
							WHERE 
							a.`jenis_perkara_id`=$jenis_perkara_id AND 
							b.`jenis_blangko_id`=$jenis_blangko_id
							order by b.nama asc
							";
							 
							$quer=mysql_query($sql); 
							while($h=mysql_fetch_array($quer)) 
							{
								foreach($h as $key=>$value) {$$key=$value;} 
								?>
								<option value="<?php echo $template_dokumen_id ?>"> <?php echo $nama ?></option> 
						<?php }?>
</select>		
 <br>
 <br>
<p>
<!--<input type="submit" name="" value="OK" id="search-btn1" style="dislay:none;"> --> 
<input type="button" name="" value="OK" class="btn btn-warning" onclick="cetak_dokumen_ku()"> 
<input type="button" name="" value="Batal" class="btn btn-danger" onclick="backed()"></form> 
<div id="pesane">aaaa</div>
</p> 
	</center>
	<?php 
	//echo $sql;
	?>
	<p> </p>
	<p> </p>
	<p> </p>
</div> 
<script>
function backed() 
{
	var perkara_id=document.getElementById('perkara_id').value;
	window.location='perkara_detail?perkara_id='+perkara_id;
}
function cetak_dokumen_ku() 
{
	 
	var tglsidang=document.getElementById('tglsidang').value;
	var perkara_id=document.getElementById('perkara_id').value;
	var jenis_blangko_id=document.getElementById('jenis_blangko_id').value; 
	var jenis_perkara_id=document.getElementById('jenis_perkara_id').value; 
	var id_sidang=document.getElementById('id_sidang').value;
	var template_id=document.getElementById('template_id').value;
	 
		var xhr = new XMLHttpRequest(); 
		xhr.open("GET", "cetak?tglsidang="+tglsidang+"&perkara_id="+perkara_id +"&jenis_blangko_id="+jenis_blangko_id+"&jenis_perkara_id="+jenis_perkara_id+"&id_sidang="+id_sidang+"&template_id="+template_id, true); 
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		xhr.onreadystatechange = function() 
		{ 
			if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
				 
				//myObj = JSON.parse(xhr.responseText);
				//document.getElementById("jenis_blangko_id").value=myObj.jenis_blangko_id;  
				 alert(xhr.responseText) ;
				var pesan=xhr.responseText;
				document.getElementById("pesane").innerHTML=pesan;
				var res=pesan.split("^");
				 window.location = res[1];	
				
			}
		}
		xhr.send(); 
		
		
	   
	 
}
</script>
