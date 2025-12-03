<?php
if (!isset($_SESSION)) {
    session_start();
}
if ($_SESSION['username'] <> "admin") {
    exit;
}
ini_set('max_execution_time', 600);
ini_set('display_errors', 'off');
error_reporting(E_ALL);
$versi_aplikasi = file_get_contents("versi.json");
echo '<p>Versi Terinstall : ' . $versi_aplikasi . '</p>';
$url_update =  base64_decode("aHR0cDovL3NlcnZlci5wYS1iYW5qYXJuZWdhcmEuZ28uaWQvZG9rdW1lbi8=");
$versi_sekarang = file_get_contents($url_update) or die('ERROR');
//echo $versi_sekarang;
if (!isset($_GET['update'])) {
?><div id="pageTitle">UPDATE APLIKASI </div><br>Apabila diinstall di server mohon untuk setting pemilik file chown sudah diatur atur untuk folder aplikasi Dokumen (chown -R apache:apache /var/www/html/dokumen) <hr class="thick-line" style="clear:both"><div id="left"></div><div id="left"><div id="hasil"><?php
    if ($versi_sekarang != '') {
        $daftar_versi = explode("\n", $versi_sekarang);
        $terbaru      = 'tidak';
        foreach ($daftar_versi as $aV) {
            if ($versi_aplikasi < $aV) {
                $terbaru = 'ya';
            }
        }
        if ($terbaru == 'ya') {
            echo 'Versi Terbaru ditemukan, <a  href="?update=update">Download dan Install</a>';
?><?php
        } else {
            echo 'Versi Sudah Menggunakan Versi Terbaru';
        }
    } else
        echo '<p>Tidak ditemukan versi terbaru.</p>';
?></div></div><?php
} else {
    if ($_GET['update'] == "update") {
        if ($versi_sekarang != '') {
            $daftar_versi = explode("\n", $versi_sekarang);
            $terbaru      = 'tidak';
            foreach ($daftar_versi as $aV) {
                if ($versi_aplikasi < $aV) {
                    echo '<p>Update baru Ditemukan : versi ' . $aV . '</p>';
                    $found      = true;
                    $file_versi = 'update_dokumen-' . $aV . '.zip';
                    if (!is_file('UPDATES/update_dokumen-' . $aV . '.zip')) {
                        echo '<p>Download Update baru... Silahkan tunggu sebentar..</p>';
                        $newUpdate = file_get_contents($url_update . "update/" . $file_versi);
                        if (!is_dir('UPDATES/'))
                            mkdir('UPDATES/');
                        $dlHandler = fopen('UPDATES/' . $file_versi, 'w');
                        if (!fwrite($dlHandler, $newUpdate)) {
                            echo '<p>Tidak dapat menyimpan file Update. Operasi dibatalkan.</p>';
                            exit();
                        }
                        fclose($dlHandler);
                        echo '<p>Update baru sudah didownload dan tersimpan</p>';
                    } else {
                        $hapus_file_lama = unlink('UPDATES/' . $file_versi);
                        echo '<p>Download Update baru... Silahkan tunggu sebentar..</p>';
                        $newUpdate = file_get_contents($url_update . "update/" . $file_versi);
                        if (!is_dir('UPDATES/'))
                            mkdir('UPDATES/');
                        $dlHandler = fopen('UPDATES/' . $file_versi, 'w');
                        if (!fwrite($dlHandler, $newUpdate)) {
                            echo '<p>Tidak dapat menyimpan file Update. Operasi dibatalkan.</p>';
                            exit();
                        }
                        fclose($dlHandler);
                        echo '<p>Update baru sudah didownload dan tersimpan</p>';
                    }
                    $zipHandle = zip_open('UPDATES/' . $file_versi);
                    echo '<ul>';
                    while ($aF = zip_read($zipHandle)) {
                        $thisFileName = zip_entry_name($aF);
                        $thisFileDir  = dirname($thisFileName);
                        if (substr($thisFileName, -1, 1) == '/')
                            continue;
                        if (!is_dir($thisFileDir)) {
                            mkdir($thisFileDir);
                            echo '<li>Membuat Directory ' . $thisFileDir . '</li>';
                        }
                        if (!is_dir($thisFileName)) {
                            echo '<li>' . $thisFileName . '...........';
                            $contents   = zip_entry_read($aF, zip_entry_filesize($aF));
                            $contents   = str_replace("\r\n", "\n", $contents);
                            $updateThis = '';
                            $updateThis = fopen($thisFileName, 'w');
                            fwrite($updateThis, $contents);
                            fclose($updateThis);
                            unset($contents);
                            echo ' Proses Selesai</li>';
                        }
                    }
                    echo "<a style='text-decoration:none;color:#DC143C' href='help_" . $aV . ".txt'><h1 color=red>Baca Petunjuk</h1>";
                }
            }
        }
    }
}
?> 