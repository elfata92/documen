<?php 
ini_set('display_errors', 'on');
include "sys_koneksi.php";  

//looping_tanya_jawab_saksi_agama belum memperhatian sidang id beserta tanya jawab masuk semuanya
function looping_tanya_jawab_saksi_agama($perkara_id, $pihak_ke,$pihak)
{ 
	$sql="SELECT 
				  a.id AS saksi_id,
				  a.perkara_id,
				  a.urutan,
				  a.jenis_pihak_id,
				  a.pihak_id,
				  a.nama,
				  b.tempat_lahir,
				  convert_tanggal_indonesia (b.tanggal_lahir) AS tanggal_lahir,
				  get_umur (
					b.tanggal_lahir,
					g.tanggal_pendaftaran
				  ) AS umur,
				  nama_hari (b.tanggal_lahir) AS hari_lahir,
				  IF(
					b.jenis_kelamin = 'L',
					'Laki-Laki',
					'Perempuan'
				  ) AS jenis_kelamin,
				  IF(
					d.id IS NULL,
					b.agama_nama,
					d.nama
				  ) AS agama,
				  IF(
					e.id IS NULL,
					b.pendidikan,
					e.nama
				  ) AS pendidikan,
				  b.pekerjaan,
				  IF(
					b.warga_negara = '' 
					OR b.warga_negara IS NULL,
					'Indonesia',
					b.warga_negara
				  ) AS warga_negara,
				  a.alamat,
				  a.keterangan 
				   
				FROM
				  perkara_pihak5 AS a 
				  JOIN pihak AS b 
					ON a.pihak_id = b.id 
				   
				  LEFT JOIN agama AS d 
					ON b.agama_id = d.id 
				  LEFT JOIN tingkat_pendidikan AS e 
					ON b.pendidikan_id = e.id 
				  JOIN perkara AS g 
					ON a.perkara_id = g.perkara_id 
				WHERE a.perkara_id =$perkara_id AND a.jenis_pihak_id=$pihak_ke"; 
	$query=mysql_query($sql) ;
		echo "<br>XXXX<br>".$sql."<br>XXXX<br>";
	$isinya='';
	$no=0;
	while($h=mysql_fetch_array($query)) 
	{
		$no++;
		foreach($h as $key=>$value) {$$key=$value;}  
		if(terbilang($urutan)=="satu")
		{
			$urutan_saksi='pertama';
		}else
		{
			$urutan_saksi='ke'.terbilang($urutan);
		}
		$isinya.=" \\tab "."Selanjutnya saksi ".$urutan_saksi." $pihak dipanggil masuk ke ruang sidang dan atas pertanyaan Ketua Majelis, saksi tersebut mengaku bernama : \\par " ;
		$isinya.= " \\tab ".$nama.", umur ".$umur." tahun, agama ".$agama.", Pekerjaan ".$pekerjaan.", tempat tinggal di ".$alamat."; \\par " ;
		$isinya.=" \\tab " ."Atas pertanyaan Ketua Majelis, saksi menyatakan bersedia menjadi saksi dan diangkat sumpah"."; \\par ";
		$isinya.=" \\tab " .'Kemudian saksi bersumpah menurut tata cara agama Islam dengan lafadz sumpah : "Demi Allah saya bersumpah bahwa saya akan menerangkan yang sebenarnya tidak lain dari pada yang sebenarnya"'."; \\par " ;
		$isinya.=" \\tab " ."Selanjutnya terjadi tanya jawab antara Majelis Hakim dengan saksi ".$urutan_saksi." $pihak sebagai berikut: "."; \\par " ;
	 
		//tanya jawab 
		//tabel awal 
	 
		//tabel awal 
		$sql_tanya="SELECT  urutan_pertanyaan, pertanyaan, jawaban FROM perkara_keterangan_saksi  WHERE perkara_id =$perkara_id AND saksi_id=$saksi_id "; 
		$query_tanya=mysql_query($sql_tanya) ;
			echo "<br>DDDD<br>".$sql_tanya."<br>DDDD<br>"; 
			 
		while($h_tanya=mysql_fetch_array($query_tanya)) 
		{
			$no++;
			foreach($h_tanya as $key=>$value) {$$key=$value;}
			 $isinya.="\\par\\pard ".$pertanyaan."\\par\pard\li2544\r\sa200\sl276\slmult1\qj ".$jawaban." \\par";
		}
		//tabel akhir  
		//tabel akhir 
		//tanya jawab  
		$isinya.="\pard \\tab " ."Kemudian saksi ".$urutan_saksi." $pihak dipersilahkan meninggalkan ruang sidang".";\\par" ;
		
		
	}
	//isi
	  
	 
	   
	return $isinya;
}
function tampilkan_data_tabel($nama_tabel="", $nama_field="", $filter="", $order_by="", $limit="", $db_host2)
{
	//jumlah field
	$cek_field=explode("#",$nama_field); 
	$jumlah= count($cek_field);
//	echo "<br>".$jumlah."<br>";
	//jumlah field	
	//header
	$tabel='<table class="table"><thead>';
	$tabel.='<tr>';
	 $tabel.="<th>No</th>";
	for ($x = 1; $x <= $jumlah-1; $x=$x+1) 
	{
		$tabel.="<th>".$cek_field[$x]."</th>";
	}
	$tabel.='</tr></thead><tbody>';
	//jumlah field
	
	//isi
	$sql="SELECT ".str_replace("#",",",$nama_field)." FROM ".$db_host2.".".$nama_tabel."  ".$filter. 
		" ".$order_by ." ".$limit; 
	$query=mysql_query($sql)	; 
	//echo "<br>".$sql."<br>";
	
	$no=0;
	while($h=mysql_fetch_row($query)) 
	{
		$no++;
		//foreach($h as $key=>$value) {$$key=$value;}  
		$tabel.="<tr><td>".$no."</td>";
		for ($x = 1; $x <= $jumlah-1; $x=$x+1) 
		{
			$tabel.='<td  contenteditable=true onBlur=Edit(this.innerHTML,"'.$cek_field[$x].'",'.$h[0].')>'.$h[$x].'</td>';
		}
		$tabel.="</tr>";  
	}
	//isi
	  
	 $tabel.= "</tbody></table><br><br><br><br><br><br><br>";
	   
	return $tabel;
}
function bukti_pihak($perkara_id,$id_bukti)
{
	$sql="SELECT  uraian FROM perkara_data_template_sidang  
		WHERE  perkara_id=$perkara_id AND kolom_id=$id_bukti"; 
	$query=mysql_query($sql)	; 
	 
	$no=0;
	while($h=mysql_fetch_array($query)) 
	{
		$no++;
		foreach($h as $key=>$value) {$$key=$value;}  
		 
	}
	  
	  
	   
	return cleanHtmlTag($uraian);	 
	 
}
function tampilkan_variabel($id_template,$db_host2)
{
	$sql="SELECT a.id,a.template_id,b.kode, b.nama, c.var_nama, c.var_keterangan FROM ".$db_host2.".template_variabel AS a 
		LEFT JOIN ".$db_host2.".template_dokumen AS b ON b.id=a.template_id
		LEFT JOIN ".$db_host2.".master_variabel AS c ON c.var_id=a.var_id
		WHERE a.template_id=$id_template"; 
	$query=mysql_query($sql)	; 
	$tabel='';
	$tabel1='';
	$no=0;
	while($h=mysql_fetch_array($query)) 
	{
		$no++;
		foreach($h as $key=>$value) {$$key=$value;}  
		$tabel.="<tr><td>".$no."</td><td>".$var_nama."</td><td>".$var_keterangan."</td><td align=center><a href=# onclick=hapus_variabel_template(".$id.",".$template_id.")><font color=red><b>X</b></font></a></td></tr>";  
	}
	  
	 $tabel1.= "<style>.list_variabel tr:nth-child(odd) {
     background-color: #C9E6C8;  
}</style><br><p><a href='#' onclick='kembali_pilih()'  class='btn btn-danger'>.:: Kembali ::.</a>   
<a href='#' onclick='cek_ulang_variabel()' class='btn btn-warning'>.:: Cek Ulang Variabel Blangko ::. </a> </p><br><table cellpadding='5'  class=list_variabel style='width:100%'><tr><td><b>NO</b></td><td><b>NAMA</b></td><td><b>KETERANGAN</b></td><td><b>OPERASI</b></td></tr>".$tabel."</table><br><br><br><br><br><br><br>";
	   
	return $tabel1;	 
	 
}
 
