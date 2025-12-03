<?php   $judul="Daftar Perkara Perdata";
	include "sys_header.php"; 
	 if(isset($_GET['search']))
	 {
		 $kunci=$_GET['search'];
		 $file="getPerdata.php?search=".$kunci;
		 $pencarian="WHERE perkara.nomor_perkara like '%$kunci%' 
					OR perkara.jenis_perkara_nama like '%$kunci%' 
					OR perkara.para_pihak like '%$kunci%'  
					OR perkara.tahapan_terakhir_text like '%$kunci%'  
					OR perkara.proses_terakhir_text like '%$kunci%'  
					";
	 }ELSE
	 {
		  $pencarian=" ";
		 $file="getPerdata.php";
	 }
	
?> 		<link rel="stylesheet" type="text/css" href="resources/css/1.11.4.jquery-ui.css"> 
		<script src="resources/js/jquery-1.7.1.min.js" type="text/javascript"></script>
		<script src="resources/js/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
		<script>
		$(function() {$( "#datepicker" ).datepicker({changeMonth: true,changeYear: true,dateFormat : 'dd/mm/yy',minDate: '01/01/2015',maxDate: '<?php echo date('d/m/Y');?>'  });});
		 
		</script>
		

<div id="pageTitle">DAFTAR PERKARA PERDATA </div> <br><hr class="thick-line" style="clear:both;"><div id="left"></div>
	<div id="left">
		<form  method="get" accept-charset="utf-8">
		<input type="text" name="search" value="" id="search-box" size="50" placeholder="  Ketik kata kunci  ">
		 <span>  </span>
		<input type="submit" name="" value="Search" id="search-btn1"></form>
	</div> 
		  <div style="clear:both;"></div>
		  
		<div class="cssTable" id="tablePerkara">
			<table id="tablePerkaraAll">
				<colgroup><col width="3%">
				<col width="15%">
				<col width="7%">
				<col width="15%">
				<col width="17%">
				<col width="11%">
				<col width="13%">
				<col width="5%">
				<col width="4%">
				</colgroup>
				<tbody>
					<tr>
						<td>No</td>
						<td onclick="sorting(1)">Nomor Perkara</td>
						<td onclick="sorting(2)">Tanggal Register</td>
						<td onclick="sorting(3)">Klasifikasi Perkara</td>
						<td onclick="sorting(4)">Para Pihak</td>
						<td onclick="sorting(5)">Tahapan</td>
						<td onclick="sorting(6)">Status Perkara</td>
						<td onclick="sorting(7)">Lama Proses</td>
						<td>Link</td>
					</tr>
					<?php
					include('Pagination.php');
					$limit = 50;
					foreach($_POST as $key=>$value) {$$key=$value;}
					$sql="	SELECT
								perkara.perkara_id, 
								DATE_FORMAT(perkara.tanggal_pendaftaran, '%d/%m/%Y') as tanggal_pendaftaran,  
								perkara.jenis_perkara_kode,
								perkara.jenis_perkara_nama,
								perkara.nomor_perkara,
								perkara.para_pihak,
								perkara.tahapan_terakhir_text,
								perkara.proses_terakhir_text
							FROM
								perkara
							$pencarian
							ORDER BY perkara_id DESC
							LIMIT $limit
										";
					$quer=mysql_query($sql);
					$rowCount=mysql_num_rows(mysql_query("SELECT perkara_id from perkara $pencarian"));
					//initialize pagination class
					$pagConfig = array('baseURL'=>$file, 'totalRows'=>$rowCount, 'perPage'=>$limit, 'contentDiv'=>'tablePerkara');
					$pagination =  new Pagination($pagConfig);
						//echo $sql;
					$no=0; 
					while($h=mysql_fetch_array($quer)) { foreach($h as $key=>$value) {$$key=$value;}
						$no++;
					?>
<tr><td><?php echo $no?></td> <td><?php echo $nomor_perkara?></td> <td><?php echo $tanggal_pendaftaran?></td> <td><?php echo $jenis_perkara_nama?></td><td><?php echo $para_pihak?></td><td><?php echo $tahapan_terakhir_text?></td><td><?php echo $proses_terakhir_text?></td><td style="text-align:right;">  Hari</td><td align="center">[<a href="perkara_detail?perkara_id=<?php echo $perkara_id?>">detil</a>]</td></tr><?php }?>
				</tbody>
			</table>
			<?php echo $pagination->createLinks(); ?>
			<br><br><br>
		</div>  
		  
  
	 
<?php 
	include "sys_footer.php";  
?>  	 