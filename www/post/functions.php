<?php

include($_SERVER["DOCUMENT_ROOT"] . '/../config.php');

function log_message($message) {
	$logPath = '/srv/inz/log/rsd.log';
    $date = date('Y-m-d H:s');
    $logFile = fopen($logPath,'a');
    fwrite($logFile ,$date . ';' . $_SERVER['PHP_AUTH_USER'] . ';' . $message . "\n");
    fclose($logFile);
}

?>