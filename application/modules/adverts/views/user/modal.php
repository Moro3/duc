<?php


	if(isset($objects) && is_array($objects) && count($objects) > 0){

	assets_style(
	'
	 .b-modal {
    position: relative;
    width: 96%;
    height:600px;
    padding: 24px;
    background: #fff;
    border: 3px solid #bbb;
    border-radius: 10px;
}
.b-modal_close {
    position: absolute;
    right: 12px;
    top: 6px;
    font-weight: bold;
    color: #999;
    cursor:	pointer;
}
.b-modal_close:hover {
    color: #000;
}
	'
	);

	$script = <<<SCRIPT

/*********** Событие на бездействие пользователя *************/
idleTimer = null;
idleState = false; // состояние отсутствия
idleWait =
SCRIPT;
$script .= $config['user']['modal']['time_on'];
$script .= <<<SCRIPT
000; // время ожидания в мс. (1/1000 секунды)

$(document).ready( function(){
  $(document).bind('mousemove keydown scroll', function(){
    clearTimeout(idleTimer); // отменяем прежний временной отрезок
    if(idleState == true){
      	// Действия на возвращение пользователя

    }

    idleState = false;
    idleTimer = setTimeout(function(){
      // Действия на отсутствие пользователя
      //alert("<p>Вы отсутствовали более чем " + idleWait/1000 + " секунд.</p>");
      	var c = $('<div class="b-modal" />');
		c.html($('.b-text').html());
		c.prepend('<div class="b-modal_close arcticmodal-close">X</div>');
		if($(".switch input").is(":checked")){
			$.arcticmodal({
			    content: c
			});
		}
      idleState = true;

    }, idleWait);
  });


  	$("body").trigger("mousemove"); // сгенерируем ложное событие, для запуска скрипта

});

SCRIPT;

	if($config['user']['modal']['active'] == 1){
		assets_script($script);
	}
    //echo $script;
	}
?>

<div class="b-text g-hidden">
<marquee behavior="scroll" direction="up" scrollamount="2" height="100%" scrolldelay="<?php echo $config['user']['modal']['speed']; ?>" truespeed>
<div style="font-size:34px;">
<?php

assets_style('modal.css','adverts');

if(isset($objects) && is_array($objects)){	foreach($objects as $items){        //echo '<div class="madal_popup">';
		if($items['vip'] == 1){			echo '<div class="madal_popup_vip">';
		}else{			echo '<div class="madal_popup">';
		}
			echo '<strong>'.$items['name'].'</strong>';
			echo '<br />';
			echo $items['description'];
		echo '</div>';
		//echo '</div>';

	}
}else{
	echo 'Добро пожаловать в Детско-юношеский центр';
}
?>
</div>
</marquee>
</div>