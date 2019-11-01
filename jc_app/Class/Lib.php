<?php
/*	----------------------------------------------------------------------------------------
	# Labrary Project
	# Author : JC Programs
--------------------------------------------------------------------------------------------*/
class Lib {
	public function __construct() {
		//load config json
		$pathConfig 	= (__DIR__ . "/../../config.json");
		if ( ! defined("CONFIG") ) {
			define("CONFIG", json_decode( file_get_contents($pathConfig), TRUE ) );
		}

		// var get position directory
		$this->querySTR 	= $_SERVER["QUERY_STRING"];
		$this->contentPath 	= CONFIG["setting"]["content_path"];
	}

	// ----------------------------------------------------------------------------------------------------------
	private function dirPosition() {
		$renderPath	= explode("/", $_SERVER["PHP_SELF"]);
		array_shift($renderPath); array_pop($renderPath);

		$pathHttp 	= explode( CONFIG["setting"]["path"] . "/", $this->querySTR );

		$simPat 	= "";
		for($i = 0; $i < count($renderPath); $i++) {
			$simPat .= "../";
		}
		return $simPat . $pathHttp[1];
	}

	// ----------------------------------------------------------------------------------------------------------
	public function createNewFile($data) {
		$fileName 		= htmlspecialchars($data["file-name"]);
		$pathPosition 	= $this->dirPosition();
		
		if ( strlen($this->querySTR) === 0 ) {
			$reCtnPath = "";
			if ( $this->contentPath === 0 ) {
				$reCtnPath = $this->contentPath;
			}
			else {
				$reCtnPath = $this->contentPath . "/";
			}
			
			// --------------------------------------------
			if ( ! file_exists( $reCtnPath . $fileName ) ) {
				file_put_contents( $reCtnPath . $fileName, "JC New File");
				echo json_encode(["code" => 1, "pesan" => "File ". $fileName ." Success Created"]);
			}
			else {
				echo json_encode(["code" => 0, "pesan" => "Failed!, File Name ". $fileName ." is Exist!"]);
			}
		}
		else {
			if ( ! file_exists( $pathPosition . "/" . $fileName ) ) {
				file_put_contents( $pathPosition . "/" . $fileName, "JC New File");
				echo json_encode(["code" => 1, "pesan" => "File ". $fileName ." Success Created"]);
			}
			else {
				echo json_encode(["code" => 0, "pesan" => "Failed!, File Name ". $fileName ." is Exist!"]);
			}
		}
	}

	// ----------------------------------------------------------------------------------------------------------
	public function createNewFolder($data) {
		$folderName 	= htmlspecialchars($data["folder-name"]);
		$pathPosition 	= $this->dirPosition();
		
		if ( strlen($this->querySTR) === 0 ) {
			$reCtnPath = "";
			if ($this->contentPath === 0) {
				$reCtnPath = $this->contentPath;
			}
			else {
				$reCtnPath = $this->contentPath . "/";
			}
			if ( ! file_exists( $reCtnPath . $folderName ) ) {
				mkdir( $reCtnPath . $folderName );
				echo json_encode(["code" => 1, "pesan" => "Folder ". $folderName ." Success Created"]);
			}
			else {
				echo json_encode(["code" => 0, "pesan" => "Folder Name ". $folderName ." is Exist!"]);
			}
		}
		else {
			if ( ! file_exists( $pathPosition . "/" . $folderName ) ) {
				mkdir( $pathPosition . "/" . $folderName );
				echo json_encode(["code" => 1, "pesan" => "Folder ". $folderName ." Success Created"]);
			}
			else {
				echo json_encode(["code" => 0, "pesan" => "Folder Name ". $folderName ." is Exist!"]);
			}
		}
	}

	// ----------------------------------------------------------------------------------------------------------
	private function deleteDirectory($dirPath) {
		if ( is_dir($dirPath) ) {
			$objects = scandir($dirPath);
			foreach ( $objects as $object ) {
				if ( $object != "." && $object !=".." ) {
					if ( filetype( $dirPath . DIRECTORY_SEPARATOR . $object ) == "dir" ) {
						$this->deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
					} else {
						unlink( $dirPath . DIRECTORY_SEPARATOR . $object );
					}
				}
			}
			reset( $objects );
			rmdir( $dirPath );
			if ( file_exists( $dirPath ) ) {
				return false;
			}
			else {
				return true;
			}
		}
	}

