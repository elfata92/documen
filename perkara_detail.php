 
<?php $judul="Detail Perkara Perdata"; include "sys_header.php";?>
<?php require_once("Encrypt.php"); $x = new CI_Encrypt();$key   = "m4hk4m4h4gung"; $perkara_id=$_GET['perkara_id'];$perkaraid=base64_encode($x->encode($perkara_id,$key))?>
<link rel="stylesheet" type="text/css" href="resources/css/1.11.4.jquery-ui.css"> 

	  
	<link rel="stylesheet" type="text/css" href="resources/css/bootstrap.css"> 
	<link rel="stylesheet" type="text/css" href="resources/css/font-awesome/css/font-awesome.css">


<script src="resources/js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="resources/js/jquery-ui-1.8.18.custom.min.js" type="text/javascript"></script>
<script type="text/javascript" src="resources/js/jquery.idTabs.min.js"></script>
<script src="resources/js/Modernizr.js"></script>
<script src="resources/js/jquery-sipp.js"></script>
<script>
$(function() {$( "#datepicker" ).datepicker({changeMonth: true,changeYear: true,dateFormat : 'dd/mm/yy',minDate: '01/01/2015',maxDate: '<?php echo date('d/m/Y');?>'  });});
</script> 

<div id="pageTitle"> INFORMASI DETAIL PERKARA  </div> <br><hr class="thick-line" style="clear:both;"><div id="left"></div>
	<div id="left"><span><a class='btn btn-danger' target="_blank" href="http://<?php echo $url_sipp?>/perkara_detil_agama/<?php echo $perkaraid?>" style="text-decoration:none">Buka Perkara di SIPP</a></span> <br><br>
	</div> 
	<div style="clear:both;"></div>
	<div style="margin-top:5px;" class="cssTable">
		<table id="tableinfo">
			<colgroup>
				<col width="15%">
				<col width="30%">
				<col width="30%">
				<col width="20%">
				<col width="15%">
			</colgroup>
			<tbody>
			 <?php
			 $sql="	SELECT
							perkara.alur_perkara_id,
							perkara.jenis_perkara_id,
							perkara.jenis_perkara_nama,
							DATE_FORMAT(perkara.tanggal_pendaftaran, '%d/%m/%Y') as tanggal_pendaftaran,
							perkara.nomor_perkara, 
							perkara.nomor_indeks,
							perkara.nomor_urut_perkara,
							perkara.pihak1_text,
							perkara.pihak2_text,
							perkara.proses_terakhir_text,
							perkara.proses_terakhir_id,
 							perkara.nomor_surat,
							perkara.tanggal_surat, 
							case  when perkara.pihak_dipublikasikan='Y' then 'Ya'   else 'Tidak' end as pihak_dipublikasikan, 
							 
							case  when perkara.prodeo=1 then 'Prodeo'   else 'Tidak' end as prodeo, 
							 
							perkara.petitum,
							DATE_FORMAT(perkara_putusan.tanggal_bht , '%d/%m/%Y') as tanggal_bht 
							, perkara_data_pernikahan.tgl_nikah
							, perkara_data_pernikahan.tgl_kutipan_akta_nikah
							, perkara_data_pernikahan.no_kutipan_akta_nikah
							, perkara_data_pernikahan.kua_tempat_nikah
							, perkara_mediator.nama_mediator
							, (select count(perkara_id) from perkara_pihak5 where perkara_id=$perkara_id ) AS jml_saksi
						FROM
							perkara 
							LEFT JOIN perkara_putusan 
								ON (perkara_putusan.perkara_id = perkara.perkara_id) 
							LEFT JOIN perkara_data_pernikahan 
								ON (perkara_data_pernikahan.perkara_id = perkara.perkara_id)  
							LEFT JOIN perkara_mediator 
								ON (perkara_mediator.perkara_id = perkara.perkara_id) 
							 
						WHERE perkara.perkara_id=$perkara_id
									";
				$quer=mysql_query($sql); 
			//	echo "<br>".$sql."<br>";
				$nama_mediator='';
				while($h=mysql_fetch_array($quer)) 
				{ foreach($h as $key=>$value) {$$key=$value;}
					 if($alur_perkara_id==15)
					 {
						 $sebutan_pihak1="Penggugat";
						 $sebutan_pihak2="Tergugat";
						 if($jenis_perkara_nama=="Cerai Talak")
						 {
							 $sebutan_pihak1="Pemohon";
								$sebutan_pihak2="Termohon";
						 }
							 
					 }else
					 {
						  $sebutan_pihak1="Pemohon";
						  $sebutan_pihak2="Termohon";
					 }
				?>
				<tr>
					<td>Nomor Perkara</td>
					<td><?php echo $sebutan_pihak1?></td>
					<td><?php echo $sebutan_pihak2?></td> 
					<td>Status Perkara</td>
					<td>Tanggal BHT</td>			</tr>
				<tr>
					<td><?php echo $nomor_perkara?></td>
					<td><?php echo $pihak1_text?></td>
					<td><?php echo $pihak2_text?></td> 
					<td><?php echo $proses_terakhir_text?></td>  
					<td><?php echo $tanggal_bht?></td>  
					
				</tr>
				<?php 
				}?>
			</tbody>
		</table>
	</div>  																																																																																																													
 
	<div id="usual2" class="usual"> 
		<ul>
			<li class="tab"><a href="#tabs0" id="tabInfo"  class="selected">Bas / Putusan</a></li>
			<li class="tab"><a href="#tabs1" id="tabInfo">Data Umum</a></li>
			<?php 
			if($proses_terakhir_id>=20)
			{?>
			<li class="tab"><a href="#tabs2" id="tabInfo">Penetapan</a></li>
			<?php }?>
			<?php 
			if($proses_terakhir_id>=80)
			{?>
			<li class="tab"><a href="#tabs4" id="tabInfo">Jadwal Sidang</a></li>
			<?php }?>
			<?php 
			if(!$jml_saksi==0)
			{?>
			<li class="tab"><a href="#tabs23" id="tabInfo">Saksi</a></li>
			<?php }?>
			<?php 
			if(!$nama_mediator=='')
			{?>
			<li class="tab"><a href="#tabs6" id="tabInfo">Mediasi</a></li>
			<?php }?>
			<?php 
			if($proses_terakhir_id>=210)
			{?>
			<li class="tab"><a href="#tabs10" id="tabInfo">Putusan Akhir</a></li>
			<?php }?>
			<?php 
			if(mysql_num_rows(mysql_query("SELECT perkara_id FROM perkara_ikrar_talak WHERE perkara_id=$perkara_id ")) ==1 )
			{?> 
			<li class="tab"><a href="#tabs24" id="tabInfo" >Ikrar Talak</a></li>
			<?php }?>
			<?php 
			if(mysql_num_rows(mysql_query("SELECT tgl_akta_cerai FROM perkara_akta_cerai WHERE perkara_id=$perkara_id AND  tgl_akta_cerai IS NOT NULL")) ==1 )
			{?> 
			<li class="tab"><a href="#tabs25" id="tabInfo">Akta Cerai</a></li>
			<?php }?>
			<li class="tab"><a href="#tabs11" id="tabInfo">Biaya Perkara</a></li>
			<li class="tab"><a href="#tabs12" id="tabInfo">Riwayat Perkara</a></li>
		</ul>
		<style type="text/css">
			#infoPerkara .wrapword li {
			  display: list-item !important;
			  clear: both;
			  float: none !important;
			 
			} td{
				  border:none;
			  }
			</style>
