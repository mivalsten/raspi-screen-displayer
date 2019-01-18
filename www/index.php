<html>
	<head>
		<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato" />
		<link rel="stylesheet" type="text/css" href="/css/clean.css" />
		<link rel="stylesheet" type="text/css" href="/css/style.css" />
		<script type="text/javascript" src="/js/functions.js"></script>

		<!--Datetime picker -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
		<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="/resources/demos/style.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	</head>
	<body>
<?php

session_start();

include('config.php');
include('login.php');

if ($_POST['scheduleSelect']) {
	$directory = $uploadsRoot.'/'.$_POST['scheduleSelect'];
	$_SESSION['fileExpDir']=$_POST['scheduleSelect'];
}
else {
	if ($_SESSION['fileExpDir']) {$directory = $uploadsRoot.'/'. $_SESSION['fileExpDir'];}
	else {$directory = $uploadsRoot.'/default';}
	
}

$scanned_directory = array_diff(scandir($directory), array('..', '.'));
natcasesort($scanned_directory);

print '<div id="fileManager">';
include('fileManager.php');
print '<h2>Dodaj pliki</h2>';
print '
<form action="upload.php" method="post" multipart="" enctype="multipart/form-data">
<input type="file" name="files[]" multiple value="wybierz pliki"><br>
<input type="submit" value="wyslij">
</form>';

print '</div>';
print '<div id="configuration">';

$scheduleConfiguration=file($scheduleConfig);
$isEditable = False;
foreach($scheduleConfiguration as $line){
	$sch = explode(';',$line)[0];
	if (explode(';',$line)[0] == $_SESSION['fileExpDir']) {
		$scheduleStartEpoch = explode(';',$line)[1] * 1000;
		$scheduleEndEpoch = explode(';',$line)[2] * 1000;
	}
};

print '
<script>
			$( function() {
				flatpickr("#pickerStart", {
    				enableTime: true,
					dateFormat: "Y-m-d H:i",
					time_24hr: true,
					defaultDate: ' . $scheduleStartEpoch . '
				});
				flatpickr("#pickerEnd", {
    				enableTime: true,
					dateFormat: "Y-m-d H:i",
					time_24hr: true,
					defaultDate: ' . $scheduleEndEpoch . '
				});
			} );
		</script>
';
print '<h2>Harmonogramy</h2>';
print '<form id="scheduleForm" action="" method="post"> <select name="scheduleSelect" onchange="scheduleChanged()">';
	foreach($schedules as $sc) {if ($sc == $_SESSION['fileExpDir']) {$tmp = ' selected';} else {$tmp = '';};
		print '<option value="'. $sc . '"' . $tmp . '>' . $sc . '</option>';}
print '</select></form>';
if ($_SESSION['fileExpDir'] != 'default') {
	print '<form id=scheduleTime action="/set_schedule.php" method="post">';
	print '<table><tr><td>Początek:</td><td><input type="text" name="start-date" id="pickerStart" class="flatpickr"></td></tr>';
	print '<tr><td>Koniec:</td><td><input type="text" name="end-date" id="pickerEnd" class="flatpickr"></td></tr></table>';
	print '<input type="submit" value="wyslij"></form>';
}
print '<h2>Konfiguracja</h2>';

print '<form action="/update_config.php" method="POST">';
$configuration=file($scriptConfig);
print "<table><th>Zmienna</th><th>Wartosć</th><th>Typ</th>";
$isEditable = False;
$i=0;
foreach($configuration as $line){
	if (preg_match('/^#php editable end/',$line)) {$isEditable = False;	}
	$i++;
	if ($isEditable) {
		$conf = explode('=', $line);
		$name = $conf[0];
		$value = str_replace('"','',explode(' #', $conf[1])[0]);
		$type = str_replace(array("\n", "\r"), '', explode(' #', $conf[1])[1]);
		$unit = str_replace(array("\n", "\r"), '', explode(' #', $conf[1])[2]);
		print '<tr>
		<td>' . $name .  '<input type="text" name="name[]"  value="' . $name . '" style="display: none;"></td>
		<td><span id="configSpan' . $i . '">' . $value . '</span><input type="text" name="value[]" value=' . $value . '  style="display: none;" id="configValue' . $i . '"></td>
		<td>' . $type .  '<input type="text" name="type[]"  value="' . $type . '" style="display: none;"></td>
		<td class="control"><button type="submit" class="button" onclick="changeConfigValue(\'' . $i . '\')">zmodyfikuj</button></td>
		</tr>';
	}

	if (preg_match('/^#php editable start/',$line)) {$isEditable = True;}
}
print '</table>
<input type="submit" value="wyslij">
</form>';
print '</div>';

?>

</body>
</html>