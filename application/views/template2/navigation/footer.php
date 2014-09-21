<?php
  $data = Modules::run('menus/menus/get_trees_place_data', 'footer');
  //echo '<pre>';
  //print_r($contents);
  //echo '</pre>';



	if(is_array($data)){
		foreach($data as $items){
			foreach($items as $item){            	foreach($item as $page){            		if(isset($page['data']) && $page['data']['active'] == 1 && isset($contents)){

	            		if($contents['id_page'] !== $page['name']){
	            			echo '<a href="/'.trim($page['data']['uri'],'/').'/" class="menu2">';
	            		}
	            		//print_r($page);
	            		echo mb_ucfirst(mb_strtolower($page['data']['content'][0]->name));
	            		if($contents['id_page'] !== $page['id']){
	            			echo '</a>';
	            		}
	            		if ( next($item)) echo ' | ';
            		}
            	}
			}
		}

	}

