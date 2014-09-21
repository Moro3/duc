<?php
	$script = '
	   $(document).ready(function(e) {
	   });
	';


	$data = Modules::run('menus/menus/get_trees_place_data', 'side_left');
    /*
    echo '<pre>';
	print_r($this->bufer->pages);
	echo '</pre>';
	echo '<pre>';
	print_r($this->bufer->menus);
	echo '</pre>';
    */
    $current_node = (isset($this->bufer->menus['node_valid'])) ? $this->bufer->menus['node_valid'] : '0';
	$data_place = Modules::run('menus/menus/get_data_node',$current_node);
	if(isset($this->bufer->menus['uri_path']) && is_array($this->bufer->menus['uri_path'])){
		foreach($this->bufer->menus['uri_path'] as $vvv){			$path_menus_name[] = $vvv['name'];
			$path_menus_id[] = $vvv['id'];
		}
	}else{		$path_menus_name = array();
		$path_menus_id = array();
	}
	//var_dump($data_place);
  	//echo '<pre>';
  	//print_r($data);
  	//echo '</pre>';
  	if(is_array($data)){
		foreach($data as $key_place=>$items){
			$place = Modules::run('menus/menus_places/data_place_of_alias', $key_place);
			if(isset($place->active) && $place->active == 1){
				echo '<table class="menu_block" width="100%" cellpadding="0">';
				echo '<tr>';
				echo '<td height="37" colspan="3">';
					echo '<table width="100%" height="37" cellpadding="0" >';
					echo '<tr>';
					echo '<td width="1%" align="left" background="'.assets_img('pl1_centr.gif', false).'"><img src="'.assets_img('pl1_left.gif', false).'" width="11" height="37"></td>';
					echo '<td width="98%" align="left" background="'.assets_img('pl1_centr.gif', false).'">';

		                echo '<table class="menu_zag" width="240" height="37" cellpadding="0" >';
	  					echo '<tr>';
						echo '<td width="15"><img src="'.assets_img('bulet1.gif', false).'" width="12" height="12"></td>';
						echo '<td class="zag1">';
						if(isset($place->name)){
		                	echo mb_strtoupper($place->name);
		                }
						echo '</td>';
	  					echo '</tr>';
						echo '</table>';

				    echo '</td>';
					echo '<td width="1%" align="right" background="'.assets_img('pl1_centr.gif', false).'"><img src="'.assets_img('pl1_right.gif', false).'" width="11" height="37"></td>';
					echo '</tr>';
					echo '</table>';


				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td width="2" background="'.assets_img('02.gif', false).'"><img src="'.assets_img('00.gif', false).'" width="1" height="1"></td>';
				echo '<td align="center">';

					//echo '<pre>';
					//print_r($items[0]);
					//echo '<br />';
					//var_dump($data_place);
					//echo '</pre>';

                    $class_active = false;
                    foreach($path_menus_id as $node){                    	if(isset($items[0][$node])){                    		$class_active = true;
                    	}
                    }

                    if(is_object($data_place) && ($data_place->place_id == $place->id) || ($class_active === true)){                    	echo '<table width="100%" cellpadding="0">';
                    }else{                    	echo '<table class="menu_content" width="100%" cellpadding="0">';
                    }

					echo '<tr>';
					echo '<td height="10" colspan="3"><img src="'.assets_img('00.gif', false).'" width="1" height="10"></td>';
					echo '</tr>';
					echo '<tr>';
					echo '<td width="5%"><img src="'.assets_img('00.gif', false).'" width="10" height="1"></td>';
					echo '<td  width="90%" valign="top">';

			  			if(isset($items[0]) && is_array($items[0])){
			  				foreach($items[0] as $child=>$item){
					  			if(isset($item['data']['content'][0])){
						  			if(isset($item['data']['active']) && $item['data']['active'] == 1){
							  			echo '<table width="100%" cellpadding="0">';
							  			echo '<tr>';
										if(isset($items[$child])){											echo '<td width="6%" align="left"><img src="'.assets_img('bulet4-active.gif', false).'"></td>';
										}else{											echo '<td width="6%" align="left"><img src="'.assets_img('bulet4.gif', false).'"></td>';
										}

										//echo '<td width="94%" height="20" class="menu3">';
										//echo $this->bufer->pages['id'];
										//echo $child;

										if($this->bufer->pages['id'] == $item['data']['id'] || in_array($item['data']['id'], $path_menus_name)){
											echo '<td width="94%" 1height="20" class="menu3 menu-select">';
											echo $item['data']['content'][0]->name;
										}else{											echo '<td width="94%" 1height="20" class="menu3">';
											echo '<a href="'.Modules::run('pages/pages/getFieldUri', $item['data']['uri']).'" 1class="menu3">';
											echo $item['data']['content'][0]->name;
											echo '</a>';
										}
										echo '</td>';
							  			echo '</tr>';
							  			echo '<tr>';
										echo '<td colspan="2" background="'.assets_img('02.gif', false).'"><img src="'.assets_img('00.gif', false).'" width="238" height="1"></td>';
							  			echo '</tr>';
							  			echo '</table>';

							  			if(isset($items[$child])){                                        	//echo '<ul>';
                                        	foreach($items[$child] as $child_item){                                        		if(isset($child_item['data']['content'][0])){
						  							if(isset($child_item['data']['active']) && $child_item['data']['active'] == 1){

						  								echo '<table width="100%" cellpadding="0" style="margin-left:10px;font-size:90%;">';
											  			echo '<tr>';
														echo '<td width="6%" align="left"><img src="'.assets_img('bulet4.gif', false).'"></td>';
														echo '<td width="94%" height="20" class="menu3">';
														if(in_array($child_item['data']['id'], $path_menus_name)){															echo $child_item['data']['content'][0]->name;
														}else{															echo '<a href="'.Modules::run('pages/pages/getFieldUri', $child_item['data']['uri']).'">';
															echo $child_item['data']['content'][0]->name;
															echo '</a>';
														}

														echo '</td>';
											  			echo '</tr>';
											  			echo '<tr>';
														echo '<td colspan="2" background="'.assets_img('02.gif', false).'"><img src="'.assets_img('00.gif', false).'" width="238" height="1"></td>';
											  			echo '</tr>';
											  			echo '</table>';

						  							}
						  						}
                                        	}
                                        	//echo '</ul>';
							  			}
						  			}
					  			}
			  				}
                        }
					echo '</td>';
					echo '<td width="5%">&nbsp;</td>';
					echo '</tr>';
					echo '<tr>';
					echo '<td height="10" colspan="3"><img src="'.assets_img('00.gif', false).'" width="1" height="10"></td>';
					echo '</tr>';
					echo '</table>';

				echo '</td>';
				echo '<td width="1" background="'.assets_img('02.gif', false).'"><img src="'.assets_img('00.gif', false).'" width="1" height="1"></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td colspan="3" valign="bottom">';
					echo '<table width="100%" height="10" cellpadding="0" >';
					echo '<tr>';
					echo '<td width="5%" align="left" background="'.assets_img('pl3_centr.gif', false).'"><img src="'.assets_img('ugw_left2.gif', false).'" width="10" height="10"></td>';
					echo '<td width="90%" height="1" background="'.assets_img('pl3_centr.gif', false).'"><img src="'.assets_img('00.gif', false).'" width="1" height="1"></td>';
					echo '<td width="5%" align="right" background="'.assets_img('pl3_centr.gif', false).'"><img src="'.assets_img('ugw_right2.gif', false).'" width="10" height="10"></td>';
					echo '</tr>';
					echo '</table>';
				echo '</td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td height="15" colspan="3" valign="bottom"><img src="'.assets_img('00.gif', false).'" width="1" height="10"></td>';
				echo '</tr>';
				echo '</table>';
			}
    	}
  	}
