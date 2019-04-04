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
	str = "/post/delete_file.php?filename=" + encodeURI(filename);
	ajaxPost(str);
	location.reload();
}

function copyFile(filename) {
	str = "/post/delete_file.php?filename=" + encodeURI(filename);
	ajaxPost(str);
	location.reload();
}

function setTime(filename) {
	str = "/post/delete_file.php?filename=" + encodeURI(filename);
	ajaxPost(str);
	location.reload();
}

function changeConfigValue(ID) {
	configSpan="configSpan"+ID;
	configValue="configValue"+ID;
	var newValue = prompt("Podaj nową wartosć", document.getElementById(configSpan).innerHTML);
	document.getElementById(configSpan).innerHTML = newValue;
	document.getElementById(configValue).value = newValue;
};

function changeClientConfigValue(ID) {
	configSpan= "clientConfigSpan"+ID;
	configValue="clientConfigValue"+ID;
	var newValue = prompt("Podaj nową wartosć", document.getElementById(configSpan).innerHTML);
	document.getElementById(configSpan).innerHTML = newValue;
	document.getElementById(configValue).value = newValue;
};

function scheduleChanged() {
	document.getElementById("scheduleForm").submit();
};

