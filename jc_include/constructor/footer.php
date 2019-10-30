<?php
if (! defined('INCPATH') ) {
	define( "INCPATH", str_replace("jc_app/../", "", $this->src ) );
} 
if (! defined('JSPATH') ) {
	define( "JSPATH", INCPATH . "js/" );
}

$jsPath = glob(JSPATH . "*.js");
foreach ( $jsPath as $path ) {
	echo '
	<script type="text/javascript" src="'. $path .'"></script>';
}

echo '
</body>
</html>';
