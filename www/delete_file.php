<?php

$var = $_POST;
unlink($var['filename']);

header("Location: {$_SERVER['HTTP_REFERER']}");
exit;

?>