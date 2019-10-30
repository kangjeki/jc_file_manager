function jc_app() {
	let reStrucLayout = function() {
		const 	header 		= query('.header'),
				sidebar 	= queryAll('.sidebar'),
				container 	= query('.container');
		let iW 			 	= window.innerWidth;
		
		if (header !== null) {
			let mode = header.getAttribute('mode');
			if (mode !== null) {
				if (iW >= 767.98) {
					if (mode == 'fixed') {
						let hH = header.clientHeight;
						header.classList.add('header-fixed');
						container.style.cssText = `margin-top: ${hH}px`;
					};
				}
				else {
					container.removeAttribute('style');
				}
			};
		};
		if (sidebar !== null) {
			sidebar.forEach(function(elSidebar) {
				let mode = elSidebar.getAttribute('mode');
				if (mode !== null) {
					if (mode == "fixed") {
						let nvL = elSidebar.querySelector('.nav');
						let nvR = elSidebar.querySelector('.nav-right');
						let hH = 0;
						if (header !== null) {
							hH = header.clientHeight;
						}
						if (iW >= 767.98) {
							let	fs = elSidebar.parentElement.clientWidth;
							elSidebar.classList.add('sidebar-fixed');

							let bA 			= 0;
							let	baSelect 	= elSidebar.querySelector('.bar-account');

							if (baSelect !== null) {
								bA = elSidebar.querySelector('.bar-account').clientHeight;
								baSelect .style.cssText = `top: ${hH}px; max-width: ${nvL.parentElement.clientWidth}px`;
							}
							if (nvL !== null) {
								nvL.style.cssText = `top: ${hH + bA}px; max-width: ${nvL.parentElement.clientWidth}px;`;
							}
							if (nvR !== null) {
								nvR.style.cssText = `top: ${hH + bA}px; max-width: ${nvR.parentElement.clientWidth}px;`;
							}
						}
						else {
							if (nvL !== null) {
								nvL.removeAttribute('style');
							}
							if (nvR !== null) {
								nvR.removeAttribute('style');
							}
						}
					}
				}	
			})
			
		}	
	}
	reStrucLayout();
	jcEvent(window, 'resize', function() {reStrucLayout()});

	this.sidebarToggle = function() {
		queryAll('.sidebar-show').forEach(function(el) {
			el.classList.toggle('sidebar-show');
		})
	}

	queryAll('.btn-sidebar').forEach(function(el) {
		let target = el.getAttribute("expand-target");
		if (target !== null) {
			jcEvent(el, 'click', function() {
				query(target).classList.toggle('sidebar-show');
			});
		}
	});

	this.navbarToggle = function() {
		queryAll('.navbar-show').forEach(function(el) {
			el.classList.toggle('navbar-show');
		})
	}

	queryAll('.btn-navbar').forEach(function(el) {
		let target = el.getAttribute("expand-target");
		if (target !== null) {
			jcEvent(el, 'click', function() {
				query(target).classList.toggle('navbar-show');
			});
		}
	})

	let expandRoll = function(groupRoll) {
		let expandAct = queryAll('.expanded');
		if (expandAct !== null) {
			expandAct.forEach(function(el) {
				let roll = el.getAttribute("roll");
				if (roll !== null) {
					if (roll == groupRoll) {
						heightNode 			= 0;
						el.style.cssText 	= `height: ${heightNode.toString()}px;`;
						el.classList.remove('expanded');	
					}
				}
			});
		}
	}

	queryAll('.expand').forEach(function(el) {
		let target 		= el.getAttribute('expand-target'),
			groupRoll 	= el.getAttribute('group-roll');
		if (target !== null) {
			jcEvent(el, 'click', function() {
				let heightNode 	= 0,
					tg 			= query(target),
					node 		= tg.children,
					exAct 		= tg.classList.contains('expanded');
				if (exAct == false) {
					for (let i = 0; i < node.length; i++) {
						heightNode += node[i].offsetHeight;
					}
					if (groupRoll !== null) {
						expandRoll(groupRoll);
					}
					tg.setAttribute('style', `height: ${heightNode.toString()}px;`);
					tg.classList.add('expanded');
				}
				else {
					heightNode 			= 0;
					tg.style.cssText 	= `height: ${heightNode.toString()}px;`;
					tg.classList.remove('expanded');
				}
			});
		}
	});

	queryAll('.table-striped').forEach(function(el) {
		let row = el.querySelectorAll('tr');
		row.forEach(function(elRow, num) {
			if (num % 2 == 0) {
				elRow.style.cssText = 'background: #efefef;';
			}
		});
	});

	queryAll('.tooltips').forEach(function(el, indk) {
		let atb 	= el.attributes;
		let place 	= atb.place.nodeValue,
			tips 	= atb.tips.nodeValue,
			mode 	= atb.mode.nodeValue;

		if (place == null || place == undefined) {
			place = "right";
		}
		if (tips == null || tips == undefined) {
			tips = "tooltips active";
		}
		if (mode == null || mode == undefined) {
			mode = "normal";
		}

		let elem 	= newTag('div'),
			txt 	= newText(tips);

		elem.classList.add('tips-active');
		elem.appendChild(txt);
		elem.classList.add('actTip-' + indk.toString());

		let postTips = function(clX, clY, elem, place, callback) {
			let	elH 	= elem.offsetHeight,
				elW 	= elem.offsetWidth,
				elPd 	= 10;

			let elX, elY;
			if (place == "top") {
				elY = (clY - elH) - elPd;
				elX = clX - (elW/2); 
			}
			else if (place == "right") {
				elY = clY - (elH/2);
				elX = clX + (elPd * 2);
			}
			else if (place == "bottom") {
				elY = (clY + elH);
				elX = clX - (elW/2) + 10; 
			}
			else if (place == "left") {
				elY = clY - (elH/2);
				elX = (clX - elW) - elPd;
			}
			if (typeof callback == "function") {
				callback({x: elX, y: elY});
			}
		}

		jcEvent(el, 'mousemove', function(e) {
			let clY 	= e.clientY,
				clX 	= e.clientX;
			let elX, elY;
			if (query('.actTip-' + indk.toString()) == null) {
				el.appendChild(elem);

				postTips(clX, clY, elem, place, function(res) {
					elX = res.x; elY = res.y;
				});
				elem.classList.add('tips-'+mode);
				elem.style.cssText = `padding: 5px 10px; top: ${elY}px; left: ${elX}px; z-index:99999999;`;
			}
			else {
				postTips(clX, clY, elem, place, function(res) {
					elX = res.x; elY = res.y;
				});
				elem.style.cssText = `padding: 5px 10px; top: ${elY}px; left: ${elX}px; z-index:99999999;`;
			}
			jcEvent(el, 'mouseleave', function() {
				elem.remove();
			});
		});
	});

	let toggler_hideDropdown = function (drMn) {
		document.body.onclick = function(ev) {
			if (drMn.classList.contains('show') === true) {
				let atbRoll = drMn.getAttribute("roll");
				if (atbRoll !== null) {
					if (atbRoll == "active") {
						drMn.classList.toggle('show');
					}
				}
			}
		}
	}

	queryAll('.dropdown').forEach(function(eldr) {
		let btnTgl 	= eldr.querySelector('.dropdown-toggle'),
			drMn 	= eldr.querySelector('.dropdown-menu'),
			drCls 	= eldr.querySelector('.dropdown-close');

		jcEvent(btnTgl, 'click', function(ev) {
			if (drMn.classList.contains('show') === true) {
				drMn.classList.toggle('show');
			}
			else {
				setTimeout(function() {
					drMn.classList.toggle('show');
				}, 10);
				toggler_hideDropdown(drMn);	
			}
		});
		if (drCls !== null) {
			jcEvent(drCls, 'click', function() {
				drMn.classList.toggle('show');
			});	
		}
	});
	queryAll('.dropright').forEach(function(eldr) {
		let btnTgl 	= eldr.querySelector('.dropdown-toggle'),
			drMn 	= eldr.querySelector('.dropdown-menu'),
			drCls 	= eldr.querySelector('.dropdown-close');

		jcEvent(btnTgl, 'click', function() {
			if (drMn.classList.contains('show') === true) {
				drMn.classList.toggle('show');
			}
			else {
				setTimeout(function() {
					drMn.classList.toggle('show');
				}, 10);
				toggler_hideDropdown(drMn);	
			}
			let cW = btnTgl.clientWidth,
				cH = btnTgl.clientHeight;
			drMn.style.cssText = `top: 0; left: ${cW}px`;
		});
		if (drCls !== null) {
			jcEvent(drCls, 'click', function() {
				drMn.classList.toggle('show');
			});	
		}
	});

	queryAll('.dropleft').forEach(function(eldr) {
		let btnTgl 	= eldr.querySelector('.dropdown-toggle'),
			drMn 	= eldr.querySelector('.dropdown-menu'),
			drCls 	= eldr.querySelector('.dropdown-close');

		jcEvent(btnTgl, 'click', function() {
			if (drMn.classList.contains('show') === true) {
				drMn.classList.toggle('show');
			}
			else {
				setTimeout(function() {
					drMn.classList.toggle('show');
				}, 10);
				toggler_hideDropdown(drMn);	
			}
			let cW = btnTgl.clientWidth,
				cH = btnTgl.clientHeight;
			drMn.style.cssText = `top: 0; right: ${cW}px`;
		});
		if (drCls !== null) {
			jcEvent(drCls, 'click', function() {
				drMn.classList.toggle('show');
			});	
		}
	});

	queryAll('.droptop').forEach(function(eldr) {
		let btnTgl 	= eldr.querySelector('.dropdown-toggle'),
			drMn 	= eldr.querySelector('.dropdown-menu'),
			drCls 	= eldr.querySelector('.dropdown-close');

		jcEvent(btnTgl, 'click', function() {
			if (drMn.classList.contains('show') === true) {
				drMn.classList.toggle('show');
			}
			else {
				setTimeout(function() {
					drMn.classList.toggle('show');
				}, 10);
				toggler_hideDropdown(drMn);	
			}
			let cW = btnTgl.clientWidth,
				cH = btnTgl.clientHeight;
			drMn.style.cssText = `bottom: ${cH}px; left: 0`;
		});
		if (drCls !== null) {
			jcEvent(drCls, 'click', function() {
				drMn.classList.toggle('show');
			});	
		}
	});

	queryAll('.modal-toggle').forEach(function(el) {
		jcEvent(el, 'click', function() {
			let target 	= el.attributes.target.nodeValue,
				modal 	= query(target),
				modalW 	= modal.getAttribute('modal-width');
			modal.classList.toggle('modal-show');
			modal.querySelector('.modal-block').style.cssText = `max-width: ${modalW}`;	
		});
	});

	let actionCancleEditable = function(slc) {
		slc.removeAttribute('contentEditable');
		slc.parentElement.parentElement.querySelectorAll('.btn-save-content').forEach(el => { el.remove() });
		slc.parentElement.parentElement.querySelectorAll('.btn-cancle-save').forEach(el => { el.remove() });
		slc.parentElement.parentElement.querySelectorAll('.frame-btn-editable').forEach(el => { el.remove() });
	}

	let actionEditable = function(slc) {
		let frmBtnEditable 	= newTag('div');
			frmBtnEditable.classList.add('frame-btn-editable');

		let btnSave 	= newTag('btn'),
			txtBtnSave 	= newText('Simpan');
			btnSave.classList.add('btn');
			btnSave.classList.add('btn-success');
			btnSave.classList.add('btn-save-content');
			btnSave.setAttribute('target-save', slc.id);

		let btnCancle 		= newTag('btn'),
			txtBtnCancle 	= newText('Batal');
			btnCancle.classList.add('btn');
			btnCancle.classList.add('btn-info');
			btnCancle.classList.add('btn-cancle-save');
			btnCancle.classList.add('switch-editable');
			btnSave.setAttribute('switch-target', slc.id);

		btnSave.append(txtBtnSave);
		btnCancle.append(txtBtnCancle);

		frmBtnEditable.appendChild(btnSave);
		frmBtnEditable.appendChild(btnCancle);
		slc.parentElement.parentElement.appendChild(frmBtnEditable);

		btnCancle.onclick = function() {
			actionCancleEditable(slc);
		}
	}

	queryAll(".switch-editable").forEach(function(el) {
		el.onclick = function() {
			el.style.cssText = `position: relative`;
			let trg 	= el.getAttribute('switch-target'),
				slc 	= query(trg);
			// let optPost = slc.parentElement.style.position;
			
			// if ( optPost !== 'absolute' && optPost !== 'fixed') {
			// 	slc.parentElement.style.cssText = 'position:relative;';
			// }
				
			if (slc.getAttribute('contentEditable') === null) {
				slc.setAttribute('contentEditable', 'true');
				slc.focus();
				actionEditable(slc);
			}
			else {
				actionCancleEditable(slc);
			}
		}
	});
	// let JC_editText = function(zonEdit, seter) {
	// 	let range 	= document.createRange();
	// 		range.selectNodeContents(zonEdit);

	// 	let sel 	= document.getSelection();
	// 		//sel.removeAllRanges(); //---> untuk select semua
	// 		//sel.addRange(range); //---> untuk select semua
	// 	if (sel.focusNode !== null) {
	// 		let parNd 		= sel.focusNode.parentElement,
	// 			parNHTML 	= parNd.innerText,
	// 			txNd 		= sel.focusNode.nodeValue,
	// 			fNd 		= sel.baseOffset,
	// 			eNd 		= sel.focusOffset;

	// 		let nTx 		= parNHTML.substring(fNd, eNd);
	// 		let setEdit;

	// 		if (nTx !== "" && parNd !== undefined  && parNd !== null) {
	// 			if (seter == "bold") {
	// 				console.log(typeof parNHTML[fNd]);
	// 				setEdit 	= parNHTML.replace(parNHTML[fNd], '<b>' + parNHTML[fNd]).replace(parNHTML[eNd], parNHTML[eNd] + '</b>');
	// 			}
	// 			else if (seter == "italic") {
	// 				setEdit 	= parNHTML.replace(parNHTML[fNd], '<i>' + parNHTML[fNd]).replace(parNHTML[eNd], parNHTML[eNd] + '</i>');
	// 			}
	// 			parNd.innerHTML = setEdit;	
	// 			console.log(fNd + " " + eNd);
	// 		}
	// 	}
		
	// };

	// queryAll('.box-editor').forEach(function(zonTx) {
	// 	jcEvent(zonTx, 'select', function() {
	// 		console.log(zonTx.innerHTML);
	// 	});
	// });

	// queryAll('.btn-edit-bold').forEach(function(btnB) {
	// 	jcEvent(btnB, 'click', function() {
	// 		let zonEdit = btnB.parentElement.parentElement.querySelector('.box-editor');
	// 		JC_editText(zonEdit, 'bold');
	// 	});
	// });
	// queryAll('.btn-edit-italic').forEach(function(btnI) {
	// 	jcEvent(btnI, 'click', function() {
	// 		let zonEdit = btnI.parentElement.parentElement.querySelector('.box-editor');
	// 		JC_editText(zonEdit, 'italic');
	// 	});
	// });

}

