<?php

assets_style('user/public.css');

if(isset($teachers) && is_array($teachers)){	echo '<div class="duc_table">';
        echo '<h1>Наши педагоги</h1>';
        echo '<table class="table table-bordered">';

		echo '<tbody>';
		foreach($teachers as $key=>$item){
			echo '<tr class="header">';
				echo '<th colspan=3 >';
					echo $item['surname'].' '.$item['name'].' '.$item['name2'];
				echo '</th>';
			echo '</tr>';
			if(isset($item['qualification']->name)){
				echo '<tr>';
					echo '<td>';
						echo 'Квалификационная категория:';
					echo '</td>';
					echo '<td>';
						echo $item['qualification']->name;
						//echo $item['qualification__name'];
					echo '</td>';
					echo '<td rowspan=2>';
						if(!empty($item['foto'])){							$root = $config['path']['root'];
							$dir = $config['images']['config']['teachers']['path_resize'].$config['images']['resize']['teachers']['small']['dir'].'/';
							//echo $root.$dir.$item['foto'];
							if(is_file($root.$dir.$item['foto'])){                            	echo '<img src="/'.$dir.$item['foto'].'" />';
							}
						}
						//echo $item['qualification__name'];
					echo '</td>';
				echo '</tr>';
			}
			if(isset($item['rank']->name)){
				echo '<tr>';
					echo '<td>';
						echo 'Звания:';
					echo '</td>';
					echo '<td>';
						echo $item['rank']->name;
						//echo $item['rank__name'];
					echo '</td>';
				echo '</tr>';
			}
			if(!empty($item['experience'])){
			echo '<tr>';
				echo '<td>';
				echo 'Педагогический стаж:';
				echo '</td>';
				echo '<td>';
				echo $item['experience'];
				echo '</td>';
			echo '</tr>';
			}
			if(!empty($item['description'])){
			echo '<tr>';
				echo '<td>';
				echo 'Немного о педагоге:';
				echo '</td>';
				echo '<td>';
				echo $item['description'];
				echo '</td>';
			echo '</tr>';
            }

			if(isset($item['groups']) && is_array($item['groups'])){
			echo '<tr>';
				echo '<td>';
					echo 'Коллективы которые ведет данный педагог:';
				echo '</td>';
				echo '<td>';
					foreach($item['groups'] as $key=>$items){
						if($items->active == 1){
							echo '<a href="/'.$uri['point'].uri_replace($uri['groups_id'],array($items->id), 'user_objects').'">';
								echo $items->name;
							echo '</a>';
							echo '<br />';
						}
					}
				echo '</td>';
			echo '</tr>';
            }
            echo '<tr>';
				echo '<td>';
				echo 'Расписание:';
				echo '</td>';
				echo '<td>';
				echo '<a href="/'.$uri['point'].uri_replace($uri['schedules_teacher'],array($item['id']), 'user_objects').'">';
								echo 'посмотреть расписание педагога';
				echo '</a>';

				echo '</td>';
			echo '</tr>';
		}
        echo '</tbody>';
		echo '</table>';


	echo '</div>';
}
