<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Controller.php";

class MY_Controller extends MX_Controller {

	protected $prefix_model = '';
    protected $postfix_model = '_model';

    protected $MY_errors;
    protected $MY_logs;

    protected $MY_table;
    protected $MY_module;

	function __construct(){		parent::__construct();
	}

	/**
    *  Возвращает данные в виде как они есть
    *  @param array - поля, по умолчанию все
    *  @param array - условия
    *  @param string - "20,10" 20 - кол-во, 10 - смещение
    *
    *  return array
    */
	public function MY_data($select = '*', $where = false, $limit = false, $order_by = false, $group_by = false, $having = false){
		$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
			return $this->$ob->MY_data($select, $where, $limit, $order_by, $group_by, $having);
		}
		return false;
	}

	/**
    *  Возвращает данные в виде как они есть
    *  @param array - поля, по умолчанию все
    *  @param array - условия
    *  @param string - "20,10" 20 - кол-во, 10 - смещение
    *
    *  return array
    */
	public function MY_data_array($select = '*', $where = false, $limit = false, $order_by = false, $group_by = false, $having = false){
		$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
			return $this->$ob->MY_data_array($select, $where, $limit, $order_by, $group_by, $having);
		}
		return false;
	}

	/**
    *  Возвращает данные одной строки в виде объекта
    *  @param array - поля, по умолчанию все
    *  @param array - условия
    *  @param string - "20,10" 20 - кол-во, 10 - смещение
    *
    *  return object
    */
	public function MY_data_row($select = '*', $where = false, $limit = false, $order_by = false){
    	$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
			return $this->$ob->MY_data_row($select, $where, $limit, $order_by);
		}
		return false;
 	}

	/**
    *  Возвращает данные одной строки в простом массиве
    *  @param array - поля, по умолчанию все
    *  @param array - условия
    *  @param string - "20,10" 20 - кол-во, 10 - смещение
    *
    *  return array
    */
	public function MY_data_array_row($select = '*', $where = false, $limit = false, $order_by = false){
    	$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
			return $this->$ob->MY_data_array_row($select, $where, $limit, $order_by);
		}
		return false;
 	}

 	/**
	* 	Возвращает данные в массиве
	*  Расчитана на частое применение вызовов для построения списков форм
	*   @param string - поле ключа ($key) массива (по умолчанию id)
	*  @param string - поле значения ($value) массива (по умолчанию name)
	*  @param array - массив с условиями выборки (по умолчанию false)
	*  @param array || string - массивс условиями сортировки
	*
	*   return array($key=>$value) $key='id', $value='name'
	*/
	public function MY_data_array_one($key_field = 'id', $value_field = 'name', $where = false, $order_by = false, $separator = ' '){
        $ob = $this->MY_get_class_model();
        if(class_exists($ob)){
			return $this->$ob->MY_data_array_one($key_field, $value_field, $where, $order_by, $separator);
		}
		return false;
	}

	/**
    *  Возвращает связаные данные
    *	@param string - имя модели
    *  @param array - поля, по умолчанию все
    *  @param array - условия
    *  @param string - "20,10" 20 - кол-во, 10 - смещение
    *
    *  return array
    */
	public function MY_related_has_many($model_name, $value, $select = '*'){
		$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
			return $this->$ob->MY_related_has_many($model_name, $value, $select);
		}
		return false;
	}

    /**
    *	Возвращает данные принадлежащие к другим таблицам (belongs to)
    *   @param string - имя модели
    *	@param string - значение связующего параметра
    *
    */
    public function MY_related_belongs_to($model, $value, $select = '*'){
    	$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
			return $this->$ob->MY_related_belongs_to($model, $value, $select);
		}
		return false;
    }

	protected function MY_get_class_model(){
		return strtolower(get_class($this).$this->postfix_model);
	}

	/**
	* Встроенная функция вставки данных для модели данного контроллера
	*
	*  @param array - массив параметров запроса 'set'
	*
	*/
    public function MY_insert($set){
    	$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
    		return $this->$ob->MY_insert($set);
    	}
    	return false;
    }

    /**
	* Встроенная функция обновления данных для модели данного контроллера
	*
	*  @param array - массив параметров запроса 'set'
	*  @param array - массив параметров запроса 'where'
	*/
    public function MY_update($set, $where){
    	$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
    		return $this->$ob->MY_update($set, $where);
    	}
    	return false;
    }

    /**
	* Встроенная функция удаления данных для модели данного контроллера
	*
	*  @param array - массив параметров запроса 'where'
	*
	*/
    public function MY_delete($where){
    	$ob = $this->MY_get_class_model();
    	if(class_exists($ob)){
    		return $this->$ob->MY_delete($where);
    	}
    	return false;
    }

