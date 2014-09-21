<?php

class config_json{
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
        $str = file_get_contents($this->driver->filename);
        $arr = json_decode($str, true);
        if(is_array($arr)) return $arr;
        return false;
    }

    /**
     * Построение данных в строку для записи
     */
    function _build_string_save(){
        return json_encode($this->driver->data);
    }
}