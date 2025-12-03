<?php 
	date_default_timezone_set('Asia/Jakarta');

	$sql="SELECT id, value FROM sys_config where (id>=61 AND   id<=73) or  id=76 or  id=75 or  id=80 ";
							 
		$quer=mysql_query($sql);  
		while($h=mysql_fetch_array($quer)) { foreach($h as $key=>$value) {$$key=$value;}
			if($id==61)
			{
				$KodePN=$value;
			}else
			if($id==62)
			{
				$NamaPN=$value;
			}else
			if($id==63)
			{
				$AlamatPN=$value;
			}else
			if($id==64)
			{
				$KetuaPNNama=$value;
			}else
			if($id==65)
			{
				$KetuaPNNIP=$value;
			}else
			if($id==66)
			{
				$WakilKetuaPNNama=$value;
			}else
			if($id==67)
			{
				$WakilKetuaPNNIP=$value;
			}else
			if($id==68)
			{
				$PanSekNama=$value;
			}else
			if($id==69)
			{
				$PanSekNIP=$value;
			}else
			if($id==70)
			{
				$WaPanNama=$value;
			}else
			if($id==71)
			{
				$WaPanNIP=$value;
			}else
			if($id==72)
			{
				$WaSekNama=$value;
			}else
			if($id==73)
			{
				$WaSekNIP=$value;
			}else
			if($id==76)
			{
				$NamaPT=$value;
			}else
			if($id==80)
			{
				$app_version=$value;
			} else
			if($id==75)
			{
				$zona_waktu=$value;
			} 
				
		} 
		
		
		
        $nama_aplikasi = "Aplikasi Cetak BAS, Putusan, DLL";
			 
?> 