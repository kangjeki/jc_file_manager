<div class="note note-info">
	# Write new file with extension | ex: (.html) <br>
	# if you want to edit file -> right click on new file -> select edit
</div>
<input type="text" class="input-control" name="file-name" id="file-name" placeholder="file name..">
<div class="clear"></div>
<button class="btn btn-success" name="create-file" onclick="createFile(query('#file-name').value)">Create File</button>
