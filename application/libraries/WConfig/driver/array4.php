<?php

class Array_php{

    /**
     *  Массив с распарсенными значениями
     *
     * @var array
     */
    private $array = array();

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
     *  Разделители начало считывания массива
     *  если несколько - через знак |
     *
     * @var string
     */
    private $start_separator = '<\?php|<\?';

    /**
     *  Разделитель конца считывания массива
     *  если несколько - через знак |
     *
     * @var string
     */
    private $end_separator = '\?>';

    /**
     *  Разделители комментарий
     *  key - начало комментария
     *  value - конец комментария
     *
     * @var array
     */
    private $comments = array('//' => '\\n',
                              '/\*' => '\*/'
                             );

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

    /**
     *  Инициализация объекта с настройками
     *
     * @param array $setting
     */
    function __construct($setting = array()) {

    }

    function read($filename){
        if(is_readable($filename)){
            //$this->read_vars($filename);

            $this->parse_file($filename);
        }
    }

    /**
     *  Чтение переменных в файле
     *
     * @param string $filename
     */
    function read_vars($filename){
        include($filename);
        $arr = get_defined_vars();
        if(is_array($arr)){
            $this->config_vars = $arr;
        }
    }

    /**
     *  Парсинг файла
     *  Разделение строк массива, комментариев, начала и конца скрипта php
     *
     * @param string $filename
     */
    function parse_file($filename){
        $this->content = file_get_contents($filename);
        //var_dump($fp);
        //print_r($fp);
        //echo "<br> file: ".$fp." -<br>";
        if(strlen($this->content) > 0){
            $parse_php = $this->parse_php( & $this->content);
        }
    }

    /**
     *  Парсинг кода php
     *
     * @param string $string
     */
    function parse_php($string){

        //$pattern = "|($this->start_separator[^($this->end_separator)]*)|is";
        $pattern = '%(?:'.$this->start_separator.')[^'.$this->end_separator.']*%si';
        echo "<br>$pattern<br>";
        preg_match_all($pattern, $string, $matches, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE);

        if(is_array($matches[0])){
            $this->set_array_php( & $matches[0]);
        }
        //print_r($matches);
    }

    /**
     *  установка свойства массива файла
     *  разделяются элементы кода php и текст за пределами их тегов
     *  формат: array['num_position']['php|txt']['num_fragment'] = array('name' => php|comment|text,
     *                                                                   'value'=> string,
     *
     * @param array $matches
     */
    function set_array_php($matches){
        $start_search_text = 0;
        foreach($matches as $match){

            if($match[1] > 0 && $match[1] != $start_search_text){
                $str_text = substr($this->content, $start_search_text, $match[1]);
                $arr[]['text'][] = array('name' => 'text',
                                         'value' => $str_text,
                                        );
            }
            $start_search_text = $match[1] + strlen($match[0]);
            $arr_php = $this->parse_comment_php($match[0]);
            $arr[]['php'][] = array('name' => 'php',
                                         'value' => $match[0],
                                        );
        }
        $this->array = $arr;

        //echo "<pre>";
        //print_r($this->array);
        //echo "</pre>";
    }