<div style="display: block;" id="tabs0">
			<div class="cssTable" style="margin-top:5px;">
			 
			<div id="pageTitle"><a class="btn btn-success" href="#" onclick="popup_form('_dokumen_pilih_jenis_blangko_a?jenis_blangko_id=15&jenis_perkara_id=<?php echo $jenis_perkara_id?>&perkara_id=<?php echo $perkara_id?>')"><font color=white>Buat Putusan / Penetapan</font></a>	
			
			<a class="btn btn-warning" href="#" onclick="popup_form('_dokumen_pilih_jenis_blangko_a?jenis_blangko_id=18&jenis_perkara_id=<?php echo $jenis_perkara_id?>&perkara_id=<?php echo $perkara_id?>')"><font color=white>Dokumen Pendukung</font></a>	
			</div>
			<div style="display: block;"></div>
			
			<div id="pageTitle"> BERITA ACARA SIDANG</div> 
				<table id="tableinfo">
					<colgroup></colgroup>
					<tbody>
						<tr>
                <td style="width:5%">No</td>
                <td style="width:15%">Tanggal</td>
                <td style="width:12%">Jam Sidang</td>
                <td style="width:21%">Agenda Sidang</td>
                <td style="width:8%">Sidang Keliling</td>
                <td style="width:24%">Ruang
                	&amp; Data Persidangan                </td>                
				<td style="width:15%">Cetak Dokumen</td>    </tr>
						 <?php
							 $sql="	SELECT a.alur_perkara_id, b.*  
									FROM perkara AS a left join perkara_jadwal_sidang AS b 
									on b.perkara_id=a.perkara_id
									WHERE b.perkara_id=$perkara_id and b.perkara_id=a.perkara_id ORDER BY tanggal_sidang ASC ";
									//echo $sql;
								$quer=mysql_query($sql); 
								$noo=0;
								while($h=mysql_fetch_array($quer)) 
								{ foreach($h as $key=>$value) {$$key=$value;}
									 $noo++;
									 if (($sidang_keliling=="t")||($sidang_keliling=="T")){
										$sidang_keliling="Tidak";
									}else{
										$sidang_keliling="Ya";
									}
									if (!empty($sampai_jam)){ 
										$jamnya=substr($jam_sidang,0,5)."&nbsp;s/d&nbsp;".substr($sampai_jam,0,5); 
									}else{ 
										$jamnya=substr($jam_sidang,0,5);
									}
									
									$dihadirioleh ='';
									$dihadirioleh = $dihadiri_oleh;

									$tglsidang = $tanggal_sidang;

									$curr_date = date('Y-m-d');
									$selisih = getSelisihHari($tglsidang,$curr_date);
									
									if($selisih>=0){
										if($dihadirioleh==1){
											$dihadirioleh = '(Dihadiri Oleh Semua Pihak)';
										}elseif ($dihadirioleh==2) {
											$dihadirioleh = '(Dihadiri Oleh '.$sebutan_pihak1.'  Saja)';
										}elseif ($dihadirioleh==3) {
											$dihadirioleh = '(Dihadiri Oleh '.$sebutan_pihak1.'  Saja)';
										}elseif ($dihadirioleh==4) {
											$dihadirioleh = '(Para Pihak Tidak Hadir)';
										}else{
											$dihadirioleh = '<font color="red">Lengkapi Data Kehadiran!!!</font>';
										}
									}
									else{
										$dihadirioleh='';
									}
									$ruangan = (empty($ruangan))? '<font color="red">Ruangan Belum Ditentukan</font>':$ruangan;
									//$infoVerzet = ($verzet=='Y')? '<br>(Sidang  verzet) ';
									 
									 
								?>
						
						<tr>
							<td align="center"><?php echo $noo?></td>
							<td align="center"><?php echo nama_hari($tanggal_sidang)."<br>". tgl_panjang_dari_mysql($tanggal_sidang)?></td>
							<td align="center"><?php echo $jamnya?></td>
							<td><font color="#259FD3">Agenda Sidang :</font><br><?php echo $agenda?>
							<br><font color="#259FD3"><br>Alasan Ditunda :</font><br><?php echo $alasan_ditunda?>
							</td>
							<td align="center"><?php echo $sidang_keliling?></td>
							<td align="center"><?php echo $ruangan?><br><?php echo $dihadirioleh?></td>
						 
								<td align="center">
								<?php 
								
								if($ikrar_talak=="T")
								{
									if($noo==1)
									{?>
									 [<a href="#" onclick="popup_form('_pilih_jenis_blangko_b?jenis_blangko_id=1&jenis_perkara_id=<?php echo $jenis_perkara_id?>&id_sidang=<?php echo $id?>&perkara_id=<?php echo $perkara_id?>&tglsidang=<?php echo $tglsidang?>')"> BAS Pertama</a>]<br>
									[<a href="#" onclick="popup_form2nd('_pilih_jenis_blangko_b?jenis_blangko_id=3&jenis_perkara_id=<?php echo $jenis_perkara_id?>&id_sidang=<?php echo $id?>&perkara_id=<?php echo $perkara_id?>&tglsidang=<?php echo $tglsidang?>')"> BAS Putus</a>]
									<?php 
									}else
									if($noo>=2)
									{
									 ?>
									  [<a href="#" onclick="popup_form2nd('_pilih_jenis_blangko_b?jenis_blangko_id=2&jenis_perkara_id=<?php echo $jenis_perkara_id?>&id_sidang=<?php echo $id?>&perkara_id=<?php echo $perkara_id?>&tglsidang=<?php echo $tglsidang?>')"> BAS Lanjutan</a>] 
									 <br>
									 [<a href="#" onclick="popup_form2nd('_pilih_jenis_blangko_b?jenis_blangko_id=3&jenis_perkara_id=<?php echo $jenis_perkara_id?>&id_sidang=<?php echo $id?>&perkara_id=<?php echo $perkara_id?>&tglsidang=<?php echo $tglsidang?>')"> BAS Putus</a>]</td> <?php 
									}
								}else 
								{
								 ?>
								  [<a href="#" onclick="popup_form2nd('_pilih_jenis_blangko_b?jenis_blangko_id=4&jenis_perkara_id=<?php echo $jenis_perkara_id?>&id_sidang=<?php echo $id?>&perkara_id=<?php echo $perkara_id?>&tglsidang=<?php echo $tglsidang?>')"> BAS Ikrar Talak</a>] 
								 <br>
								 [<a href="#" onclick="popup_form2nd('_pilih_jenis_blangko_b?jenis_blangko_id=17&jenis_perkara_id=<?php echo $jenis_perkara_id?>&id_sidang=<?php echo $id?>&perkara_id=<?php echo $perkara_id?>&tglsidang=<?php echo $tglsidang?>')"> Penetapan Ikrar Talak</a>]</td> <?php 
								}?>									 
							</td>
						</tr>
						<?php
						}?>
					</tbody>
				</table>
				<br>
				<br>
				<br>
				<br>
				<br>
				<br>
			</div>
			 
			<p></p>
 
