<?php

//====== ������� ��� ������� duc_teachers ==========


/**
*  ������� ��� ���������� � �������������� ����� ������� �����
*	����������� ���� ��� ��� ���� (�.�. �������� �� ���� �����)
* �������� ���������:
	$selector - string || array - �������� ��� ������ �� ���������� ������� ��������� ��������
*
*/

if(isset($selector)){
	echo '<script>
		$grid.bind("jqGridAddEditBeforeShowForm", function(event, $form)
		{
		   var row_id = $(this).getGridParam("selrow");
		';

	foreach($selector as $item){
			echo '
			var html = $(this).getCell(row_id, "'.$item.'");
			$("#'.$item.'").replaceWith("<div id=\"'.$item.'\">" + html + "</div>");
		    ';
	}
	echo '});
		</script>';
}else{
}
