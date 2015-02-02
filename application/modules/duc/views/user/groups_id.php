<?php

assets_style('user/public.css');

if(isset($groups) && is_array($groups)){
	echo '<div class="duc_table">';
        echo '<h1>Виды деятельности</h1>';
        echo '<table class="table table-bordered">';

		echo '<tbody>';
		foreach($groups as $key=>$item){
			echo '<tr class="header">';
				echo '<th colspan=2 >';
					echo $item['name'];
				echo '</th>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>';
					echo 'Направленность деятельности:';
				echo '</td>';
				echo '<td>';
					echo $item['direction__name'];
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>';
					echo 'Вид деятельности:';
				echo '</td>';
				echo '<td>';
					echo $item['activity__name'];
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>';
				echo 'Образовательная программа:';
				echo '</td>';
				echo '<td>';
				echo $item['programm'];
				if($item['paid'] == 1){
					echo '<br />';
					echo '<div class="warning">';
						echo 'Занятия проводятся на внебюджетной основе';
					echo '</div>';
					if(!empty($item['price'])){
						//echo '<br />';
						echo '<div class="note">';
						echo 'Стоимость: '.$item['price'].' руб/месяц';
						echo '</div>';
					}
				}
				if(!empty($item['school'])){
					//echo '<br />';
					echo '<div class="note">';
						echo 'Занятия проводятся на базе школы '.$item['school'];
					echo '</div>';
				}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>';
				echo 'Возраст детей:';
				echo '</td>';
				echo '<td>';
				echo $item['age_from'].'-'.$item['age_to'].' лет';
				echo '</td>';
			echo '</tr>';
			if(!empty($item['year_create'])){
			echo '<tr>';
				echo '<td>';
				echo 'Год создания коллектива:';
				echo '</td>';
				echo '<td>';
				echo $item['year_create'];
				echo '</td>';
			echo '</tr>';
			}
			echo '<tr>';
				echo '<td>';
				echo 'Срок реализации:';
				echo '</td>';
				echo '<td>';
				echo $item['period'].' года/лет';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>';
				echo 'Руководитель:';
				echo '</td>';
				echo '<td>';
				echo $item['teacher__surname'].' '.$item['teacher__name'].' '.$item['teacher__name2'];
				//дополнительные педагоги если есть
					if(isset($item['dop_teachers']) && is_array($item['dop_teachers'])){
						foreach($item['dop_teachers'] as $dops){
                           	echo '<br />';
                           	//echo '<a href="/'.$uri['point'].uri_replace($uri['teachers_id'],array($dops->teachers->id), 'user_objects').'">';
                           	echo $dops->teachers->surname.' '.$dops->teachers->name.' '.$dops->teachers->name2;
                           	//echo '</a>';
                           	if(isset($dops->teachers->qualification)){
								//echo '<div class="note">';
								//echo 'Категория: '.$dops->teachers->qualification->name;
								//echo '</div>';
							}
						}
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td>';
				echo 'Описание:';
				echo '</td>';
				echo '<td>';
				if(!empty($item['description'])){
					echo $item['description'];
				}else{
					echo $item['short_description'];
				}

				echo '</td>';
			echo '</tr>';
            echo '<tr>';
				echo '<td>';
				echo 'Расписание:';
				echo '</td>';
				echo '<td>';
				/*
				echo '<a href="/'.$uri['point'].uri_replace($uri['schedules_group'],array($item['id']), 'user_objects').'">';
								echo 'посмотреть расписание';
				echo '</a>';
                echo '<br />';
                */
				echo '<a href="/'.$uri['point'].uri_replace($uri['schedules_groupname'],array($item['id']), 'user_objects').'">';
								echo 'посмотреть расписание группы';
				echo '</a>';

				echo '</td>';
			echo '</tr>';


		}
        echo '</tbody>';
		echo '</table>';

		if(!empty($id_group)){
			echo Modules::run('duc/duc_photos/tpl_gallery_group', $id_group);
		}
		
		/*
		if(!empty($item['photos'])){
			echo '<table>';
			echo '<tr>';
				echo '<td>';
				echo 'Фотографии коллектива:<br />';
				//echo '</td>';

				//echo '<td>';
				//echo '<pre>';
				//print_r($item['photos']);
				//echo '</pre>';
				$resize_config = Modules::run('duc/duc_settings/get_config_resize', 'groups');
				$resize_param = Modules::run('duc/duc_settings/get_param_resize', 'groups');
				foreach($item['photos'] as $photos){
					if(!empty($photos->img)){
						$path = $config['path']['root'];
						$dir = $resize_config['path'].'/'.$resize_param['small']['dir'].'/';
						if(is_file($path.$dir.$photos->img)){
							if($photos->active == 1){
								echo '<img src="/'.$dir.$photos->img.'" style="padding:7px;">';
							}
						}
					}
				}

				echo '</td>';
			echo '</tr>';
			echo '</table>';
		}
		*/

	echo '</div>';
}
