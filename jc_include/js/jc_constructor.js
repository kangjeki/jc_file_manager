let JC_Construct = function() {
	let iH = window.innerHeight;
	//query('.list-dir').style.height = iH + "px";
	//query('xmp').style.height = iH + "px";
}

window.onload = function() {
	let loadPage 	= new XMLHttpRequest();
	loadPage.onload = function() {
		JC_Construct();
	}
	loadPage.open('GET', window.location, true);
	loadPage.send();
};



