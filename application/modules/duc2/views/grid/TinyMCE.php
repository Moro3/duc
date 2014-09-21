<?php

//------------- ����������� ������� ��� ����� � �������������� TinyMCE -----------
/*
�������� ������
  ������������:
  	$field - ���� ��� ��������� (��� ������ � ������)
  �����������:
  	$theme - string - ���� (default: simple)
  	$mode - string - ����� (default: exact)


*/


        echo "<script>";
        //��� ������ ����� � �������������� TinyMCE
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
		// ��� ������ ��������� � �������������� TinyMCE
		echo '$grid.bind("jqGridAddEditClickSubmit", function(event, $form)
			{
                //tinyMCE.triggerSave();
				// replace textareaId by the id of your textarea
				//return tinyMCE.execCommand("mceAddControl", false, "description");

				var mce = tinyMCE.get("description");
    			//alert(mce.getContent());
    			return {
			     description: mce.getContent() //�������� ����� � �������
			    };
    		});
		';
        echo '</script>';
