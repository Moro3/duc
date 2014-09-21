<?php
$resize_config = Modules::run('duc/duc_settings/getConfig');


$style = '
  table.main{  	padding: 10px;
    background-color: #f3f3f3;
  }
  table.main tr{
  	1padding: 10px;
  	border:1px solid #ffffff;
  }
  table.main tr td{
  	padding: 5px 10px;
  	1border:1px solid #006600;
  }
  table.sub{
  	padding: 10px;
  	background-color: #cfcfcf;
  	width:100%;
  }
   table.sub tr{
  	border:1px solid #1d5dbc;

  }
   table.sub tr td{
  	padding: 3px 7px;
  }
  table.sub table{
  	background-color: #f9f9f9;
  }
  table.sub table table{
  	border:2px solid #5a89e8;
  	width:100%;
  }
  table td.value{  	width:100px;
  }
  table td.button_resize input{  	padding: 2px 20px;
  	margin: 0 20px;
  }

	#loadImg{position:absolute; z-index:1000; display:none}
';
assets_style($style, false);

$script = '
  	$(document).ready(function(){                          // по завершению загрузки страницы
    	$(".action_resize #clear").click(function(){                  // вешаем на клик по элементу с id
		  	var divresize = $(this).parent();
			var group = divresize.find("#group").val();
			var resize = divresize.find("#resize").val();
		  	if(confirm("Очистить папку [" + resize + "] с изображениями?")){

			  	$.ajax({			  	 	type: "POST",
			  	 	url: "/ajax/?resource=action_photos_resize/ajax/duc",
			  	 	data: {"action": "clear", "group": group, "resize": resize},
			  	 	beforeSend: function (){                        startLoadingAnimation();
			  	 	},
			  	 	success: function (data){                    	stopLoadingAnimation();
                    	divresize.find(".answer").empty().append(data);
			  	 	},
			  	 	error: function (){                        stopLoadingAnimation();
			  	 	}
			  	});
		  	}
		})
		$(".action_resize #rebuild").click(function(){                  // вешаем на клик по элементу с id
		  	//alert("нажали на #rebuild");
		  	var divresize = $(this).parent();
		  	var group = divresize.find("#group").val();
		  	var resize = divresize.find("#resize").val();
		  	$.ajax({
		  	 	type: "POST",
		  	 	url: "/ajax/?resource=action_photos_resize/ajax/duc",
		  	 	data: {"action": "rebuild", "group": group, "resize": resize},
		  	 	beforeSend: function (){
                    startLoadingAnimation();
		  	 	},
		  	 	success: function (data){
                    stopLoadingAnimation();
                    divresize.find(".answer").empty().append(data);
		  	 	},
		  	 	error: function (){
                    stopLoadingAnimation();
		  	 	}
		  	});
		})
		$(".action_resize #rebuildOrig").click(function(){                  // вешаем на клик по элементу с id
		  	//alert("нажали на #rebuild");
		  	var divresize = $(this).parent();
		  	var group = divresize.find("#group").val();
		  	$.ajax({
		  	 	type: "POST",
		  	 	url: "/ajax/?resource=action_photos_resize/ajax/duc",
		  	 	data: {"action": "rebuildOrig", "group": group},
		  	 	beforeSend: function (){
                    startLoadingAnimation();
		  	 	},
		  	 	success: function (data){
                    stopLoadingAnimation();
                    divresize.find(".answer").empty().append(data);
		  	 	},
		  	 	error: function (){
                    stopLoadingAnimation();
		  	 	}
		  	});
		})
	});

	function startLoadingAnimation() // - функция запуска анимации
	{
	  	$.arcticmodal({
        	content: \'<div style="width:200px; height:200px;"></div>\',
        	closeOnEsc : false,
        	closeOnOverlayClick: false
		});
	  // найдем элемент с изображением загрузки и уберем невидимость:
	  var imgObj = $("#loadImg");
	  imgObj.show();

	  // вычислим в какие координаты нужно поместить изображение загрузки,
	  // чтобы оно оказалось в серидине страницы:
	  var centerY = $(window).scrollTop() + ($(window).height() + imgObj.height())/2;
	  var centerX = $(window).scrollLeft() + ($(window).width() + imgObj.width())/2;

	  // поменяем координаты изображения на нужные:
	  imgObj.offset({top:centerY, left:centerX});
	}

	function stopLoadingAnimation() // - функция останавливающая анимацию
	{
	  $.arcticmodal("close");
	  $("#loadImg").hide();
	}
';
assets_script($script, false);