	// ----------------------------------------------------------------------------------------------------------
	public function deleteFolder($data) {
		$resfolderDel 	= explode( "/", htmlspecialchars( $_POST["delete-folder"] ) );
		$folderDelete 	= end($resfolderDel);
		$pathPosition 	= $this->dirPosition();

		//cek default dir content path position
		if ( strlen($this->querySTR) === 0 ) {
			$reCtnPath = "";
			if ( $this->contentPath === 0 ) {
				$reCtnPath = $this->contentPath;
			}
			else {
				$reCtnPath = $this->contentPath . "/";
			}
			// exec del
			if ( file_exists( $reCtnPath . $folderDelete ) ) {
				$setDel = $this->deleteDirectory( $reCtnPath . $folderDelete );
				if ( $setDel === true ) {
					echo json_encode(["code" => 1, "pesan" => "Delete Directory Success"]);
				}
				else {
					echo json_encode(["code" => 0, "pesan" => "ERROR, Delete Recursive!"]);
				}
			}
			else {
				echo json_encode(["code" => 0, "pesan" => "ERROR, Directory Not Found!"]);
			}
		}
		else {
			// exec del
			if ( file_exists( $pathPosition . "/" . $folderDelete ) ) {
				$setDel = $this->deleteDirectory( $pathPosition . "/" . $folderDelete );
				if ( $setDel === true ) {
					echo json_encode(["code" => 1, "pesan" => "Delete Directory Success"]);
				}
				else {
					echo json_encode(["code" => 0, "pesan" => "ERROR, Delete Recursive!"]);
				}
			}
			else {
				echo json_encode(["code" => 0, "pesan" => "ERROR, Directory Not Found!"]);
			}
		}
	}

	// ----------------------------------------------------------------------------------------------------------
	public function deleteFile($data) {
		$resfileDel 	= explode( "/", htmlspecialchars( $_POST["delete-file"] ) );
		$fileDelete 	= end($resfileDel);
		$pathPosition 	= $this->dirPosition();

		//cek default dir content path position
		if ( strlen($this->querySTR) === 0 ) {
			$reCtnPath = "";
			if ( $this->contentPath === 0 ) {
				$reCtnPath = $this->contentPath;
			}
			else {
				$reCtnPath = $this->contentPath . "/";
			}
			//exec del
			if ( file_exists( $reCtnPath . $fileDelete ) ) {
				unlink( $reCtnPath . $fileDelete );
				echo json_encode(["code" => 1, "pesan" => "Delete File Success"]);
			}
			else {
				echo json_encode(["code" => 0, "pesan" => "ERROR, File Not Found!"]);
			}
		}
		else {
			//exec del
			if ( file_exists( $pathPosition . "/" . $fileDelete ) ) {
				unlink( $pathPosition . "/" . $fileDelete );
				echo json_encode(["code" => 1, "pesan" => "Delete File Success"]);
			}
			else {
				echo json_encode(["code" => 0, "pesan" => "ERROR, File Not Found!"]);
			}
		}
	}

	// ----------------------------------------------------------------------------------------------------------
	public function dirSize($size) {
		$reSize 	= "";
		if ( (double)$size > 999 && (double)$size <= 999999 ) {
			$reSize = (string)( round( (double)$size / 1000, 2 ) ) . " KB";
		}
		else if ( (double)$size > 999999 && (double)$size <= 999999999 ) {
			$reSize = (string)( round( (double)$size / 1000000, 2 ) ) . " MB";
		}
		else if ( (double)$size > 999999999 && (double)$size <= 999999999999 ) {
			$reSize = (string)( round( (double)$size / 1000000000, 2 ) ) . " GB";
		}
		else {
			$reSize = (string)( (double)$size ) . " B";
		}
		return $reSize;
	}

	// ----------------------------------------------------------------------------------------------------------
	public function convertText($data) {
		if ( CONFIG["setting"]["text_highlight"] === true ) {
			$data 	= str_replace("</", ":TA2:", $data);
			$data 	= str_replace("<", ":TA1:", $data);
			$data 	= str_replace(">", ":TE:", $data);

			preg_match_all('!"[a-z-A-Z-0-9- -.-/]*"!', $data, $mat2);
			foreach ($mat2[0] as $fin2) {
				$data 	= str_replace($fin2, ':of:'.$fin2.':eof:', $data);
			}
			$data 	= str_replace(':of::of:', ':of:', $data);
			$data 	= str_replace(':eof::eof:', ':eof:', $data);

			preg_match_all('! [a-z-A-Z-0-9- -.-/]*=!', $data, $mat1);
			foreach ($mat1[0] as $fin1) {
				$data 	= str_replace($fin1, ':lf:'.$fin1.':elf:', $data);
			}
			$data 	= str_replace(':lf::lf:', ':lf:', $data);
			$data 	= str_replace(':elf::elf:', ':elf:', $data);


			preg_match_all('!:TA1:[a-z-A-Z-0-9- -.-/]*:lf:!', $data, $mat3);
			foreach ($mat3[0] as $fin3) {
				$re 	= str_replace(":lf:", "", $fin3);
				$data 	= str_replace($fin3, ':bf:'.$re.':ebf::lf:', $data);
			}

			preg_match_all('!:TA2:[a-z-A-Z-0-9- -.-/]*:TE:!', $data, $mat4);
			foreach ($mat4[0] as $fin4) {
				$re2 	= str_replace(":TA2:", ":TA1:/", $fin4);
				$re2 	= str_replace(":TE:", "", $re2);
				$data 	= str_replace($fin4, ':bf:'.$re2.':ebf::TE:', $data);
			}

			$data = str_replace(":elf:", '</span>', $data);
			$data = str_replace(":eof:", '</span>', $data);
			$data = str_replace(":ebf:", '</span>', $data);

			$data = str_replace(":lf:", '<span class="cl">', $data);
			$data = str_replace(":of:", '<span class="co">', $data);
			$data = str_replace(":bf:", '<span class="cb">', $data);

			$data = str_replace(":TE:", '<span class="cb">></span>', $data);
			$data = str_replace(":TA1:", '<span class="cb"><</span>', $data);


			$data 	= str_replace("\n", "<br>", $data);
			$hasil 	= str_replace("	", "&nbsp;", $data);

			$tagAct = "div";
		}
		else if ( CONFIG["setting"]["text_highlight"] === false ) {
			$hasil 	= $data;
			$tagAct = "xmp";
			//$data 	= str_replace("\n", "<br>", $data);
			//$hasil 	= str_replace("	", "<tab-1>", $data);
		}
		
		return "<div id='out'><button class='btn btn-noOut-info' onclick='editFile(this)'><i class='fas fa-edit'></i> edit</button><button class='btn btn-noOut-info' onclick='saveEditFile(this)'><i class='fas fa-save'></i> save</button><button class='btn btn-noOut-info' onclick='visitFile(this)'><i class='fas fa-eye'></i> visit open</button><div class='clear'></div><". $tagAct ." class='code-block' contenteditable='false'>" . $hasil . "</". $tagAct ."></div>";
	}

