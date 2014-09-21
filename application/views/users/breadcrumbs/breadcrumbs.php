<?php
assets_style('users/breadcrumbs/breadcrumbs.css', false);

/* Входящие параметры
  * Breadcrumbs - текущий путь до страницы (хлебные крошки)
	* @param array - массив, параметры для формирования пути
	*				num	 - ключ в массиве порядковый номер ветки следования пути
	*				sub_num - порядковый номер для ссылке в данной ветке (можно строить дополнительное меню при наведении мыши на данной ветви пути)
	*                	[num][sub_num] = array(	'name' - название
	*                					'link' - ссылка
	*                					'current_path' - boolean (относится ли ссылка к текущему пути)
	*                					'last'  - boolean (является ли ссылка последней в пути)
	*                           	)


*/
echo 'breadcrumbs';
echo '<div class="breadcrumbs_objects">';
	if(isset($objects) && is_array($objects)){		foreach($objects as $key=>$value){			//echo '<pre>';
			//print_r($value);
			//echo '</pre>';
			foreach($value as $items){                if(isset($items['current_path']) && $items['current_path'] == true && @$items['last'] !== true){
					echo '<span class="l_active">';
					echo '<a href="'.$items['link'].'">';
	   					echo $items['name'];
	   				echo '</a>';
   				}elseif(isset($items['current_path']) && $items['current_path'] == true && isset($items['last']) && $items['last'] === true){
   				   echo '<span class="l_passive">';
                   echo $items['name'];
                   echo '</span>';
   				}else{
   				}

			}
			if(!isset($items['last']) || $items['last'] !== true){
				echo ' <span class="next">>></span> ';
			}
		}
	}

echo '</div>';

