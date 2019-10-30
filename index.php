<?php 
/*	----------------------------------------------------------------------------------------
	# CSS, JS, HTML : Library Using JC Boster Licenced JC_Programs
	# Author 		: JC Programs
--------------------------------------------------------------------------------------------*/
require ( dirname(__FILE__) . "/jc_app/init.php" );
require ( dirname(__FILE__) . "/jc_app/map.php" );

$Load->header("Dasboard");
$Load->containerTop();

/* Primary Load Directory ------------------------------------------------------------------
	# PATH = "" => default access parent Directory
	# to load dir example = "../../directoryName"
	# ending load not use "/"
	# This cant access parent directory host
-------------------------------------------------------------------------------------------*/
const PATH 		= "../../my_fun"; // set directory here 

// Set Default Directory Path --------------
$uriLoad 		= PATH;
if ( strlen( $uriLoad ) === 0 ) {
	$strSrc 	= explode("\\", __DIR__);
	$uriLoad 	= "../" . end( $strSrc );
}
// -----------------------------------------
$getDirectory 	= $Lib->directoryInfo( $uriLoad );
$hirarkyRoot 	= $getDirectory["hirarky"];
?>

<div id="index">
	<div class="row">
		<?php // block sidebar left ------------------------------------------------ ?>
		<div class="col-2 sidebar sidebar-fixed" mode="fixed" id="sidebar-left">
			<div class="nav">
				<div class="file-link">
					<a class="nav-link expand" expand-target="#data-contoh2"><i class="fas fa-folder" orange></i></a>
					<a load="index.php" class="nav-link spa-model" mode="sync"><?= $getDirectory["name"];?></a>	
				</div>
				<div class="expand-group" id="data-contoh2">
					<?php foreach ( $hirarkyRoot as $dirSidebar ) : ?>
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
				</div>
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
								$dirSize 	= ceil($getDirInfo["size"] / 1000);
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
									<?= $dirSize; ?>Kb
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
					if ( $mime === "image" ) {
						echo "<img src='". $target . "' style='padding: 10px; width: 100%;'>";
					}
					else if ( $mime === "audio" ) {
						echo "<audio src='". $target . "' style='padding: 10px; width: 100%;' controls>";
					}
					else if ( $mime === "video" ) {
						echo "<video src='". $target . "' style='padding: 10px; width: 100%;' controls>";
					}
					else if ( $mime === "text" ) {
						echo "<xmp style='padding: 10px; width: 100%;'>" . $file . "</xmp>";
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
			<div class="note note-warning">
				<table class="table">
					<tr>
						<th colspan="2">Directory Properties</th>
					</tr>
					<tr>
						<td colspan="2">
							<i class="fas fa-folder" orange></i> <?= $getDirectory2["name"]; ?>
						</td>
					</tr>
					<tr>
						<td>Size</td>
						<td>: <?= ceil($getDirectory2["size"] / 1000); ?>Kb</td>
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

<?php  
$Load->containerBottom();
$Load->footer();
?>
