<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Класс для конвертации подготовленного массива с PHP данными в строку
 *
 * 1. Сохранение комментариев, html вне кода PHP
 * 2. Численные одномерные массивы с последовательной нумерацией представляются как массив в строке array(a,b,c,d...n)
 * 3. Каждый параметр массива представлен одной строкой
 * 4. При добавление параметра он дописывается в конец файла
 *
 *
 *
 */

class phparray_build_string {

    /**
     *  Распарсенный массив с данными
     * @var type
     */
    private $data_array;

    /**
     *  Класс парсинга массива php
     * @var object
     */
    private $parse;

    /**
     *  Текущий шаблон вывода
     * @var string
     */
    private $current_template = 'optimum';

    /**
     *  параметры шаблонов
     * @var array
     */
    private $templates = array(
                              'standard' => array(
                                                'unique_param' => 'string',
                                                'num_array' => 'line',
                                                'last_param' => 'array',
                                               ),
                              'array'=> array(
                                                'unique_param' => 'array',
                                                'num_array' => 'array',
                                                'last_param' => 'array',
                                               ),
                              'string'=> array(
                                                'unique_param' => 'string',
                                                'num_array' => 'string',
                                                'last_param' => 'string',
                                               ),
                              'optimum' => array(
                                                'unique_param' => 'array',
                                                'num_array' => 'line',
                                                'last_param' => 'array',
                                               ),
                             );

    function __construct($var) {
        if(is_object($var)){
            $this->parse = $var;
        }else{
            exit("Не передан объект парсера массива php в класс формирования строки");
        }
    }

    function set_template($string){
        if(isset($this->templates[$string])){
            $this->current_template = $string;
        }
    }

    /**
     *  Установка массива с данными
     * @param array $array
     */
    function set_array($array){
        $this->data_array = $array;

        //echo "<pre>";
        //print_r($this->data_array);
        //echo "</pre>";
    }

    /**
     * Генерация всего масива в строку
     */
    function generate_to_string($array){
        if( ! is_array($array)) return false;
        $this->set_array($array);
        $this->set_log('Запущен процесс генерации массива в строку PHP');
        $string = '';

        if(is_array($this->data_array)){
           $flag_php = false;
           $p = 0;
           foreach($this->data_array as $key=>$items){
               if(isset($items['php']) && is_array($items['php'])){

                   if($flag_php === false){
                   	 $string .= "<?php";
                   	 $flag_php = true;
                   }
                   foreach($items['php'] as $key_php=>$val){
                       $p ++;
                       if(!empty($val['vars_new'])){
                           if($p == 1){
                               if($start_string = $this->parse->driver->driver->get_start_string()){
                                   $string .= " ".$start_string;
                               }
                           }
                           $string .= "\r\n".$this->generate_vars_to_string($val['vars_new'])."\r\n";
                           //$string .= "\r\n".$this->generate_vars_to_string_last_array($val['vars_new'])."\r\n";

                       }elseif(!empty($val['php'])){
                       	   $string .= $val['php'];
                       }
                       if(isset($val['comment'])){
                           $string .= $this->generate_comment_to_string($val['comment']);
                       }
                   }

               }
               if(!empty($items['html'])){
                   if ($flag_php === true){
                   	 	$string .= "?>";
                   	 	$flag_php = false;
                   }
                   $string .= $this->generate_html_to_string($items['html']);

               }
           }
       }

       return $string;
    }

    /**
     *  Преобразование данных массива в строку
     * @param array $array
     * @return string
     */
    function generate_vars_to_string($array){
        $printer = '';
        if(is_array($array)){
            foreach($array as $key=>$value){
                $prefix = '$'.$key;
                if(is_array($value)){
                    if($this->parse->is_sequence_array_numeric($value) === true && $this->template_is_num_array() === true){
                        $printer  .= $prefix." = ".$this->string_vars_to_num_array($value);
                    }elseif($this->is_unique_array(array($key)) === true && $this->template_is_unique_array() === true){
                        $printer .= $prefix.' = '.$this->string_vars_to_array($value);
                    }else{
                        $printer .= $this->_get_vars_template($value, $prefix, array($key));
                    }
                }else{
                    $printer .= $prefix.' = '.var_export($value, true).";\r\n";
                }
            }
        }
        return $printer;
    }

