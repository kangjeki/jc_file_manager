<?php 
/*	----------------------------------------------------------------------------------------
	# CSS, JS, HTML : Library Using JC Boster Licenced JC_Programs
	# Author 		: JC Programs
--------------------------------------------------------------------------------------------*/
require ( dirname(__FILE__) . "/jc_app/init.php" );
require ( dirname(__FILE__) . "/jc_app/map.php" );

// ----------------------------------------------------------------------------------------
if ( ! defined("CONFIG") ) { new Lib(); };

$Load->header(CONFIG["app_name"]);
$Load->containerTop();

/* First Load Directory ------------------------------------------------------------------
	# PATH = "" => default access parent Directory
	# to load dir example = "../../directoryName"
	# ending load not use "/"
	# all default config json
-------------------------------------------------------------------------------------------*/
const PATH 		= CONFIG["setting"]["content_path"]; // set first directory load on content 

// Set Default Directory Path --------------
$uriLoad 		= PATH;
if ( strlen( $uriLoad ) === 0 ) {
	$strSrc 	= explode("\\", __DIR__);
	$uriLoad 	= "../" . end( $strSrc );
}
// -----------------------------------------
$getDirectory 	= $Lib->directoryInfo( $uriLoad );

// Root Sidebar Hirarky Menu -------------------------------------------------------------
// change if you use other server
$pathSidebar 	= CONFIG["setting"]["path"];
$sidebarRoot 	= glob( $pathSidebar . "/*");

?>

<div id="index">
	<div class="row">
		<?php // block sidebar left ------------------------------------------------ ?>
		<div class="col-2 sidebar sidebar-fixed" mode="fixed" id="sidebar-left">
			<div class="nav">
				<!-- <div class="file-link">
					<a class="nav-link expand" expand-target="#data-contoh2"><i class="fas fa-folder" orange></i></a>
					<a load="index.php" class="nav-link spa-model" mode="sync"><?= $getDirectory["name"];?></a>	
				</div> -->
				<!-- <div class="expand-group" id="data-contoh2"> -->
					<?php foreach ( $sidebarRoot as $dirSidebar ) : ?>
						<?php  
							$reNameFileSidebar 	= explode("/", $dirSidebar);
							$nameFileSidebar 	= end($reNameFileSidebar);
						?>
						<?php  if ( is_dir( $dirSidebar ) ) :  ?>
							<a load="?dir=<?= $dirSidebar; ?>" class="nav-link spa-model" mode="sync">
								<i class="fas fa-folder" orange></i> <?= $nameFileSidebar; ?>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
				<!-- </div> -->
			</div>
		</div>

		<?php // block content ------------------------------------------------ ?>
		<div class="clear"></div>
		<div class="col-8">
			<?php
				$Lib2			= new Lib();
				$target 		= $uriLoad;
				if ( isset($_GET["dir"]) ) {
					$target = $_GET["dir"];
				}
				// read directory content
				$getDirectory2 	= $Lib2->directoryInfo($target);
			?>
			<?php if ( is_dir( $target) ) { ?>
				<div class="list-dir">
					<table class="table">
						<tr>
							<th>Name</th>
							<th>Type</th>
							<th>Size</th>
						</tr>
						<?php foreach ( $getDirectory2["hirarky"] as $dirData ) : ?>
							<?php
								$locMatch 	= explode("/", $dirData);
								$fileName 	= end($locMatch);

								// new Load library drectory 
								$Lib3 		= new Lib();
								$getDirInfo = $Lib3->directoryInfo($dirData);
								$dirSize 	= $Lib3->dirSize( $getDirInfo["size"] );
							?>
							<tr>
								<td>
									<?php  if ( $getDirInfo["type"] == "Folder" ) {  ?>
										<a load="?dir=<?= $dirData; ?>" class="nav-link spa-model" mode="sync">
											<i class="fas fa-folder" orange></i> <?= $fileName; ?>
										</a>
									<?php } else { ?>
										<a load="?dir=<?= $dirData; ?>" class="nav-link spa-model" mode="sync">
											<i class="fas fa-file-alt" gray></i> <?= $fileName; ?>
										</a>
									<?php } ?>		
								</td>
								<td>
									<?= $getDirInfo["type"]; ?>
								</td>
								<td>
									<?= $dirSize; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
				</div>
			<?php } else { ?>
				<div class="list-dir">
				<?php
					$ext 	= explode(".", $target);
					$file 	= file_get_contents($target);
					
					//Access mime type of file 
					$mime 	= $Lib->exist_fileType( end($ext) );

					// if this part is error try to combin with your serve
					$path 		= str_replace($pathSidebar, '', $target);
					$rePath 	= CONFIG["setting"]["protocol"] . $_SERVER["HTTP_HOST"] . $path;

					if ( $mime["jenis"] === "image" ) {
						echo "<img src='". $rePath . "' class='dir-preview'>";
					}
					else if ( $mime["jenis"] === "audio" ) {
						echo "<audio src='". $rePath . "' class='dir-preview' controls>";
					}
					else if ( $mime["jenis"] === "video" ) {
						echo "<video src='". $rePath . "' class='dir-preview' controls>";
					}
					else if ( $mime["jenis"] === "text" ) {
						echo $Lib->convertText($file);
					}
				?>
				</div>
			<?php } ?>

			<?php
				// Load footer container 
				$Load->footerContainer();
			?>
		</div>

		<?php // block sidebar right ------------------------------------------------ ?>
		<div class="clear"></div>
		<div class="col-2 sidebar sidebar-fixed" mode="fixed" id="sidebar-right">
			<div class="nav-right">
				<div class="card-light" style="font-size: 13px;">
					<table class="table">
						<tr>
							<th colspan="2"><?= ucfirst( $Lib->exist_fileType( $getDirectory2["type"] )["jenis"] ); ?> Properties</th>
						</tr>
						<tr>
							<td colspan="2">
								<?php if ( $getDirectory2["type"] === "Folder" ) { ?>
									<i class="fas fa-folder" orange></i> <?= $getDirectory2["name"]; ?>
								<?php } else { ?>
									<i class="fas fa-file-alt" gray></i> <?= $getDirectory2["name"]; ?>
								<?php } ?>
							</td>
						</tr>
						<tr>
							<td>Type</td>
							<td>: <?= ucfirst( $Lib->exist_fileType( $getDirectory2["type"] )["jenis"] ); ?> 
							(<?= $Lib->exist_fileType( $getDirectory2["type"] )["exist"]; ?>)</td>
						</tr>
						<tr>
							<td>Size</td>
							<td>: <?= $Lib->dirSize( $getDirectory2["size"] ); ?></td>
						</tr>
						<tr>
							<td>Folder</td>
							<td>: <?= $getDirectory2["folder"]; ?> Folder</td>
						</tr>
						<tr>
							<td>File</td>
							<td>: <?= $getDirectory2["file"]; ?> File</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
	
</div>

<?php  
$Load->containerBottom();
$Load->footer();
?>
