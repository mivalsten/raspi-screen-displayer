<?php

$uploadsRoot = './uploads';

$schedules = array('default',
			  'schedule1',
			  'schedule2',
			  'schedule3',
			  'schedule4',
			  'schedule5',
			  'schedule6',
			  'schedule7');

$scriptConfig=$_SERVER["DOCUMENT_ROOT"] . '/../scripts/config.sh';
$clientConfig=$_SERVER["DOCUMENT_ROOT"] . '/../out/client.conf';
$scheduleConfig=$_SERVER["DOCUMENT_ROOT"] . '/../scripts/schedule.txt';

$logPath = '/srv/inz/log/rsd.log';

?>
