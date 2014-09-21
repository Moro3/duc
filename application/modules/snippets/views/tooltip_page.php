<h5>Сниппеты</h5>
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
			echo '{snip name='.$value['name'].'} - '.$value['alias'] ;
		echo '</option>';
	}
	echo '</select>';
}