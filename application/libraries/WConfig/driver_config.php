<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


/**
 * драйвер работы с конфигурационными файлами типов:
 *   array_php - обычный php файл который содержит только php переменные или массивы
 *   array_comments - файл php который может содержать комментарии между строк переменных и массивов, так же возможно присутствие html вне тегов PHP
 *   serialize - файл содержащий в себе переменные и/или массивы PHP в сериализованном виде
 *   json - файл содержащий в себе переменные и/или массивы PHP в виде json
 */



class driver_config{

    /**
    *  Разрешение на использования метода грубой перезаписи
    *  При действии 'update', при установке метода 'set' если значение установлено как 'array' и включен третий параметр true
    *  Значение в виде массива полностью перезапишет данный параметр,
    *  в отличие если тритий параметр опущен(false) - где произойдет только добавление параметров
    */
    private $rewrite_array = true;

    /**
     *  Массив с распарсенными значениями
     *
     * @var array
     */
    public $data = array();

    /**
     *  имя файла для редактирования
     *
     * @var string
     */
    public $filename;

	    /**
     *  Расширение файла
     *
     * @var string
     */
    public $ext_file = 'php';

    /**
     * Начало строки в файле
     *
     * @var string
     */
    public $start_string;

    /**
     *  Конец строки в файле
     *
     * @var string
     */
    public $end_string;

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
     *  запись действий
     * @var array
     */
    private $logs = array();

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
     * @var array - key = псевдоним обращения к типу данных
     *				value = тип данных
     */
    private $access_type = array(
    			'array' => 'array_comments',
    			'json',
    			'arrphp' => 'array_php',
    			//'serialize'
    );

    /**
     *  Запрещенные имена к названиям переменных
     *
     * @var array
     */
    private $exception_key = array('null', 'yes', 'no', 'true', 'false', 'on', 'off', 'none');

    /**
     *  Объект взаимодействия чтения и записи файла в завиимости от типа данных
     * @var object
     */
    private $driver;

    private $template_out;

    function __construct($setting = array()) {
    	$this->set_log('Инициализирован класс '.__CLASS__);
    }

    function set_driver($driver){
        $this->set_log('запущен процесс подключения драйвера конфигурационных файлов "'.$driver.'"');
        if(isset($this->access_type[$driver])){
        	$driver = $this->access_type[$driver];
        }

        if(in_array($driver, $this->access_type)){
            //echo dirname(__FILE__).'/driver/'.$driver.'.php';
                if( ! class_exists('config_'.$driver)){
	                if(is_file(dirname(__FILE__).'/driver/'.$driver.'.php')){
	                    include_once('driver/'.$driver.'.php');
	                    $this->set_log('подключен файл драйвера конфигурационных файлов "'.$driver.'.php"');
	                }else{
	                    exit('Не найден файл драйвера для конфигурационных файлов "'.$driver.'"');
	                }
                }
                $class = 'config_'.$driver;
                $this->driver = new $class($this);
                $this->set_log('Инициализирован объект драйвера типа "'.$driver.'"');
        }else{
        	$this->set_log('Не обнаружен доступный к инициализации драйвер типа "'.$driver.'"');
        	exit('Не найден разрешенный драйвер ('.$driver.') для работы конфигурационных файлов');
        }
    }

    function set_template($string){
        $this->template_out = $string;
    }

    /**
     *  Установка формата данных
     *
     * @param string $type
     */
    function set_type($type){
        $this->set_log('Выполнен запрос на установку типа данных '.$type);
        foreach($this->access_type as $alias=>$item){
            if($type === $item || $type === $alias){
                $this->type = $item;
                $this->set_log('Установлен тип данных '.$item);
                $this->set_driver($item);
            }
        }
    }

    /**
     * Возвращает значение типа
     */
    function get_type(){
        if($this->type){
        	$alias = array_keys($this->access_type, $this->type);
        	$this->set_log('Ключ типа данных "'.$this->type.'" равен "'.$alias[0].'"');
        	if( ! is_numeric($alias[0])){
        		$this->set_log('получен тип псевдонима конфигурационных файлов "'.$alias[0].'"');
        		return $alias[0];
        	}else{
        		$this->set_log('получен тип конфигурационных файлов "'.$this->type.'"');
        		return $this->type;
        	}
    	}
        return false;
    }

