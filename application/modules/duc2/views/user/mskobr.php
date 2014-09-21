<?php


$style = '
     .mskobr{
     	padding:5px;
     	margin:5px;
     }
     .mskobr .filter_group{        font-size:smaller;
        padding:5px;
        background-color: #f1ead7;
        border: 1px solid #e1c981;
        border-radius: 4px;
     }
     .mskobr .filter_group a{
        padding-right:30px;
        color: #c95b18;
     }
';


echo '<style>';
	echo $style;

echo '</style>';

//echo '<pre>';
//print_r($groups);
//echo '</pre>';
if(isset($departments) && is_array($departments)){        echo '<div class="mskobr">';
        echo '<h3>Шаблоны групп по отделам:</h3>';
        echo '<ul>';
		foreach($departments as $key=>$items){
			echo '<li>';
			echo '<a href="/'.$uri['point'].uri_replace($uri['mskobr_department'], array($items->id, 'all'), 'user_objects').'">';
			echo $items->name;
            echo '</a>';
            echo '<div class="filter_group">';
            	echo '<a href="/'.$uri['point'].uri_replace($uri['mskobr_department'], array($items->id, 'paid'), 'user_objects').'">';
				echo 'Платно';
            	echo '</a>';
            	echo '<a href="/'.$uri['point'].uri_replace($uri['mskobr_department'], array($items->id, 'free'), 'user_objects').'">';
				echo 'Бесплатно';
            	echo '</a>';
            echo '</div>';
            echo '</li>';
		}
		echo '</ul>';
        echo '</div>';
}

if(isset($directions) && is_array($directions)){
        echo '<div class="mskobr">';
        echo '<h3>Шаблоны групп по направлениям:</h3>';
        echo '<ul>';
		foreach($directions as $key=>$items){
			echo '<li>';
			echo '<a href="/'.$uri['point'].uri_replace($uri['mskobr_direction'], array($items->id, 'all'), 'user_objects').'">';
			echo $items->name;
            echo '</a>';
            echo '<div class="filter_group">';
            	echo '<a href="/'.$uri['point'].uri_replace($uri['mskobr_direction'], array($items->id, 'paid'), 'user_objects').'">';
				echo 'Платно';
            	echo '</a>';
            	echo '<a href="/'.$uri['point'].uri_replace($uri['mskobr_direction'], array($items->id, 'free'), 'user_objects').'">';
				echo 'Бесплатно';
            	echo '</a>';
            echo '</div>';
            echo '</li>';
		}
		echo '</ul>';
        echo '</div>';
}
