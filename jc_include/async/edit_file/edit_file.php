<?php

require ( dirname(__FILE__) . "/../../../jc_app/init.php" );
require ( dirname(__FILE__) . "/../../../jc_app/map.php" );

if ( CONFIG["setting"]["text_highlight"] === true ) {
	$content1 	= str_replace( "t#a#b", "	", $_POST["content"] );
	$content 	= str_replace( "l#n#l", "\n", $content1);	
}
if ( CONFIG["setting"]["text_highlight"] === false ) {
	$content 	= $_POST["content"];
}

$file 		= $_POST["file"];
$Rname 		= explode("/", $file);
$name 		= end($Rname);

// set lop access file
$renderPath	= explode("/", $_SERVER["PHP_SELF"]);
array_shift($renderPath); array_pop($renderPath);

// replace host root file edit
$pathHttp 	= str_replace( CONFIG["setting"]["protocol"] . CONFIG["setting"]["host"] . "/", "", $file );

// set sum access dir
$simPat 	= "";
for($i = 0; $i < count($renderPath); $i++) {
	$simPat .= "../";
}

// def file
if (file_exists( $simPat . $pathHttp ) ) {
	$def 	= file_get_contents($simPat . $pathHttp);

	// save file
	$save 	= file_put_contents($simPat . $pathHttp, $content);

	// response
	if ( ! $save ) {
		echo json_encode(["code" => 0, "pesan" => "Gagal! Edit File"]);
	}
	else {
		$char 	= "";
		if ( (int)strlen($content) == (int)strlen($def) ) {
			$char .= "0 Chacter Dirubah";
		}
		else if ( (int)strlen($content) > (int)strlen($def) ) {
			$char .= (string)( (int)strlen($content) - (int)strlen($def) ) . " Chacter Dirubah";
		}
		else {
			$char .= (string)( (int)strlen($def) - (int)strlen($content) ) . " Chacter Dirubah";
		}
		echo json_encode(["code" => 1, "pesan" => "Sukses Edit, " . $char]);
	}
}
else {
	echo json_encode(["code" => 0, "pesan" => "Gagal! File Not Hosted"]);
}

