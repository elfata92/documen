<?php 
if(!isset($_SESSION)){session_start();}
		include "sys_koneksi.php"; include "sys_config.php";include "resources/fungsi.php";
foreach($_GET as $key=>$value) {$$key=$value; }

?>
<form action="_dokumen_view"> 
<input value="<?php echo $perkara_id?>" id="perkara_id" name="perkara_id" type="hidden">
<input value="<?php echo $jenis_blangko_id?>" id="jenis_blangko_id" name="jenis_blangko_id" type="hidden">
<input value="<?php echo $jenis_perkara_id?>" id="jenis_perkara_id" name="jenis_perkara_id" type="hidden"> 
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
<input type="submit" name="" value="OK" class="btn btn-warning"> 
<input type="button" name="" value="Batal" class="btn btn-danger" onclick="backed()"></form> 

</p> 
</form>
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
</script>
