<?php
  $data = Modules::run('menus/menus/get_trees_place_data', 'top');
  //echo '<pre>';
  //print_r($contents);
  //echo '</pre>';



	if(is_array($data)){		echo '<table cellspacing="0" cellpadding="0" border="0">';
		echo '<tbody><tr>';
		foreach($data as $items){			foreach($items as $item){            	foreach($item as $page){            		if(isset($page['data']) && $page['data']['active'] == 1 && isset($contents)){
	            		echo '<td class="hmenu_td">';
	            		if($contents['id_page'] !== $page['name']){
	            			echo '<a href="/'.trim($page['data']['uri'],'/').'/">';
	            		}
	            		//print_r($page);
	            		echo mb_ucfirst(mb_strtolower($page['data']['content'][0]->name));
	            		if($contents['id_page'] !== $page['id']){
	            			echo '</a>';
	            		}
	            		echo '</td>';
            		}
            	}
			}
		}
		echo '</tr></tbody>';
		echo '</table>';
	}
