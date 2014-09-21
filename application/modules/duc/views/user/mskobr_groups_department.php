<?php

if(isset($data['department'])){
	echo '<h1>';
	echo $data['department']->name;
	echo '</h2>';
	echo 'Найдено '.count($data['groups']).' коллективов';
	$this->load->view('user/mskobr_groups_list', $data);
}else{
	echo 'Отдел выбран неверный';
}