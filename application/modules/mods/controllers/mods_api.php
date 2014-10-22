<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен


if( ! class_exists('mods')){
  include_once ('mods.php');
}

/*
 * Класс Mods_api
 *
 */

class Mods_api extends Mods {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_mods';
    }

    /**
	*	возвращает массив модулей с определенным форматированием
    */
    public function listSelectPrefix(){
    	$res = Modules::run('mods/mods/MY_data',
    		//select
    		'*'
    	);
    	foreach($res as $items){
    		$replace = array('"','\'');
    		$name = str_replace($replace, ' ', $items->name);
    		$result[$items->id] = '<b>'.htmlspecialchars($name, ENT_QUOTES,'UTF-8'). '</b> : '. htmlspecialchars($items->uri) . ' : <i style=\'grey\'>id=' . $items->id . '</i>';
    		//$result[$items->id] = $items->{content}[0]->name;
    	}

		if(isset($result)) return $result;
		return array();
    }
}