<script type="text/javascript">
$("#refresh").change(function(){
	if($(this).val()==1){
		var urlRefresh = $('#refresh_dest').val();
		window.open(urlRefresh,'_self')
	}
});


function refreshOrtuList(jenis_pihak){
	$("#popup_form").remove();
	if(jenis_pihak==0){
		popup_form('<?php echo $url_sipp?>/add_ortuwali/<?php echo $perkaraid?>/add')
	}else if(jenis_pihak==1){
		popup_form('<?php echo $url_sipp?>/add_ortuwalikorban/<?php echo $perkaraid?>/add')
	}
}

function backed(){
	$("#popup_form").remove();
	$('#usual2 .selected').click();
	$("body").css({ overflow: 'inherit' })
}

function pilih_cetak(ele){
	if (ele.value!=1) {
		popup_form(ele.value);
		$(ele).val('1');
	}
}

$('#cetakResume').click(function(){
	var enc = $('#enc').val();
	window.open("<?php echo $url_sipp?>/c_template_tun_resume/cetakResume/"+enc);
});

$('#cetakSurat').click(function(){	
	popup_form('<?php echo $url_sipp?>/c_template_tun_perlawanan/popup_pilihan_panitera/<?php echo $perkaraid?>/1782'); 
});

$('#donlot').click(function(){	
	closeLoading();
});
if ( $( "div[name=table_pihak1]" ).length ) {
	if($('.table_pihak1 tr').length>1){
		$('#span_ijin_cerai_pihak1').show();
	}else{
		$('#span_ijin_cerai_pihak1').hide();
	}
}else{
	$('#span_ijin_cerai_pihak1').hide();
}
if ( $( "div[name=table_pihak2]" ).length ) {
	if($('.table_pihak2 tr').length>1){
		$('#span_ijin_cerai_pihak2').show();
	}else{
		$('#span_ijin_cerai_pihak2').hide();
	}
}else{
	$('#span_ijin_cerai_pihak2').hide();
}

