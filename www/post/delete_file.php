<?php

session_start();
include('../config.php');
if ($_SESSION['fileExpDir']) {$directory = $uploadsRoot . '/' . $_SESSION['fileExpDir'];}


$var = $_POST;
unlink($directory . '/' . $var['filename']);

header("Location: {$_SERVER['HTTP_REFERER']}");
exit;

?>