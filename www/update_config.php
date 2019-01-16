<?php

include('config.php');

$form = $_POST;

$mod = '';
for ($i = 0; $i < count($form['name']); $i++) {
	if ($form['type'][$i] == 'string') {$mod.=$form['name'][$i] . '="' . $form['value'][$i] . '" #' . $form['type'][$i] . "\n";}
	else {$mod.=$form['name'][$i] . '=' . $form['value'][$i] . ' #' . $form['type'][$i] . "\n";}
}

$newConfigurationFileContents = '';
$configuration=file($scriptConfig);
$isEditable = False;
foreach($configuration as $line){
	if (preg_match('/^#php editable end/',$line)) {
		$isEditable = False;
	};
	if (!$isEditable) {$newConfigurationFileContents .= $line;};
	if (preg_match('/^#php editable start/',$line)) {
		$isEditable = True;
		$newConfigurationFileContents .= $mod;
	};
}
file_put_contents($scriptConfig, $newConfigurationFileContents);

header("Location: {$_SERVER['HTTP_REFERER']}");
exit;

?>