<?php
/*	----------------------------------------------------------------------------------------
	# Project Init 
--------------------------------------------------------------------------------------------*/
// Dec Directory jc_include 
if ( !defined('INC') ) {
	define( 'INC',  dirname(__FILE__) . '/../jc_include/' );
}

// init Load class
class InitLoad {
	public function __construct() {
		$arysrc 	= explode('\\', INC);
		$this->src 	= end($arysrc); 
	}
	protected function initClass() {
		spl_autoload_register(function($class) {
			require_once "Class/". $class . ".php";
		});
	}
	protected function containerTop() {
		require ( INC . "constructor/container_top.php");
	}
	protected function containerBottom() {
		require ( INC . "constructor/container_bottom.php");
	}
	protected function constructor($file, $title = false) {
		require ( INC . "constructor/" . $file . ".php");
	}
} 

//-------------------------------------------------------------------------------------------
// include extend class initLoad
class Includer extends InitLoad {
	public function __class() {
		parent::initClass();
	}

	public function header($title) {
		parent::constructor('header', $title);
	}

	public function containerTop() {
		parent::containerTop();
	}

	public function containerBottom() {
		parent::containerBottom();
	}

	public function footer() {
		parent::constructor('footer');
	}
	public function footerContainer() {
		parent::constructor('footer_container');
	}
}