echo '<div class="setting">';
echo validation_errors();
//echo '<form class="form-horizontal" action="" method="POST">';

    if(is_array($resize_config) && isset($resize_config['images']['config'])){
		echo '<table class="main">';
	    if(isset($resize_config['images']['config']['!'])){	    	echo '<tr>';
	    		echo '<td>';
	    	 	echo '<h2>Общие параметры оригинальных изображений</h2>';
	    	 	echo '</td>';
	    	echo '</tr>';
	    	if(isset($resize_config['images']['config']['!']['allowed_types'])){	    	 	echo '<tr><td>';
	    			echo lang('duc_allowed_types');
	    		echo '</td>';
	    		echo '<td>';
	    			echo $resize_config['images']['config']['!']['allowed_types'];
	    		echo '</td></tr>';
	    	}
	    	if(isset($resize_config['images']['config']['!']['max_size'])){
	    	 	echo '<tr><td>';
	    			echo lang('duc_max_size');
	    		echo '</td>';
	    		echo '<td>';
	    			echo $resize_config['images']['config']['!']['max_size'].' Кб';
	    		echo '</td></tr>';
	    	}
	    	if(isset($resize_config['images']['config']['!']['max_width'])){
	    	 	echo '<tr><td>';
	    			echo lang('duc_max_width');
	    		echo '</td>';
	    		echo '<td>';
	    			echo $resize_config['images']['config']['!']['max_width'].' px';
	    		echo '</td></tr>';
	    	}
	    	if(isset($resize_config['images']['config']['!']['max_height'])){
	    	 	echo '<tr><td>';
	    			echo lang('duc_max_height');
	    		echo '</td>';
	    		echo '<td>';
	    			echo $resize_config['images']['config']['!']['max_height'].' px';
	    		echo '</td></tr>';
	    	}
	    	if(isset($resize_config['images']['config']['!']['dir'])){
	    	 	echo '<tr><td>';
	    			echo lang('duc_resize_dir');
	    		echo '</td>';
	    		echo '<td>';
	    			echo $resize_config['images']['config']['!']['dir'];
	    		echo '</td></tr>';
	    	}
	    	if(isset($resize_config['images']['config']['!']['x'])){
	    	 	echo '<tr><td>';
	    			echo lang('duc_resize_x');
	    		echo '</td>';
	    		echo '<td>';
	    			echo $resize_config['images']['config']['!']['x'];
	    		echo '</td></tr>';
	    	}
	    	if(isset($resize_config['images']['config']['!']['y'])){
	    	 	echo '<tr><td>';
	    			echo lang('duc_resize_y');
	    		echo '</td>';
	    		echo '<td>';
	    			echo $resize_config['images']['config']['!']['y'];
	    		echo '</td></tr>';
	    	}
	    	if(isset($resize_config['images']['config']['!']['max_img'])){
	    	 	echo '<tr><td>';
	    			echo lang('duc_max_img');
	    		echo '</td>';
	    		echo '<td>';
	    			echo $resize_config['images']['config']['!']['max_img'];
	    		echo '</td></tr>';
	    	}

	    }
			echo '<tr><td colspan=2>';
			echo '<table class="sub">';
			foreach($resize_config['images']['config'] as $name=>$items){
	            if($name === '!') continue;
			    echo '<tr>';
		    	echo '<td>';
		    	echo '<h3>'.lang('duc_resize'). ' группы';
		    	echo (isset($items['name'])) ? ' '.$items['name'].'' : '';
		    	echo ' ['.$name.']';
		    	echo '</h3>';
		        echo '</td>';
		        echo '</tr>';

		    	echo '<tr><td class="button_resize">';
				    			echo '<div class="action_resize">';
				    				echo '<input type="submit" id="rebuildOrig" name="rebuildOrig" value="Перестроить" />';
				    				echo '<input type="hidden" id="group" name="group" value="'.$name.'" />';
				    				 echo '<div class="answer"></div>';
				    			echo '</div>';
                                echo '<img id="loadImg" src="'.assets_img('ajax-loader-big.gif', false).'" />';

				    		//echo '</td>';
				    		//echo '<td>';

				    	echo '</td></tr>';
		    	if(isset($items['allowed_types'])){
		    	 	echo '<tr><td>';
		    			echo lang('duc_allowed_types');
		    		echo '</td>';
		    		echo '<td>';
		    			echo $items['allowed_types'];
		    		echo '</td></tr>';
		    	}
		    	if(isset($items['max_size'])){
		    	 	echo '<tr><td>';
		    			echo lang('duc_max_size');
		    		echo '</td>';
		    		echo '<td>';
		    			echo $items['max_size'];
		    		echo '</td></tr>';
		    	}
		    	if(isset($items['max_width'])){
		    	 	echo '<tr><td>';
		    			echo lang('duc_max_width');
		    		echo '</td>';
		    		echo '<td>';
		    			echo $items['max_width'];
		    		echo '</td></tr>';
		    	}
		    	if(isset($items['max_height'])){
		    	 	echo '<tr><td>';
		    			echo lang('duc_max_height');
		    		echo '</td>';
		    		echo '<td>';
		    			echo $items['max_height'];
		    		echo '</td></tr>';
		    	}
		    	if(isset($items['path'])){
		    	 	echo '<tr><td>';
		    			echo lang('duc_images_path');
		    		echo '</td>';
		    		echo '<td>';
		    			echo $items['path'];
		    		echo '</td></tr>';
		    	}
		    	if(isset($items['dir'])){
		    	 	echo '<tr><td>';
		    			echo lang('duc_resize_dir');
		    		echo '</td>';
		    		echo '<td>';
		    			echo $items['dir'];
		    		echo '</td></tr>';
		    	}
		    	if(isset($items['x'])){
		    	 	echo '<tr><td>';
		    			echo lang('duc_resize_x');
		    		echo '</td>';
		    		echo '<td>';
		    			echo $items['x'];
		    		echo '</td></tr>';
		    	}
		    	if(isset($items['y'])){
		    	 	echo '<tr><td>';
		    			echo lang('duc_resize_y');
		    		echo '</td>';
		    		echo '<td>';
		    			echo $items['y'];
		    		echo '</td></tr>';
		    	}
		    	if(isset($items['max_img'])){
		    	 	echo '<tr><td>';
		    			echo lang('duc_max_img');
		    		echo '</td>';
		    		echo '<td>';
		    			echo $items['max_img'];
		    		echo '</td></tr>';
		    	}
		    	if(isset($resize_config['images']['resize'][$name])){
					echo '<tr><td colspan=2>';
					echo '<h3>Варианты ресайзов</h3>';
					echo '<table class="sub">';
					foreach($resize_config['images']['resize'][$name] as $name_resize=>$item_resize){
	                	echo '<tr>';
				    	echo '<td>';
				    	echo '<h4>Ресайз:';
				    	echo (isset($item_resize['name'])) ? ' '.$item_resize['name'].'' : '';
				    	echo ' ['.$name_resize.']';
				    	echo '</h4>';
				        echo '</td>';
				        echo '</tr>';

                        echo '<tr><td class="button_resize">';
				    			echo '<div class="action_resize">';
				    				echo '<input type="submit" id="clear" name="clear" value="Очистить" />';
				    				echo '<input type="submit" id="rebuild" name="rebuild" value="Перестроить" />';
				    				echo '<input type="hidden" id="group" name="group" value="'.$name.'" />';
				    				echo '<input type="hidden" id="resize" name="resize" value="'.$name_resize.'" />';
				    				 echo '<div class="answer"></div>';
				    			echo '</div>';
                                echo '<img id="loadImg" src="'.assets_img('ajax-loader-big.gif', false).'" />';

				    		//echo '</td>';
				    		//echo '<td>';

				    	echo '</td></tr>';

				        echo '<tr><td>';
				        echo 'Параметры ресайза';
						echo '<table class="param">';
				        if(isset($item_resize['dir'])){
				    	 	echo '<tr><td class="item">';
				    			echo lang('duc_resize_dir');
				    		echo '</td>';
				    		echo '<td class="value">';
				    			echo $item_resize['dir'];
				    		echo '</td></tr>';
				    	}
				    	if(isset($item_resize['x'])){
				    	 	echo '<tr><td>';
				    			echo lang('duc_resize_x');
				    		echo '</td>';
				    		echo '<td>';
				    			echo $item_resize['x'];
				    		echo '</td></tr>';
				    	}
				    	if(isset($item_resize['y'])){
				    	 	echo '<tr><td>';
				    			echo lang('duc_resize_y');
				    		echo '</td>';
				    		echo '<td>';
				    			echo $item_resize['y'];
				    		echo '</td></tr>';
				    	}
				    	//if(isset($item_resize['maintain_ratio'])){
				    	 	echo '<tr><td>';
				    			echo lang('duc_resize_maintain_ratio');
				    		echo '</td>';
				    		echo '<td>';
				    			if(!isset($item_resize['maintain_ratio'])){
				    			    echo 'да';
				    			}else{				    				echo ($item_resize['maintain_ratio'] == true) ? 'да' : 'нет';
				    			}
				    		echo '</td></tr>';
				    	//}
				    	echo '<tr><td>';
				    			echo lang('duc_resize_master_dim');
				    		echo '</td>';
				    		echo '<td>';
				    			if(!isset($item_resize['master_dim'])){
				    			    echo 'auto';
				    			}else{
				    				echo $item_resize['master_dim'];
				    			}
				    		echo '</td></tr>';

						echo '<tr><td>';
				    			echo lang('duc_resize_type');
				    		echo '</td>';
				    		echo '<td>';
				    			if(!isset($item_resize['type'])){
				    			    echo 'resize';
				    			}else{
				    				echo $item_resize['type'];
				    			}
				    		echo '</td></tr>';

				    	echo '</table>';
						echo '</td></tr>';
					}

					echo '</table>';
					echo '</td></tr>';
				}

			}
        	echo '</table>';
			echo '</td></tr>';
		echo '</table>';
    }
//echo '</form>';
echo '</div>';

