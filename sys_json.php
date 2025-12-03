 <?php
if(isset($_GET['menu']))
{
	
	$sekarang=date("Y-m-d");	
        $tahune=date("Y");
        $tahun_lalu=$tahune-1;
	include "sys_koneksi.php"; include "sys_config.php";include "resources/fungsi.php";
	$menu=$_GET['menu'];
	//kinerja
	if($menu=="kinerja")
	{
		$tahun=date("Y");
		$sql = " 	SELECT 
						SUM(IF((YEAR(b.tanggal_minutasi)>$tahun_lalu OR b.tanggal_minutasi IS NULL)AND YEAR(a.tanggal_pendaftaran)<$tahune , 1, 0)) AS sisa_lalu,
						SUM(IF(YEAR(a.tanggal_pendaftaran)=$tahun , 1, 0)) AS tambah_tahun_ini,
						SUM(IF(YEAR(b.tanggal_minutasi)=$tahune, 1, 0)) AS dimunitasi ,
						SUM(IF((YEAR(b.tanggal_putusan)>$tahun_lalu  OR b.tanggal_putusan IS NULL)AND YEAR(a.tanggal_pendaftaran)<$tahune , 1, 0)) AS sisa_lalu_belum_putus,
						SUM(IF(YEAR(b.tanggal_putusan)=$tahune, 1, 0)) AS diputus  
					FROM
						perkara AS a  
					LEFT JOIN
						perkara_putusan  AS b  USING(perkara_id) 
		"; 
		$query=mysql_query($sql); 
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$temp = array(
						"sisa_lalu"=> $row[0], 
						"tambah"=> $row[1], 
						"dimunitasi"=> $row[2],
						"kinerja"=> number_format(($row[2]/($row[0]+$row[1]))*100,2,',','.').'%',
						"tunggakan_minut"=>$row[0] + $row[1]-$row[2] . ' perkara', 
						"sisa_lalu_belum_putus"=> $row[3],
						"diputus"=> $row[4],
						"kinerja_diputus"=> number_format(($row[4]/($row[3]+$row[1]))*100,2,',','.').'%',
						"tunggakan_diputus"=>$row[3] + $row[1]-$row[4] . ' perkara', 
						)  ;
			
			array_push($arr, $temp); 
			echo json_encode($arr);
		} 
	}
	//kinerja
	//kepatuhanok
	if($menu=="kepatuhanok")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		   
		$sql = " 	SELECT 
							a.`proses_id`,
							a.`proses_nama`,
							SUM(IF(DATEDIFF(a.diinput_tanggal,a.tanggal )=0  , 1, 0)) AS tepat,  
							SUM(IF(DATEDIFF(a.diinput_tanggal,a.tanggal )<>0  , 1, 0)) AS tidak_tepat ,
							SUM(IF((a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System'), 1, 0)) AS sesuai_tupoksi ,
							
							SUM(IF((a.diinput_oleh='admin' OR a.diinput_oleh='System'), 1, 0)) AS tidak_sesuai_tupoksi
																	 
												
						FROM perkara_proses AS a
						WHERE a.`proses_id`<>81 AND a.`proses_id`<>200 AND  
						(MONTH(a.tanggal)>=$bulan_awal AND YEAR(a.tanggal)>=$tahun_awal) AND (MONTH(a.tanggal)<=$bulan_akhir AND YEAR(a.tanggal)<=$tahun_akhir)
						GROUP BY a.`proses_id`
					
				"; 
		$query=mysql_query($sql); 
		$arr = array();	$no=0; 	
		while ($row=mysql_fetch_row($query))
		{  $no++;
			$temp = array(
						"no"=> $no, 
						"proses_id"=> $row[0], 
						"proses_nama`"=> $row[1],
						
						"tepat"=>$row[2] ,
						"tidak_tepat"=>$row[3],
						"sesuai_tupoksi"=>$row[4],
						"tidak_sesuai_tupoksi"=>$row[5] 
						
						)  ;
			
			array_push($arr, $temp); 
			echo json_encode($arr);
		} 
	}
	//kepatuhanok
	//kepatuhan
	if($menu=="kepatuhan")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		   
		$sql = " 	SELECT 
						SUM(IF(a.proses_id=10, 1, 0)) AS jumlah_pendaftaran,
						SUM(IF((DATE(a.diinput_tanggal)=DATE(a.tanggal))AND a.proses_id=10, 1, 0)) AS pendaftaran_tepat_waktu,
						SUM(IF((a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System')AND a.proses_id=10, 1, 0)) AS pendaftaran_sesuai_tupoksi ,
						SUM(IF((a.diinput_oleh='admin' OR a.diinput_oleh='System')AND a.proses_id=10, 1, 0)) AS pendaftaran_tidak_sesuai_tupoksi,
						SUM(IF(proses_id=20, 1, 0)) AS jml_pmh, 
						SUM(IF(DATEDIFF(a.diinput_tanggal, b.tanggal_pendaftaran) <=$validasi_pmh 
							AND
							DATEDIFF(a.tanggal, b.tanggal_pendaftaran)<=$validasi_pmh AND a.proses_id=20, 1, 0)) AS pmh_tepat_waktu,
						SUM(IF((a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System')AND a.proses_id=20, 1, 0)) AS pmh_sesuai_tupoksi ,
						SUM(IF((a.diinput_oleh='admin' OR a.diinput_oleh='System')AND a.proses_id=20, 1, 0)) AS pmh_tidak_sesuai_tupoksi ,
						SUM(IF(proses_id=80, 1, 0)) AS jml_phs,  
						SUM(IF(DATEDIFF(a.diinput_tanggal, c.penetapan_majelis_hakim) <=$validasi_phs
							AND
							DATEDIFF(a.tanggal, c.penetapan_majelis_hakim)<=$validasi_phs AND a.proses_id=80, 1, 0)) AS phs_tepat_waktu ,
						SUM(IF((a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System')AND a.proses_id=80, 1, 0)) AS phs_sesuai_tupoksi ,
						SUM(IF((a.diinput_oleh='admin' OR a.diinput_oleh='System')AND a.proses_id=80, 1, 0)) AS phs_tidak_sesuai_tupoksi,
						SUM(IF(proses_id=110, 1, 0)) AS jml_mediasi,
						SUM(IF(DATEDIFF(d.keputusan_mediasi, a.tanggal) <=$validasi_mediasi AND  a.proses_id=130 , 1, 0)) AS mediasi_tepat_waktu,
						SUM(IF((DATEDIFF(d.keputusan_mediasi, a.tanggal) >$validasi_mediasi OR (DATEDIFF( a.tanggal, $sekarang)>$validasi_mediasi AND d.keputusan_mediasi=NULL) ) AND  a.proses_id=130 , 1, 0)) AS mediasi_tidak_tepat_waktu,
						SUM(IF((a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System')AND a.proses_id=130, 1, 0)) AS mediasi_sesuai_tupoksi ,
						SUM(IF((a.diinput_oleh='admin' OR a.diinput_oleh='System')AND a.proses_id=130, 1, 0)) AS mediasi_tidak_sesuai_tupoksi,
						SUM(IF(a.proses_id=210, 1, 0)) AS jml_putusan ,
						SUM(IF(DATEDIFF(a.diinput_tanggal,e.tanggal_putusan ) <=$validasi_putusan AND  a.proses_id=210 , 1, 0)) AS putusan_tepat_waktu,
						SUM(IF((DATEDIFF(a.diinput_tanggal,e.tanggal_putusan) >$validasi_putusan ) AND  a.proses_id=210 , 1, 0)) AS putusan_tidak_tepat_waktu,
						SUM(IF((a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System')AND a.proses_id=210, 1, 0)) AS putusan_sesuai_tupoksi ,
						SUM(IF((a.diinput_oleh='admin' OR a.diinput_oleh='System')AND a.proses_id=210, 1, 0)) AS putusan_tidak_sesuai_tupoksi,
						SUM(IF(a.proses_id=220, 1, 0)) AS jml_minutasi ,
						SUM(IF(DATEDIFF(a.diinput_tanggal,e.tanggal_minutasi) <=$validasi_minutasi AND  a.proses_id=220 , 1, 0)) AS minutasi_tepat_waktu,
						SUM(IF((DATEDIFF(a.diinput_tanggal,e.tanggal_minutasi) >$validasi_minutasi ) AND  a.proses_id=220 , 1, 0)) AS minutasi_tidak_tepat_waktu,
						SUM(IF((a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System')AND a.proses_id=220, 1, 0)) AS minutasi_sesuai_tupoksi ,
						SUM(IF((a.diinput_oleh='admin' OR a.diinput_oleh='System')AND a.proses_id=220, 1, 0)) AS minutasi_tidak_sesuai_tupoksi,
						SUM(IF(a.proses_id=218, 1, 0)) AS jml_pbt ,
						SUM(IF(DATEDIFF(a.diinput_tanggal,a.tanggal) <=$validasi_pbt AND  a.proses_id=218 , 1, 0)) AS pbt_tepat_waktu,
						SUM(IF((DATEDIFF(a.diinput_tanggal,a.tanggal) >$validasi_pbt ) AND  a.proses_id=218 , 1, 0)) AS pbt_tidak_tepat_waktu,
						SUM(IF((a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System')AND a.proses_id=218, 1, 0)) AS pbt_sesuai_tupoksi ,
						SUM(IF((a.diinput_oleh='admin' OR a.diinput_oleh='System')AND a.proses_id=218, 1, 0)) AS pbt_tidak_sesuai_tupoksi,
						(SELECT COUNT(tanggal_bht) FROM perkara_putusan where (MONTH(tanggal_bht)>=$bulan_awal AND YEAR(tanggal_bht)>=$tahun_awal) AND (MONTH(tanggal_bht)<=$bulan_akhir AND YEAR(tanggal_bht)<=$tahun_akhir)) AS jml_pbt	,
						SUM(IF(a.proses_id=295, 1, 0)) AS jml_ikrar_talak,
						(SELECT COUNT(perkara_id) FROM perkara_akta_cerai where (MONTH(tgl_akta_cerai)>=$bulan_awal AND YEAR(tgl_akta_cerai)>=$tahun_awal) AND (MONTH(tgl_akta_cerai)<=$bulan_akhir AND YEAR(tgl_akta_cerai)<=$tahun_akhir)) AS jml_ac	,
						(SELECT COUNT(id) FROM arsip where (MONTH(tanggal_masuk_arsip)>=$bulan_awal AND YEAR(tanggal_masuk_arsip)>=$tahun_awal) AND (MONTH(tanggal_masuk_arsip)<=$bulan_akhir AND YEAR(tanggal_masuk_arsip)<=$tahun_akhir)) AS jml_arsip
						
					FROM
						perkara_proses AS a  
					LEFT JOIN perkara AS b 
						ON b.perkara_id=a.perkara_id
					LEFT JOIN perkara_penetapan AS c 
						ON a.perkara_id=c.perkara_id
					LEFT JOIN v_perkara_mediasi_terakhir AS d 
						ON a.perkara_id=d.perkara_id
					LEFT JOIN perkara_putusan AS e 
						ON a.perkara_id=e.perkara_id
					 
					WHERE
						(MONTH(a.tanggal)>=$bulan_awal AND YEAR(a.tanggal)>=$tahun_awal) AND (MONTH(a.tanggal)<=$bulan_akhir AND YEAR(a.tanggal)<=$tahun_akhir)
				"; 
		$query=mysql_query($sql); 
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$temp = array(
						"jumlah_pendaftaran"=> $row[0], 
						"pendaftaran_tepat_waktu"=> $row[1],
						"pendaftaran_tidak_tepat_waktu"=>$row[0]-$row[1],
						"pendaftaran_sesuai_tupoksi"=>$row[2] ,
						"pendaftaran_tidak_sesuai_tupoksi"=>$row[3],
						"jumlah_pmh"=>$row[4],
						"pmh_tepat_waktu"=>$row[5] ,
						"pmh_tidak_tepat_waktu"=>$row[4]-$row[5],  
						"pmh_sesuai_tupoksi"=>$row[6],  
						"pmh_tidak_sesuai_tupoksi"=>$row[7],
						"jumlah_phs"=>$row[8],
						"phs_tepat_waktu"=>$row[9],
						"phs_tidak_tepat_waktu"=>$row[8]- $row[9] ,  
						"phs_sesuai_tupoksi"=>$row[10],  
						"phs_tidak_sesuai_tupoksi"=>$row[11] , 
						"jumlah_mediasi"=>$row[12],
						"mediasi_tepat_waktu"=>$row[13], 
						"mediasi_tidak_tepat_waktu"=>$row[14],
						"mediasi_sesuai_tupoksi"=>$row[15],
						"mediasi_tidak_sesuai_tupoksi"=>$row[16] ,
						"jumlah_putusan_akhir"=>$row[17],
						"putusan_akhir_tepat_waktu"=>$row[18],
						"putusan_akhir_tidak_tepat_waktu"=>$row[19],
						"putusan_akhir_sesuai_tupoksi"=>$row[20],
						"putusan_akhir_tidak_sesuai_tupoksi"=>$row[21],
						"jumlah_minutasi"=>$row[22],
						"minutasi_tepat_waktu"=>$row[23],
						"minutasi_tidak_tepat_waktu"=>$row[24],
						"minutasi_sesuai_tupoksi"=>$row[25],
						"minutasi_tidak_sesuai_tupoksi"=>$row[26],
						"jumlah_pbt"=>$row[27],
						"pbt_tepat_waktu"=>$row[28],
						"pbt_tidak_tepat_waktu"=>$row[29],
						"pbt_sesuai_tupoksi"=>$row[30],
						"pbt_tidak_sesuai_tupoksi"=>$row[31] ,
						"jumlah_bht"=>$row[32],  
						"jumlah_ikrar_talak"=>$row[33] , 
						"jumlah_ac"=>$row[34],  
						"jumlah_arsip"=>$row[35]  
						
						
						
						)  ;
			
			array_push($arr, $temp); 
			echo json_encode($arr);
		} 
	}
	//kepatuhan
	//kepatuhan_tupoksi pendaftaran
	if($menu=="kepatuhan_tupoksi_pendaftaran")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		$tupoksi=$_GET["tupoksi"];
		IF($tupoksi==0)
		{
			$where="(a.diinput_oleh='admin' OR a.diinput_oleh='System')";
		}else
		{
			$where="(a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System')";
		}
		$sql = " 	SELECT 
						a.nomor_perkara,
						DATE_FORMAT(a.tanggal_pendaftaran, '%d/%m/%Y') ,
						a.diinput_oleh,
						DATE_FORMAT(a.diinput_tanggal, '%d/%m/%Y %T'),
						a.perkara_id
					FROM
						perkara AS a  
					where
						(MONTH(a.tanggal_pendaftaran)>=$bulan_awal AND YEAR(a.tanggal_pendaftaran)>=$tahun_awal) AND (MONTH(a.tanggal_pendaftaran)<=$bulan_akhir AND YEAR(a.tanggal_pendaftaran)<=$tahun_akhir)
						AND 
						$where 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_pendaftaran"=> $row[1], 
						"diinput_oleh"=>$row[2],  
						"diinput_tanggal"=>$row[3],
						"perkara_id"=>$row[4] 
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan_tupoksi pendaftaran
	//kepatuhan pendaftaran_diinput
	if($menu=="kepatuhan_tanggal_input_pendaftaran")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		$tepat=$_GET["tepat"];
		IF($tepat==0)
		{
			$where="AND (date(a.diinput_tanggal)<>date(a.tanggal_pendaftaran))";
		}else
		IF($tepat==1)	
		{
			$where="AND (date(a.diinput_tanggal)=date(a.tanggal_pendaftaran))";
		}else
		{
			$where="";
		}
		$sql = " 	SELECT 
						a.nomor_perkara,
						DATE_FORMAT(a.tanggal_pendaftaran, '%d/%m/%Y') ,
						a.diinput_oleh,
						DATE_FORMAT(a.diinput_tanggal, '%d/%m/%Y %T'),
						a.perkara_id
					FROM
						perkara AS a  
					where
						(MONTH(a.tanggal_pendaftaran)>=$bulan_awal AND YEAR(a.tanggal_pendaftaran)>=$tahun_awal) AND (MONTH(a.tanggal_pendaftaran)<=$bulan_akhir AND YEAR(a.tanggal_pendaftaran)<=$tahun_akhir)
						$where 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_pendaftaran"=> $row[1], 
						"diinput_oleh"=>$row[2],  
						"diinput_tanggal"=>$row[3]  ,
						"perkara_id"=>$row[4]
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan pendaftaran diinput
	
	//kepatuhan pmh diinput
	if($menu=="kepatuhan_tanggal_input_pmh")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		$tepat=$_GET["tepat"];
		IF($tepat==0)
		{
			$where="AND (DATEDIFF(a.tanggal,b.tanggal_pendaftaran)>$validasi_pmh  OR  DATEDIFF( a.diinput_tanggal,b.tanggal_pendaftaran)>$validasi_pmh )";
		}else
		IF($tepat==1)	
		{
			$where="AND  DATEDIFF(a.tanggal, b.tanggal_pendaftaran)<=$validasi_pmh  AND  DATEDIFF(a.diinput_tanggal, b.tanggal_pendaftaran)<=$validasi_pmh ";
		}else
		{
			$where="";
		}
		$sql = " 	SELECT 
						b.nomor_perkara,
						DATE_FORMAT(b.tanggal_pendaftaran,'%d/%m/%Y'), 
						DATE_FORMAT(a.tanggal,'%d/%m/%Y'),
						a.diinput_oleh,
						DATE_FORMAT(a.diinput_tanggal, '%d/%m/%Y %T'),
						DATEDIFF(a.tanggal, b.tanggal_pendaftaran)
					FROM
						perkara_proses AS a  
					LEFT JOIN perkara AS b 
						ON a.perkara_id=b.perkara_id
					WHERE
						(MONTH(a.tanggal)>=$bulan_awal AND YEAR(a.tanggal)>=$tahun_awal) AND (MONTH(a.tanggal)<=$bulan_akhir AND YEAR(a.tanggal)<=$tahun_akhir)
						 AND 						
						a.proses_id=20
						$where 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_pendaftaran"=> $row[1], 
						"tanggal_pmh"=> $row[2], 
						"diinput_oleh"=>$row[3],  
						"diinput_tanggal"=>$row[4]  ,
						"selisih"=>$row[5]
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan pmh diinput
	//kepatuhan pmh tupoksi
	if($menu=="kepatuhan_tupoksi_pmh")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		$tupoksi=$_GET["tupoksi"];
		IF($tupoksi==0)
		{
			$where="AND (a.diinput_oleh='admin' OR a.diinput_oleh='System')";
		}else
		 
		{
			$where="AND  a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System' ";
		}
		$sql = " 	SELECT 
						b.nomor_perkara,
						DATE_FORMAT(b.tanggal_pendaftaran,'%d/%m/%Y'), 
						DATE_FORMAT(a.tanggal,'%d/%m/%Y'),
						a.diinput_oleh,
						DATE_FORMAT(a.diinput_tanggal, '%d/%m/%Y %T'),
						DATEDIFF(date(b.tanggal_pendaftaran),date(a.tanggal))
					FROM
						perkara_proses AS a  
					LEFT JOIN perkara AS b 
						ON a.perkara_id=b.perkara_id
					WHERE
						(MONTH(a.tanggal)>=$bulan_awal AND YEAR(a.tanggal)>=$tahun_awal) AND (MONTH(a.tanggal)<=$bulan_akhir AND YEAR(a.tanggal)<=$tahun_akhir)
						 AND 						
						a.proses_id=20
						$where 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_pendaftaran"=> $row[1], 
						"tanggal_pmh"=> $row[2], 
						"diinput_oleh"=>$row[3],  
						"diinput_tanggal"=>$row[4]  ,
						"selisih"=>$row[5]
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan pmh tupoksi
	//kepatuhan phs diinput
	if($menu=="kepatuhan_tanggal_input_phs")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		$tepat=$_GET["tepat"];
		IF($tepat==0)
		{
			$where="AND (DATEDIFF(a.tanggal,c.penetapan_majelis_hakim)>$validasi_phs  OR  DATEDIFF( a.diinput_tanggal,c.penetapan_majelis_hakim)>$validasi_phs )";
		}else
		IF($tepat==1)	
		{
			$where="AND  DATEDIFF(a.tanggal, c.penetapan_majelis_hakim)<=$validasi_phs  AND  DATEDIFF(a.diinput_tanggal, c.penetapan_majelis_hakim)<=$validasi_phs ";
		}else
		{
			$where="";
		}
		$sql = " 	SELECT 
						b.nomor_perkara,
						DATE_FORMAT(b.tanggal_pendaftaran,'%d/%m/%Y'), 
						DATE_FORMAT(c.penetapan_majelis_hakim,'%d/%m/%Y'),
						DATE_FORMAT(a.tanggal,'%d/%m/%Y'),
						a.diinput_oleh, 
						DATE_FORMAT(a.diinput_tanggal, '%d/%m/%Y %T'), 
						c.majelis_hakim_nama
					FROM
						perkara_proses AS a  
					LEFT JOIN perkara AS b 
						ON a.perkara_id=b.perkara_id
					LEFT JOIN perkara_penetapan AS c 
						ON a.perkara_id=c.perkara_id
						
					WHERE
						(MONTH(a.tanggal)>=$bulan_awal AND YEAR(a.tanggal)>=$tahun_awal) AND (MONTH(a.tanggal)<=$bulan_akhir AND YEAR(a.tanggal)<=$tahun_akhir)
						 AND 						
						a.proses_id=80
						$where
					 ORDER BY 
						c.majelis_hakim_nama asc, b.tanggal_pendaftaran asc,   b.nomor_perkara asc 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$km=$row[6];
			$km=str_replace("/","",$km);
			$pecah_km=explode("<br>",$km);
			$data_km=$pecah_km[0];
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_pendaftaran"=> $row[1], 
						"tanggal_pmh"=> $row[2], 
						"tanggal_phs"=> $row[3], 
						"diinput_oleh"=>$row[4],  
						"diinput_tanggal"=>$row[5],
						"km"=>$data_km
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan phs diinput
	//kepatuhan phs tupoksi
	if($menu=="kepatuhan_tupoksi_phs")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		$tupoksi=$_GET["tupoksi"];
		IF($tupoksi==0)
		{
			$where="AND (a.diinput_oleh='admin' OR a.diinput_oleh='System')";
		}else
		 
		{
			$where="AND  a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System' ";
		}
		$sql = " 	SELECT 
						b.nomor_perkara,
						DATE_FORMAT(b.tanggal_pendaftaran,'%d/%m/%Y'), 
						DATE_FORMAT(c.penetapan_majelis_hakim,'%d/%m/%Y'),
						DATE_FORMAT(a.tanggal,'%d/%m/%Y'),
						a.diinput_oleh,
						DATE_FORMAT(a.diinput_tanggal, '%d/%m/%Y %T'), 
						c.majelis_hakim_nama
					FROM
						perkara_proses AS a  
					LEFT JOIN perkara AS b 
						ON a.perkara_id=b.perkara_id
					LEFT JOIN perkara_penetapan AS c 
						ON a.perkara_id=c.perkara_id
					WHERE
						(MONTH(a.tanggal)>=$bulan_awal AND YEAR(a.tanggal)>=$tahun_awal) AND (MONTH(a.tanggal)<=$bulan_akhir AND YEAR(a.tanggal)<=$tahun_akhir)
						 AND 						
						a.proses_id=80
						$where
					 ORDER BY 
						c.majelis_hakim_nama asc, b.tanggal_pendaftaran asc,   b.nomor_perkara asc 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			
			$km=$row[6];
			$km=str_replace("/","",$km);
			$pecah_km=explode("<br>",$km);
			$data_km=$pecah_km[0];
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_pendaftaran"=> $row[1], 
						"tanggal_pmh"=> $row[2], 
						"tanggal_phs"=> $row[3], 
						"diinput_oleh"=>$row[4],  
						"diinput_tanggal"=>$row[5]  ,
						"km"=>$data_km
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan phs tupoksi
	//kepatuhan mediasi diinput
	if($menu=="kepatuhan_tanggal_input_mediasi")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		$tepat=$_GET["tepat"];
		IF($tepat==0)
		{
			$where="AND (DATEDIFF(a.tanggal,c.keputusan_mediasi)>$validasi_mediasi) OR  (DATEDIFF( a.tanggal, $sekarang)>$validasi_mediasi AND c.keputusan_mediasi=NULL )  ";
		}else
		IF($tepat==1)	
		{
			$where="AND  DATEDIFF(a.tanggal, c.keputusan_mediasi)<=$validasi_mediasi   ";
		}else
		{
			$where="";
		}
		$sql = " 	SELECT 
						b.nomor_perkara, 
						DATE_FORMAT(a.tanggal,'%d/%m/%Y'),
						c.mediator_text, 
						DATE_FORMAT(c.keputusan_mediasi,'%d/%m/%Y'),
						c.hasil_mediasi,
						a.diinput_oleh,
						DATE_FORMAT(a.diinput_tanggal, '%d/%m/%Y %T') 
					FROM
						perkara_proses AS a  
					LEFT JOIN perkara AS b 
						ON a.perkara_id=b.perkara_id 
					LEFT JOIN v_perkara_mediasi_terakhir AS c
						ON a.perkara_id=c.perkara_id 	
					WHERE
						(MONTH(a.tanggal)>=$bulan_awal AND YEAR(a.tanggal)>=$tahun_awal) AND (MONTH(a.tanggal)<=$bulan_akhir AND YEAR(a.tanggal)<=$tahun_akhir)
						 AND 						
						a.proses_id=110
						$where
					 ORDER BY 
						 a.tanggal asc, c.mediator_text asc  
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_penetapan_mediator"=> $row[1], 
						"mediator"=> $row[2], 
						"tanggal_hasil_mediasi"=> $row[3], 
						"hasil_mediasi"=>hasil_mediasi($row[4]),  
						"diinput_oleh"=>$row[5] ,  
						"diinput_tanggal"=>$row[6]   
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan mediasi diinput
	
	//kepatuhan mediasi tupoksi
	if($menu=="kepatuhan_tupoksi_mediasi")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		$tupoksi=$_GET["tupoksi"];
		IF($tupoksi==0)
		{
			$where="AND (a.diinput_oleh='admin' OR a.diinput_oleh='System')";
		}else
		 
		{
			$where="AND  a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System' ";
		}
		$sql = " 	SELECT 
						b.nomor_perkara, 
						DATE_FORMAT(a.tanggal,'%d/%m/%Y'),
						c.mediator_text, 
						DATE_FORMAT(c.keputusan_mediasi,'%d/%m/%Y'),
						c.hasil_mediasi,
						a.diinput_oleh,
						DATE_FORMAT(a.diinput_tanggal, '%d/%m/%Y %T') 
					FROM
						perkara_proses AS a  
					LEFT JOIN perkara AS b 
						ON a.perkara_id=b.perkara_id 
					LEFT JOIN v_perkara_mediasi_terakhir AS c
						ON a.perkara_id=c.perkara_id 	
					WHERE
						(MONTH(a.tanggal)>=$bulan_awal AND YEAR(a.tanggal)>=$tahun_awal) AND (MONTH(a.tanggal)<=$bulan_akhir AND YEAR(a.tanggal)<=$tahun_akhir)
						 AND 						
						a.proses_id=110
						$where
					 ORDER BY 
						 a.tanggal asc, c.mediator_text asc 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_penetapan_mediator"=> $row[1], 
						"mediator"=> $row[2], 
						"tanggal_hasil_mediasi"=> $row[3], 
						"hasil_mediasi"=>hasil_mediasi($row[4]),  
						"diinput_oleh"=>$row[5] ,  
						"diinput_tanggal"=>$row[6]   
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan mediasi tupoksi
	
	//kepatuhan putusan diinput
	if($menu=="kepatuhan_tanggal_input_putusan")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		$tepat=$_GET["tepat"];
		IF($tepat==1)
		{
			$where="AND (DATEDIFF(a.diinput_tanggal,c.tanggal_putusan ) <=$validasi_putusan)  ";
		}else
		IF($tepat==0)	
		{
			$where="AND  DATEDIFF(a.diinput_tanggal,c.tanggal_putusan ) >$validasi_putusan  ";
		}else
		{
			$where="";
		}
		$sql = " 	SELECT 
						b.nomor_perkara, 
						DATE_FORMAT(a.tanggal,'%d/%m/%Y'), 
						case 
							 when c.status_putusan_id=61 then 'Tidak dapat diterima' 
							 when c.status_putusan_id=62 then 'Dikabulkan' 
							 when c.status_putusan_id=63 then 'Ditolak'
							 when c.status_putusan_id=64 then 'Tidak Dapat Diterima'
							 when c.status_putusan_id=65 then 'Digugurkan'
							 when c.status_putusan_id=66 then 'Dicoret dari Register'
							 when c.status_putusan_id=67 then 'Dicabut'
							 else ' ' end as jenis_p, 
						case when c.putusan_verstek='Y' then 'Ya'  
						else 'Tidak' end as putusan_verstek, 
						d.majelis_hakim_nama,
						a.diinput_oleh,
						DATE_FORMAT(a.diinput_tanggal, '%d/%m/%Y %T') 
					FROM
						perkara_proses AS a  
					LEFT JOIN perkara AS b 
						ON a.perkara_id=b.perkara_id 
					LEFT JOIN perkara_putusan AS c
						ON a.perkara_id=c.perkara_id 	
					LEFT JOIN perkara_penetapan AS d
						ON a.perkara_id=d.perkara_id 	
					WHERE
						(MONTH(a.tanggal)>=$bulan_awal AND YEAR(a.tanggal)>=$tahun_awal) AND (MONTH(a.tanggal)<=$bulan_akhir AND YEAR(a.tanggal)<=$tahun_akhir)
						 AND 						
						a.proses_id=210
						$where
					 ORDER BY 
						d.majelis_hakim_nama asc, b.tanggal_pendaftaran asc,   b.nomor_perkara asc 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			
			$km=$row[4];
			$km=str_replace("/","",$km);
			$pecah_km=explode("<br>",$km);
			$data_km=$pecah_km[0];
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_putusan"=> $row[1], 
						"jenis_putusan"=> $row[2], 
						"verstek"=> $row[3], 
						"km"=>$data_km,
						"diinput_oleh"=>$row[5] ,  
						"diinput_tanggal"=>$row[6]   
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan putusan diinput
	
	//kepatuhan putusan tupoksi
	if($menu=="kepatuhan_tupoksi_putusan")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		$tupoksi=$_GET["tupoksi"];
		IF($tupoksi==0)
		{
			$where="AND (a.diinput_oleh='admin' OR a.diinput_oleh='System')";
		}else
		 
		{
			$where="AND  a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System' ";
		}
		$sql = " 	SELECT 
						b.nomor_perkara, 
						DATE_FORMAT(a.tanggal,'%d/%m/%Y'), 
						case 
							 when c.status_putusan_id=61 then 'Tidak dapat diterima' 
							 when c.status_putusan_id=62 then 'Dikabulkan' 
							 when c.status_putusan_id=63 then 'Ditolak'
							 when c.status_putusan_id=64 then 'Tidak Dapat Diterima'
							 when c.status_putusan_id=65 then 'Digugurkan'
							 when c.status_putusan_id=66 then 'Dicoret dari Register'
							 when c.status_putusan_id=67 then 'Dicabut'
							 else ' ' end as jenis_p, 
						case when c.putusan_verstek='Y' then 'Ya'  
						else 'Tidak' end as putusan_verstek, 
						d.majelis_hakim_nama,
						a.diinput_oleh,
						DATE_FORMAT(a.diinput_tanggal, '%d/%m/%Y %T') 
					FROM
						perkara_proses AS a  
					LEFT JOIN perkara AS b 
						ON a.perkara_id=b.perkara_id 
					LEFT JOIN perkara_putusan AS c
						ON a.perkara_id=c.perkara_id 	
					LEFT JOIN perkara_penetapan AS d
						ON a.perkara_id=d.perkara_id 	
					WHERE
						(MONTH(a.tanggal)>=$bulan_awal AND YEAR(a.tanggal)>=$tahun_awal) AND (MONTH(a.tanggal)<=$bulan_akhir AND YEAR(a.tanggal)<=$tahun_akhir)
						 AND 						
						a.proses_id=210
						$where
					 ORDER BY 
						d.majelis_hakim_nama asc, b.tanggal_pendaftaran asc,   b.nomor_perkara asc 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$km=$row[4];
			$km=str_replace("/","",$km);
			$pecah_km=explode("<br>",$km);
			$data_km=$pecah_km[0];
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_putusan"=> $row[1], 
						"jenis_putusan"=> $row[2], 
						"verstek"=> $row[3], 
						"km"=>$data_km,
						"diinput_oleh"=>$row[5] ,  
						"diinput_tanggal"=>$row[6]   
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan putusan tupoksi
	
	
	//kepatuhan minutasi diinput
	if($menu=="kepatuhan_tanggal_input_minutasi")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		$tepat=$_GET["tepat"];
		IF($tepat==1)
		{
			$where="AND (DATEDIFF(a.diinput_tanggal,c.tanggal_minutasi ) <=$validasi_minutasi)  ";
		}else
		IF($tepat==0)	
		{
			$where="AND  DATEDIFF(a.diinput_tanggal,c.tanggal_minutasi ) >$validasi_minutasi  ";
		}else
		{
			$where="";
		}
		$sql = " 	SELECT 
						b.nomor_perkara, 
						DATE_FORMAT(a.tanggal,'%d/%m/%Y'), 
						case 
							 when c.status_putusan_id=61 then 'Tidak dapat diterima' 
							 when c.status_putusan_id=62 then 'Dikabulkan' 
							 when c.status_putusan_id=63 then 'Ditolak'
							 when c.status_putusan_id=64 then 'Tidak Dapat Diterima'
							 when c.status_putusan_id=65 then 'Digugurkan'
							 when c.status_putusan_id=66 then 'Dicoret dari Register'
							 when c.status_putusan_id=67 then 'Dicabut'
							 else ' ' end as jenis_p, 
						DATE_FORMAT(c.tanggal_minutasi, '%d/%m/%Y') ,
						d.majelis_hakim_nama,
						a.diinput_oleh,
						DATE_FORMAT(a.diinput_tanggal, '%d/%m/%Y %T') 
					FROM
						perkara_proses AS a  
					LEFT JOIN perkara AS b 
						ON a.perkara_id=b.perkara_id 
					LEFT JOIN perkara_putusan AS c
						ON a.perkara_id=c.perkara_id 	
					LEFT JOIN perkara_penetapan AS d
						ON a.perkara_id=d.perkara_id 	
					WHERE
						(MONTH(a.tanggal)>=$bulan_awal AND YEAR(a.tanggal)>=$tahun_awal) AND (MONTH(a.tanggal)<=$bulan_akhir AND YEAR(a.tanggal)<=$tahun_akhir)
						 AND 						
						a.proses_id=220
						$where
					 ORDER BY 
						d.majelis_hakim_nama asc, c.tanggal_putusan asc,   b.nomor_perkara asc 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$km=$row[4];
			$km=str_replace("/","",$km);
			$pecah_km=explode("<br>",$km);
			$data_km=$pecah_km[0];
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_putusan"=> $row[1], 
						"jenis_putusan"=> $row[2], 
						"tanggal_minutasi"=> $row[3], 
						"km"=>$data_km,
						"diinput_oleh"=>$row[5] ,  
						"diinput_tanggal"=>$row[6]   
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan minutasi diinput
	
	//kepatuhan minutasi tupoksi
	if($menu=="kepatuhan_tupoksi_minutasi")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"];
		$tupoksi=$_GET["tupoksi"];
		IF($tupoksi==0)
		{
			$where="AND (a.diinput_oleh='admin' OR a.diinput_oleh='System')";
		}else
		 
		{
			$where="AND  a.diinput_oleh<>'admin' AND a.diinput_oleh<>'System' ";
		}
		$sql = " 	SELECT 
						b.nomor_perkara, 
						DATE_FORMAT(a.tanggal,'%d/%m/%Y'), 
						case 
							 when c.status_putusan_id=61 then 'Tidak dapat diterima' 
							 when c.status_putusan_id=62 then 'Dikabulkan' 
							 when c.status_putusan_id=63 then 'Ditolak'
							 when c.status_putusan_id=64 then 'Tidak Dapat Diterima'
							 when c.status_putusan_id=65 then 'Digugurkan'
							 when c.status_putusan_id=66 then 'Dicoret dari Register'
							 when c.status_putusan_id=67 then 'Dicabut'
							 else ' ' end as jenis_p, 
						DATE_FORMAT(c.tanggal_minutasi, '%d/%m/%Y') ,
						d.majelis_hakim_nama,
						a.diinput_oleh,
						DATE_FORMAT(a.diinput_tanggal, '%d/%m/%Y %T') 
					FROM
						perkara_proses AS a  
					LEFT JOIN perkara AS b 
						ON a.perkara_id=b.perkara_id 
					LEFT JOIN perkara_putusan AS c
						ON a.perkara_id=c.perkara_id 	
					LEFT JOIN perkara_penetapan AS d
						ON a.perkara_id=d.perkara_id 	
					WHERE
						(MONTH(a.tanggal)>=$bulan_awal AND YEAR(a.tanggal)>=$tahun_awal) AND (MONTH(a.tanggal)<=$bulan_akhir AND YEAR(a.tanggal)<=$tahun_akhir)
						 AND 						
						a.proses_id=220
						$where
					 ORDER BY 
						d.majelis_hakim_nama asc, c.tanggal_putusan asc,   b.nomor_perkara asc 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$km=$row[4];
			$km=str_replace("/","",$km);
			$pecah_km=explode("<br>",$km);
			$data_km=$pecah_km[0];
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_putusan"=> $row[1], 
						"jenis_putusan"=> $row[2], 
						"tanggal_minutasi"=> $row[3], 
						"km"=>$data_km,
						"diinput_oleh"=>$row[5] ,  
						"diinput_tanggal"=>$row[6]   
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan minutasi tupoksi
	//kepatuhan bht
	if($menu=="kepatuhan_bht")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"]; 
		$sql = " 	SELECT 
						b.nomor_perkara, 
						DATE_FORMAT(a.tanggal_putusan,'%d/%m/%Y'), 
						case 
							 when a.status_putusan_id=61 then 'Tidak dapat diterima' 
							 when a.status_putusan_id=62 then 'Dikabulkan' 
							 when a.status_putusan_id=63 then 'Ditolak'
							 when a.status_putusan_id=64 then 'Tidak Dapat Diterima'
							 when a.status_putusan_id=65 then 'Digugurkan'
							 when a.status_putusan_id=66 then 'Dicoret dari Register'
							 when a.status_putusan_id=67 then 'Dicabut'
							 else ' ' end as jenis_p, 
						DATE_FORMAT(d.tanggal,'%d/%m/%Y'),
						DATE_FORMAT(a.tanggal_bht, '%d/%m/%Y') ,
						c.majelis_hakim_nama,
						case when a.putusan_verstek='Y' then 'Ya'  
						else 'Tidak' end as putusan_verstek 
					FROM
						perkara_putusan AS a 
					LEFT JOIN perkara AS b
						ON a.perkara_id=b.perkara_id
					LEFT JOIN perkara_penetapan AS c
						ON c.perkara_id=a.perkara_id 	
					LEFT JOIN (select perkara_id, tanggal from perkara_proses where proses_id=218 )AS d
						ON d.perkara_id=a.perkara_id 	
					WHERE
						(MONTH(a.tanggal_bht)>=$bulan_awal AND YEAR(a.tanggal_bht)>=$tahun_awal) AND (MONTH(a.tanggal_bht)<=$bulan_akhir AND YEAR(a.tanggal_bht)<=$tahun_akhir)
						   
						
					 ORDER BY 
						c.majelis_hakim_nama asc, a.tanggal_putusan asc,   b.nomor_perkara asc 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$km=$row[5];
			$km=str_replace("/","",$km);
			$pecah_km=explode("<br>",$km);
			$data_km=$pecah_km[0];
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_putusan"=> $row[1], 
						"jenis_putusan"=> $row[2], 
						"tanggal_pbt"=> $row[3], 
						"tanggal_bht"=> $row[4], 
						"km"=>$data_km, 
						"putusan_verstek"=>$row[6] 
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan bht
	//login
	if($menu=="login")
	{ 
		   
		$sql = " 	SELECT  
						a.userid,
						a.fullname,
						b.host_address,
						DATE_FORMAT(b.login_time, '%d/%m/%Y %T') ,
						a.username,
						d.name 
						
					FROM
						sys_users AS a  
					LEFT JOIN sys_user_online AS b 
						ON b.userid=a.userid
					LEFT JOIN sys_user_group AS c
						ON c.userid=a.userid
					LEFT JOIN sys_groups AS d
						ON c.groupid=d.groupid 
					where 
						a.block=0
						
					ORDER BY c.groupid asc,  b.login_time desc
				"; 
		$query=mysql_query($sql); 
		$arr = array();	 	
		$no=0;
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$temp = array(
						"no"=> $no, 
						"userid"=> $row[0], 
						"fullname"=> $row[1],
						"host_address"=>$row[2] ,
						"login_time"=>$row[3], 
						"username"=>$row[4], 
						"tupoksi"=>$row[5] 
						
						
						)  ;
			
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//login
	//ip
	if($menu=="ip")
	{ 
		   
		$sql = " 	SELECT  
						a.ipaddress,
						count(a.id)  as jumlah
						
					FROM
						sys_audittrail AS a  
					LEFT JOIN sys_users AS b 
						ON b.username=a.username 
					GROUP BY a.ipaddress 
					order by ipaddress asc
				"; 
		$query=mysql_query($sql); 
		$arr = array();	 	
		$no=0;
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$temp = array(
						"no"=> $no, 
						"ip"=> $row[0] , 
						"jumlah"=> $row[1]  
						 
						
						
						)  ;
			
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//ip
	//user
	if($menu=="user")
	{ 
		   
		$sql = " 	SELECT  
						a.username,
						a.`fullname`,
						
						COUNT(b.id) AS jumlah
					FROM
						sys_users AS a  
					LEFT JOIN sys_audittrail AS b 
						ON b.username=a.username 
					GROUP BY a.username 
					ORDER BY jumlah DESC
				"; 
		$query=mysql_query($sql); 
		$arr = array();	 	
		$no=0;
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$temp = array(
						"no"=> $no, 
						"username"=> $row[0] , 
						"fullname"=> $row[1] , 
						"jumlah"=> $row[2]  
						 
						
						
						)  ;
			
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//user
	//kepatuhan pbt
	if($menu=="kepatuhan_pbt")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"]; 
		$sql = " 	SELECT 
						b.nomor_perkara, 
						DATE_FORMAT(a.tanggal_putusan,'%d/%m/%Y'), 
						case 
							 when a.status_putusan_id=61 then 'Tidak dapat diterima' 
							 when a.status_putusan_id=62 then 'Dikabulkan' 
							 when a.status_putusan_id=63 then 'Ditolak'
							 when a.status_putusan_id=64 then 'Tidak Dapat Diterima'
							 when a.status_putusan_id=65 then 'Digugurkan'
							 when a.status_putusan_id=66 then 'Dicoret dari Register'
							 when a.status_putusan_id=67 then 'Dicabut'
							 else ' ' end as jenis_p, 
						DATE_FORMAT(p.tanggal,'%d/%m/%Y'),
						c.jurusita_text,
						c.majelis_hakim_nama,
						case when a.putusan_verstek='Y' then 'Ya'  
						else 'Tidak' end as putusan_verstek ,
						p.diinput_oleh,
						DATE_FORMAT(p.diinput_tanggal,'%d/%m/%Y') 
					FROM
						perkara_proses AS p
						
					LEFT JOIN	perkara_putusan AS a 
						ON a.perkara_id=p.perkara_id
					LEFT JOIN perkara AS b
						ON p.perkara_id=b.perkara_id
					LEFT JOIN perkara_penetapan AS c
						ON c.perkara_id=p.perkara_id 	
					 
					WHERE
						(MONTH(p.tanggal)>=$bulan_awal AND YEAR(p.tanggal)>=$tahun_awal) AND (MONTH(p.tanggal)<=$bulan_akhir AND YEAR(p.tanggal)<=$tahun_akhir)
						 AND
						p.proses_id=218	
						
					 ORDER BY 
						c.majelis_hakim_nama asc, a.tanggal_putusan asc,   b.nomor_perkara asc 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$km=$row[5];
			$km=str_replace("/","",$km);
			$pecah_km=explode("<br>",$km);
			$data_km=$pecah_km[0];
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"tanggal_putusan"=> $row[1], 
						"jenis_putusan"=> $row[2], 
						"tanggal_pbt"=> $row[3], 
						"js"=> str_replace(":","",str_replace("Juru Sita","",str_replace("Juru Sita Pengganti","",$row[4]))), 
						"km"=>$data_km, 
						"putusan_verstek"=>$row[6] ,
						"diinput_oleh"=>$row[7], 
						"diinput_tanggal"=>$row[8] 
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan pbt
	
	//kepatuhan iikrar
	if($menu=="kepatuhan_ikrar")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"]; 
		$sql = " 	SELECT 
						b.nomor_perkara, 
						DATE_FORMAT(a.penetapan_majelis_hakim,'%d/%m/%Y'), 
						a.majelis_hakim_nama,
						DATE_FORMAT(a.penetapan_panitera_pengganti,'%d/%m/%Y'), 
						a.panitera_pengganti_text,
						DATE_FORMAT(a.penetapan_jurusita,'%d/%m/%Y'), 
						a.jurusita_text,
						DATE_FORMAT(a.tanggal_penetapan_sidang_ikrar,'%d/%m/%Y'), 
						DATE_FORMAT(a.tgl_ikrar_talak,'%d/%m/%Y'),  
						case 
							 when a.status_penetapan_ikrar_talak_id=1 then 'Diikrarkan' 
							 when a.status_penetapan_ikrar_talak_id=2 then 'Pemohon Tidak Hadir' 
							 when a.status_penetapan_ikrar_talak_id=3 then 'Rukun Kembali' 
							 else ' ' end as status_penetapan_ikrar_talak_id  		
					FROM
						 
						perkara_ikrar_talak AS a  
					LEFT JOIN perkara AS b
						ON a.perkara_id=b.perkara_id
					 
					WHERE
						 (MONTH(a.tgl_ikrar_talak)>=$bulan_awal AND YEAR(a.tgl_ikrar_talak)>=$tahun_awal) AND (MONTH(a.tgl_ikrar_talak)<=$bulan_akhir AND YEAR(a.tgl_ikrar_talak)<=$tahun_akhir)
						 
						
					 ORDER BY 
						a.tgl_ikrar_talak asc,   b.nomor_perkara asc 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"penetapan_majelis_hakim"=> $row[1], 
						"majelis_hakim_nama"=> $row[2], 
						"penetapan_panitera_pengganti"=> $row[3], 
						"panitera_pengganti_text"=> $row[4], 
						"penetapan_jurusita"=>$row[5] , 
						"jurusita_text"=>$row[6] ,
						"tgl_ikrar_talak"=>$row[7], 
						"status_penetapan_ikrar_talak_id"=>$row[8] 
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan ikrar
	//kepatuhan ac
	if($menu=="kepatuhan_ac")
	{
		$bulan_awal=$_GET["bulan_awal"];
		$tahun_awal=$_GET["tahun_awal"];
		$bulan_akhir=$_GET["bulan_akhir"];
		$tahun_akhir=$_GET["tahun_akhir"]; 
		$sql = " 	SELECT 
						b.nomor_perkara,  
						a.nomor_akta_cerai,
						DATE_FORMAT(a.tgl_akta_cerai,'%d/%m/%Y'), 
						a.no_seri_akta_cerai,
						a.jenis_cerai,
						a.diinput_oleh,
						DATE_FORMAT(a.diinput_tanggal , '%d/%m/%Y %T') 
					FROM
						 
						perkara_akta_cerai AS a  
					LEFT JOIN perkara AS b
						ON a.perkara_id=b.perkara_id
					 
					WHERE
						 (MONTH(a.tgl_akta_cerai)>=$bulan_awal AND YEAR(a.tgl_akta_cerai)>=$tahun_awal) AND (MONTH(a.tgl_akta_cerai)<=$bulan_akhir AND YEAR(a.tgl_akta_cerai)<=$tahun_akhir)
						 
						
					 ORDER BY 
						a.tgl_akta_cerai asc,   a.nomor_akta_cerai asc 
		"; 
		$query=mysql_query($sql); 
		$no=0;
		$arr = array();	 	
		while ($row=mysql_fetch_row($query))
		{  
			$no++;
			$temp = array(
						"no"=> $no, 
						"nomor_perkara"=> $row[0], 
						"nomor_akta_cerai"=> $row[1], 
						"tgl_akta_cerai"=> $row[2], 
						"no_seri_akta_cerai"=> $row[3], 
						"jenis_cerai"=> $row[4], 
						"diinput_oleh"=>$row[5] , 
						"diinput_tanggal"=>$row[6] 
						)  ; 
			array_push($arr, $temp); 
		} 
		
		echo json_encode($arr);
	}
	//kepatuhan ac
}
?>