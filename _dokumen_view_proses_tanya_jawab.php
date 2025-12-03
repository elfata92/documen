<?php  //error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true); 
ini_set('display_startup_errors', true);
include "sys_koneksi.php";
include "sys_config.php";
foreach($_POST as $key=>$value) {$$key=$value;} 

//detail tanya jawab

if(isset($_POST["proses_tanya_jawab_ke_textarea"]))
{ 
	 $sql="SELECT * FROM ".$db_host2.".perkara_keterangan_saksi WHERE saksi_id=$saksi_id AND perkara_id=$perkara_id AND sidang_id=$sidang_id ORDER by urutan_pertanyaan ASC" ;
	 
	$quer_info1=mysql_query($sql);
	$isian='';
	$no=0; 
	 while($perkara_info1=mysql_fetch_array($quer_info1)) 
	{
		foreach($perkara_info1 as $key=>$value) {$$key=$value;} $no++;
		 $isian.=$pertanyaan."^".$jawaban."|";
	}
echo trim($isian); 
	exit;
}
if(isset($_POST["hapus_detail_tanya_jawab"]))
{
	 
	$sql="DELETE FROM ".$db_host2.".perkara_keterangan_saksi  WHERE id=$id";
	//echo $sql."<br>";
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
//	$sql="INSERT INTO ".$db_host2.".perkara_keterangan_saksi  (m_id, urutan_pertanyaan)  values($m_id, $id)";
	$sql="INSERT INTO ".$db_host2.".perkara_keterangan_saksi  (perkara_id, sidang_id, penanya_id, saksi_id, urutan_pertanyaan)  values($perkara_id, $sidang_id,1,$saksi_id, $urutan_pertanyaan)";
	
	$simpan=mysql_query($sql);
	echo mysql_insert_id();	
	exit;	
}
if(isset($_POST["insert_detail"]))
{
	$sqlnya="SELECT id,urutan_pertanyaan FROM ".$db_host2.".perkara_keterangan_saksi 
            WHERE 
            perkara_id=$perkara_id AND sidang_id=$sidang_id AND   saksi_id=$saksi_id AND urutan_pertanyaan >=$urutan_pertanyaan order by urutan_pertanyaan DESC
            ";
   // ECHO $sqlnya;
    $query=mysql_query($sqlnya); 
    while($h_info=mysql_fetch_array($query))
    {
        $new_urutan=$h_info["urutan_pertanyaan"]+1;
        $id=$h_info["id"];
        $sql2="UPDATE ".$db_host2.".perkara_keterangan_saksi SET urutan_pertanyaan=$new_urutan WHERE 
                    id=$id
                    ";
       // echo "<br>".$sql2."<br>";
        mysql_query($sql2);
    }
   ///// 
    
	$sql="INSERT INTO ".$db_host2.".perkara_keterangan_saksi  (perkara_id, sidang_id, penanya_id, saksi_id, urutan_pertanyaan)  values($perkara_id, $sidang_id,1,$saksi_id, $urutan_pertanyaan)";
	
	$simpan=mysql_query($sql);
	echo mysql_insert_id();	
	exit;	
}
if(isset($_POST["edit_detail"]))
{
	//cek 
		$isi=mysql_escape_string(strip_tags($isi));
		$sql="UPDATE ".$db_host2.".perkara_keterangan_saksi SET $kolom='".$isi."' WHERE id=$id"; //echo $sql;
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
if(isset($_POST["ganti_template_tanya_jawab"]))
{
	//templat_tanya_jawab="+encodeURIComponent(templat_tanya_jawab) 
	 //hapus semua tanya jwab 
	 $sql="DELETE FROM ".$db_host2.".perkara_keterangan_saksi WHERE saksi_id=$saksi_id AND perkara_id=$perkara_id AND sidang_id=$sidang_id";
	 mysql_query($sql);
	 //hapus semua tanya jwab 
	 //cek sebutan bagi perkara ini
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
	 //cek sebutan bagi perkara ini 
	 //isi baru 
	 $sql_info1="SELECT pertanyaan, jawaban 
				FROM ".$db_host2.".template_keterangan_saksi_d  WHERE m_id=$templat_tanya_jawab ORDER BY urutan_pertanyaan ASC";
		//	echo $sql_info1;
	$quer_info1=mysql_query($sql_info1);
	$no=0;
	echo '<table id="tanyajawab_'.$saksi_id.'" class="w3-table" border="1">
		<tbody>
		<tr style="color: white; background-color: dimgray;">
		<th>No.</th><th  style="width:350px">Pertanyaan</th><th  style="width:350px">Jawaban</th></tr>';
	 while($perkara_info1=mysql_fetch_array($quer_info1)) 
	{
		foreach($perkara_info1 as $key=>$value) {$$key=$value;} $no++;
		$pertanyaan=str_replace("#0046#", $sebutan_pihak1, $pertanyaan);
		$pertanyaan=str_replace("#0047#", $sebutan_pihak2, $pertanyaan);
		$pertanyaan=str_replace("#0053#", $gugatan_permohonan, $pertanyaan);
		$jawaban=str_replace("#0046#", $sebutan_pihak1, $jawaban);
		$jawaban=str_replace("#0047#", $sebutan_pihak2, $jawaban);
		$jawaban=str_replace("#0053#", $gugatan_permohonan, $jawaban);
		$sql="INSERT INTO ".$db_host2.".perkara_keterangan_saksi  (perkara_id, sidang_id, penanya_id, saksi_id, urutan_pertanyaan, pertanyaan, jawaban)  values($perkara_id, $sidang_id,1,$saksi_id, $no, '$pertanyaan', '$jawaban')"; 
		$simpan=mysql_query($sql);
		$id= mysql_insert_id();	
		
		?> 
		<tr>
			<td><div id="data_id<?php echo $id?>"   ketid=""><?php echo $no?></div></td> 
			<td  style="width:350px"><div id="q_tanya<?php echo $id?>"   contenteditable=""  onBlur="Edit_Detail(this.innerHTML,'pertanyaan',<?php echo $id?>,<?php echo $saksi_id?>)"><?php echo $pertanyaan?></div></td>
			<td style="width:350px"><div id="q_jawab<?php echo $id?>" contenteditable="" class="jawab" onBlur="Edit_Detail(this.innerHTML,'jawaban',<?php echo $id?>,<?php echo $saksi_id?>)" onkeydown="return myKeyPress(event,<?php echo $no?>,this,<?php echo $id?>,<?php echo $perkara_id?>,<?php echo $sidang_id?>,<?php echo $saksi_id?>)"><?php echo $jawaban?></div></td> 
		</tr>
		<?php 
	}
	 echo '</tbody></table>';
	echo "<span style='color:red'>Tombol Enter: pada Kolom Jawaban Paling Akhir, untuk menambah Baris <br>
Tombol F2 : pada kolom jawaban, untuk menghapus Baris<br>
Tombol F3 : pada kolom jawaban, untuk Menyisipkan Baris<br>";
    if($saksi_id==5058)
    {?>
        <a href="#" onclick="copy_tanya_jawab(<?php echo $perkara_id?>, <?php echo $sidang_id?>, <?php echo $saksi_id?>,5059)">Duplikat Tanya Jawab ke Tanya Jawab Saksi 2</a><br></span>
    <?php 
    }else
    {?>
<a href="#" onclick="copy_tanya_jawab(<?php echo $perkara_id?>, <?php echo $sidang_id?>, <?php echo $saksi_id?>,5058)">Duplikat Tanya Jawab ke Tanya Jawab Saksi 1</a><br></span>
    <?php 
    }echo "</div>";
	 //isi baru 
	 
	 //tampilkan
	 
	 //tampilkan
	exit;
}
if(isset($_POST["copy_tanya_jawab"]))
{
	mysql_query("DELETE FROM ".$db_host2.".perkara_keterangan_saksi WHERE  sidang_id=$sidang_id AND perkara_id=$perkara_id AND saksi_id=$variabel_new ");
	 $sql_info1="SELECT *
				FROM ".$db_host2.".perkara_keterangan_saksi WHERE  sidang_id=$sidang_id AND perkara_id=$perkara_id AND saksi_id=$saksi_id ORDER BY urutan_pertanyaan ASC";
		echo $sql_info1;
	$quer_info1=mysql_query($sql_info1);
	  
	 while($perkara_info1=mysql_fetch_array($quer_info1)) 
	{
		foreach($perkara_info1 as $key=>$value) {$$key=$value;}  
        $sql="INSERT INTO ".$db_host2.".perkara_keterangan_saksi  (perkara_id, sidang_id, penanya_id, saksi_id, urutan_pertanyaan, pertanyaan, jawaban)  values($perkara_id, $sidang_id,1,$variabel_new, $urutan_pertanyaan, '$pertanyaan', '$jawaban')"; 
		$simpan=mysql_query($sql); 
	} 
	exit;
}
?>
 