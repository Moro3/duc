<?php
    assets_img('fon_navigation2.gif');
    assets_img('fon_navigation1.gif');

    //скрипт фиксации навигационной панели в топ браузера при прокрутке
    //регистрируем точку с помощью метода  .waypoint()для элемента.
    $style = '
      	nav {
		  width:100%;
          z-index:10;
		}
      	.sticky {
		  position: fixed;
		  top: 0;
		}
		.1sticky .nav-above {
		  position: absolute;
		  top:-15px;
		  left:1em;
		  right:1em;
		  height:15px;
		  background: linear-gradient(top, rgba(255,255,255,1) 0%,rgba(255,255,255,0) 100%);
		  /* добавляем градиент, если нужно */
		}
    ';
    assets_style($style, false);
    $script = '
      	$(function() {
		  	var nav_container = $(".nav_bar");
		  	var nav = $("nav");
		  	var top_spacing = 0;
			var waypoint_offset = 0;
			nav_container.waypoint({
			  handler: function(direction) {
			    if (direction == "down") {
			      nav_container.css({ "height" : nav.outerHeight() });
			      nav
			      	 .stop()
			      	 .addClass("sticky")
			         .css("top", -nav.outerHeight())
			         .animate({"top" : top_spacing});
				  //nav.find(".block_up2").css({"background-color":"rgba(241,243,164, 0.7)"});
			    } else {
			      nav_container.css({ "height" : "auto" });
			      nav
			      	 .stop()
			      	 .removeClass("sticky")
			         .css("top", nav.outerHeight() + waypoint_offset)
			         .animate({"top" : ""});
			    }
			  },
			  offset: function() {
			    return -(nav.outerHeight() + waypoint_offset);
			  }
			});

		});
    ';

    assets_script($script, false);


?>

<div class='block_button'>



	<div class='button_center'>



<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$('div.button').hover(
		function(){
		  $(this).attr("class", "button_select");
		},
		function(){
          $(this).attr("class", "button");
	});


});
</script>
<?php
$corner = <<<corner

                                      $(this).corner('10px');
                                      //$(".button_jquery").corner("round 8px").parent().css('padding', '1px').corner("round 10px");
                                      //$(".button_jquery").corner("round 10px").parentsUntil(".button_border_jquery").last().parent().css('padding', '1px').corner("round 10px");
                                      //$("div.button_jquery").corner("round 10px").closest("div.button_border_jquery").css('padding', '3px').corner("round 10px");

corner;

//assets_script($corner);

$data = Modules::run('menus/menus_api/get_trees_group_place_data', 'top');
        
	//dd($this->bufer->pages);
	
	//dd($this->bufer->menus);
	
    
    $current_node = (isset($this->bufer->menus['node_valid'])) ? $this->bufer->menus['node_valid'] : '0';
	$data_place = Modules::run('menus/menus/get_data_node',$current_node);
	if(isset($this->bufer->menus['uri_path']) && is_array($this->bufer->menus['uri_path'])){
		foreach($this->bufer->menus['uri_path'] as $vvv){
			$path_menus_name[] = $vvv['name'];
			$path_menus_id[] = $vvv['id'];
		}
	}else{
		$path_menus_name = array();
		$path_menus_id = array();
	}
	//dd($data_place);
  	
  	dd($data);
  	
  	if(is_array($data)){
		foreach($data as $place_name=>$items){
			if($place_name == 0){
				if(isset($items[0])){					
					foreach($items[0] as $item){						
						echo "<div class='button_main'>";
							echo "<div class='button_border'>";
				               	echo "<a href='".$item['data']['link']."'>";
									echo "<div class='button'>";
										echo "<div class='nav_img'>";
					                        if(isset($item['data']['img'])){
					                        	echo "<img src='".assets_img($item['data']['img'])."' />";
					                        }else{
					                        	echo "<img src='".assets_img('navigation-8.gif')."' />";
					                        }
					                        
					                    echo "</div>";
										echo "<div class='nav_text'>";
											echo $item['data']['name'];
										echo "</div>";
									echo "</div>";
				               	echo "</a>";
							echo "</div>";
						echo "</div>";
					}
				}
			}
		}
	}


?>

<script type="text/javascript" language="javascript">
                                        $(".button_jquery").corner("round 10px");
                                        $(".button_border_jquery").corner("round 10px");

                                     </script>


        <div class='button_main'>
			<div class='button_border'>
               <a href='/administration/'>
				<div class='button'>

					<div class='nav_img'>

                                            <img src='<?php echo assets_img('navigation-8.gif');?>' />

                                        </div>
					<div class='nav_text'>

                                            Администрация

					</div>
				</div>
               </a>
			</div>
		</div>


		  <div class='button_main'>
			<div class='button_border'>
				<a href='/duc/groups/'>
				<div class='button'>

					<div class='nav_img'>
						<img src='<?php echo assets_img('navigation-2.gif');?>' />
					</div>
					<div class='nav_text'>
					   Виды деятельности
					</div>
				</div>
				</a>
			</div>
		  </div>

         <div class='button_main'>
			<div class='button_border'>
               <a href='/duc/teachers/'>
				<div class='button'>

					<div class='nav_img'>

                                            <img src='<?php echo assets_img('navigation-6.gif');?>' />

                                        </div>
					<div class='nav_text'>

                                            Наши педагоги

					</div>
				</div>
               </a>
			</div>
		   </div>

		  <!--
		  <div class='button_main'>
			<div class='button_border'>
				<a href='/schedule/'>
				<div class='button'>
					<div class='nav_img'>
						<img src='<?php echo assets_img('navigation-3.gif');?>' />
					</div>
					<div class='nav_text'>
					   Расписание занятий
					</div>
				</div>
				</a>
			</div>
		  </div>
          -->

          <div class='button_main'>
			<div class='button_border'>
				<a href='/duc/schedules/'>
				<div class='button'>
					<div class='nav_img'>
						<img src='<?php echo assets_img('navigation-3.gif');?>' />
					</div>
					<div class='nav_text'>
					   Расписание занятий
					</div>
				</div>
				</a>
			</div>
		  </div>


		  <div class='button_main'>
			<div class='button_border'>
               <a href='/activity/'>
				<div class='button'>
					<div class='nav_img'>

                                                <img src='<?php echo assets_img('navigation-4.gif');?>' />

                                        </div>
					<div class='nav_text'>

                                                Мероприятия

					</div>
				</div>
               </a>
			</div>
		  </div>

         <div class='button_main'>
			<div class='button_border_t'>
				<a href='/pay_service/'>
				<div class='button'>

					<div class='nav_img'>
						<img src='<?php echo assets_img('navigation-7.gif');?>' />
					</div>
					<div class='nav_text'>
					   Платные услуги
					</div>
				</div>
				</a>
			</div>
		  </div>

		  <div class='button_main'>
			<div class='button_border'>
				<a href='/contacts/'>
				<div class='button'>
					<div class='nav_img'>
						<img src='<?php echo assets_img('navigation-5.gif');?>' />
					</div>
					<div class='nav_text'>
					   Контакты
					</div>
				</div>
				</a>
			</div>
		  </div>

	</div>
</div>