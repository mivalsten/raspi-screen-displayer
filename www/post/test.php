<?php

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
$var = $_POST;
print_r($var);
print('dupa');
} else {
	print('dupa');
}



?>