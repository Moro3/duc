<?php
//===== Сортировка строк =============
/*
  входные параметры:
  	$selector - css селектор
    $grid - имя грида
  	$url - url запроса



*/
$grid = (isset($grid)) ? $grid : false;
$url = (isset($url)) ? $url : false;
$selector = (isset($selector)) ? $selector : '.ui-state-highlight';


echo '<script>';

echo "
  jQuery(document).ready(function(){
    jQuery( \"#".$grid."\" ).sortable({
      connectWith: \"".$selector."\",
      height: '400px',
      update: function( event, ui ) {}
    });
  });
";

echo '</script>';
