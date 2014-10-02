<?php

//------------- Обработчики событий для полей с использованием CKeditor -----------
/*
  Входящие данные:
  $field - поле для обработки (или массив с полями)


*/

		//для вывода полей с использованием CKeditor
		echo '<script>';
		echo '$grid.bind(\'jqGridAddEditAfterShowForm\', function(event, $form)
			{
                //$.noConflict();
			    //jQuery(document).ready(function($) {
			    //    CKEDITOR.replace( "description",{});
			    //});
			});
		';

        //echo '</script>';
        //echo "<script>";
		// Для записи изменений с использованием CKeditor
        echo '$grid.bind(\'jqGridAddEditClickSubmit\', function(event, $form)
			{
				oEditor = CKeditorAPI.GetInstance("description"); //получаем ссылку на объект "редактор"
				//description   = oEditor.GetXHTML("html");

				//text = oEditor.GetXHTML("html");
				return {
			     description: oEditor.GetHTML() //вызываем метод у объекта
			    };
			});
        ';
        echo '</script>';