    /**
     *  Парсер кода php на наличие комментариев
     *  Разделение от комментариев которые не входят в состав переменных
     *
     * @param string $string
     */
    function parse_comment_php($string){
        //$pattern = '%(?:'.$this->start_separator.')[^'.$this->end_separator.']*%si';
        //$pattern = '';

        // шаблон для замены кода php
        $pattern_code = "%\\$[^;]*;%is";


        $pattern_quote = '(?:(?:\'[^\']*\')|(?:\"[^\"]*\"))*';
        $pattern_var = '\$[^;]*'.$pattern_quote.';';
        $pattern_code2 = '#'.$pattern_var.'#is';

        preg_match_all($pattern_code2, $string, $matches_code);
        //$string = preg_replace($pattern_code2, '', $string);
        //echo "<br><br><b>Строка состояния:</b> ".$string."<br>";
        //echo "строка после вырезания php шаблоном ($pattern_code2): <br>".print_r($matches_code)."<br>";


        $regenerated='';
        //$arr_code['comment'] = '';
        //$arr_code['php'] = '';
        $tokens = token_get_all($string);
        $ii = 0;
        $flag = '';
        $flag_next = '';
        $label = '';
        foreach($tokens as $idx=>$t)
        {
            if($flag_next != $flag) $ii ++;
            if (is_array($t))
            {
                 //do something with string and comments here?
                 switch($t[0])
                 {
                     case T_COMMENT:
                         @$arr_code[$ii]['comment'] .= $t[1];
                         $flag = 'comment';
                         $label = 'T_COMMENT';
                         break;
                     case T_DOC_COMMENT:
                         @$arr_code[$ii]['comment'] .= $t[1];
                         $flag = 'comment';
                         $label = 'T_COMMENT';
                         break;
                     case T_VARIABLE:
                         @$arr_code[$ii]['php'] .= $t[1];
                         $flag = 'php';
                         $label = 'T_VARIABLE';
                         break;
                     case T_CONSTANT_ENCAPSED_STRING:
                         @$arr_code[$ii]['php'] .= $t[1];
                         $flag = 'php';
                         $label = 'T_CONSTANT_ENCAPSED_STRING';
                         break;
                     case T_ARRAY:
                         @$arr_code[$ii]['php'] .= $t[1];
                         $flag = 'php';
                         $label = 'T_ARRAY';
                         break;
                     case T_WHITESPACE:
                         @$arr_code[$ii]['php'] .= $t[1];
                         $flag = 'php';
                         $label = 'T_WHITESPACE';
                         break;
                     case T_LNUMBER:
                         @$arr_code[$ii]['php'] .= $t[1];
                         $flag = 'php';
                         $label = 'T_LNUMBER';
                         break;
                 }
                 //$regenerated.=$t[1];
            }
            else
            {
                //if($label == 'T_VARIABLE' OR $label == 'T_CONSTANT_ENCAPSED_STRING'){
                    @$arr_code[$ii]['php'] .= $t;
                    $flag = 'php';
                    $label = 'T_STRING';
                //}
                //$regenerated.=$t;
            }
            $flag_next = $flag;
        }

        echo "<pre>".token_name(307);
        print_r($arr_code);
        echo "</pre>";


        foreach($this->comments as $start=>$end){
            $pattern_arr[] = "(?<=".$this->get_escape_slash($start).")(?:.*?)(?=".$this->get_escape_slash($end).")";
        }
        $pattern = implode('|', $pattern_arr);

        //$pattern = "([^\$]*;?(".$pattern."))";
        $pattern = "#$pattern#is";
        //$pattern = '#/\*(?:[^\*][^/])*\*/#ism';
        //$pattern = '#(?<=/\*)(?:.*?)(?=\*/)#is';
        //$pattern = '#/\*(?:.*?)\*/#ism';
        //echo "<br>$pattern<br>";

        preg_match_all($pattern, $string, $matches, PREG_PATTERN_ORDER | PREG_OFFSET_CAPTURE);
        if(is_array($matches)){
            //print_r($matches);
        }
    }

    /**
     *  Возвращает строку из начальных символов коментарий разделенных символом |
     *
     * @return string
     */
    function get_all_start_comments(){
        if(is_array($this->comments)){
            foreach($this->comments as $start=>$end){
                $arr[] = $this->get_escape_slash($start);
            }
        }
        if(isset($arr)) return implode('|', $arr);
        return false;
    }
    /**
     *  Возвращает строку из конечных символов коментарий разделенных символом |
     *
     * @return string
     */
    function get_all_end_comments(){
        if(is_array($this->comments)){
            foreach($this->comments as $start=>$end){
                $arr[] = $this->get_escape_slash($end);
            }
        }
        if(isset($arr)) return implode('|', $arr);
        return false;
    }

    function get_escape_slash($string){
        if(in_array($string, $this->escape_slash)){
            $string = str_replace($this->escape_slash, "\\\\1", $string);
        }
        return $string;
    }
}