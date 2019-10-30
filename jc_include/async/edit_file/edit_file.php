<?php
$content 	= $_POST["content"];
$file 		= $_POST["file"];
$Rname 		= explode("/", $file);
$name 		= end($Rname);

$pathRe 	= str_replace("C:\\xampp\\htdocs", "", __DIR__);
$pathHttp 	= str_replace("http://localhost/", "", $file);

$pathIt 	= explode("\\", $pathRe);

$simPat 	= "";
for($i = 1; $i < count($pathIt); $i++) {
	$simPat .= "../";
}

file_put_contents($simPat . $pathHttp, $content);

echo "true";

