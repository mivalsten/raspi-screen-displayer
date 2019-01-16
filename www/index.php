<html>
	<head>
		<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato" />
		<link rel="stylesheet" type="text/css" href="/css/clean.css" />
		<link rel="stylesheet" type="text/css" href="/css/style.css" />
		<script type="text/javascript" src="/js/functions.js"></script>

		<!--Datetime picker -->
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
		<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
	</head>
	<body>

<?php

include('config.php');
include('login.php');
$scanned_directory = array_diff(scandir($directory), array('..', '.'));
natcasesort($scanned_directory);

print '<div id="fileManager">';
include('fileManager.php');
print '<h2>Dodaj pliki</h2>';
print '
<form action="upload.php" method="post" multipart="" enctype="multipart/form-data">
<input type="file" name="files[]" multiple value="wybierz pliki"><br>
<input type="submit" value="wyslij">
</form>
';
print '</div>';
print '<div id="configuration">';
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

function ifSelected($input, $expected) {
	if ($input == $expected) {return "selected";}
	else {return "";}
};

?>

</body>
</html>