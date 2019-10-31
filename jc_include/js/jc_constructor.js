let JC_Construct = function(config) {
	let loc     	= window.location,
	    origin  	= window.location.origin,
	    matchSrc 	= window.location.search,
	    pathnm  	= window.location.pathname;

	let reHs    = matchSrc.split('='),
		uriData 		= "",
		validURLedit 	= loc.href.search("dir=");
	if ( validURLedit > 0 ) {
		uriData = reHs[1].replace(config.setting.path, config.setting.protocol + config.setting.host);
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
			else if (res !== false) {
				console.log(res);
			}
		});
	}
	this.visitFile = function(el) {
		// let dirExist 	= reHs[1].split(":");
		// disini belum selesai .... 
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