function bacafile_dan_input_variabel($Alamat_Dokumen,$db_host2,$id_template)
{
	$isi_file= file_get_contents($Alamat_Dokumen);
	$cek_variabel=explode("#",$isi_file); 
	$cek_variabel_unik=$cek_variabel; 
	$jumlah= count($cek_variabel_unik); 
	echo $jumlah;
	for ($x = 1; $x <= $jumlah-1; $x=$x+2) 
	{    
		$variabel= $cek_variabel[$x]; 
		echo "<h1>".$x." - ".$cek_variabel[$x]."</h1><br>"; 
		$sql_baca="	SELECT  var_id as id_variabel FROM ".$db_host2.".master_variabel WHERE var_nama ='$variabel' limit 1"; 
		echo '<br>'.$sql_baca.'<br>';
		//masukkan variabel
		$jumlah_variabel_db=mysql_num_rows(mysql_query($sql_baca));
		if($jumlah_variabel_db==0)
		{
			$sql_simpannya="INSERT INTO ".$db_host2.".master_variabel (var_nama,var_keterangan) values('$variabel','$variabel')";
			echo '<br>'.$sql_simpannya.'<br>';
			$simpannya=mysql_query($sql_simpannya);
			//$id_variabel=last_id($simpannya);
			if($simpannya)
			{
				echo "Penyimpanan Berhasil<br>";
			}else
			{
				echo "Error Menyimpan Variabel ".$variabel." <br>";	
			}
		}
		
			
			//SIMPAN TEMPLATE VARIABEL
			 $query_baca=mysql_query($sql_baca);
			while($h_simpan_tem_var=mysql_fetch_array($query_baca)) 
			{ 
				foreach($h_simpan_tem_var as $key=>$value) {$$key=$value;}
				$sql_simpan_var="INSERT INTO ".$db_host2.".template_variabel (template_id,var_id) values($id_template,$id_variabel)";
				echo '<br>'.$sql_simpan_var.'<br>';
				
				if(mysql_num_rows(mysql_query("select id FROM ".$db_host2.".template_variabel WHERE template_id=$id_template AND var_id=$id_variabel "))==0)
				{
					$simpan_var=mysql_query($sql_simpan_var);
					if($simpan_var)
					{
						echo "Penyimpanan Berhasil<br>";
					}else
					{
						echo "Error Menyimpan Variabel ".$variabel." <br>";	
					}
				}
				
				
			}
				
			//SIMPAN TEMPLATE VARIABEL 
		//masukkan variabel 
		 
	}
}
 
