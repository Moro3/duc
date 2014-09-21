<?php

//------------- Обработчики событий для полей с использованием TinyMCE -----------
/*
Входящие данные
  обязательные:
  	$field - поле для обработки (или массив с полями)
  опционально:
  	$theme - string - тема (default: simple)
  	$mode - string - режим (default: exact)


*/


        echo "<script>";
        //для вывода полей с использованием TinyMCE
        echo '$grid.bind(\'jqGridAddEditAfterShowForm\', function(event, $form)
			{
				//window.tinyMCEPreInit = {base : "/wysiwyg/tiny_mce/tiny_mce.js", suffix : "", query : ""};
				//tinymce.dom.Event.domLoaded = true;
				tinyMCE.init({
				  	mode : "exact",
				  	theme : "simple",
				  	elements : "description"
				  	//editor_selector : "description"
				});
			});
		';
		// Для записи изменений с использованием TinyMCE
		echo '$grid.bind("jqGridAddEditClickSubmit", function(event, $form)
			{
                //tinyMCE.triggerSave();
				// replace textareaId by the id of your textarea
				//return tinyMCE.execCommand("mceAddControl", false, "description");

				var mce = tinyMCE.get("description");
    			//alert(mce.getContent());
    			return {
			     description: mce.getContent() //вызываем метод у объекта
			    };
    		});
		';
        echo '</script>';
