<?php

echo '<a href="/'.$uri['point'].uri_replace($uri['schedules'], false, 'user_objects').'">';
	echo 'Расписание всех педагогов';
echo '</a>';
if(!empty($teacher)){
	if($teacher->active == 1){
		echo '<h1>';			echo 'Расписание занятий педагога:<br />';
			echo $teacher->surname.' '.$teacher->name.' '.$teacher->name2;
		echo '</h1>';

		$this->load->view('user/schedules', $data);
	}else{
		echo '<div class="notice">';
			echo 'Педагог не найден в базе';
		echo '</div>';
	}
}else{
	echo '<div class="notice">';
		echo 'Педагог не найден';
	echo '</div>';
}


