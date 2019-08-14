<?php

session_start();

if (!isset($_SESSION["username"])) {
	include($_SERVER["DOCUMENT_ROOT"] . '/login.php');
} else {
	include($_SERVER["DOCUMENT_ROOT"] . '/db.php');
}

$vars = $_GET;
if($_SESSION["isAdmin"] == 1 || $vars['username'] == $_SESSION["username"]) {

	switch ($vars['action']) {
		case 'rename':
			$sql = "
				UPDATE t_users
				SET username = '" . $vars['newUsername'] . "'
				WHERE username = '" . $vars['username'] . "';";
				break;
		case 'password':
			$sql = "
				UPDATE t_users
				SET passwd = '" . password_hash($vars['newPassword'], PASSWORD_BCRYPT) . "'
				WHERE username = '" . $vars['username'] . "';";
				break;
		case 'type':
			if ($_SESSION["isAdmin"] == 1) {
				$sql = "
					UPDATE t_users
					SET isAdmin = (isAdmin + 1) % 2
					WHERE username = '" . $vars['username'] . "';";
			}
			break;
		case 'delete':
			if ($_SESSION["isAdmin"] == 1) {
				$sql = "
					DELETE FROM t_users
					WHERE username = '" . $vars['username'] . "';";
			}
			break;
		case 'new':
			$sql = "
				INSERT INTO t_users VALUES ('" . $vars['username'] . "', '" . password_hash($vars['password'], PASSWORD_BCRYPT) . "', 0);";
	}
	error_log($sql);
	$ret = $db->query($sql);

}
?>
