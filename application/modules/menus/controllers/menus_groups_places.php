<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 * Класс Menus_groups_places
 *
 */

class Menus_groups_places extends Menus {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_menus_groups_places';
    }


	 /**
    * Запись новых мест в группу
    * @param int - id_group
    * @param array - массив с новыми id_place
    *
    * return boolean - true || false
    */
    function write_places($id_group, $id_places = array()){
    	if( ! isset($id_group)){
    		throw new jqGrid_Exception('Не указаны объекты для редактирования');
      		return false;
    	}
    	if( ! is_numeric($id_group)){
    		throw new jqGrid_Exception('Не верно указаны объекты для редактирования');
      		return false;
    	}
    	//текущие данные объекта
    	$rows = Modules::run('menus/menus_groups_places/MY_data_array',
    	                    //select
    	                    array('id', 'place_id'
    	                    ),
    	                    //where
    						array('group_id' => intval($id_group)
    						)
    	);
    	//все данные places
    	$count_places = Modules::run('menus/menus_places/MY_data_array',
    										//select
    										array('id')
    	);

    	//приводим данные places к массиву
    	foreach($count_places as $k=>$v){
    		$arr_places[] = $v['id'];
    	}
    	//если places нет - генерируем ошибку и возвращаем false
    	if(!isset($arr_places) || count($arr_places) <= 0){
    		throw new jqGrid_Exception('Ни одного места положения не создано');
      		return false;
    	}
        //приводим текущие данные к одномерному массиву
        //проверяем также массив на наличие значений
        if(is_array($rows) && count($rows) > 0){
	        foreach($rows as $key=>$value){
	        	$rows_id[] = $value['place_id'];
	        }
	    }else{
	    	$rows_id = array();
	    }
        // приводим введенные данные мест к одномерному массиву
        // и проверяем корректность массива
        if(!is_array($id_places)){
        	$rows_in = array($id_places);
        }else{
        	$rows_in = array();
        	foreach($id_places as $item){
        		if(!empty($item)){
        			$rows_in[] = $item;
        		}
        	}
        }
        //если введенные данные не найдены в существующих местах
        // генерируем ошибку и возвращаем false
        if(count($rows_in) > 0){
	        if( array_diff($rows_in, $arr_places)){
	        	throw new jqGrid_Exception('Указано несуществующее место положение');
	               return false;
	    	}
    	}
        
        /* 
        //==== Данные с учетом разницы введенных и существующих ====

        //вычисляем данные для добавления
        $cols_add = array_diff($rows_in, $rows_id);
        $cols_add = array_unique($cols_add);
        //вычисляем данные для удаления
        $cols_del = array_diff($rows_id, $rows_in);
        $cols_del = array_unique($cols_del);
        */
        
        //==== Данные без учетом разницы введенных и существующих ====
        //==== сохраняется последовательность введенных и тем самым возможность добавить запись для сортировки
        $cols_add = $rows_in;
        $cols_del = $rows_id;

        /*
        dd(array("Data be:" => $rows_id,
                "Data input:" => $rows_in,
                "Data for add:" => $cols_add,
                "Data for delete:" => $cols_del,
            )
        );
        */
        
        // удаляем
        if(is_array($cols_del) && count($cols_del) >= 1){
        	$where = array('group_id' => $id_group,
        					'place_id' => $cols_del
        	);
        	if( ! Modules::run('menus/menus_groups_places/MY_delete',
        	        			//where
        	        			$where
        			)
        	){
        		throw new jqGrid_Exception('Не удалось удалить места положения у объекта "menus_groups_places"');
        		return false;
        	}
        }
        //добавляем
        if(is_array($cols_add) && count($cols_add) >= 1){
        	$set = array('group_id' => $id_group,
        	);

        	foreach($cols_add as $key=>$item){
        		$set['place_id'] = $item;
                //добавляем ключ в качестве сортировки
                $set['sorter'] = $key;
        		if( ! Modules::run('menus/menus_groups_places/MY_insert', $set)){
         			throw new jqGrid_Exception('Не удалось добавить "место положение" у объекта "menus_groups_places"');
         			return false;
        		}
        	}
        }
        return true;

    }

     /**
    * Удаление места положения из списка групп
    * @param int - id group
    *
    *
    * return boolean - true || false
    */
    public function delete_places($id_group){
    	if( ! isset($id_group)){
    		throw new jqGrid_Exception('Не указаны объекты для удаления');
      		return false;
    	}
    	if( ! is_numeric($id_group)){
    		throw new jqGrid_Exception('Не верно указаны объекты для удаления');
      		return false;
    	}
    	// удаляем

        	$where = array('group_id' => $id_group
        	);
        	if( ! Modules::run('menus/menus_groups_places/MY_delete', $where)){
        		throw new jqGrid_Exception('Не удалось удалить "место положение" у объекта "групп"');
        		return false;
        	}else{
        		return true;
        	}

    }

    /**
	*	Возвращает группы зависящие от мест положений
	*
	*/
	public function get_groups_have_places($ids){
		$res = $this->MY_data(
					//select
					array('id', 'group_id'),
					//where
					array('place_id' => $ids)
		);
		if($res) return $res;
		return false;
	}

	/**
	*	Возвращает места положений зависящие от групп
	*
	*/
	public function get_have_places_groups($ids){
		$res = $this->MY_data(
					//select
					array('id', 'place_id'),
					//where
					array('group_id' => $ids)
		);
		if($res) return $res;
		return false;
	}
}
