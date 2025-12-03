
<?php
if(isset($_POST['page'])){
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
		 
	 
	?>
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
    //Include pagination class file
    include('Pagination.php');
    
    //Include database configuration file
   include "sys_koneksi.php"; 
    
    $start = !empty($_POST['page'])?$_POST['page']:0;
    $limit = 50;
    
    //get number of rows
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
							LIMIT $start,$limit
									";
	$quer=mysql_query($sql);
	$rowCount=mysql_num_rows(mysql_query("SELECT perkara_id from perkara $pencarian "));
    
    //initialize pagination class
    $pagConfig = array('baseURL'=>$file, 'totalRows'=>$rowCount, 'currentPage'=>$start, 'perPage'=>$limit, 'contentDiv'=>'tablePerkara');
    $pagination =  new Pagination($pagConfig);
    
    //get rows
    //$query = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT $start,$limit");
    $no=$start; 
    while($h=mysql_fetch_array($quer)) 
		{ 
			foreach($h as $key=>$value) {$$key=$value;}
						$no++;
					?>
					<tr>
						<td><?php echo $no?></td>
						<td><?php echo $nomor_perkara?></td>
						<td><?php echo $tanggal_pendaftaran?></td>
						<td><?php echo $jenis_perkara_nama?></td>
						<td><?php echo $para_pihak?></td>
						<td><?php echo $tahapan_terakhir_text?></td>
						<td><?php echo $proses_terakhir_text?></td>
						<td style="text-align:right;">  Hari</td>
						<td align="center">[<a href="perkara_detail?perkara_id=<?php echo $perkara_id?>"">detil</a>]</td>
					</tr>
					 
					<?php 
					
		}
					?>
				</tbody>
			</table>
			<?php echo $pagination->createLinks(); ?>
			<br><br><br>
<?php 
					
}
?> 