<?php
//===== ���������� ����� =============
/*
  ������� ���������:
  	$selector - css ��������
    $grid - ��� �����
  	$url - url �������



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
