<?php
include "sys_koneksi.php";
set_time_limit(0);
ignore_user_abort(TRUE);


require __DIR__ . '/resources/backup/MySQLDump.php';

$dump = new MySQLDump(new mysqli($host, $user_host, $password_host, $db_host2), 'latin1');

//ini_set("zlib.output_compression", "On");
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="dokumen_backup_db_ ' . date('Y-m-d H-i') . '.sql"');
header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache');
header('Connection: close');

$dump->write();
