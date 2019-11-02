<?php  
	if ( isset( $_GET["origin-name"] ) ) {
		$originName = $_GET["origin-name"];
	}
	else {
		echo "Origin Name Not Found!";
		exit();
	}
?>

<div class="note note-danger">
	Origin Name  <b><?= $originName; ?></b> ?</div>
</div>

<input type="text" class="input-control" id="new-name-dirList" placeholder="new name..">
<div class="clear"></div>
<button class="btn btn-success" onclick="setRenameDirList(query('#new-name-dirList').value, '<?= $originName; ?>')">Rename</button>
