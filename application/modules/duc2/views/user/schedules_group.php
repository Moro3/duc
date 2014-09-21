<?php

echo '<a href="/'.$uri['point'].uri_replace($uri['schedules'], false, 'user_objects').'">';
	echo 'Расписание всех педагогов';
echo '</a>';
if(!empty($group)){
	if($group->active == 1){
		echo '<h1>';

					echo 'Расписание занятий коллектива:<br />';
					echo $group->name;


		echo '</h1>';
		echo '<div class="duc_table">';
			echo 'Возраст детей: ' . $group->age_from.'-'.$group->age_to.'<br />';
			echo 'Срок обучения: ' . $group->period.' год/лет<br />';
			if($group->paid == 1){				echo '<div class="warning">Занятия проводятся на внебюджетной основе</div>';
				//echo 'Стоимость занятий - '.$group->price. ' руб./месяц';
			}

		echo '</div>';
		$this->load->view('user/schedules', $data);
	}else{		echo '<div class="notice">';
			echo 'Группа не верная';
		echo '</div>';
	}
}else{
	echo '<div class="notice">';
		echo 'Группа не найдена';
	echo '</div>';
}

