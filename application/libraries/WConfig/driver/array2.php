<?php

class Array2_php{

    /**
     *  Массив с распарсенными значениями
     *
     * @var array
     */
    private $data = array();

    /**
     *  имя файла для редактирования
     *
     * @var string
     */
    private $filename;

	    /**
     *  Расширение файла
     *
     * @var string
     */
    private $ext_file = 'php';

    /**
     * Начало строки в файле
     *
     * @var string
     */
    private $start_string;

    /**
     *  Конец строки в файле
     *
     * @var string
     */
    private $end_string;

    /**
     *  Разделитель ключей
     *
     *  @var string
     */
    private $separator_keys = '->';

    /**
     *  Параменты при обновлении, удалении, вставке
     * @var array
     */
    private $set = array();

    /**
     *  Зарегистрированные изменения во время обновления, удаления, вставки
     * @var array
     */
    private $change = array();

    /**
    *  Массив с ошибками
    */
    private $error = array();

    /**
     *  Результаты при обновлении, удалении, вставке
     * @var array
     */
    private $status = array();

    /**
    *  Использовать автоматическое определение формата данных
    */
    private $use_auto_type = true;

    /**
    *  Формат записи файла
    */
    private $type;

    /**
    *  Автоматически определенный формат записи файла
    */
    private $auto_type;

    /**
     *  Доступные типы данных
     * @var array
     */
    private $access_type = array('array','serialize','json');

    /**
     *  Запрещенные имена к названиям переменных
     *
     * @var array
     */
    private $exception_key = array('null', 'yes', 'no', 'true', 'false', 'on', 'off', 'none');

    function __construct($setting = array()) {

    }

    /**
     *  Установка формата данных
     *
     * @param string $type
     */
    function set_type($type){
        switch ($type){
            case 'array':
                $this->type = 'array';
              break;
            case 'serialize':
                $this->type = 'serialize';
              break;
            case 'json':
                $this->type = 'json';
              break;
            default:
                //$this->type = 'array';
              break;
        }
    }

    /**
     * Автоматическое определение типа данных
     *
     */
    function auto_type(){
        $t = '';
        if(is_readable($this->filename)){
            if($this->_is_type_array()){
                $t[] = 'array';
            }
            if($this->_is_type_serialize()){
                $t[] = 'serialize';
            }
            if($this->_is_type_json()){
                $t[] = 'json';
            }
        }

        if(is_array($t) && count($t) > 0){
            if(count($t) > 1){
                $this->auto_type = 'mixed';
                $this->set_error(__CLASS__, __METHOD__, 'fatal', 'Обнаружен смешанный тип данных в файле');
            }else{
                $this->auto_type = current($t);
            }
        }else{
            $this->auto_type = false;
            $this->set_error(__CLASS__, __METHOD__, 'info', 'Не удалось автоматически вычислить тип данных');
        }
    }
    /**
     * Являеются ли данные типом array
     */
    function _is_type_array(){
        if($this->_type_array()) return true;
        return false;
    }
    /**
     * Являеются ли данные типом serialize
     */
    function _is_type_serialize(){
        if($this->_type_serialize()) return true;
        return false;
    }
    /**
     * Являеются ли данные типом json
     */
    function _is_type_json(){
        if($this->_type_json()) return true;
        return false;
    }
    /**
     * Возвращает данные в формате array записанные в формате array
     */
    function _type_array(){
        include($this->filename);
        $arr = get_defined_vars();
        if(is_array($arr)) return $arr;
        return false;
    }
    /**
     * Возвращает данные в формате array записанные в формате serialize
     */
    function _type_serialize(){
        $str = file_get_contents($this->filename);
        $arr = @unserialize($str);
        if(is_array($arr)) return $arr;
        return false;
    }
    /**
     * Возвращает данные в формате array записанные в формате json
     */
    function _type_json(){
        $str = file_get_contents($this->filename);
        $arr = json_decode($str, true);
        if(is_array($arr)) return $arr;
        return false;
    }
    /**
     *  Установка параметра стартовой строки для файла
     * @param string $string
     *
     */
    function set_start_string($string){
        if(!empty($string)){
            $this->start_string = $string;
        }
    }
    /**
     *  Установка параметра когнечной строки для файла
     * @param string $string
     *
     */
    function set_end_string($string){
        if(!empty($string)){
            $this->end_string = $string;
        }
    }

