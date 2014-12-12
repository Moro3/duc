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
    public $drivers = array();
    public $dataListTypes = array();
    public $CI;

    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->config->load('lib_menus_types', TRUE);
        //$this->valid_drivers = $this->CI->config->item('type', 'lib_menus_types');
        
        $types = Modules::run('menus/menus_types/MY_data',
            //select
            array('id', 'driver'),
            //where
            array('active' => 1)
        );
        
        foreach($types as $type){
            $this->valid_drivers[$type->id] = 'lib_menus_types_'.$type->driver;
            $this->drivers[$type->id] = $type->driver; 
        }
        /*
        foreach($this->valid_drivers as $value){
        	$this->prefix[] = str_replace('lib_menus_types_','',$value);
        }
        */
        $this->vars_data = array('name', 'link', 'type', 'type_id', 'active');
        log_message('debug', "Lib_menus_types Class Initialized");
    }

    //получение ID типа по его имени (name)
    function get_id_is_name($name){
    	$type = Modules::run('menus/menus_types/MY_data_row',
         	//select
         	'id',
         	//where
         	array('name' => $name)
     	);
     	
        if(is_numeric($type->id)) return $type->id;
     	return false;
    }

    //получение ID типа по его имени драйвера (driver)
    function get_id_is_driver($driver){
        $type = Modules::run('menus/menus_types/MY_data_row',
            //select
            'id',
            //where
            array('driver' => $driver)
        );
        
        if(is_numeric($type->id)) return $type->id;
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
     	if(is_numeric($type->id)) return $type->id;
     	return false;
    }

    /**
    *   Получение полных данных о типе меню
    *   Обязательные параметры на выходе:
    *   name - имя
    *   id - ID узла
    *   type_id - ID типа данных
    *   type - имя типа данных
    *   driver - драйвер
    *   link - ссылка на объект
    *   active - (1 || 0)активен ли объект
    *   
    *   @param - $ids - многомерный массив из элементов,
    *				 	где ключ является индентификатором узла (например id),
    *					значение представляет массив из имени (name = число) и типа данных (type = число)
    *
    *	@return array - многомерный массив с требуемыми данными
    *                   некоторые данные должны быть обязательными, чтобы быть совместимыми с общем массивом на выходе
    *                   однако можно добавить другие поля в зависимости от каждого типа
    */
    function get_data_nodes($ids){
         //получаем все типы
         $types = Modules::run('menus/menus_types/MY_data',
         	//select
         	'*'
         );
         
         //получаем все изображения
         $images = Modules::run('menus/menus_images/MY_data',
            //select
            '*'
         );

         //разбиваем данные по типу
         foreach($ids as $id=>$items){
         	$ids_type[$items['type']][] = array(
         			'name' => $items['name'],
         			'id' => $id,
                    'type_id' => $items['type']                    
         	);
         }

		
        //получаем данные скопом по каждому типу
		foreach($types as $type){
			if(isset($ids_type[$type->id])){
				if(is_object($this->{$type->driver})){
					$ids_data[$type->id] = $this->{$type->driver}->get_data_nodes($ids_type[$type->id]);
				}

			}
		}
        //dd($ids_data);
		//присоединяем данные к найденным id
		foreach($ids as $id=>$items){
			
            $res[$id] = array(
                    'name' => $items['name'],
                    'id' => $id,
                    'node' => $id,
                    'type_id' => $items['type'],
                    //'images' => $img_objects
            );
            if(isset($ids_data[$items['type']][$items['name']])){				
                $res[$id]['data'] = $ids_data[$items['type']][$items['name']];
			}else{
				$res[$id]['data'] = $items;
			}
		}

        return $res;
    }

    
    /**
     * Возвращает массив с данными о типе
     * 
     * @param  numeric $t ID типа
     * @param  $s - selector
     * @return array      массив (key - ID типа, value - данные в форматированном виде в зависимости от типа.)
     */
    public function getDataOfType($t){
        //$t = $this->input->get('id');
        if( ! is_numeric($t)) return false;
        
        $types = Modules::run('menus/menus_types/MY_data_row',
            //select
            '*',
            //where
            array('id' => $t)
        );               
        
        if( ! is_object($types)) return false;

        if( ! isset($this->dataListTypes[$types->driver])){
            if(in_array($types->driver, $this->drivers)){
                $this->dataListTypes[$types->driver] = $types;
            }
        }

        //dd($types);
        $list = array();
        if(isset($this->dataListTypes[$types->driver])){
            
            $list = $this->{$types->driver}->getDataOfType();
            
        }
        //dd($list);
        return $list;        
    }
}
