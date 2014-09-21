<?php
/**
*  Построение вертикального меню навигации
*  Параметры:
*  	array - массив значений
*		Значения:
*			name - название
*			active (boolean) - текущая ссылка
*			link - ссылка
*			current_path (boolean) - входит ли в состав меню
*			last (boolean) - последний в списке
*           children (array) - массив с детьми в виде текущего меню
*/

assets_style('users/navigation_v.css', false);
assets_img('nav_v.gif',false);

if(isset($objects) && is_array($objects)){
	echo '<ul>';
	foreach($objects as $key=>$value){		echo '<li';
		if($value['active']){			echo ' class="nav_active"';
		}
		echo '>';
		echo '<a href="'.$value['link'].'">';
		echo $value['name'];
		echo '</a>';
		echo '</li>';
		if(isset($value['children'])){        	$this->load->view('users/navigation/navigation_vertical_sub', $value['children']);
		}
	}
	echo '</ul>';

}