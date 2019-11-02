<div style="text-align: center;">
	<img src="sc.png" style="width: 60%;">
</div>

# jc_file_manager
Simple Project File Manager

# git repository

		$ git clone https://github.com/kangjeki/jc_file_manager.git

# Use
Load Index File

# setting
open config.json

	"setting" 		: 
	{
		"path" 				: "C:/xampp/htdocs",
		"content_path"			: "default",
		"protocol" 			: "http://",			
		"host" 				: "localhost",
		"default_file_read"			: "txt",
		"default_mime_type" 	: "text",
		"text_highlight"		: true
	},

if you not using xampp apache server, replace default path with your localhost directory

<h3>Path Directory Non Host</h3>

if you want to load local no host, you can raplace <b>path</b> with your target directory.
<br>
example:

		"path" 		: "D:\\",
<h3>Note: </h3>
local directory non host not suport crud / <b>read only</b>

# Extensions File
default mime type is text <br>
you can add Manualy extension file in config.json

	"mimeType" 		: 
		{
			"type" : 
			{
				"0"	: "text",
				"1" : "audio",
				"2" : "video",
				"3" : "image",
				"4" : "folder",
				"5" : "arcive"
			},
			"extensions" :
			[
				["php", "html", "css", "txt", "js", "jcm", "jc", "json", "md", "xls", "sql", "doc"],
				["wav", "mp3", "oog"],
				["mp4", "avi", "3gp", "webm"],
				["jpg", "jpeg", "png"],
				["folder"],
				["rar", "zip", "apk", "gzip"]
			]
		}


# plugin
this version 0.0.1 update, suport plugin texthighlight

	"plugin" 		: {
		"text_highlight" : "JC_DefaultTextHighLight"
	},


