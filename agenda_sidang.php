<?php if(!isset($_SESSION)){session_start();}include "sys_koneksi.php"; include "sys_config.php";include "resources/fungsi.php";ini_set('display_errors', 'on');
$judul="Agenda Sidang ";

?>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo @$judul;?> - <?php echo $nama_aplikasi;?> <?php echo $NamaPN;?> </title><link rel="shortcut icon" href="favicon.ico" type="image/png">
<head> 
<link rel="stylesheet" href="resources/css/w3.css">  
</head>
<body> 
 <a href="index" class="w3-bar-item w3-button w3-red">.:: Kembali </a>
<div class="w3-container" >
<?php 
if(isset($_SESSION["group_name"]))
{
	$group=$_SESSION["group_id"];
	$jabatan_id=$_SESSION['jabatan_id'];
	if($group==10 OR $group==20)
	{
		$sql_agenda="
					SELECT 
						  b.nomor_perkara,
						  b.para_pihak,
						  a.perkara_id,
						  b.jenis_perkara_nama,
						  a.agenda,
						  a.ruangan,
						  a.urutan,
						  LEFT(a.jam_sidang, 5) AS jam_sidang, 
						  IF(d.jabatan_hakim_id=1,'Ketua Majelis','Anggota') as sebagai
						FROM
						  perkara_jadwal_sidang AS a 
						  LEFT JOIN perkara AS b 
							ON b.perkara_id = a.perkara_id 
						  LEFT JOIN perkara_hakim_pn AS d 
							ON d.perkara_id = a.perkara_id   
						WHERE a.tanggal_sidang ='".$_GET['tanggal']."' AND d.hakim_id=$jabatan_id AND d.`aktif`='Y'
						ORDER by d.jabatan_hakim_id ASC,b.nomor_perkara ASC
						 
		";
	}else
	if($group==30 OR $group==430 OR $group==500 OR $group==1000 OR $group==1010)	
	{
		$sql_agenda="SELECT 
				  b.nomor_perkara,
				  b.para_pihak,
				  a.perkara_id,
				  b.jenis_perkara_nama,
				  a.agenda,
				  a.ruangan,
				  a.urutan,
				  LEFT(a.jam_sidang, 5) AS jam_sidang ,
				  'PP' as sebagai
				FROM
				  perkara_jadwal_sidang AS a 
				  LEFT JOIN perkara AS b 
					ON b.perkara_id = a.perkara_id 
				  LEFT JOIN perkara_penetapan AS d 
					ON d.perkara_id = a.perkara_id 
				WHERE a.tanggal_sidang = '".$_GET['tanggal']."' AND d.panitera_pengganti_id= $jabatan_id
				ORDER BY b.nomor_perkara ASC ";
	}
	

		 	// echo "---<br>".$sql_agenda."<br>--------------------";
			$query_agenda=mysql_query($sql_agenda);
			$jumlah_agenda=mysql_num_rows($query_agenda);
			 
				
				echo "<h3 align=center>Agenda Sidang Tanggal ".tgl_panjang_dari_mysql($_GET['tanggal'])."</h3>
				 <table class='w3-table w3-striped w3-border w3-hoverable  w3-medium'>
				 
				
				";
				echo "<tr>";
				echo "<td>No</td>";
				echo "<td>Nomor Perkara</td>";
				echo "<td>Jenis Perkara</td>"; 
				echo "<td>Para Pihak</td>";
				echo "<td>Sidang Ke<br>Agenda</td>";
				echo "<td>Ruangan<br>Jam</td>";
				echo "<td>Sebagai</td>";
				echo "</tr>";
				$no=0;
				while($h_agenda=mysql_fetch_array($query_agenda)) 
				{
					$no++;foreach($h_agenda as $key=>$value) {$$key=$value;}
					echo "<tr>";
					echo "<td align=center>".$no."</td>";
					echo "<td align=center>".$nomor_perkara."</td>";
					echo "<td align=center>".$jenis_perkara_nama."</td>";  
					echo "<td>".$para_pihak."</td>";  
					echo "<td>".$urutan."<br>".ucwords(strtolower($agenda))."</td>";  
					echo "<td>".$ruangan."<br>".$jam_sidang."</td>"; 
					echo "<td>".$sebagai."</td>"; 
					
					echo "</tr>";
					
				}
				echo "</table>";
				 
}			 
?>
</div>
</body>
</html>    