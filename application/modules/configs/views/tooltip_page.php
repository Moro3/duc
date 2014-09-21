<h5>Конфигурация</h5>
<?php
/*
if(isset($data) && is_array($data)){	echo '<ul>';
	foreach($data as $key=>$value){		echo '<li>';
			echo '{cfg name='.$value['name'].'} - '.$value['alias'] ;
		echo '</li>';
	}
	echo '</ul>';
}
*/
if(isset($data) && is_array($data)){
	echo '<select size="5">';
	foreach($data as $key=>$value){
		echo '<option>';
			echo '{cfg name='.$value['name'].'} - '.$value['content'] ;
		echo '</option>';
	}
	echo '</select>';
}