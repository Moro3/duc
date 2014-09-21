<?php
//echo '<script src="/assets/default/public/js/jquery/jquery-1.7.2.min.js"></script>';
//echo '<script src="/assets/default/public/js/jquery/jquery-ui/jquery-ui-1.8.11.min.js"></script>';

//----- jsurl: Манипулирование URL'ами --------
assets_script('jsurl/url.min.js');

//----- jQuery --------
//assets_script('jquery/jquery-1.7.2.min.js', false);
assets_script('jquery/jquery-1.8.2.min.js');

//---- jQuery Plugin corner --------
//assets_script('jquery/plugins/jquery.corner.js');

//---- jQuery Plugin Colorbox - modal window  --------
//assets_script('jquery/plugins/colorbox/jquery.colorbox-min.js');
$this->assets->script->package('jquery/plugins/colorbox',
							 //public file
							 array(
							 		//--css--
							 			'jquery/plugins/colorbox/colorbox.css',
							 		//--js--
							       		'jquery/plugins/colorbox/jquery.colorbox-min.js',
							 ),
							 false
);

//---- jQuery Plugin select2 select с картинками и не только --------
$this->assets->script->package('jquery/plugins/select2',
							 //public file
							 array(
							 		//--css--
							 			'jquery/plugins/select2/select2.css',
							 		//--js--
							       		'jquery/plugins/select2/select2.min.js',
							 ),
							 false
);

//---- jQuery UI --------
assets_script('jquery-ui/jquery-ui-1.8.24.custom.min.js', false);
//assets_script('jquery/jquery-ui/jquery-ui-1.8.23.custom.min.js', false);

//---- jQuery UI Plugin Timepicker --------
assets_script('jquery-ui/plugins/jquery-ui-timepicker-addon.js', false);

//---- jQuery UI Plugin multiselect --------
//assets_script('jquery-ui/plugins/ui.multiselect.js', false);

//---- jQuery UI Plugin multiselect-master --------
$this->assets->script->package('jquery-ui/plugins/multiselect-master',
							 //public file
							 array(
							 		//--css--
							 			'jquery-ui/plugins/multiselect-master/css/common.css',
							 			'jquery-ui/plugins/multiselect-master/css/ui.multiselect.css',
							 		//--js--
							       		'jquery-ui/plugins/multiselect-master/js/plugins/localisation/jquery.localisation-min.js',
							       		'jquery-ui/plugins/multiselect-master/js/plugins/scrollTo/jquery.scrollTo-min.js',
							       		'jquery-ui/plugins/multiselect-master/js/ui.multiselect.js',
							       		'jquery-ui/plugins/multiselect-master/js/locale/ui-multiselect-ru.js',
							 ),
							 false
);

$multiselect = '
		$(function(){
			//$.localise(\'ui-multiselect\', {/*language: \'ru\',*/ path: \'/{assets}jquery-ui/plugins/multiselect-master/js/locale/\'});
			$(".multiselect").multiselect();
			//$(\'#switcher\').themeswitcher();
		});
	';
//assets_script($multiselect, false);



//---- Code highslide -- создания галерей
//assets_script('highslide/highslide-full.js');

//---- jQuery UI Plugin ajaxupload --------
assets_script('jquery/plugins/ajaxupload.js', false);

// общий код для всего сайта
assets_script('public.js', false);

//---- JQGrid --------------------------------

$this->assets->script->package('jqgrid',
							 //public file
							 array(
							 		//--jQuery UI--//
							 			//--themes--
							 				'jqgrid/css/jquery-ui/themes/redmond/jquery.ui.all.min.css',
							 			//--multiselect--
							       			//'jqgrid/plugins/ui.multiselect.js',
							       			//'jqgrid/plugins/ui.multiselect.css',
							       		//--jqGrid--
							       			'jqgrid/css/ui.jqgrid.css',
							       			'jqgrid/js/i18n/grid.locale-ru.js', // !!! locale - обязательно должен идти перед jqGrid
							       			'jqgrid/js/jquery.jqGrid.min.js',
							       		//--jqGrid Extension--
							       			'jqgrid/client/jqgrid-ext.css',
							       			'jqgrid/client/jqgrid-ext.js',
							       		//-- Other plugins --
							       			'jqgrid/js/jquery/form/2.67/jquery.form.min.js',
							       		//-- Code highlighter -- подсветка кода в блоге
							       			//'jqgrid/js/highlightjs/6.0/styles/vs.css',
							       			//'jqgrid/js/highlightjs/6.0/highlight.min.js',

							 ),
							 false
);

//---- jQuery arcticModal - Модальные окна --------
$this->assets->script->package('jquery/plugins/arcticmodal',
							 //public file
							 array(
							 		//--css--
							 			'jquery/plugins/arcticmodal/jquery.arcticmodal-0.3.css',
							 		//--js--
							       		'jquery/plugins/arcticmodal/jquery.arcticmodal-0.3.min.js',
							 ),
							 false
);

//---- jQuery UI Plugin DateRangePicker --------
$this->assets->script->package('jquery-ui/plugins/DateRangePicker2',
							 //public file
							 array(
							 		//--css--
							 			'jquery-ui/plugins/DateRangePicker2/css/ui.daterangepicker.css',
							 		//--js--
							       		'jquery-ui/plugins/DateRangePicker2/js/daterangepicker.jQuery.compressed.js',
							 ),
							 false
);

//---- JQuery Uploadify — мультизагрузка файлов с использованием флэш --------------------------------
$this->assets->script->package('jquery/plugins/uploadify',
                              //public file
							 array(
							 			//--js--
							 			'jquery/plugins/uploadify/jquery.uploadify.min.js',
							 			//--style--
							       		'jquery/plugins/uploadify/uploadify.css',

							 ),
							 false

);