function pageLoaderStart(ivalBack) {
	if (query('.loader-pack') !== null) {
		query('.page-loader').style.display = "block";
		let loaderItem 	= queryAll('.loader-item'),
			wL 			= 0,
			w_anm 		= 1;
		if (loaderItem !== null) {
			let invLoader = setInterval(function() {
				loaderItem.forEach(function(el) {
					if (el.classList.contains('loader-item-animate') == true) {
						el.classList.remove("loader-item-animate");
					}
					wL += w_anm;
					if (wL == loaderItem.length - 1) {
						w_anm = -1;
					}
					if (wL == 0){
						w_anm = 1;
					}
				});
				loaderItem[wL].classList.add('loader-item-animate')
			}, 300);

			if (typeof ivalBack == "function") {
				ivalBack(invLoader);
			}
		}
	}
}
pageLoaderStart(function(resIval) {
	jcEvent(window, 'load', function() {
		clearInterval(resIval);
		query('.page-loader').style.display = "none";
	});
});

function spaModel() {
	queryAll('.spa-model').forEach(function(el) {
		let mode 		= el.getAttribute('mode'),
			load 		= el.getAttribute('load'),
			inPage 		= el.getAttribute('active-page'),
			inner_id 	= el.getAttribute('inner-id');
		jcEvent(el, 'click', function() {
			if (load !== null) {
				if (mode !== null) {
					if (mode == "async") {
						new jc_app();
						ajax.GET({
							url 	: load,
							send 	: false
						}, function(res) {
							query(inner_id).innerHTML = res;
							new jc_app();
							new JC_Construct();
							sidebarToggle();
						});
					}
					else {
						window.location.href = load;
					}
				};		
			}
		});
	});
}

jcEvent(window, 'load', function() {
	spaModel();
	jc_app();
})

