<?php
//стиль для расписания занятий
assets_style('user/schedule.css', 'duc');
$style = '
  table.schedule a{  	font-size: 20px;
  }
  table.schedule tr th.group{
  	width:70px;
  }
  table.schedule tr th.day{
  }
  table.schedule tr td{  	border-width: 2px;
  	border-style: solid;
  	border-color: #E6EEEE;

  }
  table.schedule tr td.day{
  	1width:160px;
  	white-space:nowrap;
  	1word-wrap:normal;
  }
  table.schedule tr td.fio{
  		border-width: 0 2px;

  }
  .schedule div.fio{     position: relative;
  }
  table.schedule tr td.fio .foto{
  	position:absolute;
  	top:0px;
  	left:0px;
    padding:5px;
  }
   table.schedule tr td.fio .foto img{   		width: 100px;

   }
  table.schedule tr td.subject{
  	border-width: 0 2px;
  }
  table.schedule tr td.border-bottom-color{
  	1border-color: #E6EEEE #E6EEEE #FFF #E6EEEE;
  	border-bottom-color: #E6EEEE;
  }
  table.schedule tr td.border-top-color{
  	1border-color: #FFF #E6EEEE #E6EEEE #E6EEEE;
  	border-top-color: #E6EEEE;
  }
';
//assets_style($style);

echo '<style>';
	echo $style;
echo '</style>';

//echo '<pre>';
//print_r($data);
if(isset($data) && is_array($data) && count($data) > 0){	echo '<div class="duc_table">';

	//var_dump($uri['schedules']);
	echo '<a href="/'.$uri['point'].uri_replace($uri['schedules'], false, 'user_objects').'">';
	echo 'Расписание по всем коллективам';
	echo '</a>';
	echo '<table class="schedule table table-bordered">';
	echo '<colgroup>';
	echo '<col class="fio"></col>';
	echo '<col class="subject"></col>';
	echo '<col class="group"></col>';
	echo '<col span="7" class="day"></col>';
	echo '</colgroup>';
	echo '
    <thead>
      <tr>
        <th class="fio">Ф.И.О.</th>
        <th class="subject">Предмет</th>
        <th class="group">№ гр.</th>
        <th class="day">Понедельник</th>
        <th class="day">Вторник</th>
        <th class="day">Среда</th>
        <th class="day">Четверг</th>
        <th class="day">Пятница</th>
        <th class="day">Суббота</th>
        <th class="day">Воскресенье</th>
      </tr>
    </thead>
    ';
    echo '<tbody>';
	$surname_double = '';


	$group_double = '';
	foreach($data as $items){
		$class_surname = '';
		$style_surname = '';
		$class_group = '';
		$style_group = '';
        $end_surname_duble = false;
        $end_group_duble = false;

		echo '<tr>';
		$fio = $items->teacher__surname.' '.$items->teacher__name.' '.$items->teacher__name2;
		if($surname_double !== $fio){			//$class_surname = ' border-bottom-color';
			//$style_surname = 'border-color: #E6EEEE #E6EEEE #FFFFFF #E6EEEE;';
			$style_surname = 'border-width: 2px 2px 0 2px';
			$end_surname_duble = true;
		}
		if($group_double !== $items->groups__name){
			//$class_group = ' border-top-color';
			$style_group = 'border-width: 2px 2px 0 2px';
			$end_group_duble = true;
		}
		$surname_double = $fio;
		$group_double = $items->groups__name;
		//echo 'класс '.$class_surname;
		echo '<td 1rowspan="4" class="fio '.$class_surname.'" style="'.$style_surname.';';
		/*
		if(!empty($items->groups->teacher->foto)){
			$root = $config['path']['root'];
			$dir = $config['images']['config']['teachers']['path_resize'].$config['images']['resize']['teachers']['small']['dir'].'/';

			if(is_file($root.$dir.$items->groups->teacher->foto)){
   				echo 'background: url(\'/'.$dir.$items->groups->teacher->foto.'\') no-repeat;';
			}
		}
        */
		echo '">';
		    if($end_surname_duble === true){
				echo '<a href="/'.$uri['point'].uri_replace($uri['teachers_id'],array($items->teacher__id), 'user_objects').'">';
				echo $fio;
				echo '</a>';
				/*
				if(!empty($items->groups->teacher->foto)){
					$root = $config['path']['root'];
					$dir = $config['images']['config']['teachers']['path_resize'].$config['images']['resize']['teachers']['small']['dir'].'/';
					//echo $root.$dir.$item['foto'];
					echo '<div class="fio">';
					if(is_file($root.$dir.$items->groups->teacher->foto)){
                          	echo '<div class="foto">';
                          		echo '<img src="/'.$dir.$items->groups->teacher->foto.'" />';
                          	echo '</div>';
					}
					echo '</div>';
				}
				*/
			}
        echo '</td>';
		echo '<td 1rowspan="2" class="subject '.$class_group.'" style="'.$style_group.'">';
			if($end_group_duble === true){
				echo '<a href="/'.$uri['point'].uri_replace($uri['groups_id'],array($items->id_group), 'user_objects').'">';
				echo $items->groups__name;
				echo '</a>';
			}
		echo '</td>';
		echo '<td 1rowspan="2">';
		echo $items->numgroups->name;
		echo '</td>';
		echo '<td class="day">';
			if(!empty($items->mon_from)){
				echo $items->mon_from.' - '.$items->mon_to;
			}
		echo '</td>';
		echo '<td class="day">';
			if(!empty($items->tue_from)){
				echo $items->tue_from.' - '.$items->tue_to;
			}
		echo '</td>';
		echo '<td class="day">';
			if(!empty($items->wed_from)){
				echo $items->wed_from.' - '.$items->wed_to;
			}
		echo '</td>';
		echo '<td class="day">';
			if(!empty($items->thur_from)){
				echo $items->thur_from.' - '.$items->thur_to;
			}
		echo '</td>';
		echo '<td class="day">';
			if(!empty($items->fri_from)){
				echo $items->fri_from.' - '.$items->fri_to;
			}
		echo '</td>';
		echo '<td class="day">';
			if(!empty($items->sat_from)){
				echo $items->sat_from.' - '.$items->sat_to;
			}
		echo '</td>';
		echo '<td class="day">';
			if(!empty($items->sun_from)){
				echo $items->sun_from.' - '.$items->sun_to;
			}
		echo '</td>';

		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
	echo '</div>';
}else{
	echo 'Похоже расписания ещё нет, попробуйте зайти позже';
}