    /**
    *  Установка имени файла
    *  @param string $file
    */
    function set_filename($file){
        $this->filename = $file;
    }

    /**
    *  Чтениние файла, проверка на предмет присутствия и установка параметров
    *
    *   @param string $filename - имя файла
    *   @param boolean $isfile - обязательное присутствие файла
    */
    function read($filename, $isfile = true){
        $this->set_filename($filename);
        if(is_readable($filename)){
            // авто определение типа данных
            if($this->use_auto_type == true) $this->auto_type();
            if(isset($this->type)){
                if($this->auto_type){
                    if($this->type != $this->auto_type){
                        $this->set_error(__CLASS__, __METHOD__, 'debug', 'Тип данных установленный ('.$this->type.') не соответствует автоматически найденному типу ('.$this->auto_type.')');
                    }
                }
            }else{
                if(isset($this->auto_type)){
                    $this->set_type ($this->auto_type);
                }else{
                    $this->set_type ('array');
                }
            }
            $this->read_vars();
        }else{
            if($isfile) $this->set_error(__CLASS__, __METHOD__, 'info', 'Не найден конфигурационный файл '.$filename);
        }
    }

    /**
     *  Чтение переменных в файле
     *
     * @param string $filename
     */
    function read_vars(){
        if(method_exists($this, '_type_'.$this->type)){
            $arr = $this->{'_type_'.$this->type}();
        }else{
            //$arr = $this->_type_array();
            $this->set_error(__CLASS__, __METHOD__, 'debug', 'Не найден метод чтения данных в формате '.$this->type);
        }
        if(is_array($arr)){
            $this->data = $arr;
        }
    }

    /**
     *  Получение данных
     */
    function get($filename, $key = false){
        $this->read($filename);
        if($key){
           return $this->_get_data($key);
        }
        return $this->data;
    }

