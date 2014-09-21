<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Index_request
*
* Author: Цымбал Василий
* 		  531@mail.ru
*
* Created:  31.10.2011
*
* Description:  Библиотека загружает исходные параметры модуля для формирования uri.
*   Установка представляется в виде массива в файле index_request.php в папке config каждого модуля
*
* Use: Применяется только для загрузки параметров uri.
*      Дальнейшие действия в модуле осуществляется через библиотеки uri_generation.
*      Сделано специально для автоподгрузки из одного места!
*      Возможно расширенное применение объекта для получения значений определенных параметров (например получение имени параметра в зависимости от используемого языка: lang:name)
*      Инициализируется объект $this->index_request
*
* Requirements: PHP5 or above
*
*/

class Index_request
{
    /**
    * Кеш для полученных результатов
    *
    */
    private $cache = array();

    function __construct(){
        $this->ci =& get_instance();
        $this->ci->load->library('control_uri');

    }

	/**
	*  Возвращает текущий модуль
	*
	*/
    function current_module(){    	return CI::$APP->router->current_module();
    }

    function load($_module = ''){
        //echo "вызван метод load, класса DB_alias<br>";
        $_module OR $_module = $this->current_module();
		    list($path, $file) = Modules::find('index_request', $_module, 'config/');

        if($path === FALSE){
            //echo "не обнаружен файл псевдонимов: модуль - ".$_module.", путь: $path, файл: $file<br />";


        }else{

            //echo "!! Обнаружен файл псевдонимов: модуль - ".$_module.", путь: $path, файл: $file<br />";
            if(!isset($this->cache[$_module])){
                //$this->ci->config->load('index_request', TRUE);

                if($index_request = Modules::load_file($file, $path, 'config')){
                    //$index_request = $this->ci->config->item('index_request', 'index_request');
                    $this->cache[$_module] = $index_request['index_request'];
                }else{
                    exit('Не найден файл "index_request" в модуле "'.$_module.'"');
                }
            }

            if(isset($this->cache[$_module]) && is_array($this->cache[$_module])){
                $this->set_index($this->cache[$_module]);
            }
        }
    }
    /**
    *  Установка индексов
    *
    */
    function set_index($index_request){
        if(is_array($index_request)){
                foreach($index_request as $key=>$value){
                    $this->ci->control_uri->guri($key)->set_index($value);
                }
        }
    }

    /**
    *  Установка номера стартового сегмента uri
    *
    */
    function set_start_segment($name, $n, $_module = ''){
        $_module OR $_module = $this->current_module();
        if(empty($_module)) return false;
        if(is_object($this->ci->control_uri->guri($name))){
            if(is_numeric($n)){
                $this->ci->control_uri->guri($name)->set_start_segment($n);
                return true;
            }
        }
        return false;
    }

    /**
    *  Принудительная установка строки uri
    *	Если строка url отличается от требуемых параметров маршрута (например ajax)
    *
    */
    function set_uri($name, $uri, $_module = ''){
        $_module OR $_module = $this->current_module();
        if(empty($_module)) return false;
        if(is_object($this->ci->control_uri->guri($name))){
            if(!empty($uri)){
                $this->ci->control_uri->guri($name)->set_uri($uri);
                return true;
            }
        }
        return false;
    }

    /**
    *  Установка заменять пустые индексы строкой по умолчанию или не заменять
    *  по умолчанию индексы заменяются (true), т.к. если они будут пропущены, порядок индексов может быть нарушен
    *  устанавливайте параметр в false, только если вы уверены, что порядок индексов не будет нарушен !!!
    *  Случаи когда не требуется замена индексов - красивый uri без дополнительных ПОСЛЕДНИХ порядковых индексов
    *
    */
    function replace_empty_index($name, $flag = false, $_module = ''){
        $_module OR $_module = $this->current_module();
        if(empty($_module)) return false;
        if(is_object($this->ci->control_uri->guri($name))){
            if($flag === false){
                $this->ci->control_uri->guri($name)->replace_empty_index(false);
            }else{
                $this->ci->control_uri->guri($name)->replace_empty_index(true);
            }
        }
    }
    /**
    *  Возвращает массив идексов со значениями определенного имени набора индексов
    *
    */
    function get_index($name, $_module = ''){
        $_module OR $_module = $this->current_module();
        /*
        echo 'Модуль ('.__CLASS__.__METHOD__.__FUNCTION__.__LINE__.'): '.$_module.'<br />';
        echo 'Индекс: '.$name.'<br />';
        echo 'Кеш индексов<pre>';
        print_r($this->cache);
        echo '</pre>';
        */
        if(empty($_module)) return false;

        if(isset($this->cache[$_module][$name]) && is_array($this->cache[$_module][$name])){

            foreach($this->cache[$_module][$name] as $key=>$value){
                $data[$key] = $this->ci->control_uri->guri($name)->value_segment($key);
            }
        }
        if(isset($data)) return $data;
        return false;
    }


    function get_uri($name, $config, $_module = ''){
        $_module OR $_module = $this->current_module();
        if(empty($_module)) return false;
        if(isset($this->cache[$_module][$name]) && is_array($this->cache[$_module][$name])){
            $uri = $this->ci->control_uri->guri($name)->get_uri($config);
        }
        if(isset($uri)) return $uri;
        return false;

    }

    function get_param($name, $param){

        return $this->ci->control_uri->guri($name)->get_param($param);

    }


}



