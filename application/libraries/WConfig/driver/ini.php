<?php

class config_ini{
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
        //$arr = parse_ini_file($this->driver->filename, true);
        /*
        $verbose = true;
        $str = file_get_contents($this->driver->filename);
        $arr = $this->get_tokens_from_ini_lexer($str, $verbose);
        */

        $arr = $this->parse($this->driver->filename, true);

        if(is_array($arr)) return $arr;
        return false;


    }

    /**
     * Построение данных в строку для записи
     */
    function _build_string_save(){
        $printer = '';
        foreach($this->driver->data as $key=>$value){
            $printer .= '['.$key.']'."\r\n";
            //foreach($value as $key=>$val){

                $printer .= $this->write_get_string($value, '')."\n";
            //}


        }
        $printer .= "\r\n";
        return $printer;
    }

    function get_ini_array($array, $str = ''){
        //$str = $index;
        foreach($array as $key=>$value){
            $str .= '['.$key.']';
            //$str .= '['.$key.']';
            if(is_array($value)){
                $str .= $index.$this->get_ini_array($value, $str);
            }else{
                $str .= " = ".$value."\r\n";
            }


        }
        return $str;
    }



    function write_get_string(& $ini, $prefix) {
        $string = '';
        if(!empty($prefix)) $start_p = '.'; else $start_p = '';
        if(!empty($prefix)) $end_p = ''; else $end_p = '';

        ksort($ini);
        foreach($ini as $key => $val) {
            if (is_array($val)) {
                $string .= $this->write_get_string($ini[$key], $prefix.$start_p.$key.$end_p);
            } else {
                //$string .= $prefix.$key.' = '.str_replace("\n", "\\\n", $this->set_value($val))."\n";
                $string .= $prefix.$start_p.$key.$end_p.' = '.$val."\r\n";
            }
        }
        return $string;
    }

    /**
     *  manage keys
     */
    function set_value($val) {
        if ($val === true) { return 'true'; }
        else if ($val === false) { return 'false'; }
        return $val;
    }


    function get_tokens_from_ini_lexer($data, $verbose = FALSE)
    {
        $regexp = '/
        (?<=^|\r\n|\r|\n)
        (?P<line>
            (?:
                (?(?![\t\x20]*;)
                    (?P<left_space>[\t\x20]*)
                    (?:
                        \[(?P<section>[^;\r\n]+)\]
                        |
                        (?P<setting>
                            (?P<key>
                                [^=;\r\n]+?
                            )?
                            (?P<left_equal_space>[\t\x20]*)
                            (?P<equal_sign>=)
                            (?P<right_equal_space>[\t\x20]*)
                            (?P<val>
                                \x22(?P<quoted>.*?)(?<!\x5C)\x22
                                |
                                \x27(?P<apostrophed>.*?)(?<!\x5C)\x27
                                |
                                (?P<null>null)
                                |
                                (?P<bool>true|false)
                                |
                                (?P<int>[+-]?(?:[1-9]\d{0,18}|0))
                                |
                                (?P<float>(?:[+-]?(?:[1-9]\d*|0))\.\d+(?:E[+-]\d+)?)
                                |
                                (?P<string>[^;\r\n]+?)
                            )?
                        )
                    )
                )
                (?P<right_space>[\t\x20]*)
                (?:
                    (?P<comment_seperator>;)
                    (?P<comment_space>[\t\x20]*)
                    (?P<comment>[^\r\n]+?)?
                )?
            )
            |
            (?P<error>
                [^\r\n]+?
            )
        )
        (?=\r\n|\r|\n|$)(?P<crlf>\r\n|\r|\n)?
        |
        (?<=\r\n|\r|\n)(?P<emptyline>\r\n|\r|\n)
        /xsi';

        if(!@is_int(preg_match_all($regexp, $data, $tokens, PREG_SET_ORDER)))
        {
            // parse error
        }
        else
        {
            foreach($tokens as $i => $token)
            {
                if(!$verbose)
                {
                    unset($tokens[$i]['line']);
                    unset($tokens[$i]['crlf']);
                    unset($tokens[$i]['setting']);
                    unset($tokens[$i]['equal_sign']);
                    unset($tokens[$i]['val']);
                    unset($tokens[$i]['left_space']);
                    unset($tokens[$i]['left_equal_space']);
                    unset($tokens[$i]['right_equal_space']);
                    unset($tokens[$i]['right_space']);
                    unset($tokens[$i]['comment_seperator']);
                    unset($tokens[$i]['comment_space']);
                };

                foreach($token as $key => $val)
                {
                    if(!@is_string($key) || !@strlen($val))
                    {
                        unset($tokens[$i][$key]);
                    };
                };
            };

            return($tokens);
        };
    }

    //----------------------------------------
    // Обработка массива разделенным (.)
    public function parse($filename) {
        $ini_arr = parse_ini_file($filename);
        if ($ini_arr === FALSE) {
            return FALSE;
        }
        $this->fix_ini_multi(&$ini_arr);
        return $ini_arr;
    }

    private function fix_ini_multi(&$ini_arr) {
        foreach ($ini_arr AS $key => &$value) {
            if (is_array($value)) {
                $this->fix_ini_multi($value);
            }
            if (strpos($key, '.') !== FALSE) {
                $key_arr = explode('.', $key);
                $last_key = array_pop($key_arr);
                $cur_elem = &$ini_arr;
                foreach ($key_arr AS $key_step) {
                    if (!isset($cur_elem[$key_step])) {
                        $cur_elem[$key_step] = array();
                    }
                    $cur_elem = &$cur_elem[$key_step];
                }
                $cur_elem[$last_key] = $value;
                unset($ini_arr[$key]);
            }
        }
    }

}
