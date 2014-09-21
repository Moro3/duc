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