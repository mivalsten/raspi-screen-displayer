<?php

#session_start();
#include($_SERVER["DOCUMENT_ROOT"] . '/../config.php');
#if ($_SESSION['fileExpDir']) {$directory = $uploadsRoot . '/' . $_SESSION['fileExpDir'];}

$scanned_directory = array_diff(scandir($directory), array('..', '.'));
natcasesort($scanned_directory);

print '<div id="fileExplorer">';
print '<h2>Aktualne pliki</h2>';

print '<table id="fileTable">
<th>nazwa</th>
<th>Rozmiar</th>
<th></th>';
foreach($scanned_directory as $val){
	echo '<tr><td><a href="/uploads/' . $_SESSION['fileExpDir'] . '/' . $val . '">'.$val.'</a></td>' . "\n";
	print '<td>'.ceil(filesize($directory.'/'.$val)/1024).'<span style="margin-left:1px;">kb</span></td>' . "\n";
	print '<td class="control">
	<div class="dropdown">
  <button class="button">Menu</button>
  <div class="dropdown-content">
    <a href="#" onclick="renameFile(\'' . $val . '\')">Zmień nazwę</a>
    <a href="#" onclick="copyFile(\'' . $val . '\')">Skopiuj</a>
    <a href="#" onclick="deleteFile(\'' . $val . '\')">Usuń</a>
	<a href="#" onclick="setTime(\'' . $val . '\')">Ustaw czas</a>
  </div>
</div>
	</td>' . "\n";
	print '</tr>';
};
print '</table>';

?>
