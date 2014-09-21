<?php

assets_style('user/groups_list.css');

echo '
   <script>
   	$(document).ready(function()
    	{
        	$("table.tablesorter").tablesorter();
    	}
	);
   </script>
';

if(isset($teachers) && is_array($teachers)){	echo '<div class="duc_table">';
	echo '<h1>Наши педагоги</h1>';
	echo '<div>';
	echo '<table class="tablesorter duc_table table table-striped table-bordered">';
		echo '<thead>';
		echo '<tr>';
			echo '<th>';
            	echo '№';
			echo '</th>';
			echo '<th>';
               echo 'Педагоги';
			echo '</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		$k = 0;
		foreach($teachers as $key=>$item){
			$k++;
			echo '<tr>';
				echo '<td>';
					echo $k;
				echo '</td>';
				echo '<td>';
                    echo '<a href="/'.$uri['point'].uri_replace($uri['teachers_id'],array($item['id']), 'user_objects').'">';
						echo $item['surname'].' '.$item['name'].' '.$item['name2'];
					echo '</a>';
				echo '</td>';
			echo '</tr>';
		}
		echo '</tbody>';
	echo '</table>';
	echo '</div>';
	echo '</div>';
}
