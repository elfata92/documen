<?php

if(@$_GET["testconn"]==1){
		 
		$host =$_POST["server_utama"];
		if(empty($host)){
			echo json_encode(array('st'=>0,'msg'=>'Error. Alamat Server Tidak Boleh Kosong. Silahkan isi localhost untuk settingan default'));
			return;
		}
		$username = $_POST["user_utama"];
		if(empty($username)){
			echo json_encode(array('st'=>0,'msg'=>'Error. Username Tidak Boleh Kosong.'));
			return;
		}
		$pass = $_POST["passwd_utama"];
		
		if(empty($pass)){
			echo json_encode(array('st'=>0,'msg'=>'Error. Password Tidak Boleh Kosong. Tidak dibenarkan menginstall MySQL Database Tanpa Password'));
			return;
		}
		$dbname = $_POST["db_utama"];
		
		if(empty($dbname)){
			echo json_encode(array('st'=>0,'msg'=>'Error. Database Tidak Boleh Kosong.'));
			return;
		}
		$url_sipp = $_POST["url_sipp"];
		if(empty($url_sipp)){
			echo json_encode(array('st'=>0,'msg'=>'Error. URL SIPP Tidak Boleh Kosong.'));
			return;
		}
		$koneksi=mysql_connect("$host","$username","$pass");
		
		$conn = mysql_select_db("$dbname");
		if($conn===TRUE){
			echo json_encode(array('st'=>1,'msg'=>'Test Koneksi Berhasil'));
			return;
		}else{
			echo json_encode(array('st'=>0,'msg'=>'Error. Tidak Dapat Terhubung dengan Database. '.$conn));
			return;
		}
	}
 
if(@$_GET["validateSave"]==1){
		 $host =$_POST["server_utama"];
		if(empty($host)){
			echo json_encode(array('st'=>0,'msg'=>'Error. Alamat Server Tidak Boleh Kosong. Silahkan isi localhost untuk settingan default'));
			return;
		}
		$username = $_POST["user_utama"];
		if(empty($username)){
			echo json_encode(array('st'=>0,'msg'=>'Error. Username Tidak Boleh Kosong.'));
			return;
		}
		$pass = $_POST["passwd_utama"];
		
		if(empty($pass)){
			echo json_encode(array('st'=>0,'msg'=>'Error. Password Tidak Boleh Kosong. Tidak dibenarkan menginstall MySQL Database Tanpa Password'));
			return;
		}
		$dbname = $_POST["db_utama"];
		
		if(empty($dbname)){
			echo json_encode(array('st'=>0,'msg'=>'Error. Database Tidak Boleh Kosong.'));
			return;
		}
		$url_sipp = $_POST["url_sipp"];
		if(empty($url_sipp)){
			echo json_encode(array('st'=>0,'msg'=>'Error. URL SIPP Tidak Boleh Kosong.'));
			return;
		}
		$koneksi=mysql_connect("$host","$username","$pass");
		
		$conn = mysql_select_db("$dbname");
		if($conn===TRUE){
			$file = "sys_koneksi.php";
			$arrayRead = file($file);
			
			$arrayRead[1] = "\$host = \"".base64_encode($host)."\";\n";
			$arrayRead[2] = "\$user_host = \"".base64_encode($username)."\";\n";
			$arrayRead[3] = "\$password_host = \"".base64_encode($pass)."\";\n";
			$arrayRead[4] = "\$db_host = \"".base64_encode($dbname)."\";\n";   
			$arrayRead[5] = "\$db_host2 = \"".base64_encode("dokumen")."\";\n";   
			$arrayRead[6] = "\$url_sipp = \"".base64_encode($url_sipp)."\";\n";   
			$simpan = file_put_contents($file, implode($arrayRead));
			if($simpan)
			{
				echo json_encode(array('st'=>1,'msg'=>'Database Configuration Berhasil Disimpan.'));
				return; 
			}else
			{
				echo json_encode(array('st'=>0,'msg'=>'<font color="red">Error, Database Configuration Gagal Disimpan. Check File Permission, Please Contact IT PT OR SIPP Team.</font>'));
				return; 
			}
		}else{
			echo json_encode(array('st'=>0,'msg'=>'Error. Tidak Dapat Terhubung dengan Database. '.$conn));
			return;
		}
		
	}
?>
<html><head>
<link href="resources/css/style_db_config.css" type="text/css" rel="stylesheet">
<link type="text/css" rel="stylesheet" href="resources/fonts/pacifico/stylesheet.css">
<script src="resources/js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="resources/js/jquery-sipp.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$(".password").focus(function() {
		$(".pass-icon").css("left","-48px");
	});
	$(".password").blur(function() {
		$(".pass-icon").css("left","0px");
	});
});
</script>
</head>
    <body>
        <div style="top:200px;width:350px;" id="wrapper">
            <form method="post" action="dbconfig?validateSave=1" name="login-form" id="login-form">
                <table width="350px;">
                    <tbody><tr>
                        <td>
                            <div style="width:350px;" class="login-form">
                                <div class="header">
                                    <h1>Setting Database</h1>
                                    <span>SILAHKAN Masukan Informasi untuk Database.</span>
                                    <br> 
                                    <span>Pastikan anda sudah membuat database bernama "dokumen" dan mengimpor dokumen.sql </span> 
                                </div>
                                <div class="content">
                                    <input placeholder="Username DB Utama" class="input password" name="user_utama" id="user_utama" style="width:240px;">
                                    <input type="password" placeholder="Password DB Utama" class="input password" name="passwd_utama" id="passwd_utama" style="width:240px;">
                                    <input placeholder="Alamat Server/Localhost" class="input password" name="server_utama" id="server_utama" style="width:240px;">
                                    <input placeholder="Nama Database SIPP" class="input password" name="db_utama" id="db_utama" style="width:240px;">
                                    <input placeholder="Alamat URL SIPP" class="input password" name="url_sipp" id="url_sipp" style="width:240px;">
                                </div>
                                <div style="padding: 15px 60px 12px 33px;" class="footer">
                                    <table>
                                        <tbody><tr>
                                            <td><input type="button" style="padding:8px;width:85px;height:44px;" class="button" id="test" value="Test"></td>
                                            <td><input type="button" style="padding:8px;width:85px;height:44px;" class="button" id="close_form" value="Keluar"></td>
                                            <td><input type="submit" class="button" value="Simpan" name="submit" style="padding:8px;"></td>
                                        </tr>
                                    </tbody></table>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody></table>
            </form>
        </div>
    

<script type="text/javascript">
$('.footer').on('click', '#test', function(event) {
    openLoadingDialog()
    $.post('dbconfig?testconn=1', {
        user_utama:$('#user_utama').val(),
        passwd_utama:$('#passwd_utama').val(),
        server_utama:$('#server_utama').val(),
        db_utama:$('#db_utama').val(),
        url_sipp:$('#url_sipp').val(),
    }, function(response){
        var json = jQuery.parseJSON(response);
        closeLoading()
        if(json.st==1){
            message_show(json.msg);
        }else if(json.st==0){
            message_error_show_parent(json.msg);
        }
    });
})


$('#login-form').SubmitHandling({
    refresh:'1',
    setRefresh:1,
    divRef:'#footer',
    clicked:1,
    btnClicked:'#close_form',
    whenStatus:1,
});
$('.footer').on('click', '#close_form', function(event) {
    window.open('index','_self');  
})
function closeLoading(){
    $("body").css({ overflow: 'inherit' })
    $('#loading').fadeOut();
}

function openLoadingDialog(){
    $("body").css({overflow: 'hidden'});
    $('#loading').fadeIn();
}
</script></body></html>