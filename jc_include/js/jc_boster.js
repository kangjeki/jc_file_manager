(function() { 
	const doc 	= document; 
	Window.prototype.query = function(tg) {
		return doc.querySelector(tg); 
	}
	Window.prototype.queryAll = function(tg) {
		return doc.querySelectorAll(tg); 
	}
	Window.prototype.jcEvent = function(el, eventName, callback) {
		el.addEventListener(eventName, function(ev) {
			callback(ev);
		});
	}
	Window.prototype.newTag = function(el) {
		return doc.createElement(el);
	}
	Window.prototype.newText = function(tx) {
		return doc.createTextNode(tx);
	}
	Window.prototype.refreshPage = function() {
		let loc = window.location;
		window.location.href = loc;
	}
	Window.prototype.backPage = function() {
		window.history.back();
	}
	this.ajax = {
		GET : (cf, res) => {const xhr = new XMLHttpRequest(); xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					res(xhr.responseText);
					//console.log(xhr);
				}
				else if (xhr.status == 404) {
					res(false);
				}

			};
			let sn = ""; if (cf.send){sn = "?" + cf.send};
			xhr.open('GET', cf.url + sn, true);
			xhr.send();

			
		},
		POST : (cf, res) => {
			const xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					res(xhr.responseText);
				}
				else if (xhr.status == 404) {
					res(false);
				};

			};
			xhr.open('POST', cf.url, true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.send(cf.send);
		},
		UPLOAD : (cf, res) => {
			const xhr = new XMLHttpRequest();
			xhr.onreadystatechange = function() {
				if (xhr.readyState == 4 && xhr.status == 200) {
					res(xhr.responseText);
				};
			};
			const form 	= new FormData();
			const input = cf.send;
			const file 	= input.files[0];
			const index = cf.send.getAttribute('name');

			form.append(index, file);

			xhr.open('POST', cf.url, true);
			xhr.send(form);
		}
	}

	this.jc_alert = function(txt, mode) {
		let wk = 2000;
		let el = doc.createElement('div');
			el.innerHTML = txt;
			el.style.cssText = `
				margin: auto; padding: 5px;
				position: fixed;
				top: 0; right: 0; left: 0; bottom: 0;
				width: 30%;
				align-items: center;
				border: 1px #ddd solid;
				border-radius: 5px;
				text-align: center;
				overflow: hidden;
				z-index: 55555555;
				max-height: 40px;
				line-height: 40px;
				white-space: nowrap;
				text-overflow: ellipsis;
			`;
		doc.body.appendChild(el);

		if (mode == undefined) {
			el.style.background = "#008000";
			el.style.color 		= "#fff";
			wk = 2000;
		}
		else if (mode == true) {
			el.style.background = "#008000";
			el.style.color 		= "#fff";
			wk = 2000;
		}
		else if (mode == false) {
			el.style.background = "#D31919";
			el.style.color 		= "#fff";
			wk = 3000;
		}

		setTimeout(function() {
			el.remove();
		}, wk);	
	}

	this.jc_alertMobile = function(txt, mode) {
		let wk = 2000;
		let el = doc.createElement('div');
			el.innerHTML = txt;
			el.style.cssText = `
				margin: auto; padding: 5px;
				position: fixed;
				top: 0; right: 0; left: 0; bottom: 0;
				width: 65%;
				line-height: 40px;
				max-height: 40px;
				align-items: center;
				border: 1px #ddd solid;
				border-radius: 5px;
				text-align: center;
				overflow: hidden;
				z-index: 55555555;
				white-space: nowrap;
				text-overflow: ellipsis;
			`;
		doc.body.appendChild(el);

		if (mode == undefined) {
			el.style.background = "#008000";
			el.style.color 		= "#fff";
			wk = 2000;
		}
		else if (mode == true) {
			el.style.background = "#008000";
			el.style.color 		= "#fff";
			wk = 2000;
		}
		else if (mode == false) {
			el.style.background = "#D31919";
			el.style.color 		= "#fff";
			wk = 3000;
		}

		setTimeout(function() {
			el.remove();
		}, wk);	
	}
	//<i class="fas fa-check"></i>
	this.jc_alertDialog = function(txt, mode) {
		let wk = 2000;
		let ie = "";
		if( mode == undefined ) { ie = "success" };
		mode == false ? ie = "danger" : ie = "success";
		let fD = doc.createElement('div');
			fD.classList.add('box-dialog');
			fD.setAttribute('alert', ie);

		let iH = window.innerHeight,
			iW = window.innerWidth,
			iY = (iH - 60) / 2,
			iX = (iW - 390) / 2;

			console.log(iX);

		fD.style.cssText = `
			position: fixed;
			z-index: 555555;
			top: ${(iY-10).toString()}px; 
			right: ${iX.toString()}px; 
			left: ${iX.toString()}px; 
			bottom: ${iY.toString()}px;
		`;
		let jns;
		ie == "danger" ? jns = 'times' : jns = 'check';

		let fI = doc.createElement('div');
			fI.classList.add('icon-dialog');
			fI.innerHTML = `<i class="fas fa-${jns}"></i>`;

		let fT = doc.createElement('div');
			fT.classList.add('text-dialog');
			fT.innerHTML = txt;

		fD.appendChild(fI);
		fD.appendChild(fT);
		doc.body.appendChild(fD);

		setTimeout(function() {
			fD.remove();
		}, wk);	
	}
	this.jc_import = function(uri, cb) {
		fetch(uri).then(function(r) { 
			r.text().then(function(res) {
				if (typeof cb == "function") { cb(res) }
			}).catch(function(er) { console.log(er) })
		}).catch(function(e) {console.log(e)})
	}
	// close modal
	this.closeModal = function(targetId) {
		query(targetId).classList.toggle('modal-show');
	}

	 this.getFullScreen = function(elem) {
		if (elem.requestFullscreen) {
			elem.requestFullscreen();
		} 
		else if (elem.mozRequestFullScreen) { /* Firefox */
			elem.mozRequestFullScreen();
		} 
		else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
			elem.webkitRequestFullscreen();
		} 
		else if (elem.msRequestFullscreen) { /* IE/Edge */
			elem.msRequestFullscreen();
		}
	}

	this.closeFullScreen = function() {
		if (document.exitFullscreen) {
			document.exitFullscreen();
		} 
		else if (document.mozCancelFullScreen) {
			document.mozCancelFullScreen();
		} 
		else if (document.webkitExitFullscreen) {
			document.webkitExitFullscreen();
		} 
		else if (document.msExitFullscreen) {
			document.msExitFullscreen();
		}
	}

}());