function getNewFilename(filename) {
	var newFilename = prompt("Podaj nową nazwę pliku", filename);
	if (newFilename === null) {return;}
	var fields = document.getElementsByName("newFilename");
	for (i=0; i < fields.length; i++) {
		fields[i].setAttribute('value',newFilename);
	}
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
