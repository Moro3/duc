<?php

//------------- Обработчик события для multiselect (множественный выбор)
// добавление кнопок в навигацию
/*
Входящие данные
  обязательные:

  опционально:

*/

?>
<script>
$grid.bind('jqGridLoadComplete', function(event, $form)
{
	$grid.jqGrid('navButtonAdd', pager,
	{
	    'caption'       : 'Вкл.',
	    'title'         : 'Active',
	    'buttonicon'    : 'ui-icon-play',
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
	$grid.jqGrid('navButtonAdd', pager,
	{
	    'caption'       : 'Откл.',
	    'title'         : 'Deactive',
	    'buttonicon'    : 'ui-icon-stop',
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


