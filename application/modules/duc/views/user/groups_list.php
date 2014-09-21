<?php

assets_style('user/groups_list.css');

$config_teacher = Modules::run('duc/duc_settings/get_config_resize', 'teachers');
$resize_teacher = Modules::run('duc/duc_settings/get_param_resize', 'teachers');

echo '
   <script>
   	$(document).ready(function()
    	{
        	$("table.tablesorter").tablesorter();
    	}
	);
   </script>
';
//echo '<pre>';
//print_r($groups);
//echo '</pre>';
if(isset($groups) && is_array($groups)){	echo '<div class="duc_table">';
	echo '<h1>Виды деятельности</h1>';
	echo '<div>';
	echo '<table class="tablesorter table table-striped table-bordered">';
		echo '<thead>';
		echo '<tr>';
			echo '<th>';
            	echo '№';
			echo '</th>';
			echo '<th>';
            	echo 'Вид деятельности';
			echo '</th>';
			echo '<th>';
            	echo 'Направленность';
			echo '</th>';
			echo '<th>';
            	echo 'Профиль';
			echo '</th>';
			echo '<th>';
            	echo 'Педагог';
			echo '</th>';
            echo '<th>';
            	echo 'Фото';
			echo '</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		$k = 0;
		foreach($groups as $key=>$item){
			$k++;
			echo '<tr>';
				echo '<td>';
					echo $k;
				echo '</td>';
				echo '<td>';
					echo '<a href="/'.$uri['point'].uri_replace($uri['groups_id'],array($item['id']), 'user_objects').'">';
						echo $item['name'];
					echo '</a>';
					echo '<br />';
					echo '<div class="note">';
					    echo 'Вид деятельности: '.$item['activity__name'];
					    echo '<br />';
					    //echo 'Направленность: '.$item['direction']->name;
					    //echo '<br />';
					    //echo 'Профиль: '.$item['section']->name;
					    //echo '<br />';
					    echo 'Возраст детей: '.$item['age_from'].'-'.$item['age_to'].' лет';
					    if(!empty($item['school'])){
					    	echo '<br />';
					    	echo 'Занятия проводятся на базе школы № '.$item['school'];
					    }
					    if(isset($item['department'])){
					    	echo '<br />';
					    	echo '<div class="note">'.$item['department']->name.'</div>';
					    }
					echo '</div>';
				echo '</td>';
				echo '<td>';
					echo $item['direction']->name;
				echo '</td>';
				echo '<td>';
				    echo $item['section']->name;
				echo '</td>';
				echo '<td>';

					echo '<div class="teacher_value">';
					echo '<a href="/'.$uri['point'].uri_replace($uri['teachers_id'],array($item['teacher__id']), 'user_objects').'">';
						echo $item['teacher']->surname.' '.$item['teacher']->name.' '.$item['teacher']->name2;
						//echo $item['teacher__surname'].' '.$item['teacher__name'].' '.$item['teacher__name2'];
					echo '</a>';
					if(isset($item['teacher']->qualification)){
						echo '<div class="note">';
						echo 'Категория: '.$item['teacher']->qualification->name;
						echo '</div>';
					}
					echo '</div>';
					//дополнительные педагоги если есть
					if(isset($item['dop_teachers']) && is_array($item['dop_teachers'])){
						foreach($item['dop_teachers'] as $dops){
                           	echo '<br />';
                           	echo '<div class="teacher_value">';
                           	echo '<a href="/'.$uri['point'].uri_replace($uri['teachers_id'],array($dops->teachers->id), 'user_objects').'">';
                           	echo $dops->teachers->surname.' '.$dops->teachers->name.' '.$dops->teachers->name2;
                           	echo '</a>';
                           	if(isset($dops->teachers->qualification)){
								echo '<div class="note">';
								echo 'Категория: '.$dops->teachers->qualification->name;
								echo '</div>';
							}
							echo '</div>';
						}
					}
				echo '</td>';
                echo '<td>';
                    if(!empty($item['teacher']->foto)){
						$root = '';
						$path = $resize_teacher['mini']['path'].$resize_teacher['mini']['dir'].'/'.$item['teacher']->foto;
						//echo $path;
						if(is_file($path)){
                        	echo '<div style="float:left;width:70px;height:120px;">';
                        	echo '<img src="/'.$path.'"  />';
                        	echo '</div>';
						}
					}
					if(isset($item['dop_teachers']) && is_array($item['dop_teachers'])){
						foreach($item['dop_teachers'] as $dops){
							if(!empty($dops->teachers->foto)){
								$root = '';
								$path = $resize_teacher['mini']['path'].$resize_teacher['mini']['dir'].'/'.$dops->teachers->foto;
								//echo $path;
								if(is_file($path)){
		                        	echo '<div style="float:left;width:70px;height:120px;">';
		                        	echo '<img src="/'.$path.'"  />';
		                        	echo '</div>';
								}
							}
						}
					}
                echo '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
	echo '</table>';
	echo '</div>';
	echo '</div>';
}
