<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен

if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 *  Класс Menus_result
 *  Класс выполняет функцию получения результирующих данных по иерархическому меню в нужном виде,
 *	в зависимости от типа данных!!!
 *  Только этот класс взаимодействует с драйвером lib_menus_types !!!
 *  соответсвенно все вызовы для приложений должны происходить через него
 */

class Menus_result extends Menus {

    var $prefix;
    var $dataListTypes;

    function __construct(){
        parent::__construct();
        // Load the library "lib_menus_types"
    	
        $this->load->driver('lib_menus_types');
        /*
        $this->config->load('lib_menus_types', TRUE);
        $this->valid_drivers = $this->config->item('type', 'lib_menus_types');
        foreach($this->valid_drivers as $value){
            $this->prefix[] = str_replace('lib_menus_types_','',$value);
        }
        */
    }

    /**
    *	Возвращает дерево меню c информацией о страницах
    *	Формат array[alias place][parent id][id][data node][data]
    *   @param $alias - псевдоним место положения
    *
    */
    public function get_trees_place_data($alias){
    	$nodes = $this->get_trees_place($alias);

    	/*
    	echo '<br><b>Source = '.__FILE__.' : Line = '.__LINE__.'</b><br>';
    	echo '<pre>';
		print_r($nodes);
		echo '</pre>';
		exit;
        */
    	if(is_array($nodes)){
    		foreach($nodes as &$items){
    			foreach($items as &$item){
                	foreach($item as &$value){
                		$ids[$value['id']] = array(
                			'name' => $value['name'],
                			'type' => $value['type_id']
                		);

                	}
    			}
    		}
    		$data_pages = $this->lib_menus_types->get_data_nodes($ids);
            
            //dd($data_pages);
            //exit;

    		foreach($nodes as &$items){
    			foreach($items as &$item){
                	foreach($item as &$value){
                		if($value['type_id'] == 1){
                			if(isset($data_pages[$value['type_id']][$value['name']])){
                				$value['data'] = $data_pages[$value['type_id']][$value['name']];
                			}
                		}
                	}
    			}
    		}
    	}

    	//$value['data'] = Modules::run('pages/pages/get_data_page', $value['name']);
    	return $nodes;
    	//return false;
    }


    public function getDataOfType(){
        $t = $this->input->get_post('id');
        
        $list = $this->lib_menus_types->getDataOfType($t);
        return $list;        
    }


    /**
    *   Скрипт для подгрузки через ajax списочных данных в зависимости от типа данных
    */
    public function tplDataTypeAjax(){
        return $this->load->view('admin/getDataTypeAjax', 
                        array('uri' => '/ajax/?resource=dataTypes/ajax/menus~&'
                        
                            ),
                        true
        );
    }

}