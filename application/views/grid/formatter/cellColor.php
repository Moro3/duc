<?php

//------------- Обработчик события для цвета фона ячейки
/*
Входящие данные
  обязательные:
    $selector - string || array - имя или массив из селекторов для замены
  опционально:
  	$field - selector для обработки (или массив с именами если не один), по умолчанию ".multiselect"
  	$sortable - boolean - сортировка
  	$searchable - boolean - поиск


*/

if( ! isset($selector)) $selector = 'multiselect';

	if( ! is_array($selector)) $selector = array($selector);
	echo '<script>
		$grid.bind("jqGridAddEditAfterShowForm", function(event, $form)
		{
		   var row_id = $(this).getGridParam("selrow");
		';


	echo '});
		</script>';