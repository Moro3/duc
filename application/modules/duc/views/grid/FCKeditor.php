<?php

//------------- Обработчики событий для полей с использованием FCKeditor -----------
/*
Входящие данные
  обязательные:
  	$field - поле для обработки (или массив с полями)
  опционально:
  	$height - numeric - высота таблицы
  	$toolbarSet - string - имя шаблона


*/


		//для вывода полей с использованием FCKeditor
		echo '<script>';
		echo '$grid.bind(\'jqGridAddEditAfterShowForm\', function(event, $form)
			{

                //var oFCKeditor = new FCKeditor( "description" ) ;
				//oFCKeditor.BasePath = "/'.assets_wysiwyg().'fckeditor/" ;
                //oFCKeditor.Height = 300 ;
                //oFCKeditor.ToolbarSet = "BasicA";
				//oFCKeditor.ReplaceTextarea() ;

				gridEditWysiwyg("description");
			});
		';

        //echo '</script>';
        //echo "<script>";
		// Для записи изменений с использованием FCKeditor
        echo '$grid.bind(\'jqGridAddEditClickSubmit\', function(event, $form)
			{
				oEditor = FCKeditorAPI.GetInstance("description"); //получаем ссылку на объект "редактор"
				//description   = oEditor.GetXHTML("html");

				//text = oEditor.GetXHTML("html");
				return {
			     description: oEditor.GetHTML() //вызываем метод у объекта
			    };
			});
        ';
        echo 'function gridEditWysiwyg(field)
			{
                var oFCKeditor = new FCKeditor( field ) ;
				oFCKeditor.BasePath = "/'.assets_wysiwyg().'fckeditor/" ;
                oFCKeditor.Height = 300 ;
                //oFCKeditor.ToolbarSet = "BasicImg";
				oFCKeditor.ReplaceTextarea() ;
			}';

        echo '</script>';