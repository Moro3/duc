<?php

/**
 * Класс для разбиения строки на код PHP и текста вне тегов
 *
 *
 */

class phparray_split_tags {

    /**
     *  Разделители начало считывания массива
     *  если несколько - массив
     *
     * @var string
     */
    public $start_separator = '<?';

    /**
     *  Разделитель конца считывания массива
     *  если несколько - массив
     *
     * @var string
     */
    public $end_separator = '?>';

    /**
    *   Массив с начальными и конечными разделителями
    *
    */
    private $array_separator;

    /**
    *   Результат деления кода
    *
    */
    private $result_code;

     /**
    *   принадлежность массивов с начальными и конечными разделителями
    *
    */
    private $array_belong;

    /**
     *  Класс парсинга массива php
     * @var object
     */
    private $parse;

    function __construct($var) {
        if(is_object($var)){
            $this->parse = $var;
        }else{
            exit("Не передан объект парсера массива php в класс разбиения строки по тегам");
        }
    }


    /**
     *  разбивает строку через начальные и конечные резделители кода php на массивы
     *
     *  @param string $string
     *
     */
    function get_mark_split( & $string){
        $this->clear();

        $array1 = explode($this->start_separator, $string);
        $array2 = explode($this->end_separator, $string);
        if(is_array($array1) && is_array($array2)){
            $i = 0;
            foreach($array1 as $key=>$value){
                $size_string = strlen($value);
                if( empty($size_string)) continue;
                  $this->array_separator[1][$key]['start'] = $i;
                  //$this->array_separator[1][$key]['string'] = substr($this->start_separator.$value, 0 , 100);
                  $i += $size_string + strlen($this->start_separator);
                  $this->array_separator[1][$key]['end'] = $i;
            }
            $i = 0;
            foreach($array2 as $key=>$value){
                $size_string = strlen($value);
                if( empty($size_string)) continue;
                  $this->array_separator[2][$key]['start'] = $i;
                  //$this->array_separator[2][$key]['string'] = substr($this->end_separator.$value, 0, 100);

                  $i += $size_string + strlen($this->end_separator);
                  // корректируем номер конца тега если он больше длины всей строки
                  // это происходит в случае отсутствия в конце файла конечного тега
                  if($i > strlen($string)) $i = strlen($string);
                  $this->array_separator[2][$key]['end'] = $i;
            }
            //$this->array_separator = array('1' => $array1, '2' => $array2);
        }else{
            $this->set_error(__CLASS__, __METHOD__, 'debug', 'Не удалось разбить на массивы по разделителям тегов PHP');

        }

        $this->search_belong();
        $this->search_cross();

        return $this->result_code;
    }

    /**
    *  Выявляем принадлежность частей массива2 к частям массива1
    *
    */
    function search_belong(){
        foreach($this->array_separator[1] as $key1=>$item1){
            foreach($this->array_separator[2] as $key2=>$item2){
                if($item2['end'] > $item1['start'] && $item2['end'] <= $item1['end']){
                    $new_arr[$key1][] = $key2;
                }
            }
        }

        if(isset($new_arr)){
            $this->array_belong = $new_arr;
        }

    }

    /**
    *  Находим пересечения разделителей
    *
    */
    function search_cross(){
        $n_start = 0;
        $result = false;
        foreach($this->array_separator[1] as $key=>$value){
            if($result === true) $n_start = $value['start'];
            if(isset($this->array_belong[$key])){
                foreach($this->array_belong[$key] as $key_belong=>$key_array2){
                    $n_end = $this->array_separator[2][$key_array2]['end'];
                    if($this->check_rules($n_start, $n_end)){
                        $this->write_result($n_start, $n_end);
                        $result = true;
                        break;
                    }else{
                        //$this->set_error(__CLASS__, __METHOD__, 'info', "строка НЕ валидная С вложением, параметры разделителей: старт - $n_start, конец - $n_end");
                        $result = false;
                    }
                }
            }else{
                $n_end = $value['end'];
                if($this->check_rules($n_start, $n_end)){
                    $this->write_result($n_start, $n_end);
                    $result = true;
                }else{
                    //$this->set_error(__CLASS__, __METHOD__, 'info', "строка НЕ валидная БЕЗ вложения, параметры разделителей: старт - $n_start, конец - $n_end");
                    $result = false;
                }
            }
        }
    }

    /**
    *  проверка строки на правила
    *
    *  @param int $start - номер начала строки
    *  @param int $end - номер конца строки
    *
    *   @return boolean - true|false
    */
    function check_rules($start, $end){
        if($start < $end){
            $string = substr($this->parse->get_content(), $start, $end-$start);
            $string = $this->parse->trim_separator($string);
            //echo "Проверка строки: $string<br>";
            if( ! $this->parse->check_syntax_php($string)){
                //$this->set_error(__CLASS__, __METHOD__, 'info', "строка не соответствует правилу проверки найденной строки");
                return false;
            }
        }else{
            $this->set_error(__CLASS__, __METHOD__, 'fatal', 'Номер начальной строки ('.$start.') меньше номера конечной строки ('.$end.')');
        }
        return true;
    }

    /**
    *  Запись результата парсера разделителей кода php и html
    *
    *  @param int $start - номер начала строки
    *  @param int $end - номер конца строки
    */
    function write_result($start, $end){
        $this->set_log("Записан результат строки на разделители PHP: параметры нач - $start, кон - $end");
        $this->result_code[] = array('start' => $start,
                                    'end' => $end,
                                    //'string' => substr($this->content, $start, $end-$start)
                                    );
    }

    /**
     * Очистка данных
     */
    function clear(){
        $this->result_code = array();
        $this->array_separator = array();
        $this->array_belong = array();
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