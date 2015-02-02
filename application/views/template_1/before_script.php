<?php

assets_script('jquery/jquery-1.11.2.min.js');
assets_script('jquery/plugins/jquery.corner.js');
assets_script('highslide/highslide-full.js');
//assets_script('jquery/jquery-min.js');
//assets_script("hs.graphicsDir = '{assets}js/highslide/graphics/';hs.align = 'center';hs.transitions = ['expand', 'crossfade'];hs.outlineType = 'rounded-white';hs.minWidth = 220;hs.minHeight = 220;hs.fadeInOut = true;hs.dimmingOpacity = 0.55;hs.useBox = true;hs.addSlideshow({interval: 3000, repeat: false, useControls: true,fixedControls: true, overlayOptions: {opacity: .7,position: 'bottom center',hideOnMouseOut: true} });");

//assets_script('jquery/plugins/jquery.simplemodal-1.4.3.js');

//----- jsurl: Манипулирование URL'ами --------
//assets_script('jsurl/url.min.js');

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


//---- Flash Pleer:  Flowplayer HTML5--------------------------------

$this->assets->mixed->package('flashpleer/flowplayer',
							 //public file
							 array(
							 			//--js flowplayer--
							 			'flashpleer/flowplayer/flowplayer.min.js',
							 			'flashpleer/flowplayer/config.js',
                                        //--css flowplayer--
							 			'flashpleer/flowplayer/skin/playful.css',
							 ),
							 false
);
$flowplayer = '
	flowplayer.conf = {
   	engine: "flash",
   	swf: "/{assets}mixed/flashpleer/flowplayer/flowplayer.swf"
};
';

//$this->assets->script->load($flowplayer, false);

//---- Flash Pleer:  Flowplayer Flash --------------------------------
/*
$this->assets->mixed->package('flashpleer/flowplayer_flash',
							 //public file
							 array(
							 			//--js flowplayer--
							 			'flashpleer/flowplayer_flash/flowplayer-3.2.12.min.js',
                                        'flashpleer/flowplayer_flash/config.js',
                                        //--css flowplayer--
							 			//'flashpleer/flowplayer_flash/skin/playful.css',
							 ),
							 false
);
*/
//---- Flash Pleer:  Jwplayer --------------------------------
/*
$this->assets->mixed->package('flashpleer/jwplayer',
							 //public file
							 array(
							 			//--js flowplayer--
							 			'flashpleer/jwplayer/jwplayer.js',
							 ),
							 false
);
*/

//---- JQuery Plugin:  waypoints (фиксация элементов на странице) --------------------------------
$this->assets->script->package('jquery/plugins/waypoints',
							 //public file
							 array(
							 			//--tablesorter--
							 			'jquery/plugins/waypoints/waypoints.min.js',
							 ),
							 false
);


//$this->assets->out_script();
//echo "<script language='JavaScript' src='".ASSETS."js/jquery/jquery-min.js'></script>\r\n";
//
//echo "<script language='JavaScript' src='".ASSETS."js/jquery/plugins/jquery.corner.js'></script>\r\n";
//
//echo "<script language='JavaScript' type=text/javascript src=\"".ASSETS."js/highslide/highslide-full.js\"></script>";
//
//echo "<script type=text/javascript>hs.graphicsDir = '".ASSETS."js/highslide/graphics/';hs.align = 'center';hs.transitions = ['expand', 'crossfade'];hs.outlineType = 'rounded-white';hs.minWidth = 220;hs.minHeight = 220;hs.fadeInOut = true;hs.dimmingOpacity = 0.55;hs.useBox = true;hs.addSlideshow({interval: 3000, repeat: false, useControls: true,fixedControls: true, overlayOptions: {opacity: .7,position: 'bottom center',hideOnMouseOut: true} });</script>";



