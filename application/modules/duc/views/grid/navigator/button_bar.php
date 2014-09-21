<?php

//------------- Обработчик события для multiselect (множественный выбор)
// добавление кнопок в навигацию
/*
Входящие данные
  обязательные:
	$id : string - id стиля (рекомендуется добавлять строку с именем грида по аналогии с др. кнопками $id_"grid")
	$oper : string - название операции
  опционально:  	
	$caption : string - название кнопки
	$title : string - подсказка кнопки
	$buttonicon : string - конка кнопки
	$confirm : string - надпись при подтверждении
	$position : “first” or “last”
	$cursor : string (default pointer) determines the cursor when we mouseover the element
	
	$field : string - (по умолчанию id) имя переменной которое будет передаваться на сервер

*/
if(!isset($id)){
	echo '<script>alert("не выбран параметр \"id\" при установки кнопки button_bar")</script>';
	return;
}
if(!isset($oper)){
 	echo '<script>alert("не выбран параметр \"oper\" при установки кнопки button_bar")</script>';
	return;
}
$allow_options = array('id', 'caption', 'title', 'buttonicon', 'position', 'cursor');
if(!isset($field)) $field = 'id';
if(!isset($confirm)) $confirm = 'Подтверждаете нажатие кнопки?';
if(!isset($confirm)) $confirm = 'Подтверждаете нажатие кнопки?';

$options = '';
foreach($allow_options as $option){
	if(isset($$option)){
		$options .= '"'.$option.'" : "'.$$option.'",';
	}
}

$script = '<script>';

$script .= '$grid.bind("jqGridLoadComplete", function(event, $form)
	{
		var status = $("#'.$id.'");
		//alert("'.$id.'");
		if(status.length == 0){
			$grid.jqGrid("navButtonAdd", pager,
			{
			    '.$options.'
			    "onClickButton" : function()
			    {
		          	var ids  = $grid.jqGrid("getGridParam","selarrrow");
			        if(!confirm("'.$confirm.'")) return false;

					$.post($grid.getGridParam("url"),
			        {
			            "oper"  : "'.$oper.'",
			            "'.$field.'" : ids
			        },
			        function()
			        {
			            $grid.trigger("reloadGrid");
			        });
			    }
			});
		}
	
	});
';


$script .= '</script>';

echo $script;


