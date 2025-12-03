<?php $judul="Halaman Depan ";  include('sys_header.php');  ?>

<?php 
	if(isset($_SESSION["group_name"]))
	{
		include("resources/fungsi_doc.php");
		halaman_index($_SESSION['jabatan_id'], $_SESSION["group_id"], $url_sipp, $mulai_wajib_upload_bas_put) ;
	}else
	{
		echo "<h1 align=center>Sebelum Masuk ke Aplikasi, Pastikan anda sudah Login di Aplikasi SIPP</h1>";
		echo "<h1 align=center>Untuk Masuk ke Aplikasi, silahkan pilih Login (di Kanan Atas)</h1>";
	}
	echo "<br><br><br><br>";
?>
			
<?php 
	include('sys_footer.php');
?>  