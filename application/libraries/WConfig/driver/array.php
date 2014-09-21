<?php


class config_array{

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
        if($this->_get_data()) return true;
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

}
