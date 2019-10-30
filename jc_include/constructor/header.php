<?php  
if (! defined('INCPATH') ) {
	define( "INCPATH", str_replace("jc_app/../", "", $this->src ) );
}
if (! defined('CSSPATH') ) {
	define( "CSSPATH", INCPATH . "css/" );
}

if ( is_null($title) ) {
	$title = "JC Boster";
}
echo '<!DOCTYPE html>
<html>
<head>';
echo "<title>" . $title . "</title>";
echo '<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="shortcut icon" type="image/png" href="jc_include/img/img_atb/favicon.png">';

$cssPath = glob(CSSPATH . "*.css");
foreach ( $cssPath as $path ) {
	echo '
	<link rel="stylesheet" type="text/css" href="'. $path .'">';
}

echo '
<script type="text/javascript" src="'.INCPATH.'js/jc_boster.js"></script>';

echo '
</head>
<body>';
