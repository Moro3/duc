<?php
$id_upload = Modules::run('duc/duc_photos/formSelector', 'file_upload');
$field_upload = Modules::run('duc/duc_photos/formSelector', 'foto_upload');
$id_group = Modules::run('duc/duc_photos/formSelector', 'id_group');
$field_group = Modules::run('duc/duc_photos/formSelector', 'field_group');
$id_nameIsFile = Modules::run('duc/duc_photos/formSelector', 'id_nameIsFile');
$field_nameIsFile = Modules::run('duc/duc_photos/formSelector', 'field_nameIsFile');

//стиль кнопки
$style = '
    .upload_files{    	padding:10px;
    }
    .load-button {
        background-color: transparent;
        border: none;
        padding: 0px;
    }
    .uploadify:hover .load-button {
        background-color: transparent;
    }

';
assets_style($style, false);

$script = "
  $(function() {
    $('#".$id_upload."').uploadify({        'formData'      : {'".$field_group."' : 0, '".$field_nameIsFile."': 1},
        'swf'      : '/".assets_uploads('services')."/uploadify.swf',
        'uploader' : '/ajax/?resource=photos_package_action/ajax/duc',
        'auto'      : false,
        'fileTypeExts' : '*.gif; *.jpg; *.png; *.jpeg',
        'fileObjName' : '".$field_upload."',
        'buttonText' : 'Выберите файлы для загрузки...',
        'buttonClass' : 'load-button',
        'debug'    : true,
        'width'    : 300,
        'uploadLimit' : 50,
        'fileSizeLimit' : '6000KB',
        'onUploadSuccess' : function(file, data, response) {
            //alert('The file ' + file.name + ' was successfully uploaded with a response of ' + response + ':' + data);

        },
        'onUploadError' : function(file, errorCode, errorMsg, errorString) {
            alert('The file ' + file.name + ' could not be uploaded: ' + errorString);
        },
        'onUploadComplete' : function(file) {
            //alert('The file ' + file.name + ' finished processing.');
        },
        'onUploadStart' : function(file) {
            var group = $('#".$id_group."').val();
            //var nameFile = $('#".$id_nameIsFile."').val();
            //var nameFile = $(\"input[name='".$id_nameIsFile."']\").val();
            if($('#".$id_nameIsFile."').is(\":checked\")){
				var nameFile = 1;
				//alert(\"Отмечен\");// делаем что-то, когда чекбокс включен
			}else{
				var nameFile = 0;
				//alert(\"Отметка снята\");// делаем что-то другое, когда чекбокс выключен
			};

            //var group = 0;
            //alert('Выбрана группа: ' + group);
            if((typeof group !== 'undefined') && (group > 0)){
            	$('#".$id_upload."').uploadify('settings','formData', {'".$field_group."': group, '".$field_nameIsFile."': nameFile});
            	//alert('Выбрана группа: ' + group);
            }else{            	alert('Не выбран коллектив');
            };
        },
        'onQueueComplete' : function(queueData) {
            alert(queueData.uploadsSuccessful + ' files were successfully uploaded.');
        },
        'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
            $('#progress').html(totalBytesUploaded + ' bytes uploaded of ' + totalBytesTotal + ' bytes.');
        }
        // Put your options here
    });
});


";

assets_script($script, false);

//echo '<script>';
//echo $script;
//echo '</script>';

?>
<div class="upload_files">
<p>
   Выберите коллектив:
<?php
   	$groups = Modules::run('duc/duc_groups/listGroups');
	$js = 'id="'.$id_group.'"';
	echo form_dropdown($field_group, $groups, '', $js);

	echo '</p>';
	echo '<p>';
	$form_checkbox = array(
	    'name'        =>  $field_nameIsFile,
	    'id'          => $id_nameIsFile,
	    'value'       => 'op',
	    'checked'     => false,
	    'style'       => 'margin:10px 0',
    );
    $js = 'id="'.$id_nameIsFile.'"';

    if($this->input->post($field_nameIsFile)){    	$form_checked = 'checked';
    	$form_value = '1';
    }elseif($form_checkbox['checked'] == true){    	$form_checked = 'checked';
    	$form_value = '1';
    }else{    	$form_checked = '';
    	$form_value = '0';
    }
	echo '<br />';
	echo 'Записывать название фото из имени файла: ';
	//echo form_checkbox($data['name'], $data['value'], TRUE, $js);
	//echo $field_nameIsFile;
	//echo form_checkbox($form_checkbox);
	echo '<input type="checkbox" name="'.$field_nameIsFile.'" id="'.$id_nameIsFile.'" value='.$form_value.' '.$form_checked.' style="'.$form_checkbox['style'].'" />';
?>
</p>
<p><input type="file" name="<? echo $field_upload;?>" id="<? echo $id_upload;?>" />
</p>

<p><a href="javascript:$('#<? echo $id_upload;?>').uploadify('upload','*')">Загрузить выбранные файлы</a></p>
<div id="progress"></div>
</div>