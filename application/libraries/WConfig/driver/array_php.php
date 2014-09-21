<?php


class config_array_php{

    /**
     *  Чистые данные в массиве из файла
     * @var array
     */
    private $data_array;

    function __construct($driver) {
        if(is_object($driver)){
            $this->driver = $driver;
        }else{
            exit('Не запущен драйвер для работы с конфигурационными данными');
        }
    }

    /**
     *  Проверяет является ли данные в файле форматом массива
     * @return boolean
     */
    function _is_type(){
        //if($this->_get_data()) return true;
        if($str = file_get_contents($this->driver->filename)){
        	if($this->isset_html($str) || $this->isset_comments_php($str)){        		return false;
        	}else{        		return true;
        	}
        }
        return false;
    }

    /**
     *  Возвращает значение файла
     * @return array
     */
    function _get_data(){
        include($this->driver->filename);
        $arr = get_defined_vars();
        if(is_array($arr)) return $arr;
        return false;
    }

    /**
     * Построение данных в строку для записи
     */
    function _build_string_save(){
        $printer = '<?php ';
        if(isset($this->driver->start_string)) $printer .= $this->driver->start_string."\r\n";
        foreach($this->driver->data as $key=>$value){
            $printer .= '$'.$key.' = '.var_export($value, true).";\r\n";
        }
        if(!empty($this->driver->end_string)) $printer .= $this->driver->end_string.";\r\n";
        return $printer;
    }

    /**
    * Содержит ли строка данные html
    */
	function isset_html($string){		$tokens = token_get_all($string);
        foreach($tokens as $idx=>$t)
        {
            if (is_array($t))
            {
                 //do something with string and comments here?
                 switch($t[0])
                 {
                     case T_INLINE_HTML:
                         @$arr[] = true;
                         break;
                     default:
                         break;
                 }

            }
        }
        if(isset($arr)) return true;
        return false;
	}

	/**
    * Содержит ли строка данные комментариев php
    */
	function isset_comments_php($string){
		$tokens = token_get_all($string);
        foreach($tokens as $idx=>$t)
        {
            if (is_array($t))
            {
                 //do something with string and comments here?
                 switch($t[0])
                 {
                     case T_COMMENT:
                         @$arr[] = true;
                         break;
                     case T_DOC_COMMENT:
                         @$arr[] = true;
                         break;
                     default:
                         break;
                 }

            }
        }
        if(isset($arr)) return true;
        return false;
	}

}
