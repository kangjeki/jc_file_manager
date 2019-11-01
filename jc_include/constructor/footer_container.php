<?php  
	if ( ! defined("CONFIG") ) { new Lib(); };
?>
<div class="modal" modal-width="500px" id="modalLounch" tabindex="-1">
	<div class="modal-block">
		<div class="modal-content">
			<div class="box-content">
				<div class="box-header">
					<i class="fas fa-info"></i> <span id="modal-judul"></span>
					<div class="close-box modal-toggle" target="#modalLounch">
						<i class="fas fa-times"></i>
					</div>
				</div>
				<div class="box-body" id="modal-body">
				
				</div>
				<div class="box-footer">
					<button class="btn btn-info modal-toggle" target="#modalLounch">Cancle</button>
				</div>
			</div>
		</div>	
	</div>
</div>
<div class="footer-container">
	<div class="block-licence">
		<?= CONFIG["description"]; ?> | Author : <?= CONFIG["author"]; ?>
	</div>
</div>