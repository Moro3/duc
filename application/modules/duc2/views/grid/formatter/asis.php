<?php

//====== Formater asis ==========
/**
*  События при добавлении и редактировании перед показом формы
*	отображение поля как оно есть (т.е. удаление из тега формы)
* входящие параметры:
	$selector - string || array - селектор или массив из селекторов которые требуется заменить
*
*/

if(isset($selector)){
	if( ! is_array($selector)) $selector = array($selector);
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
	return false;
}