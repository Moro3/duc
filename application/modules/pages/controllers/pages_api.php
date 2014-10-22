<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('pages')){
  include_once ('pages.php');
}

/*
 * Класс Pages_api
 *
 */

class Pages_api extends Pages {

    function __construct(){
        parent::__construct();
        
    }

    public function listPagesSelect(){
    	$res = Modules::run('pages/pages_headers/MY_data',
    		//select
    		array('id',
    			  'related' => array('content' => array('id','name')),
    			  'uri'
    		)
    	);
    	foreach($res as $items){
    		$replace = array('"','\'');
    		$name = str_replace($replace, ' ', $items->content[0]->name);
    		$result[$items->id] = '<b>'.htmlspecialchars($name, ENT_QUOTES,'UTF-8'). '</b> : '. htmlspecialchars($items->uri) . ' : <i style=\'grey\'>id=' . $items->id . '</i>';
    		//$result[$items->id] = $items->{content}[0]->name;
    	}

		if(isset($result)) return $result;
		return array();
    }

    public function listPagesSelectPrefix(){
    	//$prefix = '[page]';
    	$res = $this->listPagesSelect();
    	//return $res;
    	foreach($res as $key=>$item){
    		//$name = htmlspecialchars($item);
    		$res_pr[$key] = '<a class=\'iframe\' href=/ajaxs/?resource=pages/admin/pages~&id='.$key.'>'.$item.'</a>';
    		//$res_pr[$key] = '<a id=\'iframe cboxElement\' href=/ajaxs/?resource=pages/admin/pages~&id='.$key.'>'.$item.'</a>';

    	}
    	if(isset($res_pr)) return $res_pr;
		return array();
    }


}