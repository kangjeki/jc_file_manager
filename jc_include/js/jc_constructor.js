let JC_Construct = function(config) {
	let loc     	= window.location,
	    origin  	= window.location.origin,
	    matchSrc 	= window.location.search,
	    pathnm  	= window.location.pathname;

	let uriData 		="";
	let validURLedit 	= loc.href.search("dir=");
	if ( validURLedit > 0 ) {
		let reHs    = matchSrc.split('=');
			uriData = reHs[1].replace(config.path, config.protocol + config.host);
	}

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
			console.log(uriData);

		ajax.POST({
			url 	: "jc_include/async/edit_file/edit_file.php",
			send 	: "file=" + uriData + "&content=" + sel.focusNode.innerText
		}, function(res) {
			if (res !== false) {
				let response = JSON.parse(res);
				if (response.code == 1) {
					jc_alertDialog(response.pesan, true);
				}
				else {
					jc_alertDialog(response.pesan, false);
				}
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
		// load config json
		jc_import("config.json", function(res) {
			JC_Construct( JSON.parse(res) );
		});
	}
	loadPage.open('GET', window.location, true);
	loadPage.send();
};



