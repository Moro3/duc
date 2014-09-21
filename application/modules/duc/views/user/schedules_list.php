<?php

echo '<a href="/'.$uri['point'].uri_replace($uri['schedules'], false, 'user_objects').'">';
	//echo 'Расписание всех педагогов';
echo '</a>';
echo '<h1>';
	echo 'Расписание занятий всех коллективов';
echo '</h1>';

$this->load->view('user/schedules', $data);