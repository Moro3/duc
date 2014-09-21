<?php

assets_style('admins/navigation/pagination', false);

if(isset($pagination) && is_array($pagination)){	if(isset($pagination['total_page']) && is_numeric($pagination['total_page'])){    	echo '<div class="pagination">';

    	for($i=1; $i<=$pagination['total_page']; $i++){        	if($i == $pagination['cur_page']){        		echo '<div class="page_active">';
        	}else{
        		echo '<a class="page" href="'.$uri['point'].uri_replace($uri['adverts_filter'], array('page' => $i,'order' => $index['order'], 'order_direct' => $index['order_direct']), $uri['index_name']).'">';
            }
        	echo $i;
           	if($i == $pagination['cur_page']){           	   	echo '</div>';
           	}else{
        		echo '</a>';
        	}
    	}
    	echo '</div>';
	}
}