$('#ijin_cerai_pihak1').click(function(){	
	if ($(this).attr('checked')){
		$('.ijin_cerai_pihak1').fadeIn();
	}else{
		$('.ijin_cerai_pihak1').fadeOut();
	}
});
$('#ijin_cerai_pihak2').click(function(){	
	if ($(this).attr('checked')){
		$('.ijin_cerai_pihak2').fadeIn();
	}else{
		$('.ijin_cerai_pihak2').fadeOut();
	}
});
</script>	
	</div>	
<div style="display: none;" id="tabs1">
			
	 
			
</div>	
		<div style="display: none;" id="tabs2"></div> 
		<div style="display: none;" id="tabs4"></div>
		<div style="display: none;" id="tabs23"></div>
		<div style="display: none;" id="tabs6"></div>
		<div style="display: none;" id="tabs10"></div>
		<div style="display: none;" id="tabs24"></div>
		<div id="tabs25"></div>
		<div style="display: none;" id="tabs11"></div>
		<div style="display: none;" id="tabs12"></div>
	</div>
 <script type="text/javascript"> 
$("#usual2 ul").idTabs("tabs2");
$( document ).ready(function() {
	$('a#tabInfo').click(function(event){
		if (!$(this).hasClass('loaded')){
			 
			if($(this).attr('href')=='#tabs1'){
				 
					 
					$('#tabs1').load('http://<?php echo $url_sipp?>/open_dataumum/<?php echo $perkaraid?>');
					$('a[href=#tabs1]').addClass('loaded');
				 
			}else if($(this).attr('href')=='#tabs2'){
				openLoadingDialog();
				$('#tabs2').load('http://<?php echo $url_sipp?>/add_info_penetapan/<?php echo $perkaraid?>');
				$('a[href=#tabs2]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs4'){
				openLoadingDialog();
				$('#tabs4').load('http://<?php echo $url_sipp?>/view_info_jadwal_sidang/<?php echo $perkaraid?>');
				$('a[href=#tabs4]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs5'){
				$('#tabs5').load('http://<?php echo $url_sipp?>/add_info_verzet/<?php echo $perkaraid?>');
				$('a[href=#tabs5]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs6'){
				openLoadingDialog();
				$('#tabs6').load('http://<?php echo $url_sipp?>/tab_info_mediasi/<?php echo $perkaraid?>');
				$('a[href=#tabs6]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs7'){
				openLoadingDialog();
				$('#tabs7').load('http://<?php echo $url_sipp?>/add_info_intervensi/<?php echo $perkaraid?>');
				$('a[href=#tabs7]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs8'){
				openLoadingDialog();
				$('#tabs8').load('http://<?php echo $url_sipp?>/view_info_penuntutan/<?php echo $perkaraid?>');
				$('a[href=#tabs8]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs9'){
				openLoadingDialog();
				$('#tabs9').load('http://<?php echo $url_sipp?>/add_info_putusan_sela/<?php echo $perkaraid?>');
				$('a[href=#tabs9]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs10'){
				openLoadingDialog();
				$('#tabs10').load('http://<?php echo $url_sipp?>/add_info_putusan_akhir/<?php echo $perkaraid?>');
				$('a[href=#tabs10]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs11'){
				openLoadingDialog();
				$('#tabs11').load('http://<?php echo $url_sipp?>/tab_info_biaya_perkara/<?php echo $perkaraid?>');
				$('a[href=#tabs11]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs12'){
				openLoadingDialog();
				$('#tabs12').load('http://<?php echo $url_sipp?>/view_info_riwayat_perkara/<?php echo $perkaraid?>');
				$('a[href=#tabs12]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs13'){
				openLoadingDialog();
				$('#tabs13').load('http://<?php echo $url_sipp?>/open_banding/<?php echo $perkaraid?>');
				$('a[href=#tabs13]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs14'){
				openLoadingDialog();
				$('#tabs14').load('http://<?php echo $url_sipp?>/open_kasasi/<?php echo $perkaraid?>');
				$('a[href=#tabs14]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs15'){
				openLoadingDialog();
				$('#tabs15').load('http://<?php echo $url_sipp?>/open_pk/<?php echo $perkaraid?>');
				$('a[href=#tabs15]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs16'){
				openLoadingDialog();
				$('#tabs16').load('http://<?php echo $url_sipp?>/perkara_tab_grasi/<?php echo $perkaraid?>');
				$('a[href=#tabs16]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs17'){
				
				$('#tabs17').load('http://<?php echo $url_sipp?>/open_eksekusi/<?php echo $perkaraid?>');
				$('a[href=#tabs17]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs18'){
				//openLoadingDialog();
				$('#tabs18').load('http://<?php echo $url_sipp?>/perkara_tab_barang_bukti/<?php echo $perkaraid?>');
				$('a[href=#tabs18]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs19'){
				openLoadingDialog();
				$('#tabs19').load('http://<?php echo $url_sipp?>/add_info_keberatan/<?php echo $perkaraid?>');
				$('a[href=#tabs19]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs21'){
				openLoadingDialog();
				$('#tabs21').load('http://<?php echo $url_sipp?>/penetapan_gugur/showinfo/<?php echo $perkaraid?>');
				$('a[href=#tabs21]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs22'){
				openLoadingDialog();
				$('#tabs22').load('http://<?php echo $url_sipp?>/show_gugatan_rekonve/<?php echo $perkaraid?>');
				$('a[href=#tabs22]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs23'){
				openLoadingDialog();
				$('#tabs23').load('http://<?php echo $url_sipp?>/view_info_saksi/<?php echo $perkaraid?>');
				$('a[href=#tabs23]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs24'){
				openLoadingDialog();
				$('#tabs24').load('http://<?php echo $url_sipp?>/add_info_ikrar/<?php echo $perkaraid?>');
				$('a[href=#tabs24]').addClass('loaded');
			}else if($(this).attr('href')=='#tabs25'){
				 openLoadingDialog();
				 $('#tabs25').load('http://<?php echo $url_sipp?>/add_info_akta_cerai/<?php echo $perkaraid?>');
				 $('a[href=#tabs25]').addClass('loaded');
			}
		}
	});
});

$('#backlist').click(function(){
    window.open("perdata","_self");
});

function cetakPutusanPraPidGugur() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_pidana_pra/cetak_putusan_pra_format_gugur/<?php echo $perkaraid?>','_self');
}
function cetakPutusanPraPidKabul() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_pidana_pra/cetak_putusan_pra_kabul_tolak/<?php echo $perkaraid?>/1','_blank');
}
function cetakPutusanPraPidTolak() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_pidana_pra/cetak_putusan_pra_kabul_tolak/<?php echo $perkaraid?>/1','_blank');
}

function cetakPutusanFormatPUtidakDiterima(){
    window.open('http://<?php echo $url_sipp?>/c_template_putusan_pidana/cetak_putusan_pidana_penuntutan_tidak_diterima/<?php echo $perkaraid?>','_self');
}
function pdt1() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/1','_blank');
}
function pdt2() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/2','_blank');
}
function pdt3() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/3','_blank');
}
function pdt4() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/4','_blank');
}
function pdt5() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/5','_blank');
}
function pdt6() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/6','_blank');
}
function pdt7() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/7','_blank');
}
function pdt8() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/8','_blank');
}
function pdt9() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/9','_blank');
}
function pdt10() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/10','_blank');
}
function pdt11() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/11','_blank');
}
function pdt12() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/12','_blank');
}
function pdt13() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/13','_blank');
}
function pdt14() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/14','_blank');
}
function pdt15() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/15','_blank');
}
function pdt16() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/16','_blank');
}
function pdt17() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/17','_blank');
}
function pdt18() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/18','_blank');
}
function pdt19() {
	window.open('http://<?php echo $url_sipp?>/c_template_putusan_perdata/cetak_putusan_pdt/<?php echo $perkaraid?>/19','_blank');
}

