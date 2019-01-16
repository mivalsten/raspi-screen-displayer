<?php
$inputFiles = $_FILES['files'];

if(!empty($inputFiles))
{
    $files = reArrayFiles($inputFiles);

    foreach($files as $val)
    {
		move_uploaded_file($val['tmp_name'],'./uploads/'.$val['name']);
	}
}

function reArrayFiles($file)
{
    $file_ary = array();
    $file_count = count($file['name']);
    $file_key = array_keys($file);
    
    for($i=0;$i<$file_count;$i++)
    {
        foreach($file_key as $val)
        {
            $file_ary[$i][$val] = $file[$val][$i];
        }
    }
    return $file_ary;
}

header("Location: {$_SERVER['HTTP_REFERER']}");
exit;

?>