    /**
    *  Генерация переменных в строку в зависимости от шаблона вывода
    *
    */
    function _get_vars_template($vars, $prefix = '', $array_keys = array()){
        $printer = '';
        foreach($vars as $key=>$value){
            if(is_array($value)){
                if($this->parse->is_sequence_array_numeric($value) === true && $this->template_is_num_array() === true){
                    $printer  .= $prefix.$this->key_prefix($key)." = ".$this->string_vars_to_num_array($value);
                }elseif($this->is_unique_array(array_pad($array_keys, (count($array_keys)+1), $key)) === true && $this->template_is_unique_array() === true){
                    $printer .= $prefix.$this->key_prefix($key).' = '.$this->string_vars_to_array($value);
                }else{
                    $printer .= $this->_get_vars_template($value, $prefix.$this->key_prefix($key), array_pad($array_keys, (count($array_keys)+1), $key));
                }
            }else{
                $printer .= $prefix.$this->key_prefix($key).' = '.$this->string_vars_to_string($value);
            }
        }
        return $printer;
    }

	/*
	*  Возвращает true если шаблон для последовательного числительного массива
	*/
	function template_is_num_array(){
            if(!empty($this->current_template)){
			if(isset($this->templates[$this->current_template])){
				if($this->templates[$this->current_template]['num_array'] == 'line'){
                                    return true;
				}
			}
		}
		return false;
	}

	function template_is_unique_array(){
		if(!empty($this->current_template)){
			if(isset($this->templates[$this->current_template])){
				if($this->templates[$this->current_template]['unique_param'] == 'array'){
					return true;
				}
			}
		}
		return false;
	}

    /**
     *  Преобразует переменные в строку в виде массива
     * @param type $vars
     */
    function string_vars_to_array2($vars){
        return var_export($vars, true).";\r\n";
    }

    /**
    *  Преобразует переменные в строку в виде массива
    *
    */
    function string_vars_to_array($vars, $level = 1){
        $prefix = str_repeat('       ', $level);
        if($level === 1){
            $printer = "array(\r\n";
        }else{
            $printer = "";
        }
        foreach($vars as $key=>$value){
            $level++;
            if(is_array($value)){

                if($this->parse->is_sequence_array_numeric($value) === true && $this->template_is_num_array() === true){
                    $printer  .= $prefix.$this->key_prefix_array($key).$this->string_vars_to_num_array_for_array($value);
                }else{
                    $printer .= $prefix.$this->key_prefix_array($key)." array(\r\n";
                    //$printer .= $this->string_vars_to_array($value, $prefix.$this->key_prefix_array($key));

                    $printer .= $this->string_vars_to_array($value, $level);

                    $printer .= $prefix."),\r\n";
                }

            }else{
                $printer .= $prefix.$this->key_prefix_array($key).$this->string_vars_to_string_for_array($value);
            }
            $level--;
        }
        if($level === 1){
            $printer .= "\r\n);\r\n";
        }else{
            $printer .= "";
        }
        return $printer;
    }


    /**
     *  Преобразует переменные в строку в виде массива
     * @param type $vars
     */
    function string_vars_to_string($vars){
        return var_export($vars, true).";\r\n";
    }

    /**
     *  Преобразует переменные в строку в виде значения для массива
     * @param type $vars
     */
    function string_vars_to_string_for_array($vars){
        return var_export($vars, true).",\r\n";
    }

    /**
     *  Преобразует переменные в строку в виде массива
     * @param type $vars
     */
    function string_vars_to_num_array($vars){
       return "array(".$this->get_implode_array($vars).");\r\n";
    }

    /**
     *  Преобразует переменные в строку в виде значения для массива
     * @param type $vars
     */
    function string_vars_to_num_array_for_array($vars){
       return "array(".$this->get_implode_array($vars)."),\r\n";
    }

