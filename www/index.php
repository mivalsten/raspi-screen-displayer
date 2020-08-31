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
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	</head>
	<body>
<?php

session_start();

include($_SERVER["DOCUMENT_ROOT"] . '/../config.php');
include('login.php');
print "<p>Witaj $user! <a href=\"/logout.php\">Wyloguj</a></p>";
print '<a href="/users.php">Zarządzanie użytkownikami</a>';
if (! isset($_SESSION['fileExpDir'])) {$_SESSION['fileExpDir'] = 'default';}

if (isset($_POST['scheduleSelect'])) {
	$directory = $uploadsRoot.'/'.$_POST['scheduleSelect'];
	$_SESSION['fileExpDir']=$_POST['scheduleSelect'];
}
else {
	if ($_SESSION['fileExpDir']) {$directory = $uploadsRoot.'/'. $_SESSION['fileExpDir'];}
	else {$directory = $uploadsRoot.'/default';}
	
}

print '<div id="fileManager">';
include('fileManager.php');
print '<h2>Dodaj pliki</h2>';
print '
<form action="post/upload.php" method="post" multipart="" enctype="multipart/form-data">
<input type="file" name="files[]" multiple value="wybierz pliki"><br>
<input type="submit" value="wyslij">
</form>';

print '<br><a href="/out/final.mp4">Pobierz aktualny film</a>';

print '</div>';
print '<div id="configuration">';

$scheduleConfiguration=file($scheduleConfig);
$isEditable = False;
foreach($scheduleConfiguration as $line){
	$sch = explode(';',$line)[0];
	if (explode(';',$line)[0] == $_SESSION['fileExpDir']) {
		$scheduleStartEpoch = preg_replace('/[^0-9]/','',explode(';',$line)[1]) * 1000;
		$scheduleEndEpoch = preg_replace('/[^0-9]/','',explode(';',$line)[2]) * 1000;
	}
};

if ($_SESSION['fileExpDir'] != 'default') {
	print '
	<script>
				$( function() {
					flatpickr("#pickerStart", {
						enableTime: true,
						dateFormat: "Z",
						altInput: true,
						altFormat: "Y-m-d H:i",
						time_24hr: true,
						defaultDate: ' . $scheduleStartEpoch . '
					});
					flatpickr("#pickerEnd", {
						enableTime: true,
						dateFormat: "Z",
						altInput: true,
						altFormat: "Y-m-d H:i",
						time_24hr: true,
						defaultDate: ' . $scheduleEndEpoch . '
					});
				} );
			</script>
	';
}
print '<h2>Harmonogramy</h2>';
print '<form id="scheduleForm" action="" method="post"> <select name="scheduleSelect" onchange="scheduleChanged()">';
	foreach($schedules as $sc) {if ($sc == $_SESSION['fileExpDir']) {$tmp = ' selected';} else {$tmp = '';};
		print '<option value="'. $sc . '"' . $tmp . '>' . $sc . '</option>';}
print '</select></form>';
if ($_SESSION['fileExpDir'] != 'default') {
	print '<form id=scheduleTime action="post/set_schedule.php" method="post">';
	print '<table><tr><td>Początek:</td><td><input type="text" name="start-date" id="pickerStart" class="flatpickr"></td></tr>';
	print '<tr><td>Koniec:</td><td><input type="text" name="end-date" id="pickerEnd" class="flatpickr"></td></tr></table>';
	print '<input type="submit" value="wyslij"></form>';
}
print '<h2>Konfiguracja</h2>';

print '<form action="post/update_config.php" method="POST">';
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
		print '<tr>
		<td>' . $name .  '<input type="text" name="name[]"  value="' . $name . '" style="display: none;"></td>
		<td><span id="configSpan' . $i . '">' . $value . '</span><input type="text" name="value[]" value=' . $value . '  style="display: none;" id="configValue' . $i . '"></td>
		<td>' . $type .  '<input type="text" name="type[]"  value="' . $type . '" style="display: none;"></td>
		<td class="control"><button type="button" class="button" onclick="changeConfigValue(\'' . $i . '\')">zmodyfikuj</button></td>
		</tr>';
	}

	if (preg_match('/^#php editable start/',$line)) {$isEditable = True;}
}
print '</table>
<input type="submit" value="wyslij">
</form>';

print '<div id="client_config">';
print '<h2>godziny dzialania</h2>';

$clientConfiguration=file($clientConfig);

print '<form action="post/update_client_config.php" method="POST">';
print "<table><th>Zmienna</th><th>Wartosć</th>";
$i=0;
foreach($clientConfiguration as $line){
	$i++;
	$line=explode('=', str_replace("\n", "", $line));
	print '<tr><td>' . $line[0] . '<input type="text" name="name[]"  value="' . $line[0] . '" style="display: none;">' . '</td>';
	print '<td><span id="clientConfigSpan' . $i . '">' . str_replace('"', '', $line[1]) . '</span><input type="text" name="val[]"  value="' . str_replace('"', '', $line[1]) . '" style="display: none;" id="clientConfigValue' . $i . '"></td>';
	print '<td class="control"><button type="button" class="button" onclick="changeClientConfigValue(\'' . $i . '\')">zmodyfikuj</button></td></tr>';
}
print '</table>
<input type="submit" value="wyslij">
</form>';

print '</div>';

print '</div>';

?>

</body>
</html>
