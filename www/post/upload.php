<?php

session_start();

include($_SERVER["DOCUMENT_ROOT"] . '/../config.php');
include($_SERVER["DOCUMENT_ROOT"] . '/post/functions.php');
$inputFiles = $_FILES['files'];
$dir = $_SERVER["DOCUMENT_ROOT"] . '/' . $uploadsRoot . '/' . $_SESSION['fileExpDir'];

if(!empty($inputFiles))
{

    for( $i=0 ; $i < count($inputFiles['name']) ; $i++ ){
        move_uploaded_file($inputFiles['tmp_name'][$i], $dir . '/' . $inputFiles['name'][$i]);
        log_message('do katalogu ' . $_SESSION['fileExpDir'] . ' wgrano plik ' . $inputFiles['name'][$i]);
	}
}

#print_r($inputFiles);

header("Location: {$_SERVER['HTTP_REFERER']}");
exit;
?>