    /**
     * Автоматическое определение типа данных
     *
     */
    function auto_type(){
        $this->set_log('запущен процес автоматического определения типа данных');
        $t = '';
        $type = $this->get_type();
        if(is_readable($this->filename)){
            //меняем драйвер и пытаемся проверить формат данных
            foreach($this->access_type as $item){
                $this->set_driver($item);
                if($this->driver->_is_type()){
                    $t[] = $item;
                }
            }
        }
        // возвращаем первоначальный драйвер если он был
        if($type){
            $this->set_driver($type);
            $this->set_log('возвращен тип данных на '.$type.' после процесса автоопределения');
        }
        //если авто формат обнаружен
        if(is_array($t) && count($t) > 0){
            //если автоформат не один присваем значение mixed
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
        $this->set_log('Авто определение типа определило как "'.$this->auto_type.'"');
    }


    /**
     *  Установка параметра стартовой строки для файла
     * @param string $string
     *
     */
    function set_start_string($string){
        if(!empty($string)){
            $this->start_string = $string;
            $this->set_log('установлен параметр стартовой строки для конфигурационных файлов "'.htmlspecialchars($string).'"');
        }
    }
    /**
     *  Установка параметра конечной строки для файла
     * @param string $string
     *
     */
    function set_end_string($string){
        if(!empty($string)){
            $this->end_string = $string;
            $this->set_log('установлен параметр конечной строки для конфигурационных файлов "'.htmlspecialchars($string).'"');
        }
    }

    /**
    *  Установка имени файла
    *  @param string $file
    */
    function set_filename($file){
        $this->filename = $file;
        $this->set_log('установлено имя конфигурационного файла "'.htmlspecialchars($file).'"');
    }

    /**
     *  Возвращает стартовую строку для файла
     * @return string
     */
    function get_start_string(){
        if(!empty($this->start_string)){
            return $this->start_string;
        }
        return false;
    }

    /**
     *  Возвращает конечную строку для файла
     * @return string
     */
    function get_end_string(){
        if(!empty($this->end_string)){
            return $this->end_string;
        }
        return false;
    }

    /**
    *  Чтениние файла, проверка на предмет присутствия и установка параметров
    *
    *   @param string $filename - имя файла
    *   @param boolean $isfile - обязательное присутствие файла
    */
    function read($filename, $isfile = true){
        $this->set_log('Запущен процесс чтения конфигурационного файла "'.htmlspecialchars($filename).'"');
        $this->set_filename($filename);
        if(is_readable($this->filename)){
            // авто определение типа данных
            if($this->use_auto_type == true) $this->auto_type();
            if(isset($this->type)){
                if($this->auto_type){
                    if($this->type != $this->auto_type){
                        $this->set_error(__CLASS__, __METHOD__, 'debug', 'Тип данных установленный ('.$this->type.') не соответствует автоматически найденному типу ('.$this->auto_type.')');
                    }
                }
            }else{
                if(isset($this->auto_type) && in_array($this->auto_type,$this->access_type)){
                    $this->set_type ($this->auto_type);
                }else{
                    $this->set_log('Т.к. не установлен тип данных и не удалось определить его автоматически установлен тип данных по умолчанию "array_comments"');
                    $this->set_type ('array_comments');
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
        $this->set_log('Чтение переменных файла для обработки');
        $arr = $this->driver->_get_data();
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
           $this->set_log('Получение данных файла '.htmlspecialchars($filename).' по ключу '.$key);
           return $this->_get_data($key);
        }
        $this->set_log('Получение всех данных файла '.htmlspecialchars($filename));
        return $this->data;
    }


    /**
    *  Получение значения ключа
    */
    function _get_data($key){
        $keys = explode($this->separator_keys, $key);
        if(is_array($keys)){
            return $this->_get_data_in_array($this->data, $keys);
        }
        return null;
    }

    function _get_data_in_array($array, $array_keys = array()){
        if(is_array($array_keys) && count($array_keys) > 0){
            $current_key = array_shift($array_keys);
            if(isset($array[$current_key])){
                if(is_array($array)){
                    return $this->_get_data_in_array($array[$current_key], $array_keys);
                }else{
                    //return $this->_get_data_in_array($array[$current_key], $array_keys);
                }
            }
        }else{
            return $array;
        }
        return null;
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
        $this->_set_data_in_array($this->data, $keys, $value, $replace);
    }

    function _set_data_in_array( & $array, $keys, $value, $replace){
        if(is_array($keys) && count($keys) > 0){
            $current_key = array_shift($keys);
            if(isset($array[$current_key])){
                if( ! is_array($array)){
                    //установка в массив параметра в случае если он не является массивом
                    $array = array($current_key => '');
                }
            }else{
                // если параметр не существует создаем его
                $array[$current_key] = '';
            }
            $this->_set_data_in_array($array[$current_key], $keys, $value, $replace);
        }else{
            if($replace === true){
                $array = $value;
            }else{
                if(is_array($array) && count($array) > 0){
                    $array = $this->array_merge_recursive_distinct($array, $value);
                }else{
                    $array = $value;
                }
            }
        }
    }


    /**
    *  Удаление элемента
    *
    */
    function _del_data($key){
        $keys = explode($this->separator_keys, $key);
        $this->_del_data_in_array($this->data, $keys);
    }

    function _del_data_in_array( & $array, $keys){
        if(is_array($keys) && count($keys) > 0){
            $current_key = array_shift($keys);
            if(isset($array[$current_key])){
                if(count($keys) > 0){
                    if(is_array($array)){
                        $this->_del_data_in_array($array[$current_key], $keys);
                    }
                }else{
                    unset($array[$current_key]);
                }

            }
        }
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
                    return true;
                }else{
                    $this->set_status('error', 'Не удалось сохранить информацию при записи');
                }
            }else{
                $this->set_status('not', 'Изменений не обнаружено');
            }
    	}else{
            $this->set_status('error', 'Не удалось сохранить информацию, ошибки при обработке');
    	}
    	return false;
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
                    return true;
                }else{
                    $this->set_status('error', 'Не удалось сохранить информацию при записи');
                }
            }else{
                $this->set_status('not', 'Изменений не обнаружено');
            }
    	}else{
            $this->set_status('error', 'Не удалось сохранить информацию, ошибки при обработке');
    	}
    	return false;
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
                    return true;
                }else{
                    $this->set_status('error', 'Не удалось сохранить информацию при записи');
                }
            }else{
                $this->set_status('not', 'Изменений не обнаружено');
            }
    	}else{
            $this->set_status('error', 'Не удалось сохранить информацию, ошибки при обработке');
    	}
    	return false;
    }

    /**
     *  Обработка новых данных
     */
    function run_set($action = 'update'){
        if(count($this->set) > 0){
            foreach($this->set as $key=>$value){
                if($this->rewrite_array === true){
                    if($action == 'update'){
                        if($value['replace'] === true) $action = 'replace';
                    }
                }
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
            if (!is_array($append)){
              $base = $append;
              //$append = array($append);
            }else{
              foreach ($append as $key => $value) {
                  if (!array_key_exists($key, $base) and !is_numeric($key)) {
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
        }
        return $base;
    }

    /**
     *  Проверка и запись новых данных
     */
    function new_data($key, $value, $action = 'update'){
        $keys = explode($this->separator_keys, $key);
        $count_keys = count($keys);

        $source_value = $this->_get_data($key);
        //var_dump($source_value);
        // если элемент существует
        if($source_value !== null){
            // если значения исходного и устанавливающегося различны
            if($source_value !== $value){
                // если элемент является массивом
                if(is_array($source_value)){
                    if($action == 'delete'){
                        $this->set_error(__CLASS__, __METHOD__, 'debug', 'Нельзя удалить тип (array) элемента массива при действии  \'delete\'');
                    }
                    // если устанавливающийся элемент также как и исходный является массивом
                    if(is_array($value)){
                            if($this->array_numeric($source_value)){
                                    if($action == 'update'){
                                            $this->_set_data($key, $value);
                                            $this->set_change('update', $key, $value);
                                    }elseif($action == 'insert'){
                                            $this->_set_data($key, $value);
                                            $this->set_change('insert', $key, $value);
                                    }elseif($action == 'replace'){
                                            $this->_set_data($key, $value, true);
                                            $this->set_change('update', $key, $value);
                                    }
                            }else{
                                    if($action == 'update'){
                                            if($this->isset_array_key($value, $source_value)){
                                                    $this->_set_data($key, $value);
                                                    $this->set_change('update', $key, $value);
                                            }else{
                                                    $this->set_error(__CLASS__, __METHOD__, 'debug', 'Ключи массива назначяения не соответствуют ключам массива источника при действии \'update\'');
                                            }
                                    }elseif($action == 'replace'){
                                            $this->_set_data($key, $value, true);
                                            $this->set_change('update', $key, $value);
                                    }elseif($action == 'insert'){
                                            if($this->not_set_array_key($value, $source_value)){
                                                    $this->_set_data($key, $value);
                                                    $this->set_change('insert', $key, $value);
                                            }else{
                                                    $this->set_error(__CLASS__, __METHOD__, 'debug', 'Ключи массива назначяения присутствуют в массиве источника при действии \'insert\'');
                                            }
                                    }
                            }

                    // если устанавливающийся элемент не является массивом, а исходный является массивом
                    }else{
                        if($action == 'replace')	{
                            $this->_set_data($key, $value, true);
                            $this->set_change('update', $key, $value);
                         }else{
                            $this->set_error(__CLASS__, __METHOD__, 'debug', 'Тип (string) значения назначения не соответствует типу (array) значения источника '.$key);
                         }
                    }

                //если исходный парметр не является массивом
                }else{
                        //если устанавливающийся элемент является массивом, а исходный нет
                        if(is_array($value)){
                            if($action == 'replace')	{
                                $this->_set_data($key, $value, true);
                                $this->set_change('update', $key, $value);
                            }else{
                                $this->set_error(__CLASS__, __METHOD__, 'debug', 'Тип (array) значения назначения не соответствует типу (string) значения источника '.$key);
                            }

                        // значения обоих элементов не являются массивами и не равны
                        }else{
                            if($action == 'update' || $action == 'replace')	{
                                $this->_set_data($key, $value);
                                $this->set_change('update', $key, $value);
                            }
                            if($action == 'delete'){
                                if($value === null || $value === ''){
                                    $this->_del_data($key);
                                    $this->set_change('delete', $key, $value);
                                }else{
                                    $this->set_error(__CLASS__, __METHOD__, 'debug', 'Значение при действии "delete" должно быть пустым');
                                }
                            }
                            if($action == 'insert'){
                                $this->set_error(__CLASS__, __METHOD__, 'debug', 'Элемент '.$key.' при действии "insert" уже существует');
                            }
                        }
                }
            }else{
                // если значение элемента не изменилось

                if($value === null){
                    if($action == 'delete'){
                        $this->_del_data($key);
                	$this->set_change('delete', $key, $value);
                    }else{
                        $this->set_error(__CLASS__, __METHOD__, 'debug', 'Значение равное "null" допускается только при действии "delete"');
                    }
                }else{
                    if($action == 'delete'){
                        $this->set_error(__CLASS__, __METHOD__, 'debug', 'При действии "delete" значение должно быть пустым');

                    }else{
                        $this->set_change('not', $key, $value);
                    }
                }
            }
        }else{
            //если элемента не существует
                if($action == 'insert'){
                    $this->_set_data($key, $value);
                    $this->set_change('insert', $key, $value);
                    //$this->set_error(__CLASS__, __METHOD__, 'debug', 'Нельзя добавить ключ в дочерний элемент который не является массивом');
                }else{
                    if($action == 'replace'){
                        $this->_set_data($key, $value, true);
                        $this->set_change('insert', $key, $value);
                    }
                }
                if($action == 'update' || $action == 'replace') $this->set_error(__CLASS__, __METHOD__, 'debug', 'Не обнаружен элемент массива "'.$key.'" при действии \'update\'');
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
	*  Проверяет есть ли ключи из первого массива во втором массиве
	*  Функция проверяет также многомерные массивы
	*/
	function isset_array_key($array1, $array2){
		if(is_array($array1) && is_array($array2)){
			foreach($array1 as $key=>$value){
        if( ! isset($array2[$key])) return false;
        if(is_array($value)){
            if(is_array($array2[$key])){
              return $this->isset_array_key($value,$array2[$key]);
            }else{
              return false;
            }
        }

			}
			return true;
		}
		return false;
	}

  /**
	*  Проверяет нет ли ключей из первого массива во втором массиве
	*  Функция проверяет также многомерные массивы
	*/
	function not_set_array_key($array1, $array2){
		if(is_array($array1) && is_array($array2)){
			foreach($array1 as $key=>$value){
        if(isset($array2[$key])) return false;
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
    function set($key, $value = '', $replace = false){
    	if( ! is_array($key)){
    		$array = array($key => $value);
    	}else{
    		$array = $key;
    	}
      ($replace === true) ? $replace = true : $replace = false;
      foreach($array as $key_set=>$value_set){
    		$this->set[] = array('key' => $key_set,
    							 'value' =>	$value_set,
    							 'replace' => $replace
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
                if(method_exists($this->driver, 'set_template') && !empty($this->template_out)){
                    $this->driver->set_template($this->template_out);
                }
                $printer = $this->driver->_build_string_save();
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

	/**
	*  Установка логов системы
	*  @param string $message
	*/
    function set_log($message){
    	$this->logs[] = $message;
    }

	/**
	*  Возвращает строку из логов
	*/
    function logs(){
    	$str = '';
    	if(is_array($this->logs)){
    		$str .= "<div style=\"border:1px solid #000000;padding:10px;font-size:12px;color:#0066ff;\">";
    		$str .= "<div style=\"font-size:24px;color:#660033;padding: 10px 0;\">";
    			$str .= "Лог работы библиотеки конфигурационных файлов";
    		$str .= "</div>";
    		foreach($this->logs as $key=>$value){
    			$str .= "<li>".$value."</li>";
    		}
    		$str .= "</div>";
    	}
    	return $str;
    }
}

