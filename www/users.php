<?php

session_start();

if (!isset($_SESSION["username"])) {
	include($_SERVER["DOCUMENT_ROOT"] . '/login.php');
} else {
	include($_SERVER["DOCUMENT_ROOT"] . '/db.php');
}

if($_SESSION["isAdmin"] == 1) {
	$sql = "SELECT username, isadmin FROM t_users;";
} else {
	$sql = "SELECT username, isadmin FROM t_users
	WHERE username = '" . $_SESSION["username"] . "';";
}
$ret = $db->query($sql);

$users = array();
while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
  $users[] = [$row['username'], $row['isAdmin']];  
};

?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato" />
		<link rel="stylesheet" type="text/css" href="/css/clean.css" />
		<link rel="stylesheet" type="text/css" href="/css/style.css" />
		<script type="text/javascript" src="/js/functions.js"></script>

		<!--Datetime picker -->
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	</head>
	<body>
		<a href="/">Strona główna</a>
		<table>
			<tr>
				<th>Nazwa:</th>
				<th>Typ:</th>
			</tr>
<?php

foreach ($users as [$name, $isAdmin]) {
	print '<tr id="row_' . $name . '"><td class="name">' . $name . '</td>';
	print '<td class="type">';
	if ($isAdmin == 1) {print "Administrator";}
	else {print "Użytkownik";}
	print '</td>
	<td class="control">
	<div class="dropdown">
	<button class="button">Menu</button>
	<div class="dropdown-content">
	<a href="#" onclick="userRename(\'' . $name . '\')">Zmień nazwę</a>
	<a href="#" onclick="UserChangePassword(\'' . $name . '\')">Nowe hasło</a>
	';
	if($_SESSION["isAdmin"] == 1) {
		print '<a href="#" onclick="UserChangeType(\'' . $name . '\')">Zmień typ</a>
	<a href="#" onclick="userRemove(\'' . $name . '\')">Usuń</a>';
	}
	print '</div></div></td>';
	print '</tr>
	';
}
?>
</table>
<br>
<button class="button" onclick="userNew()">Nowy</button>
</body>
</html>


<?php
$db->close();
?>
