function ajaxPost(str) {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.open("GET", str, true);
        xmlhttp.send();
}

function renameFile(filename) {
	var newFilename = prompt("Podaj nową nazwę pliku", filename);
	str = "/post/rename_file.php?filename=" + encodeURI(filename) + "&newFilename=" + encodeURI(newFilename);
	ajaxPost(str);
	location.reload();
}

function deleteFile(filename) {
	if (confirm('Czy na pewno usunąć plik ' + filename + "?")) {
		str = "/post/delete_file.php?filename=" + encodeURI(filename);
		ajaxPost(str);
		location.reload();
	}
}

function copyFile(filename) {
	str = "/post/delete_file.php?filename=" + encodeURI(filename);
	ajaxPost(str);
	location.reload();
}

function setTime(filename) {
//	str = "/post/delete_file.php?filename=" + encodeURI(filename);
//	ajaxPost(str);
//	location.reload();
}

function changeConfigValue(ID) {
	configSpan="configSpan"+ID;
	configValue="configValue"+ID;
	var newValue = prompt("Podaj nową wartosć", document.getElementById(configSpan).innerHTML);
	document.getElementById(configSpan).innerHTML = newValue;
	document.getElementById(configValue).value = newValue;
};

function changeClientConfigValue(ID) {
	configSpan="clientConfigSpan"+ID;
	configValue="clientConfigValue"+ID;
	var newValue = prompt("Podaj nową wartosć", document.getElementById(configSpan).innerHTML);
	document.getElementById(configSpan).innerHTML = newValue;
	document.getElementById(configValue).value = newValue;
};

function scheduleChanged() {
	document.getElementById("scheduleForm").submit();
};

//user management functions

function userRename(username) {
	var loop = true;
	while(loop) {
		var newUsername = prompt("Podaj nową nazwę użytkownika, bez spacji i znaków specjalnych", username);
		if (newUsername.match(/[^a-zA-Z0-9]/g) === null || newUsername.length == 0) {
			loop = false
		} else {
			newUsername = prompt("Podaj nową nazwę użytkownika, bez spacji i znaków specjalnych", username);
		}
	}
	let str = "/post/users.php?action=rename&username=" + username + "&newUsername=" + newUsername;
	ajaxPost(str);
	location.reload();	
}

function UserChangePassword(username) {
	var loop = true;
	while(loop) {
		var newPassword = prompt("Podaj nowe hasło użytkownika");
		if (newPassword.length > 0) {
			loop = false
		} else {
			newPassword = prompt("Podaj nowe hasło użytkownika");
		}
	}
	let str = "/post/users.php?action=password&username=" + username + "&newPassword=" + newPassword;
	ajaxPost(str);
	location.reload();
}

function UserChangeType(username) {
	if(confirm("Czy na pewno chcesz zmienić typ konta " + username)) {
		let str = "/post/users.php?action=type&username=" + username;
		ajaxPost(str);
		location.reload();
	}
}

function userRemove(username) {
	if(confirm("Czy na pewno chcesz usunąć konto " + username)) {
		let str = "/post/users.php?action=delete&username=" + username;
		ajaxPost(str);
		location.reload();
	}
}

function userNew() {
	var loop = true;
	while(loop) {
		var newUsername = prompt("Podaj nową nazwę użytkownika, bez spacji i znaków specjalnych");
		if (newUsername.match(/[^a-zA-Z0-9]/g) === null || newUsername.length == 0) {
			loop = false
		} else {
			newUsername = prompt("Podaj nową nazwę użytkownika, bez spacji i znaków specjalnych");
		}
	}
	loop = true;
	while(loop) {
		var newPassword = prompt("Podaj nowe hasło użytkownika");
		if (newPassword.length > 0) {
			loop = false
		} else {
			newPassword = prompt("Podaj nowe hasło użytkownika");
		}
	}
	let str = "/post/users.php?action=new&username=" + newUsername + "&password=" + newPassword;
	ajaxPost(str);
	location.reload();
}