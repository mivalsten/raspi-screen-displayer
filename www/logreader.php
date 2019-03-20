<?php
include($_SERVER["DOCUMENT_ROOT"] . '/../config.php');

$logfile = file($logPath, FILE_IGNORE_NEW_LINES) or die ('File opening failed');
$logfile = array_reverse($logfile);

print("<table><tr><th>Data</th><th>Użytkownik</th><th>Akcja</th></tr>");

foreach ($logfile as $line) {
    $line = explode(";",$line);
    print("<tr><td>$line[0]</td><td>$line[1]</td><td>$line[2]</td>");
};
print("</table>");
?>