<?php
$script = '
  	$(document).ready(function(){                          // по завершению загрузки страницы
    	$(".switch").click(function(){                  // вешаем на клик по элементу с id
		  	//alert("Нажали на кнопку switch");
		  	if($(".switch input").is(":checked")){		  		//alert("Кнопка включена");
		  		//$("#exampleModal").parent().show();
		  	}else{		  		//alert("Кнопка вЫключена");
		  		//$("#exampleModal").parent().hide();
		  	}
		  	/*
		  	if(confirm("Очистить папку с изображениями?")){

			  	$.ajax({
			  	 	type: "POST",
			  	 	url: "/ajax/?resource=action_photos_resize/ajax/duc",
			  	 	data: {"action": "clear", "group": group, "resize": resize},
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
		  	}
		  	*/
		})
	});
';

echo '<script>';
echo $script;
echo '</script>';

if($config['user']['modal']['switch'] == 1){
	echo '
		<!--  Кнопка модального окна -->
					<div class="switch">
					    <input type="checkbox"
		';
			if($config['user']['modal']['switch_default'] == 1){				echo ' checked';
			}
	echo '
					     />
					    <label></label>
					</div>
	';
}