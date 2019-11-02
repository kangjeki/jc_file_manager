let JC_Construct = function(config) {
	if ( query("#refresh-page") !== null ) {
		jcEvent(query("#refresh-page"), 'click', function() {
			refreshPage();
		});	
	}

	if ( query("#fullScreen-page") !== null ) {
		jcEvent(query("#fullScreen-page"), 'click', function() {
			getFullScreen(query('html'));
		});	
	}

	if ( query("#back-page") !== null ) {
		jcEvent(query("#back-page"), 'click', function() {
			backPage();
		});	
	}
	if ( query("#forward-page") !== null ) {
		jcEvent(query("#forward-page"), 'click', function() {
			forwardPage();
		});	
	}
	
	let loc     	= window.location,
	    origin  	= window.location.origin,
	    matchSrc 	= window.location.search,
	    pathnm  	= window.location.pathname;

	let modalJudul 	= query("#modal-judul"),
		modalBody 	= query("#modal-body"); 

	let reHs    = matchSrc.split('='),
		uriData 		= "",
		validURLedit 	= loc.href.search("dir=");
	if ( validURLedit > 0 ) {
		uriData = reHs[1].replace(config.setting.path, config.setting.protocol + config.setting.host);
	}
	const __modalToggle = function(elPass) {
		let modalTg 	= elPass.getAttribute('target'),
			modalFrm 	= query(modalTg);
			modalW 		= modalFrm.getAttribute('modal-width');
			modalBlock 	= modalFrm.querySelector('.modal-block');
			modalBlock.style.cssText = `max-width: ${modalW}`;

		modalFrm.classList.toggle('modal-show');
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

		target.tabIndex 	= 1;
		target.focus();

		//console.log(new Event(target) );
		jcEvent(target, 'blur', function(ev) {
			if (target.contentEditable !== "false") {
				target.focus();
			}
		})

		jcEvent(target, 'keyup', function(ev) {
			if (ev.key === "Tab") {
				console.log(ev);
			}
		})
	}
	this.saveEditFile = function(el) {
		let target = el.parentElement.querySelector('.code-block');
		let range 	= document.createRange();
			range.selectNodeContents(target);

		let sel 	= document.getSelection();
			sel.removeAllRanges(); //---> untuk select semua
			sel.addRange(range); //---> untuk select semua

		let getData 	= sel.focusNode.innerHTML,
			reData 		= getData.replace(/&nbsp;/g, "t#a#b").replace(/<br>/g, "l#n#l");
		let crtNewData 	= newTag('div');
			crtNewData.innerHTML = reData;
			
		let content;
		if ( config.setting.text_highlight === true ) {
			content 	= crtNewData.innerText;
		}
		else if ( config.setting.text_highlight === false ) {
			content 	= getData;
		}

		ajax.POST({
			url 	: "jc_include/async/edit_file/edit_file.php",
			send 	: "file=" + uriData + "&content=" + content
		}, function(res) {
			//console.log(res);
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
	this.setDeleteOnFile = function(dtURI) {
		ajax.POST({
			url 	: window.location.href,
			send 	: "delete-on-file=" + dtURI
		}, function(response) {
			let localExist 		= window.location.href;
			let existDirPack 	= localExist.split('/'),
				fileName 		= existDirPack.pop();
				dirPack 		= localExist.replace("/" + fileName, "");
			if (response !== false) {
				let res = JSON.parse(response);
				if (res.code === 1) {
					jc_alertDialog(res.pesan, true);
					setTimeout(function() {
						window.location.href = dirPack;
					}, 2000);
				}
				else {
					jc_alertDialog(res.pesan, false);
				}
			}
		});
	}
	this.hapusFileEditable = function(el) {
		__modalToggle(el);
		let dataUri		= el.getAttribute('dir'),
			splGetName 	= dataUri.split("/"),
			nameIs 	 	= splGetName[splGetName.length - 1];

		modalJudul.innerHTML 	= "Hapus File";
		modalBody.innerHTML 	= "<div class='note note-danger'>Are You Sure Delete File <b>" + nameIs +"</b> ?</div>";
		let getFooterModal 		= modalBody.parentElement.querySelector('.box-footer'),
			existBtnDel 		= getFooterModal.querySelector('.setDelete');
		if (existBtnDel === null) {
			let btnDel 				= newTag('button');
				btnDel.setAttribute('onclick', 'setDeleteOnFile("'+ dataUri +'")');
				btnDel.classList.add('btn');
				btnDel.classList.add('btn-danger');
				btnDel.classList.add('setDelete');
				btnDel.innerHTML 	= '<i class="fas fa-trash"></i> Delete';
			getFooterModal.prepend(btnDel);	
		}
		else {
			existBtnDel.removeAttribute('onclick');
			existBtnDel.setAttribute('onclick', 'setDeleteOnFile("'+ dataUri +'")');
		}
	}
	this.visitFile = function(el) {
		// let dirExist 	= reHs[1].split(":");
		// disini belum selesai .... 
		window.open(uriData, "_blank");
	}
	this.newFile = function() {
		modalJudul.innerHTML = "Create New File";
		ajax.GET({
			url 	: "jc_include/async/form/form_new_file.php",
			send 	: false
		}, function(res) {
			modalBody.innerHTML = res;
		});
	}
	this.createFile = function(val) {
		ajax.POST({
			url 	: window.location.href,
			send 	: "create-file=true&file-name=" + val
		}, function(response) {
			if (response !== false) {
				let res = JSON.parse(response);
				if (res.code === 1) {
					jc_alertDialog(res.pesan, true);
					setTimeout(function() { refreshPage() }, 2000);
				}
				else {
					jc_alertDialog(res.pesan, false);
				}
			}
		});
	}
	this.newFolder = function() {
		modalJudul.innerHTML = "Create New Folder";
		ajax.GET({
			url 	: "jc_include/async/form/form_new_folder.php",
			send 	: false
		}, function(res) {
			modalBody.innerHTML = res;
		});
	}
	this.createFolder = function(val) {
		ajax.POST({
			url 	: window.location.href,
			send 	: "create-folder=true&folder-name=" + val
		}, function(response) {
			if (response !== false) {
				let res = JSON.parse(response);
				if (res.code === 1) {
					jc_alertDialog(res.pesan, true);
					setTimeout(function() { refreshPage() }, 2000);
				}
				else {
					jc_alertDialog(res.pesan, false);
				}
			}
		});
	}

	// list menu right click
	let listMenuContext =  {
		_create 	: function(elFrame) {
			let	menu 		= [];

			let open 	= newTag("button");
				open.innerHTML = '<i class="fas fa-share-square" purple></i> open';
				//open.append( newText("Open") );
				open.classList.add('btn-noOut-dark');
				open.classList.add('open');
				open.setAttribute('onclick', 'openDirList(this)');
				//open.setAttribute('dir', '');
				menu.push(open);

			// let cut 	= newTag("button");
			// 	cut.innerHTML = '<i class="fas fa-cut" green></i> cut';
			// 	//cut.append( newText("cut") );
			// 	cut.classList.add('btn-noOut-dark');
			// 	cut.classList.add('cut');
			// 	cut.setAttribute('onclick', 'cutDirList(this)');
			// 	//cut.setAttribute('dir', '');
			// 	menu.push(cut);

			// let copy 	= newTag("button");
			// 	copy.innerHTML = '<i class="far fa-clone" darkblue></i> copy';
			// 	//copy.append( newText("copy") );
			// 	copy.classList.add('btn-noOut-dark');
			// 	copy.classList.add('copy');
			// 	copy.setAttribute('onclick', 'copyDirList(this)');
			// 	//copy.setAttribute('dir', '');
			// 	menu.push(copy);

			let rename 	= newTag("button");
				rename.innerHTML = '<i class="fas fa-pencil-alt" blue></i> rename';
				//rename.append( newText("Rename") );
				rename.classList.add('btn-noOut-dark');
				rename.classList.add('rename');
				rename.setAttribute('onclick', 'renameDirList(this)');
				rename.setAttribute('target', '#modalLounch');
				menu.push(rename);

			let edit 	= newTag("button");
				edit.innerHTML = '<i class="fas fa-edit" green></i> edit';
				//edit.append( newText("Edit") );
				edit.classList.add('btn-noOut-dark');
				edit.classList.add('edit');
				edit.setAttribute('onclick', 'editDirList(this)');
				//edit.setAttribute('dir', '');
				menu.push(edit);

			let hapus 	= newTag("button");
				hapus.innerHTML = '<i class="fas fa-trash" red></i> delete';
				//hapus.append( newText("Delete") );
				hapus.classList.add('btn-noOut-dark');
				hapus.classList.add('hapus');
				//hapus.classList.add('modal-toggle');
				hapus.setAttribute('onclick', 'hapusDirList(this)');
				hapus.setAttribute('target', '#modalLounch');
				menu.push(hapus);

			menu.forEach(function(el) {
				el.classList.add('btn');
				el.style.cssText = `width: 100%; text-align: left; border-bottom: 1px #bbb solid;`;
				elFrame.append(el);
			});
				
			return elFrame;
		}
	}

	let Contextmenu = function() {
		let frmMenu 		= newTag('div');
			frmMenu.id 		= "__contextmenu";

		listMenuContext._create(frmMenu);
		document.body.append(frmMenu);

		this.setCoordinate 	= function(ev, el) {
			let item = frmMenu.querySelectorAll('button');
			item.forEach(function(elem) {
				let load = el.getAttribute('load');
				elem.setAttribute('dir', load);

				//filter hide show context menu
				if ( elem.classList.contains('edit') == true ) {
					elem.style.display = "none";

					let dataLoad 	= load.split("="),
						dataFile 	= dataLoad.pop(),
						reCekerUri	= dataFile.replace(config.setting.path, config.setting.protocol + config.setting.host);

					let isDir = new XMLHttpRequest();
						isDir.onload = function() {
							let resUrl 	= isDir.responseURL,
								uriType = resUrl.split('/');
								reUri 	= uriType[uriType.length - 1];
							if (reUri !== "") {
								elem.style.display = "";
							}
							else {
								elem.style.display = "none";
							}
						}
					isDir.open('GET', reCekerUri, true);
					isDir.send();	
				}
			});
			let cX 	= ev.clientX,
				cY 	= ev.clientY;
			frmMenu.style.cssText = `top: ${cY}px; left: ${cX}px; display: block;`;
		}
	}
	Contextmenu();

	let actionMenu = function() {
		this.openDirList = function(el) {
			let dataUri	= el.getAttribute('dir');
			let reHs    = dataUri.split('='),
				uriData = reHs[1].replace(config.setting.path, config.setting.protocol + config.setting.host);
			window.open(uriData, "_blank");
		}
		this.editDirList = function(el) {
			let dataUri	= el.getAttribute('dir');
			window.location.href = dataUri;
		}
		this.renameDirList = function(el) {
			__modalToggle(el);
			let dataUri		= el.getAttribute('dir'),
				splGetName 	= dataUri.split("/"),
				nameIs 	 	= splGetName[splGetName.length - 1];
			let	txtJudul;

			// listen class edit untuk identifikasi dir or file
			let clsEdit 	= el.parentElement.querySelector('.edit');
			clsEdit.style.display === "none" ? txtJudul = "Folder" : txtJudul = "File";
			modalJudul.innerHTML 	= "Rename " + txtJudul;
			//modalBody.innerHTML 	= "<div class='note note-danger'>Rename "+ txtJudul + " <b>" + nameIs +"</b> ?</div>";

			ajax.GET({
				url 	: "jc_include/async/form/form_rename_file.php",
				send 	: "origin-name=" + nameIs
			}, function(res) {
				modalBody.innerHTML = res;
			});

		}
		this.setRenameDirList = function(newName, originName) {
			ajax.POST({
				url 	: window.location.href,
				send 	: "rename-dir=true&new-name=" + newName + "&origin-name=" + originName
			}, function(response) {
				if (response !== false) {
					let res = JSON.parse(response);
					if (res.code === 1) {
						jc_alertDialog(res.pesan, true);
						setTimeout(function() { refreshPage() }, 2000);
					}
					else {
						jc_alertDialog(res.pesan, false);
					}
				}
			});
		}

		this.hapusDirList = function(el) {
			__modalToggle(el);

			let dataUri		= el.getAttribute('dir'),
				splGetName 	= dataUri.split("/"),
				nameIs 	 	= splGetName[splGetName.length - 1];
			let	txtJudul;

			// listen class edit untuk identifikasi dir or file
			let clsEdit 	= el.parentElement.querySelector('.edit');
			clsEdit.style.display === "none" ? txtJudul = "Folder" : txtJudul = "File";
			modalJudul.innerHTML 	= "Hapus " + txtJudul;
			
			modalBody.innerHTML 	= "<div class='note note-danger'>Are You Sure Delete "+ txtJudul + " <b>" + nameIs +"</b> ?</div>";
			let getFooterModal 		= modalBody.parentElement.querySelector('.box-footer'),
				existBtnDel 		= getFooterModal.querySelector('.setDelete');
			if (existBtnDel === null) {
				let btnDel 				= newTag('button');
					btnDel.setAttribute('onclick', 'setDelete("'+ dataUri +'", "'+ txtJudul +'")');
					btnDel.classList.add('btn');
					btnDel.classList.add('btn-danger');
					btnDel.classList.add('setDelete');
					btnDel.innerHTML 	= '<i class="fas fa-trash"></i> Delete';
				getFooterModal.prepend(btnDel);	
			}
			else {
				existBtnDel.removeAttribute('onclick');
				existBtnDel.setAttribute('onclick', 'setDelete("'+ dataUri +'", "'+ txtJudul +'")');
			}
		}
		this.setDelete = function(dtURI, type) {
			ajax.POST({
				url 	: window.location.href,
				send 	: "delete-" + type.toLowerCase() + "=" + dtURI
			}, function(response) {
				if (response !== false) {
					let res = JSON.parse(response);
					if (res.code === 1) {
						jc_alertDialog(res.pesan, true);
						setTimeout(function() { refreshPage() }, 2000);
					}
					else {
						jc_alertDialog(res.pesan, false);
					}
				}
			});
		}
	}

	let ctxMenu = query("#__contextmenu");

	queryAll('.dir-list').forEach(function(el) {
		jcEvent(el, 'contextmenu', function(ev) {
			ev.preventDefault();
			if (event.button === 2) {
				setCoordinate(ev, el);
				actionMenu();
				//new jc_app();
			}
		});
	});
	
	jcEvent( window, 'click', function() {
		if ( ctxMenu !== null ) {
			if ( ctxMenu.style.display === "block" ) {
				ctxMenu.style.display = "none";
			}
		}
	});
}

jcEvent(window, 'contextmenu', function(ev) {
	ev.preventDefault();
});

// test tanpa menunggu load
jc_import("config.json", function(res) {
	JC_Construct( JSON.parse(res) );
});

//@promise after load
// window.onload = function() {
// 	let loadPage 	= new XMLHttpRequest();
// 	loadPage.onload = function() {
// 		// load config json
		
// 	}
// 	loadPage.open('GET', window.location, true);
// 	loadPage.send();
// };





