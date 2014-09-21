<?php

$this->assets->script->package('jquery/plugins/fileupload',
                              //public file
							 array(
							 			//--js--
							 			'jquery/plugins/fileupload/vendor/jquery.ui.widget.js',
							 			'jquery/plugins/fileupload/jquery.iframe-transport.js',
							 			'jquery/plugins/fileupload/jquery.fileupload.js',
							 			//--style--
							       		'jquery/plugins/tablesorter/blue/style.css',

							 ),
							 false

);

$script = "
  $(function () {
    $('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                $('<p/>').text(file.name).appendTo(document.body);
            });
        }
        progressall: function (e, data) {
	        var progress = parseInt(data.loaded / data.total * 100, 10);
	        $('#progress .bar').css(
	            'width',
	            progress + '%'
	        );
	    }
    });
});
";

assets_script($script, false);

?>

<input id="fileupload" type="file" name="files[]" data-url="server/php/" multiple>
<div id="progress">
    <div class="bar" style="width: 0%;"></div>
</div>