	// ----------------------------------------------------------------------------------------------------------
	private $totalSize 		= 0,
			$totalFolder 	= 0,
			$totalFile 		= 0,
			$hirarky 		= [],
			$root 			= "",
			$type 			= "",
			$name 			= "",
			$countAccess 	= 0;

	// ----------------------------------------------------------------------------------------------------------
	private function realFileSize($path) {
		if ( ! file_exists($path) )
		return false;

		$size = filesize($path);
		return $size;
	}
	
	// ----------------------------------------------------------------------------------------------------------
	private function recursiveInfo($path) {
		if ( is_dir($path) === true ) {
			$this->totalFolder += 1;

			$_dir = glob($path . "/*");
			foreach ( $_dir as $file ) {

				$this->recursiveInfo($file);
				$reString 			= explode( $this->root, $file );
				$dataPath 			= explode( "/", end($reString) );
				if ( count($dataPath) == 2 ) {
					$this->hirarky[] 	= $this->root . "/" . end($dataPath);
				}					
			}
		}
		else {
			$calculate 			= $this->realFileSize($path);
			$this->totalSize 	+= $calculate;
			$this->totalFile 	+= 1;
		}
	}

	// ----------------------------------------------------------------------------------------------------------
	public function directoryInfo($path = (__DIR__ . "../../../") ) {
		$this->root = ( __DIR__ . "../../../" );

		if ($path == "") {
			$path = $this->root;
		}
		else {
			$this->root = $path;
		}

		$rootName 	= explode("\\", $path);
		array_pop($rootName); array_pop($rootName);

		if ( $rootName ) {
			$this->name 		= end($rootName);
			$this->countAccess 	= 1;
			$this->recursiveInfo( "../" . $this->name);
		}

		else {
			$readRoot 			= explode("/", $path);
			$this->name 		= end($readRoot);
			$this->countAccess 	= count($readRoot);
			$this->recursiveInfo($path);
		}	

		if ( is_dir( $path ) ) {
			$this->type = "Folder";
		}
		else {
			$pathFileExist 	= explode( "/", $path );
			$reExist 		= explode( ".", end($pathFileExist) );
			$this->type 	= "." . end($reExist);
		}

		return [
			"root" 		=> $this->root, 
			"name" 		=> $this->name, 
			"type" 		=> $this->type, 
			"size" 		=> $this->totalSize, 
			"file" 		=> $this->totalFile, 
			"folder" 	=> ( $this->totalFolder - 1 ), 
			"hirarky" 	=> $this->hirarky
		];
	}

	// ----------------------------------------------------------------------------------------------------------
	public function exist_fileType($ext) {
		// load mimeType on config
		$jenis 		= CONFIG["mimeType"]["type"];
		$mime 		= CONFIG["mimeType"]["extensions"];

		$fileType = strtolower($ext);
		$fileType = str_replace(".", "", $fileType);
		$dataType = [];

		foreach ($mime as $key => $value) {
			foreach( $value as $type ) {
				if ( $type === $fileType ) {
					$dataType["jenis"]= $jenis[$key];
					$dataType["exist"]= $type;
				}
			}
		}
		if ( ! isset( $dataType["jenis"] ) ) {
			$dataType["jenis"] = CONFIG["setting"]["default_mime_type"];
		}
		if ( ! isset( $dataType["exist"] ) ) {
			$dataType["exist"] = CONFIG["setting"]["default_file_read"];
		}
		return $dataType;
	}

}