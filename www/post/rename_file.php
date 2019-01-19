<?php

session_start();
include('../config.php');
if ($_SESSION['fileExpDir']) {$directory = $uploadsRoot . '/' . $_SESSION['fileExpDir'];}


$var = $_POST;
#print_r($var);
$oldFile = $directory . '/' . $var['filename'];
$newFile = $directory . '/' . $var['newFilename'];

rename($oldFile, $newFile);
header("Location: {$_SERVER['HTTP_REFERER']}");
exit;

?>