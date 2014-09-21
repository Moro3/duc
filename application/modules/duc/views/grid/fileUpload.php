<?php


//===== File Uploding ================

/*
Загрузка файлов из обычной формы редактирования сопряжена с большими сложностями.
jqGridPHP содержит функцию, которая позволяет сгладить большинство острых углов.

Чтобы загружать файлы, вам понадобится:

    Подключить плагин jQuery Ajax Form
    Прописать две опции грида:

editurl: null,
dataProxy: $.jgrid.ext.ajaxFormProxy,

С этого момента, при добавлении и редактировании через форму, данные будут отправляться через iframe.
Вместе с данными будут уходить и файлы.
*/

echo "\r\n<script>";
        //echo "xhr.setRequestHeader('X-REQUESTED-WITH', 'XMLHttpRequest');";

        echo <<<label
        var opts =
		{
		    //'caption'	: 'File Uploading',
		    'editurl'   : null, //this is required for dataProxy effect
		    'dataProxy' : $.jgrid.ext.ajaxFormProxy //our charming dataProxy ^__^
		}
label;

		 echo "</script>\r\n";