<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('snippets')){
  include_once ('snippets.php');
}

/*
 * Класс Snippets_groups
 *
 */

class Snippets_groups extends Snippets {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_snippets_groups';
    }

    // перезагружаем метод, т.к. нам не нужем визуальный редактор
    function grid_render(){

        $this->grid_params();

		//-------------- Загрузка таблицы jQGrid
   		echo "\r\n<script>";
        	echo $this->grid->loader->render($this->table);
        echo "</script>\r\n";
	}

    //возвращает список
    function listGroups(){
         $res = Modules::run('snippets/snippets_groups/MY_data_array_one',
         	//key
         	'id',
         	//select
         	array('name'// 'main.paid'

         	),
         	//where
         	false,
         	//order
         	'sorter',
         	//separator
         	' - '
         );
         //print_r($res);
         //exit;
         //return $res;
         $new = array();
         foreach($res as $key=>$value){
         	$new[$key] = str_replace('"','\'',$value);
         }

         return $new;
    }

    /**
    *  Поиск имени в базе
    *  @param $name - имя сниппета
    *  @param $without_id - id которые не учитывать при поиске
    *
    *	@return boolean - true: имя найдено, false - имя отсутствует
    */
    function searchName($name, $without_id = false){    	 if(empty($name)) return true;

    	 if($without_id !== false){    	 	if( ! is_array($without_id)) $without_id = array($without_id);
    	 }else{    	 	$without_id = array();
    	 }

    	 //var_dump($without_id);
         //exit;
         if(count($without_id) > 0){         	$where = array(
         				'name' => $name,
         				'not' => array('id' => $without_id)
         	);
         }else{         	$where = array(
         				'name' => $name
         	);
         }


    	 $res = Modules::run('snippets/snippets_groups/MY_data_array_row',
         	//select
         	array('id', 'name'// 'main.paid'

         	),
         	//where
			$where

         );

         //var_dump($res);
         //exit;
         if (is_array($res) && count($res) > 0) return true;
         return false;
    }
}