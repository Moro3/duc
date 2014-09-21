<?php

class Array2_php{

    /**
     *  Массив с распарсенными значениями
     *
     * @var array
     */
    private $data = array();

    /**
     *  Массив с изменяемыми значениями
     *
     * @var array
     */
    private $update_data = array();

    /**
     *  Массив с полными изменяемыми значениями массива
     *
     * @var array
     */
    private $update_data_array = array();

	/**
     *  Массив с новыми параметрами
     *
     * @var array
     */
    private $insert_data = array();

    /**
     *  имя файла для редактирования
     *
     * @var string
     */
    private $filename;

    /**
     *  Содержание файла
     *
     * @var string
     */
    private $content;

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
     *  Инициализация объекта с настройками
     *
     * @param array $setting
     */

    /**
     *  Параменты при обновлении, удалении, вставке
     * @var type
     */
    private $set = array();

    /**
     *  Параменты при обновлении, удалении, вставке
     *  со значением массива для перезаписи
     * @var array
     */
    private $set_array = array();

    function __construct($setting = array()) {

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
    */
    function read($filename){
        if(is_readable($filename)){
            $this->set_filename($filename);
            $this->read_vars();

            //$this->parse_file($filename);
        }
    }

    /**
     *  Чтение переменных в файле
     *
     * @param string $filename
     */
    function read_vars(){
        include($this->filename);
        $arr = get_defined_vars();
        if(is_array($arr)){
            $this->data = $arr;
        }
    }

    /**
     *  Получение данных
     */
    function get($filename, $config = false){
        $this->read($filename);
        echo "<pre>Данные после get:<br>";
        print_r($this->data);
        echo "</pre>";
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
        $this->save();
    }

    /**
     *  Обработка новых данных
     */
    function run_set(){
        if(count($this->set) > 0){
            foreach($this->set as $key=>$value){
                $this->new_data($key, $value);
            }
        }
        if(count($this->set_array) > 0){
            foreach($this->set_array as $key=>$value){
                $this->new_data($key, $value, true);
            }
        }
        if(isset($this->update_data) && count($this->update_data) > 0){
            $this->data = $this->array_merge_recursive_distinct($this->data, $this->update_data);
        }
        if(isset($this->update_data_array) && count($this->update_data_array) > 0){
            $this->data = $this->array_merge_replace($this->data, $this->update_data_array);
        }

    }

    function array_merge_replace($array1, $array2){

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
    function new_data($key, $value, $replace = false){
        $keys = explode('->', $key);
        $count_keys = count($keys);
        echo "<br><u>".$count_keys."</u>";


        echo $this->data;
        switch ($count_keys) {
            case 1:
                if(isset($this->data[$keys[0]]) && $this->data[$keys[0]] != $value){
                    ($replace) ? $this->update_data_array[$keys[0]] = $value : $this->update_data[$keys[0]] = $value;
                }elseif( ! isset($this->data[$keys[0]])){
                	$this->insert_data[$keys[0]] = $value;
                }
                break;
            case 2:
                if(isset($this->data[$keys[0]][$keys[1]]) && $this->data[$keys[0]][$keys[1]] != $value){
                    $this->update_data[$keys[0]][$keys[1]] = $value;
                }elseif( ! isset($this->data[$keys[0]][$keys[1]])){
                	$this->insert_data[$keys[0][$keys[1]]] = $value;
                }
                break;
            case 3:
                if(isset($this->data[$keys[0]][$keys[1]][$keys[2]]) && $this->data[$keys[0]][$keys[1]][$keys[2]] != $value){
                    ($replace) ? $this->update_data_array[$keys[0]][$keys[1]][$keys[2]] = $value : $this->update_data[$keys[0]][$keys[1]][$keys[2]] = $value;
                }elseif( ! isset($this->data[$keys[0]][$keys[1]][$keys[2]])){
                	$this->insert_data[$keys[0][$keys[1]]][$keys[2]] = $value;
                }
                break;
            case 4:
                if(isset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]]) && $this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]] != $value){
                    $this->update_data[$keys[0]][$keys[1]][$keys[2]][$keys[3]] = $value;
                }elseif( ! isset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]])){
                	$this->insert_data[$keys[0][$keys[1]]][$keys[2]][$keys[3]] = $value;
                }
                break;
            case 5:
                if(isset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]]) && $this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]] != $value){
                    $this->update_data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]] = $value;
                }elseif( ! isset($this->data[$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]])){
                	$this->insert_data[$keys[0][$keys[1]]][$keys[2]][$keys[3]][$keys[4]] = $value;
                }
                break;
            default:
                break;
        }
        echo "<pre>Измененные данные:<br>";
        print_r($this->update_data);
        echo "</pre>";
        echo "<pre>Новые данные:<br>";
        print_r($this->insert_data);
        echo "</pre>";
        echo "<pre>Измененные данные по замене всего массива:<br>";
        print_r($this->update_data_array);
        echo "</pre>";
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
    	$this->set = array_merge($this->set, $array);
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
                        $new_arr[$key_arr] = $value_arr;
                }
        }
        if(isset($new_arr)) $this->set_array = array_merge($this->set_array, $new_arr);
    }

    /**
    *  Сохранение данных в файл
    *
    */
    function save($insert = false){
        if(is_writable($this->filename)){
            if(is_array($this->data) && count($this->data) > 0){
                $printer = var_export($this->data, true);
                echo "<pre>";
                print_r($printer);
                echo "</pre>";
            }
        }else{
                $this->error(__CLASS__, __METHOD__, debug, 'Нельзя писать в файл '.$this->filename.', доступ запрещен');
        }
        $this->clear();
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
    }

    /**
    *  Метод обработки ошибок
    *  @param string $class - класс
    *  @param string $method - метод
    *  @param string $level  - уровень ошибки
    *  @param string $message - сообщение
    */
    function error($class, $method, $level, $message){

    }
}