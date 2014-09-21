<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('duc')){
  include_once ('duc.php');
}

/*
 * Класс Duc_concertmasters
 *
 */

class Duc_concertmasters extends Duc {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_duc_concertmasters';
    }


	 /**
    * Запись новых концертмейстеров(педагогов) для коллектива
    * @param int - id_group
    * @param array - массив с новыми id_teacher
    *
    * return boolean - true || false
    */
    function write_teachers($id_group, $id_teachers = array()){
    	if( ! isset($id_group)){
    		throw new jqGrid_Exception('Не указаны объекты для редактирования');
      		return false;
    	}
    	if( ! is_numeric($id_group)){
    		throw new jqGrid_Exception('Не верно указаны объекты для редактирования');
      		return false;
    	}
    	//текущие данные объекта
    	$rows = Modules::run('duc/duc_concertmasters/MY_data_array',
    	                    //select
    	                    array('id', 'id_teacher'
    	                    ),
    	                    //where
    						array('id_group' => intval($id_group)
    						)
    	);
    	//все данные teachers
    	$count_teachers = Modules::run('duc/duc_teachers/MY_data_array',
    										//select
    										array('id')
    	);

    	//приводим данные teachers к массиву
    	foreach($count_teachers as $k=>$v){
    		$arr_teachers[] = $v['id'];
    	}
    	//если teachers нет - генерируем ошибку и возвращаем false
    	if(!isset($arr_teachers) || count($arr_teachers) <= 0){
    		throw new jqGrid_Exception('Ни одного педагога не создано');
      		return false;
    	}
        //приводим текущие данные к одномерному массиву
        //проверяем также массив на наличие значений
        if(is_array($rows) && count($rows) > 0){
	        foreach($rows as $key=>$value){
	        	$rows_id[] = $value['id_teacher'];
	        }
	    }else{
	    	$rows_id = array();
	    }
        // приводим введенные данные концертмейстеров к одномерному массиву
        // и проверяем корректность массива
        if(!is_array($id_teachers)){
        	$rows_in = array($id_teachers);
        }else{
        	$rows_in = array();
        	foreach($id_teachers as $item){
        		if(!empty($item)){
        			$rows_in[] = $item;
        		}
        	}
        }
        //если введенные данные не найдены в существующих концертмейстерах
        // генерируем ошибку и возвращаем false
        if(count($rows_in) > 0){
	        if( array_diff($rows_in, $arr_teachers)){
	        	throw new jqGrid_Exception('Указан несуществующий педагог');
	               return false;
	    	}
    	}
        //вычисляем данные для добавления

        $cols_add = array_diff($rows_in, $rows_id);
        $cols_add = array_unique($cols_add);
        //вычисляем данные для удаления
        $cols_del = array_diff($rows_id, $rows_in);
        $cols_del = array_unique($cols_del);
        /*
        echo "Были данные:\r\n";
        print_r($rows_id);
        echo "Введены данные:\r\n";
        print_r($rows_in);
        echo "Всего учителей:\r\n";
        print_r($arr_teachers);
        echo "Данные для добавления:\r\n";
        print_r($cols_add);
        echo "Данные для удаления:\r\n";
        print_r($cols_del);
        exit;
        */
        // удаляем
        if(is_array($cols_del) && count($cols_del) >= 1){
        	$where = array('id_group' => $id_group,
        					'id_teacher' => $cols_del
        	);
        	if( ! Modules::run('duc/duc_concertmasters/MY_delete',
        	        			//where
        	        			$where
        			)
        	){
        		throw new jqGrid_Exception('Не удалось удалить педагогов у объекта "концертместер"');
        		return false;
        	}
        }
        //добавляем
        if(is_array($cols_add) && count($cols_add) >= 1){
        	$set = array('id_group' => $id_group,
        	);

        	foreach($cols_add as $item){
        		$set['id_teacher'] = $item;
        		if( ! Modules::run('duc/duc_concertmasters/MY_insert', $set)){
         			throw new jqGrid_Exception('Не удалось добавить педагога у объекта "концертместер"');
         			return false;
        		}
        	}
        }
        return true;

    }

     /**
    * Удаление педагогов из списка концертмейстеров
    * @param int - id group
    *
    *
    * return boolean - true || false
    */
    function delete_teachers($id_group){
    	if( ! isset($id_group)){
    		throw new jqGrid_Exception('Не указаны объекты для удаления');
      		return false;
    	}
    	if( ! is_numeric($id_group)){
    		throw new jqGrid_Exception('Не верно указаны объекты для удаления');
      		return false;
    	}
    	// удаляем

        	$where = array('id_group' => $id_group
        	);
        	if( ! Modules::run('duc/duc_concertmasters/delete_teachers', $id_group)){
        		throw new jqGrid_Exception('Не удалось удалить педагога у объекта "концертместер"');
        		return false;
        	}else{
        		return true;
        	}

    }
}
