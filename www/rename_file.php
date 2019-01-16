<?php

$var = $_POST;
#print_r($var);
$oldFile = $var['directory'] . '/' . $var['filename'];
$newFile = $var['directory'] . '/' . $var['newFilename'];

rename($oldFile, $newFile);
header("Location: {$_SERVER['HTTP_REFERER']}");
exit;

?>