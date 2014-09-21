<?php

assets_script('jquery/jquery-1.8.2.min.js');
assets_script('jquery/plugins/jquery.corner.js');
assets_script('highslide/highslide-full.js');
//assets_script('jquery/jquery-min.js');
//assets_script("hs.graphicsDir = '{assets}js/highslide/graphics/';hs.align = 'center';hs.transitions = ['expand', 'crossfade'];hs.outlineType = 'rounded-white';hs.minWidth = 220;hs.minHeight = 220;hs.fadeInOut = true;hs.dimmingOpacity = 0.55;hs.useBox = true;hs.addSlideshow({interval: 3000, repeat: false, useControls: true,fixedControls: true, overlayOptions: {opacity: .7,position: 'bottom center',hideOnMouseOut: true} });");

//assets_script('jquery/plugins/jquery.simplemodal-1.4.3.js');

//----- jsurl: Манипулирование URL'ами --------
assets_script('jsurl/url.min.js');

//======скрипт навигации бокового меню ===========
assets_script('navigation/menu.js');

//---- Модальное окно
assets_script('jquery/plugins/jquery.arcticmodal-0.2/jquery.arcticmodal-0.2.min.js');

// сортировка таблиц
//assets_script('jquery/plugins/jquery.tablesorter.js');

//---- JQuery Plugin:  TABLESORTER --------------------------------
$this->assets->script->package('jquery/plugins/tablesorter',
							 //public file
							 array(
							 			//--tablesorter--
							 			'jquery/plugins/tablesorter/jquery.tablesorter.js',
							 			//--style--
							       		'jquery/plugins/tablesorter/blue/style.css',

							 ),
							 false
);
// анимированная сортировка таблиц
//assets_script('jquery/plugins/jquery.tableSort.js');


//$this->assets->out_script();
//echo "<script language='JavaScript' src='".ASSETS."js/jquery/jquery-min.js'></script>\r\n";
//
//echo "<script language='JavaScript' src='".ASSETS."js/jquery/plugins/jquery.corner.js'></script>\r\n";
//
//echo "<script language='JavaScript' type=text/javascript src=\"".ASSETS."js/highslide/highslide-full.js\"></script>";
//
//echo "<script type=text/javascript>hs.graphicsDir = '".ASSETS."js/highslide/graphics/';hs.align = 'center';hs.transitions = ['expand', 'crossfade'];hs.outlineType = 'rounded-white';hs.minWidth = 220;hs.minHeight = 220;hs.fadeInOut = true;hs.dimmingOpacity = 0.55;hs.useBox = true;hs.addSlideshow({interval: 3000, repeat: false, useControls: true,fixedControls: true, overlayOptions: {opacity: .7,position: 'bottom center',hideOnMouseOut: true} });</script>";