/******************************************************************************
***************** Обработка статусных сообщений *******************************
*******************************************************************************
*/
    /**
    * Запись статусных сообщений
    * @param string - имя статуса
    * @param string - сообщение
    * @param array - опциональные параметры сообщения, массив key - имя опции, value - значение опции
    *
    */
	function MY_set_status($name, $level, $message, $options = false){
         if(is_array($options)){
         	foreach($options as $key=>$item){
         		$opt[$key] = $item;
         	}
         }else{
         	$opt = false;
         }
			$this->status[$name][$level][] = array('message' => $message,
			                              'options' => $opt,
            );
	}
    /**
    * Получение статусных сообщений
    * @param string - имя статуса
    * @param array - опциональные параметры сообщения,
    *				если указано, будут возвращены только соответсвующие параметрам
    *
    */
	function MY_get_status($name, $level, $options = false){

	    	if(isset($this->status[$name][$level])){
	    		return $this->status[$name][$level];
	    	}else{
	    		return false;
	    	}

	}
    /**
    * Очистка статусных сообщений
    * @param string - имя статуса
    * @param array - опциональные параметры сообщения,
    *				если указано, будут очищены только соответсвующие параметрам
    *
    */
	function MY_clear_status($name = false, $level = false, $options = false){
        if(!empty($name)){
	        if(!empty($level)){
		        if(isset($this->status[$name][$level])){
		    		unset($this->status[$name][$level]);
		    	}
	    	}else{
	    		unset($this->status[$name]);
	    	}
    	}else{
    		$this->status = array();
    	}

	}

/******************************************************************************
***************** Обработка вспомогательных данных *******************************
*******************************************************************************
*/
	/**
    * Сортировка полей по id значениям
    *	сортировка номерная (значения по возрастанию)
    *	Возвращает массив, где ключ - это id, а значение - это значение сртировки
    *	@param string || массив $ids - может принимать как строку (последовательность id разделенные запятой)
    *								   или массив из id
    *   @param string $fieldSorter - имя поля для сортировки (по умолчанию sorter)
    */
    public function sorterId($ids, $fieldSorter = 'sorter', $module = false, $table = false){
        $incNum = 10; //инкремент сортировки
    	if( ! is_array($ids)){
    		$ids = explode(',', $ids);
    	}
    	foreach($ids as $item){
    		if(is_numeric($item)){
    			$arr_id[] =	$item;
    		}else{
    			return false;
    		}
    	}
    	$module = (!empty($module)) ? $module : $this->MY_module;
    	$table = (!empty($table)) ? $table : $this->MY_table;
    	if(empty($module) && empty($table)) return false;
    	//echo '@';
    	$res = Modules::run($module.'/'.$table.'/MY_data_array_row',
    	                    //select
    	                    array('min('.$fieldSorter.') AS minsort'),
    	                    //where
    	                    array('id' => $ids)
    	);

    	if(isset($res['minsort'])){
    		$n = (is_numeric($res['minsort'])) ? $res['minsort'] : 10;
    		foreach($arr_id as $item){
            	$new[$item] = $n;
            	$n = $n + $incNum;
    		}
    	}
    	if(isset($new)) return $new;
    	return false;
    }

/***************** Конец обработки статусных сообщений ************************/



	/**
        *  Метод обработки ошибок
        *  @param string $class - класс
        *  @param string $method - метод
        *  @param string $level  - уровень ошибки
        *  @param string $message - сообщение
        */
        function MY_error($class, $method, $level, $message ){
            $this->MY_errors[] = array('class' => $class,
                                    'method' => $method,
                                    'level' => $level,
                                    'message' => $message,
                              );
            log_message($level, $message);


        }

        	/**
	*  Установка логов системы
	*  @param string $message
	*/
        function MY_log($message){
            $this->MY_logs[] = $message;
        }


/******************************************************************************
***************** Управление маршрутом модуля *******************************
*******************************************************************************
*/
    /**
	* Маршрут модуля для пользователя
	*
	*/
	function MY_route($group = 'default'){

		$buf = '';
		$buf .= $this->router_modules->run($group, $this->MY_module, true);

        echo $buf;
	}
}