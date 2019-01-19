<?php

#session_start();
#include($_SERVER["DOCUMENT_ROOT"] . '/../config.php');
#if ($_SESSION['fileExpDir']) {$directory = $uploadsRoot . '/' . $_SESSION['fileExpDir'];}

$scanned_directory = array_diff(scandir($directory), array('..', '.'));
natcasesort($scanned_directory);

print '<div id="fileExplorer">';
print '<h2>Aktualne pliki</h2>';

print "<table><th>nazwa</th><th>Rozmiar</th><th></th><th></th>";
foreach($scanned_directory as $val){
	echo '<tr><td>'.$val.'</td>' . "\n";
	print '<td>'.ceil(filesize($directory.'/'.$val)/1024).'KB</td>' . "\n";
	print '<td class="control"><form action="post/delete_file.php" method="POST"><input type="text" name="filename" value="' . 
		  $val . '" style="display: none;"><button type="submit" class="button">usuń</button></form></td>' . "\n";
	print '<td class="control"><form action="post/rename_file.php" method="POST" onclick="getNewFilename(\'' . $val . '\')">' . 
	'<input type="text" name="filename" value="' . $val . '" style="display: none;">' . 
	'<input type="text" name="newFilename" value="' .$val . '" style="display: none;">' .
	'<button type="submit" class="button" value="">zmień nazwę</button></form></td>' . "\n";
	print '</tr>';
};
print '</table>';

?>