<?php

if(isset($data['direction'])){	echo '<h1>';
	echo $data['direction']->name;
	echo '</h2>';
	echo 'Найдено '.count($data['groups']).' коллективов';
	$this->load->view('user/mskobr_groups_list', $data);
}else{	echo 'Направленность выбрана неверная';
}