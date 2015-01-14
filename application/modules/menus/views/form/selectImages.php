<?php

//===== Form select with images =============
/**
 *	входные параметры:
 *	$data - данные списка (массив) 
 *  $selector - имя класса select
 *	$selector_option - имя класса option
 *	$attr_select - аттрибуты select
 *	$attr_option - аттрибуты option
 * 	$selector_event - селектор замены
 * 
 */

$selector_option = (isset($selector_option)) ? ' '.$selector_option : '';
$attr_select = (isset($attr_select)) ? ' '.$attr_select : '';
$attr_option = (isset($attr_option)) ? ' '.$attr_option : '';




echo "\r\n<script>";        
    echo '$grid.bind("jqGridAddEditAfterShowForm", function(event, $form){';

		
		echo 'var id = $("'.$event.'").val();';
		echo 'var dataSelect;';
		echo 'var selected;';
		if(is_array($data)){
			$formSelect = '';
			echo 'dataSelect = "";'."\r\n";
			//$formSelect = "<select class=\"".$selector."\" ".$attr_select.">";
				foreach($data as $items){
					echo 'if('.$items['value'].' == id){ selected = " selected"} else {selected = ""}'."\r\n";
					echo "dataSelect = dataSelect + '<option class=".$selector_option." value=".$items['value']." data-img=\"".$items['image']."\" data-name=\"".$items['name']."\" data-path=\"".$items['path']."\" ".$attr_option."' + selected + '>"."\r\n";
					
					//echo ">";
					echo $items['name'].'<br />';
					echo "!! ".$items['image']."";

					echo "</option>';"."\r\n";
				}
			
			//$formSelect .= "</select>";
		}
		//echo 'var dataSelect = \''.$formSelect.'\';';
		echo 'alert(dataSelect);';
		echo '$("'.$event.'").empty().html(dataSelect);';
		//echo '$("'.$event.'").text('.$formSelect.')';

	echo '})';    
echo '</script>';



