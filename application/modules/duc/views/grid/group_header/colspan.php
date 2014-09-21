<?php
//========== Позволяет сгруппировать заголовки таблиц ========
/*
входящие параметры:
	$grid - имя грида (selector)
	$useColSpanStyle - boolean - употребить стиль объединения колонок
	$column['name1'] = array('numberOfColumns'=>2, 'titleText' => 'text') - массив с параметрами группирующих колонок

*/
$grid = (isset($grid)) ? $grid : false;
$useColSpanStyle = (isset($useColSpanStyle)) ? $useColSpanStyle : false;
if(empty($grid)) return false;
echo '<script>';
echo "jQuery(\"#".$grid."\").jqGrid('setGroupHeaders', {	useColSpanStyle: ".$useColSpanStyle.",
	groupHeaders:[";
	if(isset($column) && is_array($column)){
		foreach($column as $name=>$items){			echo "{startColumnName: '".$name."', numberOfColumns: ".$items['numberOfColumns'].", titleText: '".$items['titleText']."'},";
		}
    }
echo "	]
});";
echo '</script>';