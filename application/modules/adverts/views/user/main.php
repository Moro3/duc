<div>
<marquee behavior="scroll" direction="up" scrollamount="2" height="550px" scrolldelay="60" truespeed>
<div style="font-size:normal;">
<?php
//assets_style('modal.css','adverts');

if(isset($objects) && is_array($objects)){
	foreach($objects as $items){
        //echo '<div class="madal_popup">';
		if($items['vip'] == 1){
			echo '<div class="madal_popup_vip">';
		}else{
			echo '<div class="madal_popup">';
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