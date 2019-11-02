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

		if ( ! defined("PLUGIN") ) {
			define("PLUGIN", __DIR__ . "/../../jc_include/plugin/" );
		}

		// var get exist position directory
		$this->querySTR 	= $_SERVER["QUERY_STRING"];
		$this->contentPath 	= CONFIG["setting"]["content_path"];
	}

	// ----------------------------------------------------------------------------------------------------------
	private function _dirPosition() {
		$renderPath	= explode("/", $_SERVER["PHP_SELF"] );
		array_shift($renderPath); array_pop($renderPath);

		$pathHttp 	= explode( CONFIG["setting"]["path"] . "/", $this->querySTR );

		$simPat 	= "";
		for( $i = 0; $i < count( $renderPath ); $i++ ) {
			$simPat .= "../";
		}
		return $simPat . $pathHttp[1];
	}

	public function renameDirList($data) {
		$newName 		= htmlspecialchars( $data["new-name"] );
		$originName 	= htmlspecialchars( $data["origin-name"] ); 
		$pathPosition 	= $this->_dirPosition();

		if ( file_exists( $pathPosition . "/" . $originName ) ) {
			$setRename 		= rename( $pathPosition . "/" . $originName, $pathPosition . "/" . $newName );
			if ( $setRename === TRUE ) {
				echo json_encode( ["code" => 1, "pesan" => "File ". $newName ." Success Renamed"] );
			}
			else {
				echo json_encode( ["code" => 0, "pesan" => "Failed!, Reaname ". $fileName ." !"] );
			}
		}
		else {
			echo json_encode( ["code" => 0, "pesan" => "Failed!, File Name ". $originName ." not found!"] );
		}
	}

	// ----------------------------------------------------------------------------------------------------------
	public function createNewFile($data) {
		$fileName 		= htmlspecialchars( $data["file-name"] );
		$pathPosition 	= $this->_dirPosition();
		
		if ( strlen( $this->querySTR ) === 0 ) {
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
				echo json_encode( ["code" => 1, "pesan" => "File ". $fileName ." Success Created"] );
			}
			else {
				echo json_encode( ["code" => 0, "pesan" => "Failed!, File Name ". $fileName ." is Exist!"] );
			}
		}
		else {
			if ( ! file_exists( $pathPosition . "/" . $fileName ) ) {
				file_put_contents( $pathPosition . "/" . $fileName, "JC New File");
				echo json_encode( ["code" => 1, "pesan" => "File ". $fileName ." Success Created"] );
			}
			else {
				echo json_encode( ["code" => 0, "pesan" => "Failed!, File Name ". $fileName ." is Exist!"] );
			}
		}
	}

	// ----------------------------------------------------------------------------------------------------------
	public function createNewFolder($data) {
		$folderName 	= htmlspecialchars( $data["folder-name"] );
		$pathPosition 	= $this->_dirPosition();
		
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
				echo json_encode( ["code" => 1, "pesan" => "Folder ". $folderName ." Success Created"] );
			}
			else {
				echo json_encode( ["code" => 0, "pesan" => "Folder Name ". $folderName ." is Exist!"] );
			}
		}
		else {
			if ( ! file_exists( $pathPosition . "/" . $folderName ) ) {
				mkdir( $pathPosition . "/" . $folderName );
				echo json_encode( ["code" => 1, "pesan" => "Folder ". $folderName ." Success Created"] );
			}
			else {
				echo json_encode( ["code" => 0, "pesan" => "Folder Name ". $folderName ." is Exist!"] );
			}
		}
	}

	// ----------------------------------------------------------------------------------------------------------
	private function _deleteDirectory($dirPath) {
		if ( is_dir($dirPath) ) {
			$objects = scandir($dirPath);
			foreach ( $objects as $object ) {
				if ( $object != "." && $object !=".." ) {
					if ( filetype( $dirPath . DIRECTORY_SEPARATOR . $object ) == "dir" ) {
						$this->_deleteDirectory( $dirPath . DIRECTORY_SEPARATOR . $object );
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
		$resfolderDel 	= explode( "/", htmlspecialchars( $data["delete-folder"] ) );
		$folderDelete 	= end($resfolderDel);
		$pathPosition 	= $this->_dirPosition();

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
				$setDel = $this->_deleteDirectory( $reCtnPath . $folderDelete );
				if ( $setDel === true ) {
					echo json_encode( ["code" => 1, "pesan" => "Delete Directory ". $folderDelete ." Success"] );
				}
				else {
					echo json_encode( ["code" => 0, "pesan" => "ERROR, Delete Recursive! Or Dir Permission Denied!"] );
				}
			}
			else {
				echo json_encode( ["code" => 0, "pesan" => "Faild, Directory ". $folderDelete ." Not Found!"] );
			}
		}
		else {
			// exec del
			if ( file_exists( $pathPosition . "/" . $folderDelete ) ) {
				$setDel = $this->_deleteDirectory( $pathPosition . "/" . $folderDelete );
				if ( $setDel === true ) {
					echo json_encode( ["code" => 1, "pesan" => "Delete Directory ". $folderDelete ." Success"] );
				}
				else {
					echo json_encode( ["code" => 0, "pesan" => "ERROR, Delete Recursive! Or Dir Permission Denied!"] );
				}
			}
			else {
				echo json_encode( ["code" => 0, "pesan" => "Faild, Directory ". $folderDelete ." Not Found!"] );
			}
		}
	}

	// ----------------------------------------------------------------------------------------------------------
	public function deleteFile($data) {
		$resfileDel 	= explode( "/", htmlspecialchars( $data["delete-file"] ) );
		$fileDelete 	= end($resfileDel);
		$pathPosition 	= $this->_dirPosition();

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
				echo json_encode( ["code" => 1, "pesan" => "Delete File ". $fileDelete ." Success"] );
			}
			else {
				echo json_encode( ["code" => 0, "pesan" => "Faild!, File ". $fileDelete ." Not Found!"] );
			}
		}
		else {
			//exec del
			if ( file_exists( $pathPosition . "/" . $fileDelete ) ) {
				unlink( $pathPosition . "/" . $fileDelete );
				echo json_encode( ["code" => 1, "pesan" => "Delete File ". $fileDelete ." Success"] );
			}
			else {
				echo json_encode( ["code" => 0, "pesan" => "Faild, File ". $fileDelete ." Not Found!"] );
			}
		}
	}

	// ----------------------------------------------------------------------------------------------------------
	public function deleteOnFile($data) {
		$resOnfileDel 	= explode( "/", htmlspecialchars( $data["delete-on-file"] ) );
		$onFileDelete 	= end($resOnfileDel);
		$pathPosition 	= $this->_dirPosition();

		$fileOnLoad 	= explode( "/", $pathPosition );

		if ( end($fileOnLoad) === $onFileDelete ) {
			//exec del
			if ( file_exists( $pathPosition ) ) {
				unlink( $pathPosition );
				echo json_encode( ["code" => 1, "pesan" => "Delete File ". $onFileDelete ." Success"] );
			}
			else {
				echo json_encode( ["code" => 0, "pesan" => "Failed!, File ". $onFileDelete ." Not Found!"] );
			}
		}
		else {
			echo json_encode( ["code" => 0, "pesan" => "ERROR, File ". $onFileDelete ." Not Found!"] );
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
			$plugin = CONFIG["plugin"]["text_highlight"];
			require_once( PLUGIN . $plugin . "/". $plugin .".php");
			$pluginText  	= new $plugin();
			$hasil 			= $pluginText->start_plugin($data);
			$style 			= "<style>" . file_get_contents( PLUGIN . $plugin . "/". $plugin .".css") . "</style>";
			$tagAct 		= "div";
		}
		else if ( CONFIG["setting"]["text_highlight"] === false ) {
			$hasil 	= $data;
			$tagAct = "xmp";
		}
		$pathHapus 	= "?" . $this->querySTR;

		return $style . "
		<div id='code-layout'>
			<button class='btn btn-noOut-info' onclick='editFile(this)'><i class='fas fa-edit'></i> edit</button>
			<button class='btn btn-noOut-info' onclick='saveEditFile(this)'><i class='fas fa-save'></i> save</button>
			<button class='btn btn-noOut-info' onclick='hapusFileEditable(this)' target='#modalLounch' dir='". $pathHapus ."'><i class='fas fa-trash'></i> Delete</button>
			<button class='btn btn-noOut-info' onclick='visitFile(this)'><i class='fas fa-eye'></i> visit open</button>
			<div class='clear'></div>
			<". $tagAct ." class='code-block' contenteditable='false'>" . $hasil . "</". $tagAct .">
		</div>";
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
	private function _realFileSize($path) {
		if ( ! file_exists($path) )
		return false;

		$size = filesize($path);
		return $size;
	}
	
	// ----------------------------------------------------------------------------------------------------------
	private function _recursiveInfo($path) {
		if ( is_dir($path) === true ) {
			$this->totalFolder += 1;

			$_dir = glob($path . "/*");
			foreach ( $_dir as $file ) {

				$this->_recursiveInfo($file);
				$reString 			= explode( $this->root, $file );
				$dataPath 			= explode( "/", end($reString) );
				if ( count($dataPath) == 2 ) {
					$this->hirarky[] 	= $this->root . "/" . end($dataPath);
				}					
			}
		}
		else {
			$calculate 			= $this->_realFileSize($path);
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
			$this->_recursiveInfo( "../" . $this->name);
		}

		else {
			$readRoot 			= explode("/", $path);
			$this->name 		= end($readRoot);
			$this->countAccess 	= count($readRoot);
			$this->_recursiveInfo($path);
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