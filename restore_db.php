<?php
include "sys_koneksi.php";
set_time_limit(0);
ignore_user_abort(TRUE);


require __DIR__ . '/resources/backup/MySQLImport.php';



$time = -microtime(TRUE);

$dump = new MySQLImport(new mysqli($host, $user_host, $password_host, $db_host2));

$import->onProgress = function ($count, $percent) {
	if ($percent !== NULL) {
		echo (int) $percent . " %\r";
	} elseif ($count % 10 === 0) {
		echo '.';
	}
};

$import->load('restore/dump.sql');

$time += microtime(TRUE);
echo "FINISHED (in $time s)";
