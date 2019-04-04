<?php

session_start();
include($_SERVER["DOCUMENT_ROOT"] . '/../config.php');
include($_SERVER["DOCUMENT_ROOT"] . '/post/functions.php');
if ($_SESSION['fileExpDir']) {$directory = $_SERVER["DOCUMENT_ROOT"] . '/' . $uploadsRoot . '/' . $_SESSION['fileExpDir'];}
else {header("Location: {$_SERVER['HTTP_REFERER']}"); exit;}

$var = $_GET;
copy($directory . '/' . htmlspecialchars($var['filename']), $directory . '/' . htmlspecialchars($var['newFilename']));
log_message('w katalogu ' . $_SESSION['fileExpDir'] . ' skopiowano plik ' . $var['filename'] . ' z nazwą '. $var['newFilename']);

header("Location: {$_SERVER['HTTP_REFERER']}");
exit;

?>