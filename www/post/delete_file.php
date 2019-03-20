<?php

session_start();
include($_SERVER["DOCUMENT_ROOT"] . '/../config.php');
include($_SERVER["DOCUMENT_ROOT"] . '/post/functions.php');
if ($_SESSION['fileExpDir']) {$directory = $_SERVER["DOCUMENT_ROOT"] . '/' . $uploadsRoot . '/' . $_SESSION['fileExpDir'];}

$var = $_POST;
unlink($directory . '/' . $var['filename']);
log_message('z katalogu ' . $_SESSION['fileExpDir'] . ' usunieto plik ' . $var['filename']);

header("Location: {$_SERVER['HTTP_REFERER']}");
exit;

?>