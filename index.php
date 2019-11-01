<?php 
/*	----------------------------------------------------------------------------------------
	# CSS, JS, HTML : Library Using JC Boster Licenced JC_Programs
	# Author 		: JC Programs
--------------------------------------------------------------------------------------------*/
require ( dirname(__FILE__) . "/jc_app/init.php" );
require ( dirname(__FILE__) . "/jc_app/map.php" );

// ----------------------------------------------------------------------------------------
if ( ! defined( "CONFIG" ) ) { new Lib(); };

// ----------------------------------------------------------------------------------------
// operation crud
if ( isset($_POST["create-file"] ) ) {
	$Lib->createNewFile($_POST);
	exit();
}
if ( isset($_POST["create-folder"] ) ) {
	$Lib->createNewFolder($_POST);
	exit();
}
if ( isset($_POST["delete-file"] ) ) {
	$Lib->deleteFile($_POST);
	exit();
}
if ( isset($_POST["delete-folder"] ) ) {
	$Lib->deleteFolder($_POST);
	exit();
}

// ----------------------------------------------------------------------------------------
$Load->header( CONFIG["app_name"] );
$Load->containerTop();

/* First Load Directory ------------------------------------------------------------------
	# PATH = "" => default access parent Directory
	# all default config json
-------------------------------------------------------------------------------------------*/
const PATH 			= CONFIG["setting"]["path"];
$sidebarRoot 		= glob( PATH . "/*");

// Set Default Directory Content Path --------------
$contentPath 		= CONFIG["setting"]["content_path"];
// -------------------------------------------------
if ( isset($_GET["dir"]) ) {
	$contentPath 	= $_GET["dir"];
}
// -------------------------------------------------
if ( strlen( $contentPath ) === 0 ) {
	$strSrc 		= explode("\\", __DIR__);
	$contentPath 	= "../" . end( $strSrc );
}

?>

<div id="index">
	<div class="row">
		<?php // block sidebar left ------------------------------------------------ ?>
		<div class="col-2 sidebar sidebar-fixed" mode="fixed" id="sidebar-left">
			<div class="nav">
				
				<?php foreach ( $sidebarRoot as $dirSidebar ) : ?>
					<?php  
						$reNameFileSidebar 	= explode("/", $dirSidebar);
						$nameFileSidebar 	= end($reNameFileSidebar);
					?>
					<?php  
						if ( is_dir( $dirSidebar ) ) {
							
							echo '<div class="nav-link">
								<i class="fas fa-folder expand" expand-target="#'. $nameFileSidebar. '" orange></i> 
								<a load="?dir='. $dirSidebar. '" class="spa-model dir-list" mode="sync">
									'. $nameFileSidebar. '
								</a>
								<div class="expand-group" id="'. $nameFileSidebar. '">';

								$subDirData = glob( $dirSidebar . "/*" );

								foreach ( $subDirData as $key => $subDir ) {
									if ( is_dir( $subDir ) ) {
										$nameSubDir = explode( "/", $subDir );
										$nameSub 	= end( $nameSubDir );
										echo '<div class="nav-link">
											<i class="fas fa-folder" orange></i> 
											<a load="?dir='. $subDir. '" class="spa-model dir-list" mode="sync">
												'. $nameSub. '
											</a>
										</div>';
									}
								}
								
							echo '</div>		
							</div>';
						}
					?>
				<?php endforeach; ?>
				
			</div>
		</div>

		<?php // block content ------------------------------------------------ ?>
		<div class="clear"></div>

		<?php if ( $contentPath !== "default" ) { ?>
			<div class="col-8">
				<?php
					$Lib2			= new Lib();
					// read directory content
					$getDirectory2 	= $Lib2->directoryInfo($contentPath);
				?>
				<?php if ( is_dir( $contentPath ) ) { ?>
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
											<a load="?dir=<?= $dirData; ?>" class="nav-link spa-model dir-list" mode="sync">
												<i class="fas fa-folder" orange></i> <?= $fileName; ?>
											</a>
										<?php } else { ?>
											<a load="?dir=<?= $dirData; ?>" class="nav-link spa-model dir-list" mode="sync">
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
						$ext 	= explode(".", $contentPath);
						$file 	= file_get_contents($contentPath);
						
						//Access mime type of file 
						$mime 	= $Lib->exist_fileType( end($ext) );

						// if this part is error try to combin with your serve
						$path 		= str_replace( PATH , '', $contentPath);
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
		<?php } else if ( $contentPath === "default" ) { 
			echo '<div class="col-10">';
			$Load->welcomePage(); 
			$Load->footerContainer();
			echo '</div>';
		} ?>
	</div>
</div>

<?php  
$Load->containerBottom();
$Load->footer();
?>
