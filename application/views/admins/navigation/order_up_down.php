<?php
assets_style('admins/navigation/order', false);

if(isset($order)){	echo '<div class="order">';
    	echo '<div class="down">';
    	if($order['by'] == $order['current_by'] && $order['direct'] == $order['allow_direct']['desc']){
    		echo '<a class="active"';
    	}else{
    		echo '<a class="deactive"';
    	}
    	echo ' href="'.$uri['point'].uri_replace($uri['adverts_filter'], array('page' => $index['page'],'order' => $order['by'], 'order_direct' => $order['allow_direct']['asc']), $uri['index_name']).'">';
    		echo '&#9650;';
    	echo '</a>';
        echo '</div>';

    	echo '<div class="up">';
    	if($order['by'] == $order['current_by'] && $order['direct'] == $order['allow_direct']['asc']){
    		echo '<a class="active"';
    	}else{    		echo '<a class="deactive"';
    	}
    	echo ' href="'.$uri['point'].uri_replace($uri['adverts_filter'], array('page' => $index['page'],'order' => $order['by'], 'order_direct' => $order['allow_direct']['desc']), $uri['index_name']).'">';
    		echo '&#9660;';
    	echo '</a>';
        echo '</div>';
	echo '</div>';
}