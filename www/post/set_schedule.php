<?php

session_start();
include($_SERVER["DOCUMENT_ROOT"] . '/../config.php');
include($_SERVER["DOCUMENT_ROOT"] . '/post/functions.php');

$startDate=new DateTime($_POST['start-date']);
$endDate=new DateTime($_POST['end-date']);

$startDateEpoch= date_format($startDate, 'U');
$endDateEpoch= date_format($endDate, 'U');

$newSchedule = $_SESSION['fileExpDir'] . ';' . $startDateEpoch . ';' . $endDateEpoch . "\n";

$newConfigurationFileContents = '';
$configuration=file($scheduleConfig);
$isEditable = False;
foreach($configuration as $line){
	$sch = explode(';',$line)[0];
	if (explode(';',$line)[0] == $_SESSION['fileExpDir']) {$newConfigurationFileContents .= $newSchedule;}
	else {$newConfigurationFileContents .= $line;}
};
file_put_contents($scheduleConfig, $newConfigurationFileContents);

log_message('Zmieniono start harmonogramu ' . $_SESSION['fileExpDir'] . ' na ' . $startDate);
log_message('Zmieniono koniec harmonogramu ' . $_SESSION['fileExpDir'] . ' na ' . $endDate);

#print $_POST['start-date'] . " - " . $startDateEpoch . "<br>";
#print $_POST['end-date'] . " - " . $endDateEpoch;

header("Location: {$_SERVER['HTTP_REFERER']}");
exit;

?>