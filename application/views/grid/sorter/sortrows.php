<?php
//===== Сортировка строк =============
/*
  входные параметры:
  	$selector - css селектор
    $grid - имя грида
  	$url - url запроса



*/
$grid = (isset($grid)) ? $grid : false;
$url = (isset($url)) ? $url : false;
$selector = (isset($selector)) ? $selector : '.ui-state-highlight';


echo '<script>';


echo "
jQuery(document).ready(function(){
  jQuery(\"#".$grid."\").jqGrid('sortableRows', {
        connectWith: '".$selector."',
        update: function (event, ui) {
          var posturl = '".$url."';
        	var neworder = $(\"#".$grid."\").jqGrid(\"getDataIDs\").join(',');
        	$.post(
        		posturl,
        		{
        			'oper'  : 'sorter',
        			'typesorter'  : 'rows',
	            'id' : neworder,
            },
        		function(message,status) {

            		if(status != 'success') {
                		alert(message);
              		}else{
              			$(\"#".$grid."\").trigger(\"reloadGrid\");
              		}
              		//$grid.trigger('reloadGrid');
          	}
          )
        }
  });
});
";


/*
echo "
  jQuery(document).ready(function(){
    jQuery( \"#".$grid."\" ).sortable({
      update: function( event, ui ) {}
    });
  });
";
*/
echo '</script>';
