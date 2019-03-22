<?php

include($_SERVER["DOCUMENT_ROOT"] . '/../config.php');

$form = $_POST;

$mod = '';
for ($i = 0; $i < count($form['name']); $i++) {
	$mod.=$form['name'][$i] . '="' . $form['val'][$i] . "\"\n";
}

file_put_contents($clientConfig, $mod);



header("Location: {$_SERVER['HTTP_REFERER']}");
exit;

?>