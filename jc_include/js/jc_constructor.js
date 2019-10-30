let loc     = window.location,
    origin  = window.location.origin,
    search  = window.location.search,
    pathnm  = window.location.pathname;

let reHs    = search.split('='),
	uriData = reHs[1].replace("C:/xampp/htdocs", "http://localhost");

let JC_Construct = function() {
	this.editFile = function(el) {
		let target = el.parentElement.querySelector('.code-block');
		if ( target.contentEditable == "true" ) {
			target.contentEditable = "false";
			console.log( target.contentEditable );
		}
		else if ( target.contentEditable == "false" ) {
			target.contentEditable = "true";
		}
	}
	this.saveEditFile = function(el) {
		let target = el.parentElement.querySelector('.code-block');
		let range 	= document.createRange();
			range.selectNodeContents(target);

		let sel 	= document.getSelection();
			sel.removeAllRanges(); //---> untuk select semua
			sel.addRange(range); //---> untuk select semua

		ajax.POST({
			url 	: "jc_include/async/edit_file/edit_file.php",
			send 	: "file=" + uriData + "&content=" + sel.focusNode.innerText
		}, function(res) {
			if (res == "true") {
				jc_alertDialog("Sukses Edit File", true);
			}
		});
	}
	this.visitFile = function(el) {
		window.open(uriData, "_blank");
	}
}

window.onload = function() {
	let loadPage 	= new XMLHttpRequest();
	loadPage.onload = function() {
		JC_Construct();
	}
	loadPage.open('GET', window.location, true);
	loadPage.send();
};



