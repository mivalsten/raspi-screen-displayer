<?

function log_message($message) {
    $date = date('Y-m-d');
    $logFile = fopen($logPath,'a');
    fwrite($logFile ,$date . ';' . $_SERVER['PHP_AUTH_USER'] . ';' . $message);
    fclose($logFile);
}

?>