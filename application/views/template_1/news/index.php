<?php

include(ROOT.TEMPLATE."news/breadcrumbs.php");
include(ROOT.TEMPLATE."news/pagination.php");

if(isset($segments[1])){	switch($segments[1]){
		case 'id':
			include(ROOT.TEMPLATE."news/id.php");
		break;
		default:
	    	include(ROOT.TEMPLATE."news/main.php");
	}
}else{	include(ROOT.TEMPLATE."news/list.php");
}
