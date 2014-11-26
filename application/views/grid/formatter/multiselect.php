<?php

//------------- Обработчик события для полей multiselect
/*
Входящие данные
  обязательные:
    $selector - string || array - имя или массив из селекторов для замены
  опционально:
  	$field - selector для обработки (или массив с именами если не один), по умолчанию ".multiselect"
  	$sortable - boolean - сортировка
  	$searchable - boolean - поиск


*/
$sortable = (isset($sortable)) ? 'true' : 'false';
$searchable = (isset($searchable)) ? 'true' : 'false';

$multiselect = '
	function gridEditMultiselect(field)
	{
              $(".ui-"+field).remove();
              $("."+field).show().multiselect(
              {
              	sortable: '.$sortable.',
              	searchable: '.$searchable.'
              });

	}
';
//регистрируем скрипт
assets_script($multiselect, false);
if( ! isset($selector)) $selector = 'multiselect';

	if( ! is_array($selector)) $selector = array($selector);
	echo '<script>
		$grid.bind("jqGridAddEditAfterShowForm", function(event, $form)
		{
		   var row_id = $(this).getGridParam("selrow");
		';

		foreach($selector as $item){
				echo '
				gridEditMultiselect("'.$item.'");
			    ';
			    /*
			    echo'
				    	$(".ui-"+'.$item.').remove();
		              	$("."+'.$item.').show().multiselect(
		              	{
		              		sortable: '.$sortable.',
		              		searchable: '.$searchable.'
		              	});
	            	';
	           */
		}
		echo '});
		</script>';