    function _get_data($key){
        $keys = explode($this->separator_keys, $key);
        $count_keys = count($keys);
        switch ($count_keys) {
            case 1:
                if(isset($this->data[$keys[0]])){
                    return $this->data[$keys[0]];
                }
              break;
            case 2:
                if(isset($this->data[$keys[0]][$keys[1]])){
                    return $this->data[$keys[0]][$keys[1]];
                }
              break;
            case 3:
                if(isset($this->data[$keys[0]][$keys[1]][$keys[2]])){
                    return $this->data[$keys[0]][$keys[1]][$keys[2]];
                }
              break;
            case 4:
                if(isset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]])){
                    return $this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]];
                }
              break;
            case 5:
                if(isset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]])){
                    return $this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]];
                }
              break;
            case 6:
                if(isset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]][$keys[5]])){
                    return $this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]][$keys[5]];
                }
              break;
        }
        //return NULL;
    }

    /**
     *  Установка значения параметра
     * @param string $key - индексный путь (ограничено 6 вложениями)
     * @param mixer $value - значение параметра
     * @param boolean $replace - заменять полностью или добавлять к имеющемуся (применяется если значение является массивом)
     *
     */
    function _set_data($key, $value, $replace = false){
        $keys = explode($this->separator_keys, $key);
        $count_keys = count($keys);
        switch ($count_keys) {
            case 1:
                if($replace == true){
                    $this->data[$keys[0]] = $value;
                }else{
                    if(isset($this->data[$keys[0]])){
                    	$this->data[$keys[0]] = $this->array_merge_recursive_distinct($this->data[$keys[0]], $value);
                    }else{
                    	$this->data[$keys[0]] = $value;
                    }
                }
              break;
            case 2:
                if($replace == true){
                    $this->data[$keys[0]][$keys[1]] = $value;
                }else{
                    if(isset($this->data[$keys[0]][$keys[1]])){
                    	$this->data[$keys[0]][$keys[1]] = $this->array_merge_recursive_distinct($this->data[$keys[0]][$keys[1]], $value);
                    }else{
                    	$this->data[$keys[0]][$keys[1]] = $value;
                    }
                }
              break;
            case 3:
                if($replace == true){
                    $this->data[$keys[0]][$keys[1]][$keys[2]] = $value;
                }else{
                    if(isset($this->data[$keys[0]][$keys[1]][$keys[2]])){
                    	$this->data[$keys[0]][$keys[1]][$keys[2]] = $this->array_merge_recursive_distinct($this->data[$keys[0]][$keys[1]][$keys[2]], $value);
                    }else{
                    	$this->data[$keys[0]][$keys[1]][$keys[2]] = $value;
                    }
                }
              break;
            case 4:
                if($replace == true){
                    $this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]] = $value;
                }else{
                    if(isset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]])){
                    	$this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]] = $this->array_merge_recursive_distinct($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]], $value);
                    }else{
                    	$this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]] = $value;
                    }
                }
              break;
            case 5:
                if($replace == true){
                    $this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]] = $value;
                }else{
                    if(isset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]])){
                    	$this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]] = $this->array_merge_recursive_distinct($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]], $value);
                    }else{
                    	$this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]] = $value;
                    }
                }
              break;
            case 6:
                if($replace == true){
                    $this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]][$keys[5]] = $value;
                }else{
                    if(isset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]][$keys[5]])){
                    	$this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]][$keys[5]] = $this->array_merge_recursive_distinct($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]][$keys[5]], $value);
                    }else{
                    	$this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]][$keys[5]] = $value;
                    }
                }
              break;
        }

    }

    function _del_data($key){
        $keys = explode($this->separator_keys, $key);
        $count_keys = count($keys);
        switch ($count_keys) {
            case 1:
                if(isset($this->data[$keys[0]])){
                    unset($this->data[$keys[0]]);
                }
              break;
            case 2:
                if(isset($this->data[$keys[0]][$keys[1]])){
                    unset($this->data[$keys[0]][$keys[1]]);
                }
              break;
            case 3:
                if(isset($this->data[$keys[0]][$keys[1]][$keys[2]])){
                    unset($this->data[$keys[0]][$keys[1]][$keys[2]]);
                }
              break;
            case 4:
                if(isset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]])){
                    unset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]]);
                }
              break;
            case 5:
                if(isset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]])){
                    unset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]]);
                }
              break;
            case 6:
                if(isset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]][$keys[5]])){
                    unset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]][$keys[5]]);
                }
              break;
        }
        //return false;
    }

    /**
     *  Запись переменных в файл
     *	@param string $filename
     *  @param array - массив со значениями переменных
     *					array(key => value)
     *
     */
    function update($filename, $variable = array()){
    	$this->read($filename);
    	if( is_array($variable) && count($variable) > 0){
    		$this->set($variable);
    	}
    	$this->run_set();
    	if(count($this->error) == 0){
            if($this->get_change_action('update')){
                if($this->save(true)){
                    $this->set_status('ok', 'Информация сохранена');
                }else{
                    $this->set_status('error', 'Не удалось сохранить информацию при записи');
                }
            }else{
                $this->set_status('not', 'Изменений не обнаружено');
            }
    	}else{
            $this->set_status('error', 'Не удалось сохранить информацию, ошибки при обработке');
    	}
    }

    /**
     *  Запись новых параметров в файл
     *	@param string $filename
     *  @param array - массив со значениями переменных
     *					array(key => value)
     *
     */
    function insert($filename, $variable = array()){
        $this->read($filename, false);
    	if( is_array($variable) && count($variable) > 0){
    		$this->set($variable);
    	}
        $this->run_set('insert');
    	if(count($this->error) == 0){
            if($this->get_change_action('insert')){
                if($this->save(true)){
                    $this->set_status('ok', 'Информация сохранена');
                }else{
                    $this->set_status('error', 'Не удалось сохранить информацию при записи');
                }
            }else{
                $this->set_status('not', 'Изменений не обнаружено');
            }
    	}else{
            $this->set_status('error', 'Не удалось сохранить информацию, ошибки при обработке');
    	}
    }

    /**
     *  Удаление элементов в массиве
     *	@param string $filename
     *  @param array - массив со значениями переменных
     *					array(key => value)
     *
     */
    function delete($filename, $variable = array()){
    	$this->read($filename);
    	if( is_array($variable) && count($variable) > 0){
    		$this->set($variable);
    	}

    	$this->run_set('delete');
    	if(count($this->error) == 0){
            if($this->get_change_action('delete')){
                if($this->save()){
                    $this->set_status('ok', 'Информация сохранена');
                }else{
                    $this->set_status('error', 'Не удалось сохранить информацию при записи');
                }
            }else{
                $this->set_status('not', 'Изменений не обнаружено');
            }
    	}else{
            $this->set_status('error', 'Не удалось сохранить информацию, ошибки при обработке');
    	}
    }

    /**
     *  Обработка новых данных
     */
    function run_set($action = 'update'){
        if(count($this->set) > 0){
            foreach($this->set as $key=>$value){
                $this->new_data($value['key'], $value['value'], $action);
            }
        }
    }

    /**
     *  Функция принимает несколько массивов и объеденяет их по принципу array_merge_recursive,
     *  но с заменой старых значений
     * @return array
     */
    function array_merge_recursive_distinct(){
        $arrays = func_get_args();
        $base = array_shift($arrays);

        if (!is_array($base)) $base = empty($base) ? array() : array($base);

        foreach ($arrays as $append) {
            if (!is_array($append)) $append = array($append);
            foreach ($append as $key => $value) {
                if (!array_key_exists($key, $base) and!is_numeric($key)) {
                    $base[$key] = $append[$key];
                    continue;
                }
                if (is_array($value) or is_array($base[$key])) {
                    $base[$key] = $this->array_merge_recursive_distinct($base[$key], $append[$key]);
                } else if (is_numeric($key)) {
                    if (!in_array($value, $base)) $base[] = $value;
                    //$base[$key] = $value;
                } else {
                    $base[$key] = $value;
                }
            }
        }
        return $base;
    }

    /**
     *  Проверка и запись новых данных
     */
    function new_data($key, $value, $action = 'update'){
        $keys = explode($this->separator_keys, $key);
        $count_keys = count($keys);


        if($source_value = $this->_get_data($key)){
            if($source_value !== $value){
                if(is_array($source_value)){
                    if($action == 'delete') $this->set_error(__CLASS__, __METHOD__, 'debug', 'Нельзя удалить тип (array) элемента массива при действии  \'delete\'');
                    if(is_array($value)){
                            if($this->array_numeric($source_value)){
                                    if($action == 'update'){
                                            $this->_set_data($key, $value);
                                            $this->set_change('update', $key, $value);
                                    }elseif($action == 'insert'){
                                            $this->_set_data($key, $value, true);
                                            $this->set_change('insert', $key, $value);
                                    }
                            }else{
                                    if($action == 'update'){
                                            if($this->isset_array_key($value, $source_value)){
                                                    $this->_set_data($key, $value, true);
                                                    $this->set_change('update', $key, $value);
                                            }else{
                                                    $this->set_error(__CLASS__, __METHOD__, 'debug', 'Ключи массива назначяения не соответствуют ключам массива источника при действии \'update\'');
                                            }
                                    }elseif($action == 'insert'){
                                            $this->_set_data($key, $value, true);
                                            $this->set_change('insert', $key, $value);
                                    }
                            }
                    }else{
                            $this->set_error(__CLASS__, __METHOD__, 'debug', 'Тип (string) значения назначения не соответствует типу (array) значения источника '.$key);
                    }
                }else{
                    if($source_value !== $value){
                            if($action == 'update')	{
                                $this->_set_data($key, $value);
                                $this->set_change('update', $key, $value);
                            }
                            if($action == 'delete'){
                                $this->_del_data($key);
                                $this->set_change('delete', $key, $value);
                            }
                    }else{
                    	if($action == 'delete'){
                            $this->_del_data($key);
                            $this->set_change('delete', $key, $value);
                     	}
                    }
                }
            }else{
                if($value === null){
                	$this->_del_data($key);
                	$this->set_change('delete', $key, $value);
                }else{
                	$this->set_change('not', $key, $value);
                }
            }
        }else{
                if($action == 'insert'){
                    $this->_set_data($key, $value);
                    $this->set_change('insert', $key, $value);
                }
                if($action == 'update') $this->set_error(__CLASS__, __METHOD__, 'debug', 'Не обнаружен элемент массива "'.$key.'" при действии \'update\'');
                if($action == 'delete') $this->set_error(__CLASS__, __METHOD__, 'debug', 'Не обнаружен элемент массива "'.$key.'" при действии \'delete\'');
        }

    }

    /**
     *  Установка статуса действия
     * @param string $status
     * @param string $message
     */
    function set_status($status, $message){
        $this->status[$status] = $message;
    }

    /**
     * Запись изменений параметров
     * @param type $action - действие
     * @param type $key - индексный путь массива
     * @param type $value - значение
     */
    function set_change($action, $key, $value){
        $this->change[$action][] = array('key' => $key, 'value' => $value);
    }

    /**
     *  Получение изменений при определенном действии
     * @param string $action
     * @param string $key
     */
    function get_change_action($action, $key = false){
        if(isset($this->change[$action])){
            if($key){
                foreach($this->change[$action] as $items){
                    if($items['key'] == $key) return $items['value'];
                }
            }else{
                return $this->change[$action];
            }
        }
        return false;
    }

    /**
     *  Получение значения параметра при изменении
     *
     * @param string $key
     */
    function get_change($key = false){
        if($key){
            foreach($this->change as $items){
                foreach($items as $item){
                   if($item['key'] == $key) return $item['value'];
                }
            }
        }else{
            return $this->change;
        }

        return false;
    }

    /**
    *  Проверяет являются ли все ключи массива числами
    *
    *
    */
	function array_numeric($array){
		if(is_array($array)){
			foreach($array as $key=>$value){
				if( ! is_numeric($key)) return false;
			}
			return true;
		}
		return false;
	}

	/**
	*  Проверяет есть ли ключи массива из первого массива во втором массиве
	*
	*/
	function isset_array_key($array1, $array2){
		if(is_array($array1) && is_array($array2)){
			foreach($array1 as $key=>$value){
				if( ! isset($array2[$key])) return false;
			}
			return true;
		}
		return false;
	}

    /**
    *  Установка параметров для изменения данных
    *  @param string $key - имя переменной массива или массив со ключом и значением если  меняются несколько параметров
    *						(если требуется указать ключ массива, то через синтаксис ->
    *						$config['database']['username']  - 'config->database->username')
    *  @param string $value - значение переменной
    *
    */
    function set($key, $value = ''){
    	if( ! is_array($key)){
    		$array = array($key => $value);
    	}else{
    		$array = $key;
    	}
    	foreach($array as $key_set=>$value_set){
    		$this->set[] = array('key' => $key_set,
    							 'value' =>	$value_set
    							);
    	}
    }

	/**
	*  Установка параметров со значением массива
	*  Новый массив параметра будет полностью переписывать параметр массива с данными
	*
	*  @param string $key
	*  @param string $value
	*/
	function set_array($key, $value = ''){
		if( ! is_array($key)){
    		$array = array($key => $value);
    	}else{
    		$array = $key;
    	}
    	foreach($array as $key_arr=>$value_arr){
    		if(is_array($value_arr)){
    			$this->set[] = array('key' => $key_arr,
    							 	'value' =>	$value_arr
    								);
    		}
    	}
	}
    /**
    *  Сохранение данных в файл
    *
    */
    function save($insert = false){
            if(is_array($this->data) && count($this->data) > 0){
                switch ($this->type) {
                    case 'array':
                        $printer = '<?php ';
                        if(isset($this->start_string)) $printer .= $this->start_string."\r\n";
                        foreach($this->data as $key=>$value){
                            $printer .= '$'.$key.' = '.var_export($value, true).";\r\n";
                        }
                        if(!empty($this->end_string)) $printer .= $this->end_string.";\r\n";
                        /*
                        $printer .= '?>';
                        */
                      break;
                    case 'serialize':
                        $printer = serialize($this->data);
                      break;
                    case 'json':
                        $printer = json_encode($this->data);
                      break;
                    default:
                        break;
                }
            }

        if($insert == true){
            if( ! is_file($this->filename)){
                if( ! is_writable(dirname($this->filename))){
                    $this->set_error(__CLASS__, __METHOD__, 'debug', 'Нельзя писать в папку '.dirname($this->filename).', доступ запрещен');
                    return false;
                }
            }
        }elseif( ! is_writable($this->filename)){
            $this->set_error(__CLASS__, __METHOD__, 'debug', 'Нельзя писать в файл '.$this->filename.', доступ запрещен');
            return false;
        }

        if(!empty($printer)){
            if(file_put_contents($this->filename, $printer)){
                return true;
            }else{
                $this->set_error(__CLASS__, __METHOD__, 'debug', 'Ошибка при записи в файл '.$this->filename);
            }
        }else{
            $this->set_error(__CLASS__, __METHOD__, 'debug', 'Нет данных для записи в файл '.$this->filename);
        }

    }

    function convert($filename, $type_before, $type_after){
        if( ! in_array($type_before, $this->access_type) ||  ! in_array($type_after, $this->access_type)){
            $this->set_error(__CLASS__, __METHOD__, 'debug', 'Указанные форматы недоступны для конвертации');
        }
        if($type_before == $type_after){
            $this->set_error(__CLASS__, __METHOD__, 'debug', 'Указанные форматы неподходят для конвертации');
        }
        $this->clear();
        $this->set_filename($filename);
        $this->auto_type();
        if($this->auto_type != $type_before){
            $this->set_error(__CLASS__, __METHOD__, 'debug', 'Указанный начальный формат ('.$type_before.') несоответствует текущему ('.$this->auto_type.') при конвертации');
        }

        if(count($this->error) > 0) return false;
        $this->set_type($type_before);
        $this->read_vars();
        $this->set_type($type_after);
        if($this->save()){
            $this->set_status('ok', 'Информация сохранена');
        }else{
            $this->set_status('error', 'Не удалось сохранить информацию при записи');
        }
    }

    /**
    *  Очистка данных свойств
    *
    */
    function clear(){
        $this->filename = '';
        $this->data = array();
        $this->start_string = '';
        $this->end_string = '';
        $this->ext_file = 'php';
        $this->set = array();
        $this->change = array();
    	$this->error = array();
    	$this->status = array();
    }

    /**
    *  Метод обработки ошибок
    *  @param string $class - класс
    *  @param string $method - метод
    *  @param string $level  - уровень ошибки
    *  @param string $message - сообщение
    */
    function set_error($class, $method, $level, $message){
         $this->error[] = $message;
         log_message($level, $message);
    }

    /**
     * Вывод ошибок по строчно
     * @return string
     */
    function error(){
        $str = '';
        if(is_array($this->error)){
            foreach($this->error as $key=>$value){
                $str .= $value."<br />";
            }
        }
        return $str;
    }

    /**
     *  Возвращает статусные сообщения
     *  ok - информация сохранена
     *  error - ошибки при сохранении
     *  not - нет изменений
     * @param string $type - можно выбрать сообщения только конкретного типа (ok|error|not)
     * @return string
     */
    function status($type = false){
        $str = '';
        if($type != false){
            if(isset($this->status[$type])){
                $str .= $this->status[$type]."";

            }
        }else{
            foreach($this->status as $type_s=>$items){
                 $str .= $items." - ".$this->filename."<br>";
            }
        }
        //print_r($this->status);
        return $str;
    }


    function change(){
        $str = '';
        foreach($this->change as $action=>$items){
            if(is_array($items)){
                foreach($items as $keys=>$value){
                    if($action == 'update'){
                        $str .= "Параметр ".$value['key']." обновлен";
                    }
                    if($action == 'insert'){
                        $str .= "Параметр ".$value['key']." добавлен";
                    }
                    if($action == 'delete'){
                        $str .= "Параметр ".$value['key']." удален";
                    }
                    if($action == 'not'){
                        $str .= "Параметр ".$value['key']." не изменился";
                    }
                    $str .= '<br />';
                }
            }
        }
        return $str;
    }
}