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
				<div class="navbar-left">
					<div class="navbar-item">
						<div class="navbar-brand">
							<img src="<?= $this->src; ?>img/img_atb/logo.png">
							<!-- <h1 style="color: darkgreen"> JC File Manager</h1> -->
						</div>
					</div>
				</div>
			</nav>
		</div>
		<div class="col-10">
			<nav class="navbar navbar-template">
				<div class="navbar-left">
					<?php if ( is_dir( $target ) ) : ?>
						<div class="navbar-item">
							<a class="navbar-link modal-toggle" target="#modalLounch" onclick="newFile()"><i class="fas fa-file" grey></i> New File</a>
						</div>
						<div class="navbar-item">
							<a class="navbar-link modal-toggle" target="#modalLounch" onclick="newFolder()"><i class="fas fa-folder-plus" orange></i> New Folder</a>
						</div>
					<?php endif; ?>
					<!-- <div class="navbar-item">
						<a class="navbar-link">
							<i class="fas fa-bell"></i>
							<span class="badge badge-danger">299</span>
						</a>
					</div>
					<div class="navbar-item hover-down">
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
				<div class="navbar-right" style="float: right;">
					<div class="navbar-item">
						<a class="navbar-link" id="back-page" grey><i class="fas fa-long-arrow-alt-left"></i></a>
					</div>
					<div class="navbar-item">
						<a class="navbar-link" id="forward-page" grey><i class="fas fa-long-arrow-alt-right"></i></a>
					</div>
					<div class="navbar-item">
						<a class="navbar-link" id="refresh-page" grey><i class="fas fa-sync-alt"></i></a>
					</div>
					<div class="navbar-item">
						<a class="navbar-link" id="fullScreen-page" grey><i class="fas fa-expand"></i></a>
					</div>
				</div>
			</nav>		
		</div>
	</div>
</div>