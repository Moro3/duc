<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {

    // массив для хранения результатов запросов
	protected $MY_cache = array();

	/**
	* 	основная таблица модели
	*	$this->MY_table = 'table';
	*/
	protected $MY_table = false;

	/**
	* 	первичный ключ таблицы
	*	$this->MY_primary_key = 'id';
	*/
	protected $MY_primary_key = 'id';

	/** дополнительные допустимые таблицы модели
	*
	*	key - псевдоним таблицы в случае сложных запросов
	*	$this->MY_table_access = array(
	*		'main' => 'table',
	*		'cat' => 'table_category',
	*		'men' => 'table_menu'
	*	);
	*/
	protected $MY_table_access = array();

	//связь один ко многим или многие ко многим
	protected $MY_has_many = array();

	//связь многие к одному
	protected $MY_belongs_to = array();

	/**
	* Префикс используется в случае если в запросе используется нескольких таблиц
	* для исключения коллизий между одинаковыми именами полей
	* select запрос table.field будет автоматически переведен в след тип: "table"$prefix"field"
	* !!! если поле относится к основной таблице, то имя НЕ будет предваряться префиксом!
	*
	*/
	protected $MY_prefix_as = '__';

	/**
	* Разрешенные разделительные параметры для полей запросов
	* используются для генерации вывода полей
	*
	*/
	protected $MY_separator_field_allow = array('separator', 'prefix', 'suffix', 'rowprefix', 'rowsuffix', 'replace');

	/**
	* Имя псевдонима основной таблицы в модуле
	*
	*/
	protected $MY_name_table_main = 'main';

	/**
	* Временный массив при запросах для указания псевдонимов таблиц
	*/
	protected $MY_temp_from_is = array();

	function __construct(){
		parent::__construct();
	}

	/**
	* Генерация ключа для хранения полученый значений в кеш
	* @param array - массив из агрументов
	*
	* @return strung - строка
	*/
    protected function _genkey($arr = array()){
    	$keys = '';
    	if(is_array($arr)){
    		foreach($arr as $key=>$value){
    			if(is_array($value)){
    				$keys .= $key.$this->_genkey($value);
    			}else{
    				$keys .= $key.$value;
    			}
    		}
    		
    	}
    	$keys = sha1($keys);
    	if(isset($keys)) return $keys;
    	return false;
    }

	protected function _get_cache($key){
    	if(isset($this->MY_cache[$key])){
    		return $this->MY_cache[$key];
    	}
    	return null;
	}

	protected function _set_cache($key, $value){
		if(is_string($key)){
			$this->MY_cache[$key] = $value;
		}
	}

	public function _clear_cache($key = false){
        if($key){
        	if(isset($this->MY_cache[$key])) unset($this->MY_cache[$key]);
        }
        $this->MY_cache = array();
	}

	/**
    *  Возвращает данные в виде массива, а параметры в виде объектов
    *  @param array - поля, по умолчанию все
    *  @param array - условия
    *  @param string - "20,10" 20 - кол-во, 10 - смещение
    *  @param string||array - строка или массив сортировки. строка - имя поля сортировки. массив - key = имя поля, value = направление сортировки ('asc','desc','random')
    *
    *  return array
    */
	public function MY_data($select = '*', $where = false, $limit = false, $order_by = false, $group_by = false, $having = false){
		$key_cache = $this->_genkey(array_merge(func_get_args(),array('object')));
        if($r = $this->_get_cache($key_cache)) return $r;

		$query = $this->MY_data_return($select, $where, $limit, $order_by, $group_by, $having);
		$result = $query->result();
        $result_key_id = $this->MY_get_result_related($result, $select);

        if(isset($result_key_id)){
        	$this->_set_cache($key_cache, $result_key_id);
        	return $result_key_id;
        }
        //print_r($result);
        $this->_set_cache($key_cache, $result);
        return $result;
	}

	/**
    *  Возвращает данные в виде как они есть, списрчные данные и их параметры в виде массива
    *  @param array - поля, по умолчанию все
    *  @param array - условия
    *  @param string - "20,10" 20 - кол-во, 10 - смещение
    *  @param string||array - строка или массив сортировки. строка - имя поля сортировки. массив - key = имя поля, value = направление сортировки ('asc','desc','random')
    *
    *  return array
    */
	public function MY_data_array($select = '*', $where = false, $limit = false, $order_by = false, $group_by = false, $having = false){
        $key_cache = $this->_genkey(array_merge(func_get_args(),array('array')));
        if($r = $this->_get_cache($key_cache)) return $r;

        $query = $this->MY_data_return($select, $where, $limit, $order_by, $group_by, $having);
		$result = $query->result_array();
        $result_key_id = $this->MY_get_result_related($result, $select);


        if(isset($result_key_id)){
        	$this->_set_cache($key_cache, $result_key_id);
        	return $result_key_id;
        }
        //print_r($result);
        $this->_set_cache($key_cache, $result);
        return $result;
	}

	/**
	*	Возвращает результат со связующими данными
	*	@param array - массив результатов
	*	@param string - тип переданных данных
	*
	*	@result array - массив со связанными данными
	*/
	protected function MY_get_result_related($result, $select_related = false){

        $select_related = $this->get_correct_select_related($select_related);

		$i = 1;
		foreach($result as $key=>$value){
			if($i == 1){
				if(is_array($value)){
					$type = 'array';
				}elseif(is_object($value)){
					$type = 'object';
				}else{
					return $result;
				}
			}
			//собираем все id-шники
			if($type == 'array'){
				if(!empty($value['id'])) $result_key_id[$value['id']] = $value;
            }elseif($type == 'object'){
            	if(!empty($value->id))	$result_key_id[$value->id] = $value;
            }
			// собираем значения для внешних ключей (связь многие к одному)
			foreach($this->MY_belongs_to as $name_field=>$value_field){
				if($type == 'array'){
					if(isset($value[$value_field['foreign_key']])){
						$foreigns[$name_field][$value_field['foreign_key']][$value[$value_field['foreign_key']]] = $value[$value_field['foreign_key']];
					}
				}elseif($type == 'object'){
					if(isset($value->$value_field['foreign_key'])){
						$foreigns[$name_field][$value_field['foreign_key']][$value->$value_field['foreign_key']] = $value->$value_field['foreign_key'];
					}
				}
			}
			// собираем значения для внешних ключей (связь многие ко многим)
			foreach($this->MY_has_many as $name_field=>$value_field){
				if(isset($select_related[$name_field])){
					if($type == 'array'){
						if(isset($value[$this->MY_primary_key])){
							$foreigns[$name_field][$this->MY_primary_key][$value[$this->MY_primary_key]] = $value[$this->MY_primary_key];
						}
					}elseif($type == 'object'){
						if(isset($value->{$this->MY_primary_key})){
							$foreigns[$name_field][$this->MY_primary_key][$value->{$this->MY_primary_key}] = $value->{$this->MY_primary_key};
						}
					}
				}
			}
		}
		// если есть результат со значениями id по ключу
		// и собраны значения для внешних ключей
		// делаем запросы для ключей и присоединяем их к остальным значениям
		if(isset($result_key_id) && count($result_key_id) > 0 && isset($foreigns) && is_array($foreigns)){
			foreach($this->MY_belongs_to as $name_field=>$value_field){
        		//echo $foreigns[$name_field][$value_field['foreign_key']];
        		if(isset($foreigns[$name_field]) && isset($foreigns[$name_field][$value_field['foreign_key']])){
        			$related[$name_field] = $this->MY_related_belongs_to($name_field, $foreigns[$name_field][$value_field['foreign_key']]);
        		}

			    foreach($result_key_id as $key=>$value){
					if($type == 'array'){
						if(isset($related[$name_field]) && isset($related[$name_field][$value[$value_field['foreign_key']]])){
							$result_key_id[$value['id']][$name_field] = $related[$name_field][$value[$value_field['foreign_key']]];
						}
					}elseif($type == 'object'){
						if(isset($related[$name_field]) && isset($related[$name_field][$value->$value_field['foreign_key']])){
							$result_key_id[$value->id]->$name_field = $related[$name_field][$value->$value_field['foreign_key']];
						}
					}
				}
			}
			foreach($this->MY_has_many as $name_field=>$value_field){
        		//echo $foreigns[$name_field][$value_field['foreign_key']];
        		if(isset($foreigns[$name_field]) && isset($foreigns[$name_field][$this->MY_primary_key])){
        			$arr_has_many = $this->MY_related_has_many($name_field, $foreigns[$name_field][$this->MY_primary_key], $select_related[$name_field]);
        			$related[$name_field] = $this->get_array_key($arr_has_many, $value_field['foreign_key']);
        			//print_r($related[$name_field]);
        			//exit;
        		}

			    foreach($result_key_id as $key=>$value){
					if($type == 'array'){
						if(isset($related[$name_field]) && isset($related[$name_field][$value[$this->MY_primary_key]])){
							$result_key_id[$value['id']][$name_field] = $related[$name_field][$value[$this->MY_primary_key]];
						}
					}elseif($type == 'object'){
						if(isset($related[$name_field]) && isset($related[$name_field][$value->{$this->MY_primary_key}])){
							$result_key_id[$value->id]->$name_field = $related[$name_field][$value->{$this->MY_primary_key}];
						}
					}
				}
			}
		}
		if(isset($result_key_id)) return $result_key_id;
		return $result;
	}

	protected function get_correct_select_related($related){
		if($related != false){
			if(isset($related['related']) && is_array($related['related'])){
				foreach($related['related'] as $key=>$value){
					if(is_numeric($key)){
						$result[$value] = '*';
					}else{
						if(is_array($value)){
							$result[$key] = $value;
						}
					}
				}
			}
		}
		if(isset($result)) return $result;
		return false;
	}
	/**
	* Возвращает массив с выборкой по полю и его значению
	*
	*
	*/
	protected function get_array_key($array, $field){
		foreach($array as $key=>$value){
			if(is_array($value)){
				if(!empty($value[$field])){
					/* удаление из результата значения ключа, для предотвращения избыточности данных
					$value_field = $value[$field];
					unset($value[$field]);
					$result[$value_field][] = $value;
					*/
					$result[$value[$field]][] = $value;
				}
			}
			if(is_object($value)){
				if(!empty($value->$field)){
					/* удаление из результата значения ключа, для предотвращения избыточности данных
					$value_field = $value->$field;
					unset($value->$field);
					$result[$value_field][] = $value;
					*/
					$result[$value->$field][] = $value;
				}
			}
		}
		if(isset($result)) return $result;
		return false;
	}
	/**
	*	Возвращает присоединенный к результату данные по связи belongs_to (один ко многим)
	*	@param array - массив с результатами
	*	@param array - массив
	*
	*/
	protected function MY_get_result_related_belongs_to($result, $foreigns, $type = 'object'){
		if(isset($result) && count($result) > 0 && isset($foreigns) && is_array($foreigns)){
			foreach($this->MY_belongs_to as $name_field=>$value_field){
        		//echo $foreigns[$name_field][$value_field['foreign_key']];
        		if(isset($foreigns[$name_field]) && isset($foreigns[$name_field][$value_field['foreign_key']])){
        			$related[$name_field] = $this->MY_related_belongs_to($name_field, $foreigns[$name_field][$value_field['foreign_key']]);
        		}

			    foreach($result as $key=>$value){
					if($type == 'array'){
						if(isset($related[$name_field]) && isset($related[$name_field][$value[$value_field['foreign_key']]])){
							$result[$value['id']][$name_field] = $related[$name_field][$value[$value_field['foreign_key']]];
						}
					}elseif($type == 'object'){
						if(isset($related[$name_field]) && isset($related[$name_field][$value->$value_field['foreign_key']])){
							$result[$value->id]->$name_field = $related[$name_field][$value->$value_field['foreign_key']];
						}
					}
				}
			}
		}
		return $result;
	}

	/**
	*	Возвращает результат щапроса к БД
	*	@param array - массив для запроса select
	*	@param array - массив для запроса where
	*	@param string - limit запрос "20,10" 20 - кол-во, 10 - смещение
	*   @param string||array - строка или массив сортировки. строка - имя поля сортировки. массив - key = имя поля, value = направление сортировки ('asc','desc','random')
    *
	*	@result object - объект результат запроса к БД
	*/
	protected function MY_data_return($select = '*', $where = false, $limit = false, $order_by = false, $group_by = false, $having = false){
		$key_cache = $this->_genkey(func_get_args());
        if($r = $this->_get_cache($key_cache)) return $r;

        if($select !== '*'){
        	if(!is_array($select)) $select = array($select);
        	//удаляем запрос для связей
        	if(isset($select['related'])) unset($select['related']);
        	//записываем запрос для JOIN если есть
        	// обработан позже чтобы исключить коллизии в запросе FROM
        	if(isset($select['join'])){
        		$join = $select['join'];
        		unset($select['join']);
        	}
            /*
        	if(isset($select['encode'])){
        		$this->db->select($select['encode'], false);
        		unset($select['encode']);
        	}
        	*/
        	foreach($select as $key=>$value){
        		if($key == 'encode'){
        			if(is_array($value)){
        				foreach($value as $item_select){
        					$this->db->select($item_select, false);
        				}
        			}else{
        				$this->db->select($value, false);
        			}
        		}else{
	        		if($from = $this->MY_get_table_from($value)){
	        			$this->MY_temp_from_is[$from] = true;
	                }
	                $sel = $this->MY_get_select_table_alias($value);
	        		$this->db->select($sel);
        		}        		
        	}        	
        }

        if($where !== false){
           $this->MY_generate_where($where);
        }
        if($limit !== false){
        	$str = (strpos($limit, ',')) ? explode(',', $limit) : array($limit, 0);
        	if(is_numeric($str[0]) && is_numeric($str[1])){
        		if(!empty($str[0])){
        			$this->db->limit($str[0],$str[1]);
        		}
        	}else{
        		exit('не верно задан параметр limit');
        	}
        }        

        if($order_by !== false){
        	if(is_array($order_by)){
        		foreach($order_by as $key=>$value){
        			if(!empty($key) && !is_numeric($key)){
        				$order = $key;
        				if(in_array($value,array('asc','desc','random'))){
        					$direct = $value;
        				}else{
        					//echo $value;
        					$direct = 'asc';
        				}
        			}else{
        				$order = $value;
        				$direct = 'asc';
        			}
        			$this->db->order_by($order,$direct);
        		}
        	}else{
        		if(!empty($order_by)){
        			$this->db->order_by($order_by);
        		}
        	}
        }else{
        	if($this->db->field_exists('sorter',$this->MY_table)){
        		$table_alias = $this->MY_get_table_alias($this->MY_table);
        		if(isset($this->MY_temp_from_is) && is_array($this->MY_temp_from_is) && count($this->MY_temp_from_is) > 0){
        			$this->db->order_by($table_alias.'.sorter', 'asc');
        		}else{
        			$this->db->order_by('sorter', 'asc');
        		}

        	}
        }

		if($group_by !== false){
        	$this->db->group_by($group_by);
        	if($having !== false){
        		$this->MY_generate_having($having);
        	}
		}

		//обрабатываем запрос JOIN если он есть
		// !!! Обработка должна быть перед обработкой запроса FROM чтобы исключить коллизии одинаковых таблиц
		if(isset($join)){
       		$this->MY_generate_join($join);
       	}

        if(isset($this->MY_temp_from_is) && is_array($this->MY_temp_from_is) && count($this->MY_temp_from_is) > 0){

        	foreach($this->MY_temp_from_is as $name=>$b){
        		//var_dump($this->MY_temp_from_is);
                //exit;
        		//если таблица установлена в true - выводим, если в false - не выводим(значит она должна быть в запросе join)
        		if($b === true)	$this->db->from($name);
        	}
        }else{
        	$this->db->from($this->MY_table);
        }

        $query = $this->db->get();
        // очищаем временные псевдонимы
        $this->MY_temp_from_is = array();
        $this->_set_cache($key_cache, $query);
		return $query;
	}

	/**
    *  Возвращает данные одной строки в виде свойств объекта
    *  @param array - поля, по умолчанию все
    *  @param array - условия
    *  @param string - "20,10" 20 - кол-во, 10 - смещение
    *
    *  return object
    */
	public function MY_data_row($select = '*', $where = false, $limit = false, $order_by = false){
    	 $arr = $this->MY_data($select, $where, $limit, $order_by);
    	 if(is_array($arr) && count($arr) > 0){
    	 	foreach($arr as $item){
    	 		return $item;
    	 	}
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
    	 $arr = $this->MY_data_array($select, $where, $limit, $order_by);
    	 if(is_array($arr) && count($arr) > 0){
    	 	foreach($arr as $item){
    	 		return $item;
    	 	}
    	 }
    	 return false;
 	}

 	/**
	* 	Возвращает данные в массиве
	*  Расчитана на частое применение вызовов для построения списков форм
	*   @param string - поле ключа ($key) массива (по умолчанию id)
	*  @param string - поле значения ($value) массива (по умолчанию name),
	*					если указан массив с полями, то они будут возвращены одной строкой разделенные пробелом
	*  @param array - массив с условиями выборки (по умолчанию false)
	*  @param array || string - массивс условиями сортировки
	*
	*   return array($key=>$value) $key='id', $value='name'
	*/
	public function MY_data_array_one($key_field = 'id', $value_field = 'name', $where = false, $order_by = false, $separator = ' '){
        if( ! is_array($value_field)){
        	$value_field = array($value_field);
        }

        $main_table = '!';
        $main_key = $this->MY_get_field($key_field);
        $main_key = (!empty($main_key)) ? $main_key : $key_field;

        $select_value = $value_field;
        $select_value[] = $key_field;


       	if( ! is_array($separator)){
      			$separator = array('!' => array('separator' => $separator));
       	}
        $sep_result = $this->MY_separator_elements($separator);
        //print_r($sep_result);
        //exit;
        /*
        foreach($value_field as $f){
			$ff = $this->MY_get_field($f);
			$ff = ($ff !== false) ? $ff : $f;
			$tt = $this->MY_get_table($f);
			$tt = ($tt !== false) ? $tt : $main_table;
			$tt = ($tt === $this->MY_name_table_main) ? $main_table : $tt;
			$select_arr[$tt][$ff] = true;
		}
		foreach($select_arr as $t2=>$it2){
			foreach($it2 as $f2=>$v2){
				if(!empty($t2) && $t2 !== $main_table){
					$related[$t2][] = $f2;

					$select_query[] = $t2.$this->MY_prefix_as.$f2;
				}else{
					$norelated[] = $f2;
					$select_query[] = $this->MY_name_table_main.'.'.$f2;
				}
			}
		}
		$select_query[] = $main_key;
		*/
		//if(isset($norelated)) $select_query = array_merge($select_query, $norelated);
		//if(isset($related)) $select_query['related'] = $related;
        //print_r($norelated);

		//exit;
        $res_arr = $this->MY_data_array($select_value, $where, false, $order_by);
        //$query = $this->db->select()->from($this->table)->order_by("sort_i", "asc")->get();
	    //$res_arr = $query->result_array();
	    //echo '<pre>';
		//print_r($res_arr);
		//exit;
	    if(is_array($res_arr) && count($res_arr) > 0){
		    foreach ($res_arr as $row)
			{
	            $res_val = array();
	            foreach($value_field as $tb=>$items){
	            	/*
	            	foreach($items as $fld=>$val_fld){
	            		//echo 'таблица: '.$tb.', поле: '.$fld.'<br />';
	            		if(isset($row[$tb]) && is_object($row[$tb])){
	            			$res_val[] = $row[$tb]->$fld;
	            		}elseif(isset($row[$fld])){
	            			$res_val[] = $row[$fld];
	            		}
	            	}
	            	*/
	            	//$res_val[] = $row[$items];
	            }
	            foreach($row as $key=>$value){
	            	if($key != $main_key){
	            		$str_val = $value;
	            		if(isset($sep_result[$key])){
	            			if(isset($sep_result[$key]['prefix'])){
	            				$str_val = $sep_result[$key]['prefix'].$str_val;
	            			}
	            			if(isset($sep_result[$key]['suffix'])){
	            				$str_val = $str_val.$sep_result[$key]['suffix'];
	            			}
	            		}
	            		$res_val[] = $str_val;
	            	}
	            }
	            //echo 'Ключ для '.$key_field.': '.$this->MY_get_field($key_field).'<br>';
                //print_r($separator);
                //exit;
       			$res_val_string = (!empty($separator['!']['separator'])) ? implode($separator['!']['separator'], $res_val) : implode('', $res_val);
       			$res_val_string = (!empty($separator['!']['rowprefix'])) ? $separator['!']['rowprefix'].$res_val_string : $res_val_string;
       			$res_val_string = (!empty($separator['!']['rowsuffix'])) ? $res_val_string.$separator['!']['rowsuffix'] : $res_val_string;

	            $result[$row[$main_key]] = $res_val_string;

	            unset($res_val);
			}
		}else{
			$result = array();
		}
		//print_r($result);
		//exit;
        return $result;
	}

	/**
	* Формирование елементов разделителей для полей
	* @param array - массив с параметрами разделителей.
	*			[!] -  глобальные параметры для всех полей
	*				separator - разделитель между полями (если вместо массива [!] пришла строка, то она является именно этим параметром)
	*               prefix - префикс, строка перед каждым полем
	*               suffix - суфикс, строка после каждого поля
	*               rowprefix - общий префикс всех полей
	*               rowsuffix - общий суфикс всех полей
	*           [field] - аналогичные параметры как у глобальных, текщие перекрывают глобальные
	*                     !! - если вместо массива пришла строка то она заменят параметр 'префикс'
	*
	* return array - массив полей с выходными параметрами в виде массива
	*/
	protected function MY_separator_elements($separator){
		//разбираем separator на результирующие поля, если он является массивом

        if(is_array($separator)){
        	if(isset($separator['!'])){
        		if(!is_array($separator['!'])){
        			$separator['!'] = array('separator' => $separator['!']);
        		}
        			foreach($separator['!'] as $sep_key=>$sep_value){
                    	if(in_array($sep_key, $this->MY_separator_field_allow)){
                    		$global_sep[$sep_key] = $sep_value;
                    	}
        			}

        	}
        	foreach($separator as $field=>$sep_field){
               	if($field == '!') continue;
               	if($sep_res = $this->MY_get_select_alias_field_prefix($field)){
               		if( ! is_array($sep_field)){
               			$sep_field = array('prefix' => $sep_field);
               		}
               		foreach($sep_field as $sep_f_key=>$sep_f_value){
               			if(in_array($sep_f_key, $this->MY_separator_field_allow)){
               				$res_separator[$sep_res][$sep_f_key] = $sep_f_value;
               			}
               			if(isset($global_sep)){
	               			$res_separator[$sep_res] = array_merge($global_sep, $res_separator[$sep_res]);
               			}
               		}
               	}
        	}

        }
        if(isset($res_separator)) return $res_separator;
        return false;
	}

	/**
	* Генерация запроса select
	*
	*/
    protected function MY_generate_select($select){

    }

	/**
	* Генерация запроса where
	*
	*/
    protected function MY_generate_where($where, $type = 'and'){
    		if(!is_array($where)) return false;
        	foreach($where as $key=>$value){
        		if($from = $this->MY_get_table_from($key)) $this->MY_temp_from_is[$from] = true;

        		if(is_array($value)){
        			$check_type = strtolower($key);
        			if($check_type == 'or' || $check_type == 'in' || $check_type == 'ornot' || $check_type == 'not' || $check_type == 'orin'){
                    	$this->MY_generate_where($value, $check_type);
        			}elseif($check_type == 'like' || $check_type == 'orlike' || $check_type == 'notlike' || $check_type == 'ornotlike'){
                        $this->MY_generate_like($value, $check_type);
        			}else{
	        			if(isset($value['encode'])){
	        				$this->MY_generate_where_query($key, $value['encode'], false, $type);
	        			}else{
	        				$this->MY_generate_where_query($key, $value, false, $type);
	        			}
        			}
        		}else{
        			$this->MY_generate_where_query($key, $value, true, $type);
        			/*
        			if(is_numeric($key)){
        				$this->db->where($value);
        			}else{
        				$this->db->where($key,$value);
        			}
        			*/
        		}
        	}
    }

	protected function MY_generate_where_query($key, $value, $encode = true, $type = 'and'){
		if(is_numeric($key)){
			$this->db->where($value);
		}else{
			switch($type){
				case 'or':
					$this->db->or_where($key,$value, $encode);
				break;
				case 'in':
					$this->db->where_in($key,$value, $encode);
				break;
				case 'ornot':
					$this->db->or_where_not_in($key,$value, $encode);
				break;
				case 'not':
					$this->db->where_not_in($key,$value, $encode);
				break;
				case 'orin':
					$this->db->or_where_in($key,$value, $encode);
				break;
				default:
				    if(is_array($value)){
						$this->db->where_in($key,$value, $encode);
					}else{
						$this->db->where($key,$value, $encode);
					}
				break;
			}
		}
	}

	/**
	* Генерация запроса like
	*
	*/
    protected function MY_generate_like($where, $type = 'like'){
       if(!is_array($where)) return false;
        	foreach($where as $key=>$value){
        		if($from = $this->MY_get_table_from($key)) $this->MY_temp_from_is[$from] = true;

        		if(is_array($value)){
        			$check_side = strtolower($key);
        			if($check_side == 'before' || $check_side == 'after' || $check_side == 'both' || $check_side == 'none'){
                    	$this->MY_generate_like_query(0, $value, $type, $check_side);
        			}else{
	        			$this->MY_generate_like_query($key, $value, $type);
        			}
        		}else{
        			$this->MY_generate_like_query($key, $value, $type);
        		}
        	}

    }

    protected function MY_generate_like_query($key, $value, $type = 'like', $side = 'both'){
		if(is_numeric($key)){
			if(is_array($value)){
				$this->db->like($value, false, $side);
			}
		}else{
			switch($type){
				case 'like':
					if(is_array($value)){
						$this->db->like($value, false, $side);
					}else{
						$this->db->like($key,$value, $side);
					}
				break;
				case 'orlike':
					if(is_array($value)){
						$this->db->or_like($value, false, $side);
					}else{
						$this->db->or_like($key,$value, $side);
					}
				break;
				case 'notlike':
					if(is_array($value)){
						$this->db->not_like($value, false, $side);
					}else{
						$this->db->not_like($key,$value, $side);
					}
				break;
				case 'ornotlike':
					if(is_array($value)){
						$this->db->or_not_like($value, false, $side);
					}else{
						$this->db->or_not_like($key,$value, $side);
					}
				break;
				default:
				    if(is_array($value)){
						$this->db->like($value, false, $side);
					}else{
						$this->db->like($key,$value, $side);
					}
				break;
			}
		}
	}

	/**
	* Генерация запроса having
	*
	*/
    protected function MY_generate_having($where, $type = 'and'){
    		if(!is_array($where)) return false;
        	foreach($where as $key=>$value){
        		if($from = $this->MY_get_table_from($key)) $this->MY_temp_from_is[$from] = true;

        		if(is_array($value)){
        			$check_type = strtolower($key);
        			if($check_type == 'or'){
                    	$this->MY_generate_having($value, $check_type);
        			}else{
	        			if(isset($value['encode'])){
	        				$this->MY_generate_having_query($key, $value['encode'], false, $type);
	        			}else{
	        				$this->MY_generate_having_query($key, $value, false, $type);
	        			}
        			}
        		}else{
        			$this->MY_generate_having_query($key, $value, true, $type);
        			/*
        			if(is_numeric($key)){
        				$this->db->where($value);
        			}else{
        				$this->db->where($key,$value);
        			}
        			*/
        		}
        	}
    }

    protected function MY_generate_having_query($key, $value, $encode = true, $type = 'and'){
		if(is_numeric($key)){
			$this->db->having($value);
		}else{
			switch($type){
				case 'or':
					$this->db->or_having($key,$value, $encode);
				break;
				default:
				    if(is_array($value)){
						$this->db->having($key,$value, $encode);
					}else{
						$this->db->having($key,$value, $encode);
					}
				break;
			}
		}
	}

	/**
	* Генерация запроса join
	*
	*/
    protected function MY_generate_join($join, $type = 'join'){
    		if(!is_array($join)) return false;
        	foreach($join as $table=>$items){
        		if(is_array($items)){
        			$type_join = strtolower($table);
        			if($type_join == 'join' || $type_join == 'left' || $type_join == 'right' || $type_join == 'outer' || $type_join == 'inner' || $type_join == 'left outer' || $type_join == 'right outer'){
		                $this->MY_generate_join($items, $type_join);
		        	}else{
                        $type_join = $type;

                        foreach($items as $key=>$value){
			        		if(is_array($value) && isset($value['encode'])){
			        			$res_val[$key] = $value['encode'];
			        		}else{
			        			$res_val[$key] = $value;
			        		}

			        		/*
			        		if(is_array($value)){
				        			if(isset($value['encode'])){
				        				$res_val[] = $value['encode'];
				        				//$this->MY_generate_join_query($table, $key, $value['encode'], false, $type_join);
				        			}else{
				        				$res_val[] = $value;
				        				//$this->MY_generate_join_query($table, $key, $value, false, $type_join);
				        			}
                                    $this->MY_generate_join_query($table, $key, $res_val, false, $type_join);
			        		}else{
			        			$this->MY_generate_join_query($table, $key, $value, true, $type_join);
			        			/*
			        			if(is_numeric($key)){
			        				$this->db->where($value);
			        			}else{
			        				$this->db->where($key,$value);
			        			}

			        		}
			        		*/
		        		}
		        		$this->MY_generate_join_query($table, 0, $res_val, false, $type_join);
		        		unset($res_val);
		    		}
		    	}else{
        			$this->MY_generate_join_query($table, 0, $items, true, $type);
        			/*
        			if(is_numeric($key)){
        				$this->db->where($value);
        			}else{
        				$this->db->where($key,$value);
        			}
        			*/
        		}
        	}
    }

	protected function MY_generate_join_query($table, $key, $value, $encode = true, $type = 'join'){
        //вычисляем имя реальной таблицы и псевдонима
        if($real_table = $this->MY_get_table_real($table)){
            $alias_table = $this->MY_get_table_alias($real_table);
			//echo $real_table.'<br />';
			//echo $alias_table;
			//exit;
			if(!empty($alias_table) && $real_table !== $alias_table){
				$query_table = $real_table.' AS '.$alias_table;
			}else{
				$query_table = $real_table;
			}
			//делаем фиктивный запрос на получение строки для запроса парамтра FROM
			$join_table = $this->MY_get_table_from($alias_table.'.id');
			//убираем из формирования запроса FROM данную таблицу, т.к. она  будет присутствовать в запросе JOIN
			//echo $join_table;
			//exit;
			$this->MY_temp_from_is[$join_table] = false;
		}else{
			//exit('неверна задана таблица в запросе JOIN');
            //return false;
            $query_table = $table;
		}

        //вычисляем имена таблиц в ключе и значении условий
        //и если они не равны текущей реальной таблице записываем их в запрос FROM
        $key_table = $this->MY_get_table($key);
        $value_table = $this->MY_get_table($value);
        /*
        if(!empty($key_table) && $this->MY_get_table_real($key_table) !== $real_table){
        	$from_key_table = $this->MY_get_table_from($key);
        	$this->MY_temp_from_is[$from_key_table] = true;
        }
        */
        if(!empty($value_table) && $this->MY_get_table_real($value_table) !== $real_table){
        	//$from_value_table = $this->MY_get_table_from($value);
        	//$this->MY_temp_from_is[$from_value_table] = true;
        }

		if(is_array($value)){
				foreach($value as $key_f=>$item){
					if(strpos(trim($key_f), ' ')){
						$val[] = $key_f.$item;
					}else{
						$val[] = $key_f.' = '.$item;
					}
				}
				$res_val = implode(' AND ', $val);
			}else{
				$res_val = $value;
			}
			$this->db->join($query_table, $res_val, $type);
		/*
		if(is_numeric($key)){
			if(is_array($value)){
				foreach($value as $key_f=>$item){
					if(strpos(trim($key_f), ' ')){
						$val[] = $key_f.$item;
					}else{
						$val[] = $key_f.' = '.$item;
					}
                    $res_val = implode(' AND ', $val);
				}
			}else{
				$res_val = $value;
			}
			$this->db->join($query_table, $res_val, $type);
		}else{
			if(is_array($value)){
				foreach($value as $item){
					if(strpos(trim($key), ' ')){
						$val[] = $key.$item;
					}else{
						$val[] = $key.' = '.$item;
					}
                    $res_val = implode(' AND ', $val);
					$this->db->join($query_table,$res_val, $type);
				}
			}else{
				if(strpos(trim($key), ' ')){
					$val = $key.$value;
				}else{
					$val = $key.' = '.$value;
				}
				$this->db->join($query_table,$val, $type);
			}

		}
		*/
	}

	/**
	* Возвращает имя таблицы если она указана через разделитель .(точка)
	* Если разделителя нет возвращает false
	*  @param string - имя в запросе
	*
	*  @return string - строка имени таблицы или false
	*/
	protected function MY_get_table($name){
         if(!(strpos($name, '.'))) return false;
         $arr =	explode('.', $name);
         if(count($arr) > 2) return false;
         if(!empty($arr[0])){
         	return $arr[0];
         }
         return false;
	}
    /**
	* Проверяет на доступность реальной таблицы
	* и возвращает имя псевдонима таблицы если оно разрешено
	* @param string - имя псевдонима таблицы
	*/
	protected function MY_get_table_alias($name){
		if(in_array($name, $this->MY_table_access)){
			$arr = array_keys($this->MY_table_access, $name);
			if( ! is_numeric($arr[0]))	return $arr[0];
		}
		return false;
	}
	/**
	* Проверяет на доступность имени таблицы
	* и возвращает реальное имя таблицы если оно разрешено
	* @param string - имя реальной таблицы
	*/
	protected function MY_get_table_real($table){
		if(isset($this->MY_table_access[$table])){
       		return $this->MY_table_access[$table];
       		return $table;
       	}else{
       		if(in_array($table,$this->MY_table_access)){
       			return $table;
       		}
       	}
       	return false;
	}
	/**
	* Возвращает имя поля если оно указано через разделитель .(точка)
	* Если разделителя нет возвращает переданную строку как есть
	*  @param string - имя в запросе
	*
	*  @return string - строка имени таблицы
	*/
	protected function MY_get_field($name){
         if(!(strpos($name, '.'))) return $name;
         $arr =	explode('.', $name);
         if(count($arr) > 2) return false;
         if(!empty($arr[1])){
         	return $arr[1];
         }
         return false;
	}

    /**
    * Возвращает строку для запроса from
    * @param string - имя строки таблица.поле
    *
    */
	protected function MY_get_table_from($name){
		if($table = $this->MY_get_table($name)){
			if(isset($this->MY_table_access[$table])){
         		return $this->MY_table_access[$table].' AS '.$table;
         	}else{
         		if(in_array($table,$this->MY_table_access)){
         			return $table;
         		}
         	}

		}
		return false;
	}

	/**
    * Возвращает строку для запроса select
    * @param string - имя строки таблица.поле
    *
    */
    protected function MY_get_select_table_alias($name){
    	if($table = $this->MY_get_table($name)){
			if($field = $this->MY_get_field($name)){
				if($table != $this->MY_name_table_main && $table != $this->MY_table_access[$this->MY_name_table_main]){
					if(isset($this->MY_table_access[$table])){
						return $name . ' AS ' . $this->MY_get_select_alias_field_prefix($name);
		         	}else{
		         		if(in_array($table,$this->MY_table_access)){
		         			$table = $this->MY_get_table_alias($table);

		         			return $name . ' AS ' . $this->MY_get_select_alias_field_prefix($name);
		         		}
		         	}
	         	}
         	}
		}

		return $name;
    }

    /**
    * Возвращает строку результирующего поля для запроса select с учетом префикса
    * т.е. если вы запрашиваете поле основной таблицы main.name => name,
    * если это второстепенная таблица table2.name => table2__name (вернет строку с таблицей и префиксом поля)
    * @param string - имя строки таблица.поле
    *
    */
    protected function MY_get_select_alias_field_prefix($name){
    	//проверяем содержится ли имя таблице в строке
    	if($table = $this->MY_get_table($name)){
			//получаем имя поля из строки
			if($field = $this->MY_get_field($name)){
				//если таблица не является основной таблицей генерируем псевдоним
				if($table != $this->MY_name_table_main && $table != $this->MY_table_access[$this->MY_name_table_main]){
					if(isset($this->MY_table_access[$table])){
						return $table.$this->MY_prefix_as.$field;
		         	}else{
		         		if(in_array($table,$this->MY_table_access)){
		         			$table = $this->MY_get_table_alias($table);

		         			return $table.$this->MY_prefix_as.$field;
		         		}
		         	}
	         	}
         	}
		}

		return $name;
    }

    /**
    * Проверяет содержится ли в переданном массиве или строке префик названия главной таблицы
    * Это например требуется для определения с префиксом или без задан запрос в полях select, для корректного вывода их(с префиксом или без) в полях where
    * т.к. если запрос состоит из нескольких таблиц, то могут возникнуть коллизии в результатах запроса с одинаковыми названием полей
    *  @param mixed $select - строка или массив (если несколько полей)
    *
    *	@return boolean - true если префикс главной таблицы обнаружен хотя в одном из значений, иначе false
    */
    protected function MY_is_main_prefix_array($select){
    	if( ! is_array($select)) $select = array($select);
    	/*
    	echo 'Поступивший select:';
    	print_r($select);
    	echo '<br>';
        */
    	foreach($select as $item){
    		if($table = $this->MY_get_table($item)){
    			//echo 'Имя найденой таблицы: '.$table.'<br>';
    			//echo 'Имя главной таблицы: '.$this->MY_name_table_main.'<br>';
    			//echo 'Наличие главной таблицы: '.$this->MY_table_access[$this->MY_name_table_main].'<br>';
    			if($table == $this->MY_name_table_main){
    				//if(isset($this->MY_table_access[$this->MY_name_table_main])) return true;
    				//echo '<b>Название главной совпало</b><br>';
    				return true;
    			}
    		}
    	}
    	return false;
    }

	/**
	* Встроенная функция вставки данных для модели
	*
	*  @param array - массив параметров запроса 'set'
	*
	*/
    public function MY_insert($set){
    	$res = false;
    	if($set !== false){
        	if(!is_array($set)) return false;
            if($this->MY_multiarr_numeric($set)){
				if($this->db->insert_batch($this->MY_table, $set)){
					$res = true;
				}
			}else{
				$this->MY_generate_set($set);
				if($this->db->insert($this->MY_table)){
					//return $this->db->insert_id();
					$res = true;
				}
			}
        }

        if($res == true) return $this->db->insert_id();
        return false;
    }

	/**
	*  Генерирует запрос типа 'set'
	*
	*/
    protected function MY_generate_set($set, $encode = true){
    	foreach($set as $key=>$items){
    		if($key == 'encode'){
        		$this->MY_generate_set($items, false);
        	}elseif(is_array($items)){
        		$this->db->set($items);
        	}else{
        		$this->db->set($key, $items, $encode);
        	}
        }
    }

    /**
    * Проверяет является ли массив многомерным
    * и являются ли ключи массива первого уровня числовыми
    * @param array - массив
    * @return boolean
    */
    protected function MY_multiarr_numeric($array){
         if(!is_array($array)) return false;
         foreach($array as $key=>$value){
         	if( ! is_numeric($key)) return false;
         	if( ! is_array($value))	return false;
         }
         return true;
    }

    /**
	* Встроенная функция обновления данных для модели
	*
	*  @param array - массив параметров запроса 'set'
	*
	*/
    public function MY_update($set, $where = false){
    	if($set !== false){
        	if(!is_array($set)) return false;
			$this->MY_generate_set($set);
        }
        if($where == false) return false;
        $this->MY_generate_where($where);
        $res = false;
        if($this->db->update($this->MY_table)){
        	$res = true;
        }
        // очищаем временные псевдонимы
        $this->MY_temp_from_is = array();
        if($res == true) return true;
        return false;
    }

    /**
	* Встроенная функция удаления данных для модели
	*
	*  @param array - массив параметров запроса 'set'
	*
	*/
    public function MY_delete($where = false){

        if($where == false) return false;
        $this->MY_generate_where($where);

		$res = false;
        if($this->db->delete($this->MY_table)){
        	$res = true;
        }
        // очищаем временные псевдонимы
        $this->MY_temp_from_is = array();
        if($res == true) return true;
        return false;
    }

    /**
    *	Возвращает связующие данные ко многим
    *   @param string - имя модели
    *	@param string - значение связующего параметра
    *   @param array - поля для извлечения из связаной таблицы (по умолчанию '*' - все)
    *					параметр нужен что бы разгрузить данные от избыточных,
    *					т.к. если указать '*' будут подгружены зависимые данные связаной таблицы и т.д. по цепочке, коих может оказаться очень много
    */
    public function MY_related_has_many($model, $value, $select = '*'){
    	$mcm = $this->MY_get_mcm_related_has_many($model);
    	$field = $this->MY_get_field_related_has_many($model);
    	$select = $this->MY_get_select_related_has_many($model, $select);
        //print_r($select);
    	//var_dump($field);
        //echo 'Модель: '.$model.'<br>';
        //print_r($select);
        //echo '<br />';
    	$where_key = ($this->MY_is_main_prefix_array($select)) ? $this->MY_name_table_main.'.'.$field : $field;
    	//echo 'Условие: '.$where_key.'<br>';
    	if(!empty($mcm) && !empty($field)){
       		$res = Modules::run($mcm,
       			//select
       			$select,
       		     //where
       		     //array($field => $value)
       		     array($where_key => $value)
       		);
       		if(isset($res)) return $res;
    		return null;
    	}
    	return false;
    }

	/**
	*	Возвращает корректныt поля селект
	*	для определенной модели has_many
	*   @param string - $model имя модели
	*   @param array - $select массив с именами полей (по умолчанию '*')
	*/
	protected function MY_get_select_related_has_many($model, $select = '*'){
		if($select == '*') return $select;
		if(!is_array($select)) $select = array($select);
		foreach($this->MY_has_many as $key=>$value){
			//if(is_array($select)){
			//$foreign_key = array($model.'.'.$value['foreign_key'], $value['foreign_key']);
            if($key == $model){
				if(!in_array($value['foreign_key'], $select) || !in_array($this->MY_name_table_main.'.'.$value['foreign_key'], $select)){
                    if($this->MY_is_main_prefix_array($select)){
                    	$select[] = $this->MY_name_table_main.'.'.$value['foreign_key'];
                    }else{
						$select[] = $value['foreign_key'];
					}
				}
			}
			//}
		}
		return $select;
	}
    /**
    *	Возвращает данные принадлежащие к другим таблицам (belongs to)
    *   @param string - имя модели
    *	@param string - значение связующего параметра
    *
    */
    public function MY_related_belongs_to($model, $value, $select = '*'){
    	$mcm = $this->MY_get_mcm_related_belongs_to($model);
    	$field = $this->MY_get_field_related_belongs_to($model);

    	//var_dump($field);

    	$where_key = ($this->MY_is_main_prefix_array($select)) ? $this->MY_name_table_main.'.'.$field : $field;
    	if(!empty($mcm) && !empty($field)){
       		$res = Modules::run($mcm, $select,
       		     //where
       		     //array($field => $value)
       		     array($where_key => $value)
       		);
       		if(isset($res)) return $res;
    		return null;
    	}
    	return false;
    }
    /**
    * Возвращает строку модул/контроллер/метод
    * на основании прописаной связи один ко многим (has_many)
    *	@param string - имя модели связи
    *	@return string - module/controller/method
    */
    private function MY_get_mcm_related_has_many($model){
    	if(is_array($this->MY_has_many)){
    		if(isset($this->MY_has_many[$model]) && is_array($this->MY_has_many[$model])){
    			$module = (!empty($this->MY_has_many[$model]['module'])) ? $this->MY_has_many[$model]['module'].'/' : '';
    			$controller = (!empty($this->MY_has_many[$model]['controller'])) ? $this->MY_has_many[$model]['controller'].'/' : false;
    			if($controller === false) return false;
    			//берем результат в виде объекта (MY_data)
    			$method = 'MY_data';

    			$mcm = $module.$controller.$method;
    		}
    	}
    	if(isset($mcm)) return $mcm;

    	return false;
    }
    /**
    * Возвращает строку модул/контроллер/метод
    * на основании прописаной связи многие к одному (belongs_to)
    *	@param string - имя модели связи
    *	@return string - module/controller/method
    */
    private function MY_get_mcm_related_belongs_to($model){
    	if(is_array($this->MY_belongs_to)){
    		if(isset($this->MY_belongs_to[$model]) && is_array($this->MY_belongs_to[$model])){
    			$module = (!empty($this->MY_belongs_to[$model]['module'])) ? $this->MY_belongs_to[$model]['module'].'/' : '';
    			$controller = (!empty($this->MY_belongs_to[$model]['controller'])) ? $this->MY_belongs_to[$model]['controller'].'/' : false;
    			if($controller === false) return false;
    			//берем результат в виде объекта (MY_data)
    			$method = 'MY_data';

    			$mcm = $module.$controller.$method;
    		}
    	}
    	if(isset($mcm)) return $mcm;

    	return false;
    }

	/**
	*	Возвращает поле связи с один ко многим (has_many)
	*
	*/
    private function MY_get_field_related_has_many($model){
    	if(isset($this->MY_has_many[$model]['foreign_key'])){
    		return $this->MY_has_many[$model]['foreign_key'];
    	}
    	return false;
    }
    /**
	*	Возвращает поле связи многие к одному (belongs_to)
	*
	*/
    private function MY_get_field_related_belongs_to($model){
    	if(isset($this->MY_belongs_to[$model]['far_key'])){
    		return $this->MY_belongs_to[$model]['far_key'];
    	}
    	return 'id';
    }
}