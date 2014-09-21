<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  DB_alias
*
* Author: Цымбал Василий
* 		  531@mail.ru
*
* Created:  31.10.2011
*
* Description:  Библиотека сопоставляет имена настоящих полей БД с псевдонимами используемых в модуле
*   Установка сопоставлений представляется в виде массива в файле db.php в папке config каждого модуля
*
* Use: Применяется в моделях для запросов. Так же возможно применение в конструкторе.
*      Инициализируется объект $this->db_alias
*
* Requirements: PHP5 or above
*
*/

class Db_alias
{
    /**
    * Кеш для полученных результатов
    *
    */
    private $cache = array();

    function __construct(){
        $this->ci =& get_instance();

    }

    function load($_module = ''){
        //echo "вызван метод load, класса DB_alias<br>";
        $_module OR $_module = CI::$APP->router->fetch_module();
		    list($path, $file) = Modules::find('db', $_module, 'config/');
        if($path === FALSE){
            //echo "не обнаружен файл псевдонимов: модуль - ".$_module.", путь: $path, файл: $file<br />";
        }else{
            //echo "!! Обнаружен файл псевдонимов: модуль - ".$_module.", путь: $path, файл: $file<br />";
            $this->ci->config->load('db', TRUE, FALSE, $_module);
            $this->cache[$_module] = $this->ci->config->item('db', 'db');
        }
    }
    /**
    *  Находим имя таблицы для модуля если оно загружено
    *
    */
    function table($table, $_module = ''){
        $_module OR $_module = CI::$APP->router->fetch_module();
        if(empty($_module)) return false;
        $name = $this->_get_table($table, $_module);
        if($name) return $name;
        return false;
    }
    /**
    *  Возвращает реальное имя таблицы модуля
    */
    function _get_table($table, $_module){
        if(isset($this->cache[$_module]['tables'][$table])){
           return  $this->cache[$_module]['tables'][$table];
        }
        return false;
    }

    /**
    *  Находим имя поля таблицы для модуля если оно загружено
    *
    */
    function field($table, $field, $_module = ''){
        $_module OR $_module = CI::$APP->router->fetch_module();
        if(empty($_module)) return false;
        $name = $this->_get_field($table, $field, $_module);
        if($name) return $name;
        return false;
    }
    /**
    *  Возвращает реальное имя поля таблицы модуля
    */
    function _get_field($table, $field, $_module){
        if(isset($this->cache[$_module]['fields'][$table][$field])){
           return  $this->cache[$_module]['fields'][$table][$field];
        }
        return false;
    }

    function cache(){
        echo "<pre>";
        print_r($this->cache);
        echo "</pre>";
    }
}


