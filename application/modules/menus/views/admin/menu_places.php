<?php
	if(isset($objects)){
		if(is_int($level/2)){
			if(($level/2) < 1){
				$class_row = 'row_main';
			}else{
				$class_row = 'row_sub2';
			}
		}else{
			if(($level/2) < 1){
				$class_row = 'row_sub';
			}else{
				$class_row = 'row_sub3';
			}
		}
		$num_color = 9 - ($level * 3);
		$color_row = '#C'.$num_color.'C'.$num_color.'B'.$num_color;
		//echo "Уровень ".$level;
		//echo '<div style="background-color: '.$color_row.';overflow: hidden; zoom: 1;">';
		echo '<div class="'.$class_row.'">';
		foreach($objects as $item){
			 //if($item['active_link']) $active_link = true;
             echo "<div class='button'>";
              if(is_numeric($group_id)) $group = 'group='.$group_id.'&';
              else $group = '';
              if($active_id == $item->id){
                  echo "<a class=\"link_sel\" href=\"".$uri['link']."?".$group."place=".$item->id."\">".$item->name."</a>";
                  //echo "<a id=\"select_menu\" class=\"ajaxtrigger\" href=\"/ajax/news/admin/".$value2['link']."\">".$value2['name']."</a>";
					//echo "<a id=\"select_menu\" class=\"ajaxtrigger\" href=#>".$value2['name']."</a>";

              }else{
                  echo "<a class=\"link\" href=\"".$uri['link']."?".$group."place=".$item->id."\">".$item->name."</a>";
                  //echo "<a id=\"menu\" class=\"ajaxtrigger\" href=\"/ajax/news/admin/".$value2['link']."\">".$value2['name']."</a>";
                  //echo "<a id=\"menu\" class=\"ajaxtrigger\" href=#>".$value2['name']."</a>";
              }
             echo "</div>";
		}
		echo "</div>";
	}
