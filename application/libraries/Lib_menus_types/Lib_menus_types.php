<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
*  Драйвер для типов данных
*
*
*/

class Lib_menus_types extends CI_Driver_Library
{
    public $valid_drivers;
    public $prefix;
    public $CI;

    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->config->load('lib_menus_types', TRUE);
        $this->valid_drivers = $this->CI->config->item('type', 'lib_menus_types');
        foreach($this->valid_drivers as $value){        	$this->prefix[] = str_replace('lib_menus_types_','',$value);
        }
        $this->vars_data = array('name', 'link', 'type', 'type_id', 'active');
        log_message('debug', "Lib_menus_types Class Initialized");
    }

    //получение ID типа по его имени (name)
    function get_id_is_name($name){    	$type = Modules::run('menus/menus_types/MY_data_row',
         	//select
         	'id',
         	//where
         	array('name' => $name)
     	);
     	if(is_numeric($type)) return $type;
     	return false;
    }

    //получение ID типа по его имени (name)
    function get_id_is_alias($alias){
    	$type = Modules::run('menus/menus_types/MY_data_row',
         	//select
         	'id',
         	//where
         	array('alias' => $alias)
     	);
     	if(is_numeric($type)) return $type;
     	return false;
    }

    /**
    *   Получение полных данных о типе меню
    *   @param - $ids - многомерный массив из элементов,
    *				 	где ключ является индентификатором типа (например id),
    *					значение представляет массив из имени (name = число) и типа данных (type = число)
    *
    *	@return array - многомерный массив
    */
    function get_data_nodes($ids){         //получаем все типы
         $types = Modules::run('menus/menus_types/MY_data',
         	//select
         	'*'
         );
         /*
         echo '<pre>';
         var_dump($types);
         exit;
         */

         //разбиваем данные по типу
         foreach($ids as $id=>$items){         	$ids_type[$items['type']][] = array(
         			'name' => $items['name'],
         			'id' => $id
         	);
         }

		//получаем данные скопом по каждому типу
		foreach($types as $type){			if(isset($ids_type[$type->id])){				if(is_object($this->{$type->name})){					$ids_data[$type->id] = $this->{$type->name}->get_data_nodes($ids_type[$type->id]);
				}

			}
		}
		//присоединяем данные к найденным id
		foreach($ids as $id=>$items){			if(isset($ids_data[$items['type']][$items['name']])){				$res[$id] = $ids_data[$items['type']][$items['name']];
			}else{				$res[$id] = $items;
			}
		}

        return $ids_data;
    }



}
