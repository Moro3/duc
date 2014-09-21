<?php
assets_style('admins/navigation/order', false);

if(isset($order)){	echo '<div class="order">';
    	if($order['by'] == $order['current_by']){
    			echo '<a class="active" href="'.$uri['point'].uri_replace($uri['adverts_filter'], array('page' => $index['page'],'order' => $order['by'], 'order_direct' => $order['direct']), $uri['index_name']).'">';
    			if($order['direct'] == $order['allow_direct']['desc']){
    				echo '&#9650;';
    			}else{
    				echo '&#9660;';
    			}
    			echo '</a>';
    	}else{
    			echo '<a class="deactive" href="'.$uri['point'].uri_replace($uri['adverts_filter'], array('page' => $index['page'],'order' => $order['by'], 'order_direct' => $order['allow_direct']['asc']), $uri['index_name']).'">';
    			//echo '&uarr;';
    			//echo '&darr;';
    			echo '&#9650;';
    			//echo '&#9660;';
                /*
    			if($order['direct'] == $order['allow_direct']['desc']){
    				echo 'href="'.$uri['point'].uri_replace($uri['adverts_filter'], array('page' => $index['page'],'order' => $order['by'], 'order_direct' => $order['direct']), $uri['index_name']).'">';
    				echo '&uarr;';
    			}else{
    				echo 'href="'.$uri['point'].uri_replace($uri['adverts_filter'], array('page' => $index['page'],'order' => $order['by'], 'order_direct' => $order['allow_direct']['desc']), $uri['index_name']).'">';

    				echo '&darr;';
    			}
                */
    			echo '</a>';
    	}
	echo '</div>';
}