function pilih_cetak(ele){
	if (ele.value=='cetakPutusanFormatPUtidakDiterima()'){
		cetakPutusanFormatPUtidakDiterima();
	}else if (ele.value=='cetakPutusanPraPidGugur()'){
		cetakPutusanPraPidGugur();
	}else if (ele.value=='cetakPutusanPraPidKabul()'){
		cetakPutusanPraPidKabul();
	}else if (ele.value=='cetakPutusanPraPidTolak()'){
		cetakPutusanPraPidTolak();
	}else if (ele.value=='pdt1()'){
		pdt1();
	}else if (ele.value=='pdt2()'){
		pdt2();
	}else if (ele.value=='pdt3()'){
		pdt3();
	}else if (ele.value=='pdt4()'){
		pdt4();
	}else if (ele.value=='pdt5()'){
		pdt5();
	}else if (ele.value=='pdt6()'){
		pdt6();
	}else if (ele.value=='pdt7()'){
		pdt7();
	}else if (ele.value=='pdt8()'){
		pdt8();
	}else if (ele.value=='pdt9()'){
		pdt9();
	}else if (ele.value=='pdt10()'){
		pdt10();
	}else if (ele.value=='pdt11()'){
		pdt11();
	}else if (ele.value=='pdt12()'){
		pdt12();
	}else if (ele.value=='pdt13()'){
		pdt13();
	}else if (ele.value=='pdt14()'){
		pdt14();
	}else if (ele.value=='pdt15()'){
		pdt15();
	}else if (ele.value=='pdt16()'){
		pdt16();
	}else if (ele.value=='pdt17()'){
		pdt17();
	}else if (ele.value=='pdt18()'){
		pdt18();
	}else if (ele.value=='pdt19()'){
		pdt19();
	}
	else if (ele.value!=1) {
		popup_form(ele.value);
		$(ele).val('1');
	} 
}
</script>

<script type="text/javascript">
$( document ).ready(function() {
    $("body").css({ overflow: 'inherit' })
	$('#loading').fadeOut();
	$('a').click(function(event){
		var id = $(this).attr('id');
		if($(this).attr('href')!='#' && $(this).attr('href').substring(1,0)!='#' && id !='noLoading'){
			openLoadingDialog()
		}
	});
});

function closeLoading(){
    $("body").css({ overflow: 'inherit' })
    $('#loading').fadeOut();
}

function openLoadingDialog(){
	$("body").css({overflow: 'hidden'});
	$('#loading').fadeIn();
}

</script>
	 <br>
	 <br>
 
<?php  
	include "sys_footer.php";  
?>  	 