<?php

$valid_passwords = array ("test" => "testtest",
						              "user" => "password",);
$valid_users = array_keys($valid_passwords);

$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

if (!$validated) {
  header('WWW-Authenticate: Basic realm="inz"');
  header('HTTP/1.0 401 Unauthorized');
  die ("Not authorized");
}

// If arrives here, is a valid user.
echo "<p>Witaj $user!</p>";

?>
