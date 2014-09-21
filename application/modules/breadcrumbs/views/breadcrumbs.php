<?php
assets_style('breadcrumbs.css', 'breadcrumbs');

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
//echo '!!breadcrumbs';
echo '<div class="breadcrumbs_objects">';
	if(isset($objects) && is_array($objects)){        //echo '<pre>';
		//print_r($objects);
		$i = 0;
		$count_objects = count($objects);
		foreach($objects as $key=>$value){			//echo '<pre>';
			//print_r($count_objects);
			//echo '<br>';
			//print_r($i);
			//echo '</pre>';
			$i++;
			$last = ($i == $count_objects) ? true : false;
			foreach($value as $items){                if(isset($items['current_path']) && $items['current_path'] == true && $last !== true){
					echo '<span class="l_active">';
					echo '<a href="'.$items['link'].'">';
	   					echo $items['name'];
	   				echo '</a>';
   				}elseif(isset($items['current_path']) && $items['current_path'] == true && $last === true){
   				   echo '<span class="l_passive">';
                   echo $items['name'];
                   echo '</span>';
   				}else{
   				}

			}
			if($last === false){
				echo ' <span class="next">>></span> ';
			}
		}
	}

echo '</div>';

