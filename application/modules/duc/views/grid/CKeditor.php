<?php

//------------- ����������� ������� ��� ����� � �������������� CKeditor -----------
/*
  �������� ������:
  $field - ���� ��� ��������� (��� ������ � ������)


*/

		//��� ������ ����� � �������������� CKeditor
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
		// ��� ������ ��������� � �������������� CKeditor
        echo '$grid.bind(\'jqGridAddEditClickSubmit\', function(event, $form)
			{
				oEditor = CKeditorAPI.GetInstance("description"); //�������� ������ �� ������ "��������"
				//description   = oEditor.GetXHTML("html");

				//text = oEditor.GetXHTML("html");
				return {
			     description: oEditor.GetHTML() //�������� ����� � �������
			    };
			});
        ';
        echo '</script>';
