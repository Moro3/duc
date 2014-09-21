<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class config_array_comments{

    /**
     *  Распарсенные данные
     *  первый ключ = number - очередность следования данных
     *  второй ключ (php|html) - тип строки, php - код php с комментариями, html - код вне кода php
     *  третье значение массив - ('start' => номер начало строки в файле
     *                            'end' => номер конца строки в файле
     *                            'start_string' => строка в начале кода
     *                            'end_string' => строка в конце кода
     *                            'code' => массив распарсенного кода
     *                            )
     * Распарсенный код = массиву, где:
     *          1 индекс = очередность следования данных
     *          2 индекс = (comment|vars) тип части кода комментарий или переменные
     *          значение 2 индекса array( 'start' => номер начало строки в файле
     *                                    'end' => номер конца строки в файле
     *                                    'string' => строка с данными
     *                                  )
     * @var array
     */
    private $data_parse;


    /**
     *  Чистые данные в массиве из файла
     * @var array
     */
    public $data_array = array();

    /**
     *  Содержимое файла
     * @var string
     */
    private $content;


    /**
     *  Запрещенные имена к названиям переменных
     *
     * @var array
     */
    private $exception_key = array('null', 'yes', 'no', 'true', 'false', 'on', 'off', 'none');

    /**
     *  Запрещенные символы к названиям переменных
     *
     * @var array
     */
    private $exception_characters = array('?','{','}','|','&','~','!','[','(',')','^','"');

    /**
     *  Символы предываряющиеся слеш \ при работе в регулярных выражениях
     *
     * @var array
     */
    private $escape_slash = array("\\", '.', '|', '(', ')', '[', ']', '{', '}', '?', '*', '+', '^', '$');

    public $driver;

    private $obj_parse;

    function __construct($driver) {
        if(is_object($driver)){
            $this->driver = $driver;
        }else{
            exit('Не запущен драйвер для работы с конфигурационными данными');
        }
        if( ! class_exists('parse_phparray')){
            include('parse/parse_phparray.php');
        }
        $this->obj_parse = new parse_phparray($this);

    }

    /**
     *  Проверяет является ли данные в файле форматом массива
     * @return boolean
     */
    function _is_type(){
        if($this->_get_data()){
        	if($this->obj_parse->isset_html() || $this->obj_parse->isset_comments_php()){
         		return true;
         	}
        }
        return false;
    }

    /**
     *  Возвращает значение файла
     * @return array
     */
    function _get_data(){
        //include($this->driver->filename);
        //$arr = get_defined_vars();

        $this->obj_parse->parse_file($this->driver->filename);
        $arr = $this->obj_parse->get_array_collected();
        if(is_array($arr)) return $arr;
        return false;
    }

    /**
     * Построение данных в строку для записи
     */
    function _build_string_save(){
        $this->obj_parse->set_array_new($this->driver->data);
        return $this->obj_parse->_build_string_save();
    }

    /**
     *  Установка шаблона вывода
     * @param type $string
     */
    function set_template($string){
        $this->obj_parse->set_template($string);
    }
}