if ( ! function_exists('getDataMediasi'))
{
function getDataMediasi($perkara_id)
{
		 
		$sql=" SELECT A.*, B.nama_mediator as data FROM perkara_mediasi AS A INNER JOIN perkara_mediator AS B ON A.mediator_id=B.mediator_id WHERE A.perkara_id=$idperkara"; 
		echo "xxxxx<br>".$sql."<br>xxxxx";
		$quer =mysql_query($sql); 
		 
		//echo "<br> $sqlb<br>"; 
		 
		 
		while($h=mysql_fetch_array($quer)) 
		{
			 
			foreach($h as $key=>$value) {$$key=$value;}  
		}
		 
		 
		 
		return $nama_gelar;	
	}
} 
if ( ! function_exists('namaPanitera1'))
{
function namaPanitera1($perkara_id)
{
		 
		$sql=" SELECT 
  panitera_pn.nama_gelar 
FROM
  perkara_panitera_pn 
  LEFT JOIN panitera_pn 
    ON panitera_pn.id = perkara_panitera_pn.panitera_id 
WHERE perkara_panitera_pn.aktif = 'Y' 
 
  AND perkara_panitera_pn.urutan = '1'  AND perkara_id = $perkara_id  "; 
		echo "xxxxx<br>".$sql."<br>xxxxx";
		$quer =mysql_query($sql); 
		 
		//echo "<br> $sqlb<br>"; 
		 
		 
		while($h=mysql_fetch_array($quer)) 
		{
			 
			foreach($h as $key=>$value) {$$key=$value;}  
		}
		 
		 
		 
		return $nama_gelar;	
	}
}
if ( ! function_exists('namaHakim1'))
{
	function namaHakim1($perkara_id)
	{
		 
		$sql=" SELECT hakim_pn.nama_gelar FROM perkara_hakim_pn
				LEFT JOIN hakim_pn ON hakim_pn.id=perkara_hakim_pn.hakim_id
				WHERE perkara_hakim_pn.aktif='Y' AND perkara_hakim_pn.urutan='1' AND perkara_id = $perkara_id  "; 
		echo "yyyy<br>".$sql."<br>yyy";
		$quer =mysql_query($sql); 
		 
		//echo "<br> $sqlb<br>"; 
		 
		 
		while($h=mysql_fetch_array($quer)) 
		{
			 
			foreach($h as $key=>$value) {$$key=$value;}  
		}
		 
		 
		 
		return $nama_gelar;	
	}
}
if ( ! function_exists('get_var_hasil_relaas'))
{
	function get_var_hasil_relaas($perkara_id,$sidang_id,$pihak_ke)
	{
		$tanggal_relaas='';
		$sql=" SELECT 
		  
		  convert_tanggal_indonesia (a.tanggal_relaas) AS tanggal_relaas 
		    
		FROM
		  (SELECT 
			pihak_id   FROM perkara_pihak".$pihak_ke." 
		  WHERE perkara_id = $perkara_id 
		   ) AS b 
		  LEFT JOIN perkara_pelaksanaan_relaas AS a 
			ON a.pihak_id = b.pihak_id 
			AND a.sidang_id = $sidang_id "; 
		echo "0000000000000000<br>".$sql."<br>00000000000000";
		$quer =mysql_query($sql); 
		 
		//echo "<br> $sqlb<br>"; 
		 
		 
		while($h=mysql_fetch_array($quer)) 
		{
			 
			foreach($h as $key=>$value) {$$key=$value;}  
		}
		 
		 
		 
		return $tanggal_relaas;	
	}
}
if ( ! function_exists('get_var_penetapan_panitera'))
{
	function get_var_penetapan_panitera($perkara_id)
	{
		$sql=" SELECT 
						a.perkara_id, 
						a.tahapan_id,
						convert_tanggal_indonesia(a.tanggal_penetapan) AS tanggal_penunjukkan_pp,
						nama_hari(a.tanggal_penetapan) AS hari_penetapan_pp,
					 
						b.nama_gelar AS nama_pp,
						b.pangkat AS pangkat_pp,
						b.nip AS nrp_pp,
						a.keterangan AS alasan_penunjukan_kembali,
						IF((REPLACE(REPLACE(REPLACE(@nama_panitera,' ',''),',',''),'.','')) = REPLACE(REPLACE(REPLACE(b.nama_gelar,' ',''),',',''),'.',''), 'Panitera Pengganti', 'Panitera Pengganti') AS jabatan_pp
					FROM perkara_panitera_pn AS a
					JOIN panitera_pn AS b ON a.panitera_id= b.id
					WHERE perkara_id = $perkara_id AND a.aktif = 'Y'
					AND IF(@tahapan_id IS NOT NULL ,a.tahapan_id,1) = IF(@tahapan_id IS NOT NULL ,@tahapan_id,1)
					ORDER BY urutan,panitera_kode, panitera_id
				"; 
				echo $sql;
				$quer =mysql_query($sql); 
				$jumlah_pp =mysql_num_rows($quer); 
				//echo "<br> $sqlb<br>"; 
				$identitas_pp='';
				$no=0;
				while($h=mysql_fetch_array($quer)) 
				{
					$no++;
					foreach($h as $key=>$value) {$$key=$value;} 
					if($jumlah_pp==$no)
					{
						$identitas_pp.= "\\tab ".$nama_pp.", \\tab Sebagai ".$jabatan_pp.";" ;
					}else
					{
						$identitas_pp.= "\\tab ".$nama_pp.", \\tab Sebagai ".$jabatan_pp.";\\par " ;
						
					}
				}
				 
				 
				 
				return $identitas_pp;
	}
}
if ( ! function_exists('get_var_penetapan_hakim'))
{
	function get_var_penetapan_hakim($perkara_id)
	{
		$sql=" 
		SELECT 
						a.perkara_id, 
						a.tahapan_id,
						convert_tanggal_indonesia(a.tanggal_penetapan) AS tanggal_pmh,
						nama_hari(a.tanggal_penetapan) AS hari_pmh,
						a.jabatan_hakim_id,
						a.jabatan_hakim_nama,
						b.nama_gelar AS hakim_nama,
						b.pangkat,
						b.nip AS nip,
						a.keterangan AS alasan_penetapan
					FROM perkara_hakim_pn AS a
					JOIN hakim_pn AS b ON a.hakim_id = b.id
					WHERE perkara_id = $perkara_id AND a.aktif = 'Y'
					AND IF(@tahapan_id IS NOT NULL ,a.tahapan_id,1) = IF(@tahapan_id IS NOT NULL ,@tahapan_id,1)
					ORDER BY a.urutan, hakim_kode, hakim_id
		"; 
				echo $sql;
				$quer =mysql_query($sql); 
				$jumlah_hakim =mysql_num_rows($quer); 
				//echo "<br> $sqlb<br>"; 
				$identitas_majelis='';
				$no=0;
				while($h=mysql_fetch_array($quer)) 
				{
					$no++;
					foreach($h as $key=>$value) {$$key=$value;} 
					if($jumlah_hakim==$no)
					{
						$identitas_majelis.= $no." \\tab ".$hakim_nama.", \\tab Sebagai ".$jabatan_hakim_nama.";dan dibantu \\par" ;
					}else
					{
						$identitas_majelis.= $no." \\tab ".$hakim_nama.", \\tab Sebagai ".$jabatan_hakim_nama.";\\par " ;
						
					}
				}
				 
				//$identitas_majelisnya=$identitas_majelis	." ";
				 
				return $identitas_majelis;
	}
}
if ( ! function_exists('get_var_pihak'))
{
	function get_var_pihak($pihak_ke,$perkara_id, $pihak)
	{
		$sql_var_pihak="
			SELECT 
				  a.perkara_id,
				  a.urutan,
				  a.jenis_pihak_id,
				  a.pihak_id,
				  a.nama,
				  b.tempat_lahir,
				  convert_tanggal_indonesia (b.tanggal_lahir) AS tanggal_lahir,
				  get_umur (
					b.tanggal_lahir,
					g.tanggal_pendaftaran
				  ) AS umur,
				  nama_hari (b.tanggal_lahir) AS hari_lahir,
				  IF(
					b.jenis_kelamin = 'L',
					'Laki-Laki',
					'Perempuan'
				  ) AS jenis_kelamin,
				  IF(
					d.id IS NULL,
					b.agama_nama,
					d.nama
				  ) AS agama,
				  IF(
					e.id IS NULL,
					b.pendidikan,
					e.nama
				  ) AS pendidikan,
				  b.pekerjaan,
				  IF(
					b.warga_negara = '' 
					OR b.warga_negara IS NULL,
					'Indonesia',
					b.warga_negara
				  ) AS warga_negara,
				  a.alamat,
				  a.keterangan,
				  f.pengacara_id AS id_kuasa,
				  f.nama AS nama_kuasa,
				  f.alamat AS alamat_kuasa,
				  convert_tanggal_indonesia (f.tanggal_kuasa) AS tanggal_kuasa,
				  nama_hari (f.tanggal_kuasa) AS hari_kuasa,
				  nomor_kuasa AS nomor_kuasa,
				  f.jumlah_pihak,
				  f.jumlah_kuasa   
				FROM
				  perkara_pihak".$pihak_ke." AS a 
				  JOIN pihak AS b 
					ON a.pihak_id = b.id 
				  LEFT JOIN 
					(SELECT 
					  cb.pihak_id,
					  cb.pengacara_id,
					  cb.nama,
					  cb.alamat,
					  cb.tanggal_kuasa,
					  cb.nomor_kuasa,
					  a.jumlah_pihak,
					  b.jumlah_kuasa 
					FROM
					  perkara_pengacara cb 
					  LEFT JOIN 
						(SELECT 
						  pengacara_id,
						  nama,
						  COUNT(*) AS jumlah_pihak 
						FROM
						  perkara_pengacara 
						WHERE perkara_id = $perkara_id 
						GROUP BY pengacara_id 
						ORDER BY pengacara_id) AS a 
						ON cb.pengacara_id = a.pengacara_id 
					  LEFT JOIN 
						(SELECT 
						  pihak_id,
						  nama,
						  COUNT(*) AS jumlah_kuasa 
						FROM
						  perkara_pengacara 
						WHERE perkara_id = $perkara_id 
						GROUP BY pihak_id 
						ORDER BY pihak_id) AS b 
						ON cb.pihak_id = b.pihak_id 
					WHERE perkara_id = $perkara_id
					GROUP BY pihak_id 
					ORDER BY pihak_id) AS f 
					ON a.pihak_id = f.pihak_id 
				  LEFT JOIN agama AS d 
					ON b.agama_id = d.id 
				  LEFT JOIN tingkat_pendidikan AS e 
					ON b.pendidikan_id = e.id 
				  JOIN perkara AS g 
					ON a.perkara_id = g.perkara_id 
				WHERE a.perkara_id =$perkara_id
		
		"; 
				echo $sql_var_pihak;
				$quer_var_pihak=mysql_query($sql_var_pihak); 
				//echo "<br> $sqlb<br>"; 
				while($h_var_pihak=mysql_fetch_array($quer_var_pihak)) 
				{
					foreach($h_var_pihak as $key=>$value) {$$key=$value;} 
				}
				if($jumlah_kuasa>=1)
				{
				$identitas_pihakk= $nama.", tempat dan tanggal lahir ".$tempat_lahir.", ".$tanggal_lahir.", agama ".$agama.", pekerjaan ".$pekerjaan.", Pendidikan ".$pendidikan.", tempat kediaman di ".$alamat." dalam hal ini memberikan kuasa kepada ".@$nama_kuasa.", Advokat yang berkantor di ".@$alamat_kuasa." berdasarkan surat kuasa khusus tanggal ".@$tanggal_kuasa." sebagai  $pihak";
				}else
				{
					$identitas_pihakk= $nama.", tempat dan tanggal lahir ".$tempat_lahir.", ".$tanggal_lahir.", agama ".$agama.", pekerjaan ".$pekerjaan.", Pendidikan ".$pendidikan.", tempat kediaman di ".$alamat." sebagai  $pihak";
				}
				return $identitas_pihakk;
	}
}	
if ( ! function_exists('get_var_pihak_dkk'))
{
	function get_var_pihak_dkk($pihak_ke,$perkara_id, $pihak)
	{
		$sql_var_pihak="
			SELECT 
					  nama 
				FROM  perkara_pihak".$pihak_ke."
				WHERE perkara_id =$perkara_id
				  
		"; 
				$identitas_pihakk='';
				$quer_var_pihak=mysql_query($sql_var_pihak); 
				$jumlah_pihak=mysql_num_rows($quer_var_pihak);
				//echo "<br> $sqlb<br>"; 
				$no=1;
				while($h_var_pihak=mysql_fetch_array($quer_var_pihak)) 
				{
					foreach($h_var_pihak as $key=>$value) {$$key=$value;} 
					if($jumlah_pihak==1)
					{
						$identitas_pihakk= $nama." sebagai $pihak ";
					}else
					if($jumlah_pihak==0)
					{
						$identitas_pihakk= "";
					}else	
					{
						if($no==1 OR $jumlah_pihak<>$no)
						{
							$identitas_pihakk.= $nama." sebagai $pihak ".convertToRomawi_backup($no).";  \\par ";
						}else 
						{
							$identitas_pihakk.= $nama." sebagai $pihak ".convertToRomawi_backup($no).";";
						}
						
					}
					$no++;
				
				 }
				return $identitas_pihakk;
	}
}	
function cleanHtmlTag($text){
		$text = filter_var($text, FILTER_SANITIZE_STRING);
		$text = str_replace('&#39;',"'",$text);
		$text = str_replace('&#39;',"'",$text);
		$text = str_replace('&#150;',"-",$text);
		$text = str_replace('&#146;',"'",$text);
		$text = str_replace('&#160;',' ',$text);
		$text = str_replace(';','; ',$text);
	    $text = str_replace('&nsbp',' ',$text);
		$text = str_replace('MENGADILI','',$text);
	    $text = str_replace('Mengadili','',$text);
	    $text = str_replace(':','',$text);
	    $text = str_replace("&nbsp; ","",$text);
	    $text = str_replace("</p>","",$text);	   	    
		$tmp = explode(';', $text);
		$tmptext = '';

		for ($i=0; $i < count($tmp); $i++){
			if($i!=0 AND strlen($tmp[$i]) >5){
				$tmptext .='\\par';
			}
			
			$tmp[$i]= preg_replace('/\t+/', '', $tmp[$i]);
			$tmptext .= $tmp[$i].';';
		}
		$text = $tmptext;		
		$text = trim($text);

		return $text;
	}

	function cleanHtmlTagSpecial($text){
		$text = str_replace(chr(194),"",$text);
		$text = html_entity_decode($text, ENT_QUOTES, "UTF-8");
		// $text = str_replace("MENETAPKAN:","MENETAPKAN:"."\\pard"."\\qc"."\\sa100",$text);
		$text = str_replace("MENETAPKAN:"," ",$text);
		$text = str_replace("MENETAPKAN :"," ",$text);
		$text = str_replace("M E N E T A P K A N :"," ",$text);
		$text = str_replace("M E N G A D I L I"," ",$text);
		$text = str_replace("MENUNTUT :"," ",$text);
		$text = str_replace("  "," ",$text);
		$text = str_replace("<ol>","<ol type='1'> ",$text);		
		$text = str_replace("</li>","</li> \\par ",$text);
		$text = str_replace("<li>","<li> \\tab ",$text);
		$text = str_replace("<li >","<li> \\tab ",$text);
		$text = str_replace("</ol>","</ol> ",$text);
		// $text = str_replace("</p>","\\par",$text);
		$text = str_replace("<strong>","\b",$text);
		$text = str_replace("</strong>","\b0",$text);
		$text = str_replace("<p>1.","\\pard"."\\qj"."\\li630"."\\linestarts 1.",$text);		
		$text = filter_var($text, FILTER_SANITIZE_STRING);
		$text = str_replace('&#39;',"'",$text);
		$text = str_replace('&#34;','"',$text);
		$text = str_replace('&#160;',' ',$text);
		$text = str_replace(';','; ',$text);
	    $text = str_replace('&nsbp',' ',$text);
		$text = str_replace('MENGADILI','',$text);
	    $text = str_replace('Mengadili','',$text);
	    $text = str_replace("&nbsp; ","",$text);
	    $text = str_replace("</p>","",$text);
	    $text = str_replace('”'," ",$text);		
	    $text = str_replace('“'," ",$text);
		$text = trim($text);

		return $text;
	}
?>