    /**
     *  Преобразование данных массива в строку
     * @param array $array
     * @return string
     */
    function generate_vars_to_string_last_array($array, $array_keys = array(), $level = 0, $prefix = ''){
        $printer = '';
        if(is_array($array)){
            foreach($array as $key=>$value){
                if($level === 0){
                    $prefix = '$'.$key;

                }
                if(is_array($value)){
                    if($this->parse->is_sequence_array_numeric($value)){
                        $printer  .= $prefix.$this->key_prefix($key, $level)." = array(".$this->get_implode_array($value).");\r\n";
                    }elseif($this->is_unique_array(array_pad($array_keys, (count($array_keys)+1), $key))){
                        $printer .= $prefix.$this->key_prefix($key, $level).' = '.var_export($value, true).";\r\n";
                    }else{
                        $printer .= $this->generate_vars_to_string_last_array($value, array_pad($array_keys, (count($array_keys)+1), $key), 1, $prefix.$this->key_prefix($key, $level));
                    }
                }else{
                    $printer .= $prefix.$this->key_prefix($key, $level).' = '.var_export($value, true).";\r\n";
                }
            }
        }
        return $printer;
    }

    /**
     *  Проверяет является ли массив уникальным во всей структере файла
     * @param array $keys
     * @return boolean
     */
    function is_unique_array($keys){
        if(is_array($keys)){
            foreach($this->data_array as $key=>$items){
               if(isset($items['php']) && is_array($items['php'])){
                   foreach($items['php'] as $key_php=>$val){
                       if(isset($val['vars_new']) && is_array($val['vars_new'])){
                           if($this->is_key_in_array($val['vars_new'], $keys)){
                               $vars[] = true;
                           }
                       }

                   }
               }
            }
        }

        if(isset($vars) && count($vars) <= 1){
            return true;
        }
        return false;;
    }

    /**
     *  Проверяет присутствует ли ключ (цепочка ключей) в массиве
     * @param type $array
     * @param type $array_keys
     * @return type
     */
    function is_key_in_array($array, $array_keys = array()){
        if(is_array($array_keys) && count($array_keys) > 0){
            $current_key = array_shift($array_keys);
            if(isset($array[$current_key])){
                return $this->is_key_in_array($array[$current_key], $array_keys);
            }
        }else{
            if(isset($array)){
                return true;
            }
        }
        return false;
    }


    /**
     *  Возвращает префикс ключа в зависимости от типа ключа
     * @param string $key
     * @param numeric $level
     * @return string
     */
    function key_prefix($key, $level = 1){
        if($level == 1){
            if(is_numeric($key)){
                $prefix = '['.$key.']';
            }else{
                $prefix = '[\''.$key.'\']';
            }
        }else{
            $prefix = '';
        }
        return $prefix;

    }

    /**
     *  Возвращает префикс ключа для вывода в массив в зависимости от типа ключа
     * @param string $key
     * @param numeric $level
     * @return string
     */
    function key_prefix_array($key, $level = 1){
        if($level == 1){
            if(is_numeric($key)){
                $prefix = "$key => ";
            }else{
                $prefix = "'$key' => ";
            }
        }else{
            $prefix = '';
        }
        return $prefix;
    }
    /**
     *  Возвращает строку преобразованную из массива с учетом типа переменных
     * @param array $array
     *
     * @return string
     */
    function get_implode_array($array){
        $string = '';
        foreach($array as $key=>$value){
            $type = gettype($value);
            switch ($type) {
                case 'boolean':
                    ($value === true) ? $string .= 'true' : $string .= 'false';
                    break;
                case 'integer':
                    $string .= $value;
                    break;
                case 'double':
                    $string .= $value;
                    break;
                case 'string':
                    $string .= '\''.$value.'\'';
                    break;
                default:
                    $string .= '\''.$value.'\'';
                    break;
            }

            if(next($array)) $string .= ',';
        }
        return $string;
    }

    /**
     *  Преобразование данных комментариев в строку
     * @param string $comment
     * @return string
     */
    function generate_comment_to_string($comment, $type = 'T_COMMENT'){
        return $comment;
    }

    /**
     *  Преобразование данных массива html в строку
     * @param array $array
     * @return string
     */
    function generate_html_to_string($array){
        $printer = '';
            foreach($array as $key=>$value){
               $printer .= $value;
            }
        return $printer;
    }

    /**
     *  запись логов
     * @param string $string
     */
    function set_log($string){
        $this->parse->driver->driver->set_log($string);
    }

    /**
    *  Метод обработки ошибок
    *  @param string $class - класс
    *  @param string $method - метод
    *  @param string $level  - уровень ошибки
    *  @param string $message - сообщение
    */
    function set_error($class, $method, $level, $message){
         $this->parse->driver->driver->set_error($class, $method, $level, $message);
    }
}
