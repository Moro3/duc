<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Help_request_order extends CI_Driver
{
    //допустимые значения для направления сортировки
    private $direct = array('asc','desc','random');
    /**
    *  Возвращает корректное поле сортировки
    *	@param string - имя поля сортировки (!!! его псевдоним если таковой указан во втором параметре)
    *	@param array - массив допустимых полей сортировки
    *					array('alias' => 'field') - alias: псевдодим имени (может быть опущет, тогда имя будет исходить из значения 'field')
    *												field: имя сортировки в запросе
    *   @param string - имя сортировки по умолчанию. Если указано неверное значение current или оно не допустимо в allow/
    *					!!! если во втором параметре (allow) указан псевдоним, то здесь указывается также по псевдониму
    *					Если не указан параметр, берется первый параметр из массива allow
    *
    *	@return string - корректное поле для сортировки
    */	public function by($current, $allow, $default = false){
		if(!is_array($allow) || count($allow) == 0) return false;
		if(is_numeric($current)) return $this->get_first_value_array($allow);
		if(isset($allow[$current]) && $current !== false){
			return $allow[$current];
		}elseif(in_array($current, $allow)){
            $key = array_search($current, $allow);
        	if(is_numeric($key)){        		return $current;
        	}else{        		if($default === false){
					return $this->get_first_value_array($allow);
				}else{
					return $this->by($default, $allow);
				}
        	}
		}else{
			if($default === false){				return $this->get_first_value_array($allow);
			}else{
				return $this->by($default, $allow);
			}
		}
	}

	/**
    *  Возвращает корректное имя направления сортировки
    *	@param string - имя поля сортировки (!!! его псевдоним если таковой указан во втором параметре)
    *	@param array - массив допустимых параметров направления сортировки
    *					допустимые реальные параметры 'asc', 'desc', 'random'
    *					если необходимо указать псевдонимы для них, задайте этизначения с ключами,
    *							 где ключ будет являтся псевдонимом,
    *							 а значение одно из допустимых параметров    *
    *   @param string - имя направления сортировки по умолчанию. Если указано неверное значение current или оно не допустимо в allow/
    *					может быть именем как alias, так и реальным field.
    *					Если не указан параметр, берется первый параметр из массива allow
    *
    *	@return string - корректное поле для сортировки
    */
	public function direct($current, $allow = false, $default = false){
		if($allow !== false && is_array($allow)){			foreach($this->direct as $key=>$items){				if(in_array($items, $allow)){					$keys = array_search($items, $allow);
					$new_allow[$keys] = $items;
				}else{					$new_allow[$key] = $items;
				}
			}
		}else{			$new_allow = $this->direct;
		}

		if(is_numeric($current)) return $this->get_first_value_array($new_allow);
		if(isset($new_allow[$current])  && $current !== false){
			return $new_allow[$current];
		}elseif(in_array($current, $new_allow)){
            $key = array_search($current, $new_allow);
        	if(is_numeric($key)){
        		return $current;
        	}else{
        		if($default === false){
					return $this->get_first_value_array($new_allow);
				}else{
					return $this->direct($default, $new_allow);
				}
        	}
		}else{
			if($default === false){
				return $this->get_first_value_array($new_allow);
			}else{
				return $this->direct($default, $new_allow);
			}
		}
	}

	/**
	* 	Возвращает значение первого элемента массива или его ключ если он не числовой
	*   @param array - массив допустимых параметров
	*	@return string - строка первого элемента массива или его ключ если он не числовой
	*/
	private function get_first_array($array){		if(is_array($array)){
			reset($array);
            $first = each($array);
			if(isset($first[0])){				if(is_numeric($first[0])){					return $first[1];
				}else{					return $first[0];
				}
			}
		}
		return false;
	}
	/**
	* 	Возвращает значение первого элемента массива
	*   @param array - массив допустимых параметров
	*	@return string - строка первого элемента массива
	*/
	private function get_first_value_array($array){		if(is_array($array)){
			reset($array);
            $first = each($array);
			if(isset($first[1])){
				return $first[1];
			}
		}
		return false;
	}

	/**
	* 	Возвращает ключ первого элемента массива
	*   @param array - массив допустимых параметров
	*	@return string - строка первого элемента массива
	*/
	private function get_first_key_array($array){
		if(is_array($array)){
			reset($array);
            $first = each($array);
			if(isset($first[0])){
				return $first[0];
			}
		}
		return false;
	}

}
