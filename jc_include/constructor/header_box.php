<?php  
	if ( ! defined("CONFIG") ) { new Lib(); };
	$target 	= CONFIG["setting"]["content_path"];
	if ( isset($_GET["dir"]) ) {
		$target = $_GET["dir"];
	}
?>
<div class="header" mode="fixed">
	<div class="row">
		<div class="col-2">
			<nav class="navbar navbar-template">
				<div class="navbar-left" style="justify-content: space-around; width: 80%;">
					<div class="navbar-item">
						<a class="navbar-link" id="back-page"><i class="fas fa-long-arrow-alt-left" gray></i></a>
					</div>
					<div class="navbar-item">
						<a class="navbar-link" id="forward-page"><i class="fas fa-long-arrow-alt-right" gray></i></a>
					</div>
					<div class="navbar-item">
						<a class="navbar-link" id="refresh-page"><i class="fas fa-sync-alt" gray></i></a>
					</div>
					<div class="navbar-item">
						<a class="navbar-link" id="fullScreen-page"><i class="fas fa-expand" gray></i></a>
					</div>
				</div>
			</nav>
		</div>
		<div class="col-8">
			<nav class="navbar navbar-template">
				<div class="navbar-left">
					<?php if ( is_dir( $target ) ) : ?>
						<div class="navbar-item">
							<a class="navbar-link modal-toggle" target="#modalLounch" onclick="newFile()"><i class="fas fa-file" gray></i> New File</a>
						</div>
						<div class="navbar-item">
							<a class="navbar-link modal-toggle" target="#modalLounch" onclick="newFolder()"><i class="fas fa-folder" orange></i> New Folder</a>
						</div>
					<?php endif; ?>
					<!-- <div class="navbar-item">
						<a class="navbar-link">
							<i class="fas fa-bell"></i>
							Plugin
						</a>
					</div> -->
					<!-- <div class="navbar-item hover-down">
						<a class="navbar-link">Hover Menu</a>
						<ul class="hover-menu" style="position: fixed;">
							<li>
								<a href="" class="hover-link">JC Boster</a>
							</li>
							<li>
								<a href="" class="hover-link">JC Boster</a>
							</li>
							<li>
								<a href="" class="hover-link">JC Boster</a>
							</li>
						</ul>
					</div>
					<div class="navbar-item">
						<a href="" class="navbar-link">Menu 2</a>
					</div>
					<div class="navbar-item">
						<a href="" class="navbar-link">Menu 3</a>
					</div> -->
				</div>
			</nav>		
		</div>
		<div class="col-2">
			<nav class="navbar navbar-template">
				<div class="navbar-right" style="float: right;">
					<div class="navbar-item">
						<div class="navbar-brand">
							<img src="<?= $this->src; ?>img/img_atb/logo.png">
							<!-- <h1 style="color: darkgreen"> JC File Manager</h1> -->
						</div>
					</div>
				</div>
			</nav>
		</div>
	</div>
</div>
