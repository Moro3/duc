<?php


//===== File Uploding ================

/*
�������� ������ �� ������� ����� �������������� ��������� � �������� �����������.
jqGridPHP �������� �������, ������� ��������� �������� ����������� ������ �����.

����� ��������� �����, ��� �����������:

    ���������� ������ jQuery Ajax Form
    ��������� ��� ����� �����:

editurl: null,
dataProxy: $.jgrid.ext.ajaxFormProxy,

� ����� �������, ��� ���������� � �������������� ����� �����, ������ ����� ������������ ����� iframe.
������ � ������� ����� ������� � �����.
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