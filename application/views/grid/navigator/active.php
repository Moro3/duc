<?php

//------------- Обработчик события для multiselect (множественный выбор)
// добавление кнопок в навигацию
/*
Входящие данные
  обязательные:

  опционально:
  	$id : string - id стиля
	$caption : string - название кнопки
	$title : string - подсказка кнопки
	$buttonicon : string - конка кнопки
	$confirm : string - надпись при подтверждении
	$position : “first” or “last”
	$cursor : string (default pointer) determines the cursor when we mouseover the element
	$oper : string - название операции
	$field : string - (по умолчанию id) имя переменной которое будет передаваться на сервер

*/

?>
<script>
$grid.bind('jqGridLoadComplete', function(event, $form)
{
	var status = $('#active_grid_duc_groups');
	//alert(status.length);
	if(status.length == 0){
		$grid.jqGrid('navButtonAdd', pager,
		{
		    'caption'       : 'Вкл.',
		    'title'         : 'Active',
		    'buttonicon'    : 'ui-icon-play',
		    'id'			: 'active_grid_duc_groups',
		    //'loadonce'		: false
		    'onClickButton' : function()
		    {
	          	var ids  = $grid.jqGrid('getGridParam','selarrrow');
		        if(!confirm('Включить выбранные записи?')) return false;

				$.post($grid.getGridParam('url'),
		        {
		            'oper'  : 'active',
		            'id' : ids
		        },
		        function()
		        {
		            $grid.trigger('reloadGrid');
		        });
		    }
		});
	}
	$grid.jqGrid('navButtonAdd', pager,
	{
	    'caption'       : 'Откл.',
	    'title'         : 'Deactive',
	    'buttonicon'    : 'ui-icon-stop',
	    'id'			: 'deactive_grid_duc_groups',
	    //'loadonce'		: true
	    'onClickButton' : function()
	    {
          	var ids  = $grid.jqGrid('getGridParam','selarrrow');
	        if(!confirm('Отключить выбранные записи?')) return false;
			$.post($grid.getGridParam('url'),
	        {
	            'oper'  : 'deactive',
	            'id' : ids
	        },
	        function()
	        {
	            $grid.trigger('reloadGrid');
	        });
	    }
	});
});


</script>


