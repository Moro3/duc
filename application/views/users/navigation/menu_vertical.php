<?php

echo '<div class="navigation_v">';
	echo '<div class="name">'.$objects_config['name'].'</div>';
   // echo '<pre>';
    //var_dump($menu);
    //echo '</pre>';
	if(isset($objects) && is_array($objects)){		echo $this->load->view('users/navigation/navigation_vertical', array('objects' => $objects));
	}
echo '</div>';