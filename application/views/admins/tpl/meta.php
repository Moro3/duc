<title><?php if(isset($title)) echo stripslashes(str_replace ("\"","&quot;",$title)); ?></title>
<meta http-equiv=Content-Type content="text/html; charset=<?=$this->config->item('charset');?>">
<meta name=Description content="<?php if(isset($description)) echo stripslashes(str_replace ("\"","&quot;",$description)); ?>">
<meta name=Keywords content="<?php if(isset($keywords)) echo stripslashes(str_replace ("\"","&quot;",$keywords)); ?>">
<?php
if(is_file(FCPATH."/favicon.ico")){
  echo "<link rel=\"icon\" href=\"/favicon.ico\" type=\"image/x-icon\">";
  echo "<link rel=\"shortcut icon\" href=\"/favicon.ico\" type=\"image/x-icon\">";
}

  if(isset($css['link'])){
    if(!is_array($css['link'])){
      $css['link'] = array($css['link']);
    }
    foreach($css['link'] as $key=>$value){
      echo "$value\r\n";
    }
  }
  assets_style_out();
  assets_script_out();
?>
<!--jQuery-->
	

	<!--jQuery UI-->
	<script src="/1assets/default/jqgrid/js/jquery-ui/jquery-ui.min.js"></script>
	<link href="/assets/default/jqgrid/css/jquery-ui/themes/redmond/jquery.ui.all.min.css" rel="stylesheet" type="text/css" />
	
	<script src="/assets/default/jqgrid/plugins/ui.multiselect.js"></script>
	<link href="/assets/default/jqgrid/plugins/ui.multiselect.css" rel="stylesheet" type="text/css" />

	<!--jqGrid-->
	<link href="/assets/default/jqgrid/css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
	<script src="/assets/default/jqgrid/js/i18n/grid.locale-en.js"></script>
    <script src="/assets/default/jqgrid/js/jquery.jqGrid.min.js"></script>

	<!--jqGrid Extension-->
	<link href="/assets/default/jqgrid/client/jqgrid-ext.css" rel="stylesheet" type="text/css" />
    <script src="/assets/default/jqgrid/client/jqgrid-ext.js"></script>
	
	<!-- Other plugins -->
	<script src="/assets/default/jqgrid/js/jquery/form/2.67/jquery.form.min.js"></script>
	
	<!-- Code highlighter -->
	<script src="/assets/default/jqgrid/js/highlightjs/6.0/highlight.min.js"></script>
	<link href="/assets/default/jqgrid/js/highlightjs/6.0/styles/vs.css" rel="stylesheet" type="text/css" />
	
	<link rel="icon" href="misc/favicon.png" type="image/png"> 
	
	<script>
	$.extend($.jgrid.defaults,
	{
		altRows: false,
		altclass: 'altrow',
		
		hidegrid: false,
		hoverrows: false,
		
		viewrecords: true,
		scrollOffset: 21,

		width: 800,
		height: 290
	});
	
	//$.jgrid.defaults.height = '400px';
	$.jgrid.nav.refreshtext = 'Refresh';
	$.jgrid.formatter.date.newformat = 'ISO8601Short';
	
	$.jgrid.edit.closeAfterEdit = true;
	$.jgrid.edit.closeAfterAdd = true;
	
	$(function()
	{
		$('#tabs-info').html($('#descr_rus').html());
	
		$('#accordion').accordion({
			'animated' : false,
			'navigation' : true
		});
		
		$('#tabs').tabs();
		
		hljs.tabReplace = '    ';
		hljs.initHighlightingOnLoad();
	});
	</script>
	
	<style>
	bode {font-size: 11px;}
	body {background: #F5F5F5; font-size: 11px; padding: 10px;}	#descr {display: none;}
	#descr_rus {display: none;}
	
	#accordion UL {padding: 0; margin: 0; list-style-type: circle;}
	#accordion UL A {text-decoration: none; font-size: 11px;}
	#accordion UL A:hover {text-decoration: underline;}
	#accordion UL LI.active {list-style-type: disc;}
	
	.ui-widget {font-family: verdana; font-size: 12px;}

	.ui-jqgrid {font-family: tahoma, arial;}
	.ui-jqgrid TR.jqgrow TD {font-size: 11px;}
	.ui-jqgrid TR.jqgrow TD {padding-left: 5px; padding-right: 5px;}
	.ui-jqgrid TR.jqgrow A {color: blue;}

	.ui-jqgrid INPUT,
	.ui-jqgrid SELECT,
	.ui-jqgrid TEXTAREA, 
	.ui-jqgrid BUTTON {font-family: tahoma, arial;}
	</style>