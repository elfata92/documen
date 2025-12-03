<?php if(!isset($_SESSION)){session_start();}
include "sys_koneksi.php"; include "sys_config.php";include "resources/fungsi.php";

if(!$koneksi)
{ 
?>
	<script> 
		alert("Database Belum Disetting!\nAnda akan dibawa ke halaman Konfigurasi Database\nApabila aplikasi ini ditempatkan di server Linux pastikan anda sudah menjalankan 'chown -R apache:apache /var/www/dokumen/'"); 
	</script>
	<?php
	lempar('dbconfig');
}
$nm=$db_host2.'sys_menus';
?>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="favicon.ico" type="image/png">
	<title><?php echo @$judul;?> - <?php echo $nama_aplikasi;?> <?php echo $NamaPN;?> </title>
	<link rel="stylesheet" type="text/css" href="resources/css/style.css">
	<link rel="stylesheet" type="text/css" href="resources/css/table.css">
	
</head>
<body style="overflow: inherit;">  
<div id="wrapper">
	<div id="atas">		
		<div class="logo">
			<a href="index"><img src="resources/img/dokumen.png"></a>
		</div>
		<div class="front">
			<font><?php echo $nama_aplikasi;?><span><br><?php echo $NamaPN;?><br></span></font>
		</div>
		<div class="h_right">
			<?php 
				if(isset($_SESSION['username'])){ ?> 
				Selamat Datang  <b><?php echo $_SESSION['username'];?></b> <br> Anda Login Sebagai [<b><?php echo $_SESSION['fullname'];?></b>]<br>  <a href="login?aksi=keluar"><font color="yellow">Keluar</font></a>
			<?php   }else{ echo "<a href='#' onclick='login()'><img src='resources/img/login.png'></a>"; } ?>
			<div class="version_shading">
				<label><?php echo $app_version;?></label>
			</div>
			<div class="clear"></div>
			<div style="float:right"> </div> 
		</div>
			<style>.modal{display:none;position:fixed;z-index:1;padding-top:100px;left:0;top:0;width:100%;height:100%;overflow:auto;background-color:#000;background-color:rgba(0,0,0,.4)}.modal-content{position:relative;background-color:#fefefe;margin:auto;padding:0;border:1px solid #888;width:80%;box-shadow:0 4px 8px 0 rgba(0,0,0,.2),0 6px 20px 0 rgba(0,0,0,.19);-webkit-animation-name:animatetop;-webkit-animation-duration:.4s;animation-name:animatetop;animation-duration:.4s}@-webkit-keyframes animatetop{from{top:-300px;opacity:0}to{top:0;opacity:1}}@keyframes animatetop{from{top:-300px;opacity:0}to{top:0;opacity:1}}.close{color:#fff;float:right;font-size:28px;font-weight:700}.close:focus,.close:hover{color:#000;text-decoration:none;cursor:pointer}.modal-footer,.modal-header{padding:2px 16px;background-color:#008C4B;color:#fff}.modal-body{padding:2px 16px;text-align:center}</style>
			<script>
				function tutup_modal() 
				{ 
					document.getElementById("peringatan").style.display = 'none'; 
				}
				function login()
				{
					 document.getElementById("modal_header").innerHTML="Login::";  
					 document.getElementById("peringatan").style.display = 'block'; 
					 document.getElementById("username").focus();  
				}
				function proses_login()
				{
					var username=document.getElementById("username").value;
					var password=document.getElementById("password").value;
					var xhr = new XMLHttpRequest(); 
					xhr.open("POST", 'login', true); 
					xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
					document.getElementById("modal_footer").innerHTML="<h2 style='color:red'>Silahkan Tunggu..</h2";
					xhr.onreadystatechange = function() 
					{ 
						if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
							document.getElementById("modal_footer").innerHTML=xhr.responseText;
							if(document.getElementById("modal_footer").innerHTML=="Gagal")
							{
								document.getElementById("modal_footer").innerHTML="Login Gagal, Silahkan Coba Lagi";
							}else
							{
								window.location = "index";	
							}	
						}
					}
					xhr.send("username="+encodeURIComponent(username)+"&password="+encodeURIComponent(password)); 
				}
			</script>
			<div id="peringatan" class="modal"><div class="modal-content">  <div class="modal-header"> <span class="close" onclick="tutup_modal()">&times;</span> <h2 id="modal_header"></h2> </div><div class="modal-body"><p></p>  <input id='username' placeholder='isikan username' style='padding: 15px 25px; text-shadow: 1px 1px 0 rgba(255, 255, 255, 1); background: rgb(250, 255, 189); border: 1px solid #fff; border-radius: 5px;'>   <input style='padding: 15px 25px; text-shadow: 1px 1px 0 rgba(255, 255, 255, 1); background:rgb(250, 255, 189);  border: 1px solid #fff; border-radius: 5px;' type='password' id='password' placeholder='isikan password' style='width:200px'>   <button style='margin: 0;  padding: 13px 0;    width: 60px;  font-size: 13px;  font-weight: bold;  border: 0;   background-color: #f6ba35;     background-image: -webkit-linear-gradient(90deg, #eca418, #ffcd4e);  border-radius: 5px;   box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.3);' onclick='proses_login()'>Login</button><p></p> </div>  <div id='modal_footer' class="modal-footer"> <h4>Silahkan isikan username dan password</h4> </div>  </div></div>
		<div class="clear"></div>
	</div>
	<div id="cssmenu"> 
	<?php  
	if(isset($_SESSION['username'])){
		?>
		<ul><?php function get_menu_tree($parent_id){include 'sys_koneksi.php'; $menu="";$sqlquery=" SELECT * FROM  $db_host2.sys_menus as a where a.published=1 and a.parent_id='".$parent_id."' 
		order by parent_id asc, ordering asc "; $res=mysql_query($sqlquery);while($row=mysql_fetch_array($res)){$menu.="<li class='has-sub'><a href='".$row['link']."' title='".$row['title']."'>".$row['title']."</a>";$menu.="<ul>".get_menu_tree($row['id'])."</ul>";$menu.="</li>";}return $menu;}?><?php echo get_menu_tree(0);?></ul>
	<?php }?>
</div>
	
<div id="content">  