<?php

/**
 * Класс для разбиения строки с кодом PHP на комментарии и непосредственно сам код
 *
 *
 */

class phparray_split_comments {

    /**
     *  Класс парсинга массива php
     * @var object
     */
    private $parse;

    function __construct($var) {
        if(is_object($var)){
            $this->parse = $var;
        }else{
            exit("Не передан объект парсера массива php в класс разбиения строки по комментариям");
        }
    }

    /**
     * Разбиение php кода, комментариев и html вне кода
     */
    function get_array_split($result_code, & $content){
        $this->set_log("Запущен процесс разбиения кода PHP на комментарии");
        $arr = '';
        if(is_array($result_code)){
            $end = 0;
            foreach($result_code as $key=>$value){
                if(isset($value['start']) && isset($value['end'])){
                    if($end < $value['start']){
                        $string = substr($content, $end, $value['start']-$end);
                        @$arr[]['html']['string'] = $string;
                    }
                    $end = $value['end'];
                    $string = trim(substr($content, $value['start'], $value['end']-$value['start']));

                    if(!empty($string)){
                        $arr[]['php'] = $this->parse_comments($string);

                    }
                }
            }
        }

        if(is_array($arr)) return $arr;
        return false;
    }

    /**
     * Распарсивает комментарии в нутри кода
     * Возвращает последовательность кода и комментариев
     *
     * @param string $string
     *
     * @return array
     */
    function parse_comments($string){
        $this->set_log("Парсинг комментариев с помощью списка меток (token)");
        $tokens = token_get_all($string);

        $ii = 0;
        $ip = 1;
        $arr_comments = array();
        foreach($tokens as $idx=>$t)
        {
            $ii++;
            if (is_array($t))
            {
                 //do something with string and comments here?
                 switch($t[0])
                 {
                     case T_COMMENT:
                         $ip = $ii + $ip;
                         if(!isset($arr_comments[$ii]['comment']['string'])) $arr_comments[$ii]['comment']['string'] = '';
                         @$arr_comments[$ii]['comment']['string'] .= $t[1];
                         @$arr_comments[$ii]['comment']['type'] = token_name($t[0]);
                         break;
                     case T_DOC_COMMENT:
                         $ip = $ii + $ip;
                         if(!isset($arr_comments[$ii]['comment']['string'])) $arr_comments[$ii]['comment']['string'] = '';
                         @$arr_comments[$ii]['comment']['string'] .= $t[1];
                         @$arr_comments[$ii]['comment']['type'] = token_name($t[0]);
                         break;
                     default:
                         if(!isset($arr_comments[$ip]['php']['string'])) $arr_comments[$ip]['php']['string'] = '';
                         @$arr_comments[$ip]['php']['string'] .= $t[1];

                         break;
                 }

            }else{
                if(!isset($arr_comments[$ip]['php']['string'])) $arr_comments[$ip]['php']['string'] = '';
                @$arr_comments[$ip]['php']['string'] .= $t;
            }
        }
        $arr_comments = array_values($arr_comments);
        $arr_comments = $this->get_correct_array_comments($arr_comments);
        return $arr_comments;
    }

    /**
     *  Возвращает правильно скорректированный массив по отношению кода и комментариев
     *  Правильным считается такой код комментарии которого не разбивает синтаксис php кода
     *  @param array $array
     *
     *  @return array
     */
    function get_correct_array_comments($array){
        $this->set_log("Возврат скорректированного массива кода PHP и комментариев");
        $code = '';
        foreach($array as $key=>$value){
            if(isset($value['comment']['string']) && ! isset($value['php']['string'])){
                if($code === ''){
                    $arr[] = array('comment' => $value['comment']['string'],
                                   'type' => $value['comment']['type']
                                    );
                }
            }elseif(isset($value['php']['string']) && ! isset($value['comment']['string'])){
                $code .= $this->parse->trim_separator($value['php']['string']);
                // обрезаний разделителей php
                //$code .= $value['php']['string'];
                //echo "Проверка на включения комментариев в код, строка: ". $code ."<br>";

                if($this->parse->check_syntax_php($code)){
                    $arr[] = array('php' => $code,
                                   'type' => $this->parse->get_type_code($code),
                                    );
                    $code = '';
                }else{
                    $this->set_error(__CLASS__, __METHOD__, 'debug', "Обнаружен код PHP разделяющийся комментарием!!! Комментарий не должен присутствовать в незавершенном коде PHP");
                }
            }
        }
        if(isset($arr) && $code === ''){
            return $arr;
        }
        return false;
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