<?php
//===== Сортировка строк =============
/*
  входные параметры:
  	$grid - имя грида
  	$url - url запроса



*/
$grid = (isset($grid)) ? $grid : false;
$url = (isset($url)) ? $url : false;
$id_multi_select = 'ui-state-highlight';


echo '<script>';

echo "
jQuery(\"#".$grid."\").jqGrid('sortableRows', {
       connectWith: '.ui-state-highlight',
       update: function (event, ui) {
          	var posturl = '".$url."';
        	var neworder = $(\"#".$grid."\").jqGrid(\"getDataIDs\").join(',');
        	$.post(
        		posturl,
        		{        			'oper'  : 'sorter',
        			'typesorter'  : 'rows',
	            	'id' : neworder,
	            },
        		function(message,status) {

            		if(status != 'success') {
                		alert(message);
              		}else{              			$(\"#".$grid."\").trigger(\"reloadGrid\");
              		}
              		//$grid.trigger('reloadGrid');
          		}
          		)
      }
});
";
echo '</script>';
