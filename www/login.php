<?php

include($_SERVER["DOCUMENT_ROOT"] . '/../config.php');

include($_SERVER["DOCUMENT_ROOT"] . '/db.php');

$user = SQLite3::escapeString($_SERVER['PHP_AUTH_USER']);
$pass = $_SERVER['PHP_AUTH_PW'];

$sql = '
SELECT passwd FROM t_users
WHERE username = \'' . $user . '\';';

$ret = $db->query($sql);

while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
  $pass_hash = $row['passwd'];  
}
$db->close();

$validated = (password_verify($pass, $pass_hash));

if (!$validated) {
  header('WWW-Authenticate: Basic realm="inz"');
  header('HTTP/1.0 401 Unauthorized');
  die ("Not authorized");
}

// If arrives here, is a valid user.
echo "<p>Witaj $user!</p>";

?>
