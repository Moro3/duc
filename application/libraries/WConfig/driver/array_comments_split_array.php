<?php

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
     *  Первоначальное разделение файла на код php и html
     * @var array
     */
    private $data_parse_php;

    /**
     *  Разбитый и проверенный на синтаксис код php
     *  $data_code[index1] = array('index_file' => индекс первого ключа файла $data,
     *                              'index_code'=> индекс ключа в файле $data[][php][code][index_code],
     *                              'data' => array после eval $data_parse[][php][code][index_code][vars][string]
     *                              )
     *  1 индекс = очередность следования кода
     *
     * @var array
     */
    private $data_code;

    /**
     *  Чистые данные в массиве из файла
     * @var array
     */
    private $data_array;

    /**
     *  Содержимое файла
     * @var string
     */
    private $content;

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
     *  Разделители начало считывания массива
     *  если несколько - через знак |
     *
     * @var string
     */
    private $start_separator = array('<?php','<?');

    /**
     *  Разделитель конца считывания массива
     *  если несколько - через знак |
     *
     * @var string
     */
    private $end_separator = '?>';

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

    private $driver;

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


        if(is_array($arr)) $this->data_array;
        $this->parse_file();

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
        /*
        $printer .= '?>';
        */
        return $printer;
    }

    /**
     *  Проверяет синтаксис php из строки
     *
     * @param string $string
     * return boolean
     */
    function check_syntax_php($string){
        return @eval('return true;' . $string);
    }

    /**
     * Проверка целостности данных кода php в массиве $data_code
     * на основе массива $data_array
     *
     * return boolean
     */
    function wholeness_array(){
        if(is_array($this->data_code)){
            foreach($this->data_code as $key=>$value){

            }
        }
    }

    /**
     *  Парсинг файла
     *  Разделение строк массива, комментариев, начала и конца скрипта php     *
     *
     */
    function parse_file(){
        $this->content = file_get_contents($this->driver->filename);
        //var_dump($fp);
        //print_r($fp);
        //echo "<br> file: ".$fp." -<br>";
        if(strlen($this->content) > 0){
            $this->parse_php( & $this->content);
        }
    }



    function parse_php($string){
        $array1 = explode('<?', $string);
        $array2 = explode('?>', $string);


        foreach($array1 as $key1=>$value1){
            if(!empty($value1) && $pos = strpos($string, $value1)){
                if($this->check_syntax_php($value1)){
                    echo "Код соответствует PHP";
                    $verify = 1;
                }else{
                    echo "Код не соответствует PHP";
                    $verify = 0;
                }
                $arr1[] = array('start' => $pos, 'end' => strlen($value1), 'verify' => $verify, 'string' => trim($value1, 'php'));
            }
        }
        foreach($array2 as $key2=>$value2){
            if(!empty($value2) && $pos = strpos($string, $value2)){
                $arr2[] = array('start' => $pos, 'end' => strlen($value2), 'string' => $value2);
            }
        }

        foreach($arr1 as $keys=>$items){
            $range_string[$keys] = $this->search_range_string($arr2, $items['start'], $items['end'], $items['string']);
        }
        echo "<pre>Массив1:<br>";
        print_r($arr1);
        echo "</pre>";
        echo "<pre>Массив2:<br>";
        print_r($arr2);
        echo "</pre>";
        echo "<pre>Совпадения:<br>";
        print_r($range_string);
        echo "</pre>";
    }

    function search_range_string($array, $start, $end, $string){
        foreach($array as $key=>$items){
            if($items['start'] < $start && $items['end'] > $start ){
                $e[] = $key;
            }
            if($items['start'] < $end && $items['end'] > $end ){
                $e[] = $key;
            }
        }
        if(isset($e)) return $e;
        return false;
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

    /**
     * Возвращает разделитель для применении в качестве шаблона в регулярных выражениях
     * Если разделителей несколько то между ними будет установлен символ |
     * Все разделители которые имеют запрещенный символ предваряются знаком \
     *
     * @param string $string
     * @return string
     */
    function get_separator_for_pattern($string){
        if( ! is_array($string)) $string = array($string);

        foreach($string as $key=>$value){
            $arr[] = $this->get_escape_slash($value);
        }
        if(is_array($arr)) return implode("|", $arr);
        return false;
    }

    /**
     *  Возвращает строку запрещенных символов разделенных знаком |
     *
     * @param string $string
     * @return string
     */
    function get_escape_slash($string){
        //if(in_array($string, $this->escape_slash)){
        $words = implode("\\",$this->escape_slash);
        $pattern = '#(['.$words.'])#is';
        $replacement = '\\\\$1';
        //echo $pattern."<br>";
        $string = preg_replace($pattern, $replacement, $string);
        //preg_match($pattern, $string, $matches);
        //}
        //print_r($matches);

        return $string;
    }
}