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

if(isset($objects) && is_array($objects)){	echo '<ul class="b">';
	foreach($objects as $key=>$value){		echo '<li';
		if($value['active']){			echo ' class="nav_active"';
		}
		echo '>';
		echo '<a href="'.$value['link'].'">';
		echo $value['name'];
		echo '</a>';
		echo '</li>';
		if(isset($value['children'])){        	//$this->load->view('navigation_vertical_sub', $value['children']);
		}
	}
	echo '</ul>';
}