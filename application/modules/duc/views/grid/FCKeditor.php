<?php

//------------- ����������� ������� ��� ����� � �������������� FCKeditor -----------
/*
�������� ������
  ������������:
  	$field - ���� ��� ��������� (��� ������ � ������)
  �����������:
  	$height - numeric - ������ �������
  	$toolbarSet - string - ��� �������


*/


		//��� ������ ����� � �������������� FCKeditor
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
		// ��� ������ ��������� � �������������� FCKeditor
        echo '$grid.bind(\'jqGridAddEditClickSubmit\', function(event, $form)
			{
				oEditor = FCKeditorAPI.GetInstance("description"); //�������� ������ �� ������ "��������"
				//description   = oEditor.GetXHTML("html");

				//text = oEditor.GetXHTML("html");
				return {
			     description: oEditor.GetHTML() //�������� ����� � �������
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