<?php

session_start();
include($_SERVER["DOCUMENT_ROOT"] . '/../config.php');
include($_SERVER["DOCUMENT_ROOT"] . '/post/functions.php');
if ($_SESSION['fileExpDir']) {$directory = $_SERVER["DOCUMENT_ROOT"] . '/' . $uploadsRoot . '/' . $_SESSION['fileExpDir'];}


$var = $_POST;
#print_r($var);
$oldFile = $directory . '/' . $var['filename'];
$newFile = $directory . '/' . $var['newFilename'];

rename($oldFile, $newFile);
log_message('zmieniono nazwe pliku z ' . $var['filename'] . ' na ' . $var['newFilename']);
header("Location: {$_SERVER['HTTP_REFERER']}");
exit;

?>