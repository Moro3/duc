<?php
assets_style('style.css');
assets_style('header.css');
assets_style('navigation.css');
assets_style('switch.css', 'adverts');
assets_style('content.css');
assets_style('footer.css');
assets_style('public.css');


//assets_style('menu_document.css');

assets_style('main/holiday_main.css');

assets_style('main/work_state_main.css');

assets_style('main/division_main.css');

assets_style('main/foto_main.css');

assets_style('main/photogallery_main.css');

assets_style('pages/administration.css');

// общий стиль для таблиц duc
assets_style('user/public.css', 'duc');

assets_style('menu_main.css');



//plugins jQuery Tablesorter
/*
assets_img('tablesorter/asc.gif', false);
assets_img('tablesorter/bg.gif', false);
assets_img('tablesorter/desc.gif', false);
assets_style('jquery/plugins/tablesorter/blue/style.css', false);
*/
assets_style('jquery/plugins/jquery.arcticmodal-0.2.css');

// twitter bootstrap
/*
$this->assets->mixed->load('bootstrap/css/bootstrap.min.css');
$this->assets->mixed->load('bootstrap/css/bootstrap-responsive.min.css');
$this->assets->mixed->load('bootstrap/js/bootstrap.min.js');
$this->assets->mixed->package('bootstrap/img');
*/

$this->assets->mixed->package('bootstrap-3.2.0',
							 array('bootstrap-3.2.0/css/bootstrap.min.css',
							       //'bootstrap/css/bootstrap-responsive.min.css',
							       'bootstrap-3.2.0/js/bootstrap.min.js',
							 ),
							 false
);

//---- blueimp Gallery --------------------------------
$this->assets->mixed->package('Gallery-blueimp',
							 //public file
							 array(
							 			//--css--
							 			'Gallery-blueimp/css/blueimp-gallery.min.css',
							 			//--js--
							       		'Gallery-blueimp/js/jquery.blueimp-gallery.min.js',

							 ),
							 false
);
//---- Bootstrap Image Gallery --------------------------------
$this->assets->mixed->package('Bootstrap-Image-Gallery',
							 //public file
							 array(
							 			//--css--
							 			'Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css',
							 			//--js--
							       		'Bootstrap-Image-Gallery/js/bootstrap-image-gallery.min.js',

							 ),
							 false
);
