<?php
/*	----------------------------------------------------------------------------------------
	# Labrary Project
	# Author : JC Programs
--------------------------------------------------------------------------------------------*/
class Lib {
	public function deleteDirectory($dirPath) {
		if ( is_dir($dirPath) ) {
			$objects = scandir($dirPath);
			foreach ($objects as $object) {
				if ($object != "." && $object !="..") {
					if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
						$this->deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
					} else {
						unlink($dirPath . DIRECTORY_SEPARATOR . $object);
					}
				}
			}
			reset($objects);
			rmdir($dirPath);
			if (file_exists($dirPath)) {
				return false;
			}
			else {
				return true;
			}
		}
	}

	private $totalSize 		= 0,
			$totalFolder 	= 0,
			$totalFile 		= 0,
			$hirarky 		= [],
			$root 			= "",
			$type 			= "",
			$name 			= "",
			$countAccess 	= 0;

	private function realFileSize($path) {
		if ( ! file_exists($path) )
		return false;

		$size = filesize($path);
		return $size;
	}
	
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

		return ["root" => $this->root, "name" => $this->name, "type" => $this->type, "size" => $this->totalSize, "file" => $this->totalFile, "folder" => $this->totalFolder, "hirarky" => $this->hirarky];
	}

	public function exist_fileType($ext) {
		$jenis 	= [0 => "text", 1 => "audio", 2 => "video", 3 => "image"];
		$mime 	= [
			["php", "html", "css", "txt", "js", "jcm", "jc"],
			["wav", "mp3", "oog"],
			["mp4", "avi", "3gp", "webm"],
			["jpg", "jpeg", "png"]
		];

		$fileType = strtolower($ext);
		$dataType = "";

		foreach ($mime as $key => $value) {
			foreach( $value as $type ) {
				if ( $type === $fileType ) {
					$dataType = $jenis[$key];
				}	
			}
		}
		return $dataType;
	}

}