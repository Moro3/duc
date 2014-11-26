<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен

if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 *  Класс Menus_api
 *  Класс выполняет функцию получения результирующих данных по иерархическому меню в нужном виде,
 *	в зависимости от типа данных!!!
 *  Только этот класс взаимодействует с драйвером lib_menus_types !!!
 *  соответсвенно все вызовы для приложений должны происходить через него
 */

class Menus_api extends Menus {

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
    *   Возвращает дерево меню c информацией о страницах
    *   Формат array[alias place][parent id][id][data node][data]
    *   @param $alias - псевдоним место положения
    *
    */
    public function get_trees_place_data($alias, $type = false){
        //получаем дерево из узлов данного места
        $nodes = $this->get_trees_place($alias, $type);
        //dd($nodes);
        
        if(is_array($nodes)){
            foreach($nodes as &$items){                
                    foreach($items as &$value){
                        $ids[$value['id']] = array(
                            'name' => $value['name'],
                            'type' => $value['type_id']
                        );
                    }                
            }
            $data_nodes = $this->lib_menus_types->get_data_nodes($ids);
            
            //dd($data_nodes);
            

            foreach($nodes as &$items){                
                    foreach($items as &$value){                        
                            if(isset($data_nodes[$value['id']])){
                                $value += $data_nodes[$value['id']];
                            }                        
                    }                
            }
        }
        return $nodes;        
    }

    /**
    *	Возвращает дерево меню c информацией о страницах
    *	Формат array[alias place][parent id][id][data node][data]
    *   @param $alias - псевдоним место положения
    *
    */
    public function get_trees_group_place_data($alias){
    	//$nodes = $this->get_trees_group_place($alias);
        $places = Modules::run('menus/menus/get_places_of_group', $alias);
    	
    	if(is_array($places)){
    		foreach($places as $items){
    		  $nodes[$items->places->alias] = $this->get_trees_place_data($items->places->alias);	
    		}    		
    	}else{
            return false;
        }

    	//$value['data'] = Modules::run('pages/pages/get_data_page', $value['name']);
    	return $nodes;
    	//return false;
    }


    public function getDataOfType(){
        $type_id = $this->input->get_post('id');
        
        $list = $this->lib_menus_types->getDataOfType($type_id);
        return $list;        
    }

    public function getSelectTypeForGrid(){
        $type_id = $this->input->get_post('id');        

        if($node = $this->input->get_post('node')){
            $res_node = Modules::run('menus/menus_trees/MY_data_row',
                    //select
                    'name',
                    //where
                    array('id' => $node, 'type_id' => $type_id)
            );

            if(is_object($res_node) && isset($res_node->name)){
                $node_name = $res_node->name;
            }
        }


        $arr = $this->getDataOfType();
        $res = '';
        
        foreach($arr as $key=>$value){
            if(isset($node_name) && $node_name == $key){
                $selected = 'selected';
            }else{
                $selected = '';
            }
            $res .= '<option role="option" value="'.$key.'" '.$selected.'>'.$value.'</option>'; 
        }
        dd($res);
        return $res;
    }


    /**
    *   Скрипт для подгрузки через ajax списочных данных в зависимости от типа данных
    */
    public function tplDataTypeAjax(){
        return $this->load->view('admin/getDataTypeAjax', 
                        array('uri' => '/ajax/?resource=dataTypes/ajax/menus~&',
                              'selector_event' => '#type_id',
                              'selector_node' => '#id',
                              'selector_replace' => '#node_name'  
                        
                            ),
                        true
        );
    }

}