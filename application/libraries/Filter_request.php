<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
     *
 *  Предполагается работа со след. операторами сравнения:
 *  Короткое значение 	расшифровка 	Аналог в SQL
        eq              Equal           field = ‘val’
        lt              Less            field > ‘val’
        gt              Great           field < ‘val’
        ne              Not equal 	field != ‘val’ или field <> ‘val’
        ge              Great or equal 	field >= ‘val’
        le              Less or equal 	field <= ‘val’
        bw              Begin with 	field like ‘val%’
        ew              End with 	field like ‘%val’
        cn              Contain 	field like ‘%val%’
 *
 */

class Filter_request {
    private $data = array();     // данные полученные в ходе обработки

    private $current_name;       // текущее имя(для порядка рекомендуется называть именем модуля)
    // возможные сравнения
    private $compare;
    private $separator_filter = '-SF-';  // разделитель фильтров
    private $equate_filter = '~EF~';     // строка равенства в фильтрах

    //возвращает объект с установленным текущим именем
    function __construct($name){
        $this->current_name($name);

        $this->compare = array('=' => 'where',
                             '<' => 'where',
                             '>' => 'where',
                             '<=' => 'where',
                             '>=' => 'where',
                             '!=' => 'where',
                             'in' => 'where_in',
                             'like' => 'like',
                             );
        $CI =& get_instance(); // to access CI resources, use $CI instead of $this
        $this->db =& $CI->db;

        return $this;
    }

    //установка текущего имени фильтра
    function current_name($name){
        $this->current_name = $name;
        $this->set_separator($this->separator_filter);
        $this->set_equate($this->equate_filter);
    }
    /*
    // установка строки фильтра
     * arg: $string - строка фильтра
     * return: boolean  true  - успех
     *                  false - неудача
     */
    function set_string($string){
       $this->data[$this->current_name]['string'] = $string;
       if(!empty($this->data[$this->current_name]['string'])) return true;
       return false;
    }

    /*
    // установка правильной строки фильтра (без мусора)
     * на основе отфильтрованного массива data_out в кэше
     * arg: $string - строка фильтра
     * return: boolean  true  - успех
     *                  false - неудача
     */
    function set_string_out($string){
       $this->data[$this->current_name]['string_out'] = $string;
       if(!empty($this->data[$this->current_name]['string_out'])) return true;
       return false;
    }

    /*
     * Установка исходных данных фильтра
     * arg: $data - массив с данными
     *              array('name_filter' => array('table' => 'имя таблицы',
     *                                           'field' => 'имя поля',
     *                                           'compare' => 'знак сравнения',
     *                                           'or' => boolean, // - условие or (опционально)
     *                                           'not'    => boolean   // отрицание в условие (опционально)
     *                                           'like_side'  => 'before || after || both (default)' // положение вставки при like (опционально)
     *                                          ),
     *                    )
     */
    function set_data($data){
        if(is_array($data)){
            foreach($data as $key=>$value){
                if(!empty($value['table']) && !empty($value['field'])){
                    if($this->is_field($value['table'], $value['field'])){
                        if(in_array($value['compare'],array_keys($this->compare))){
                            $this->data[$this->current_name]['data'][$key] = $value;
                        }
                    }
                }
            }
        }

        if (!empty($this->data[$this->current_name]['data'])) return true;

        return false;
    }

    /*
     *  проверяет на существование данного поля в таблице
     * arg: $table - имя таблицы
     *      $field - имя поля
     * return: boolean true  - есть поле
     *                 false - нет поля
     * !!!! доработка через схему!!!
     */
    function is_field($table, $field){
       return true;
    }

    /*
     * Установка разделителя фильтров
     * arg: $string - строка которая будет служить разделителем
     * return: boolean true  - успех
     *                 false - неудача
     */
    function set_separator($string){
        $this->data[$this->current_name]['separator'] = $string;
        if(!empty($this->data[$this->current_name]['separator'])) return true;
        return false;
    }

    /*
    // установка разделителя знака равенства в фильтрах
     * arg: $string - строка знака равенства
     * return: boolean  true  - успех
     *                  false - неудача
     */
    function set_equate($string){
       $this->data[$this->current_name]['equate'] = $string;
       if(!empty($this->data[$this->current_name]['equate'])) return true;
       return false;
    }

    /*
     *  Получение данных по строке фильтра
     *  return: array - массив с параметрами фильра которые имеют значения
     *          false - если нет значений ни одно из параметров
     */
    function get_data(){
        $this->parser_string();
        $this->parser_params();
        $this->generate_string_uri();
        if(isset($this->data[$this->current_name]['data_out']) && is_array($this->data[$this->current_name]['data_out'])){
            if(count($this->data[$this->current_name]['data_out']) > 0){
                return $this->data[$this->current_name]['data_out'];
            }
        }
        return false;
    }

    /*
     * Парсер строки  фильтра
     * Разделителем строки является разделитель фильтров $separator в кэше;
     */
    function parser_string(){
        if(!empty($this->data[$this->current_name]['string'])){
            $arr = explode($this->data[$this->current_name]['separator'], $this->data[$this->current_name]['string']);

        }
        if(isset($arr) && is_array($arr) && count($arr) > 0) {
           $this->data[$this->current_name]['data_filter'] = $arr;
        }
    }

    /*
     * Парсер параметров  фильтра
     * Разделителем параметров является разделитель equate в кэше;
     */
    function parser_params(){
        if(!empty($this->data[$this->current_name]['data_filter']) && is_array($this->data[$this->current_name]['data_filter'])){
            foreach($this->data[$this->current_name]['data_filter'] as $key=>$value){
                if(!empty($value)){
                    $arr = explode($this->data[$this->current_name]['equate'], $value);
                    if(!empty($arr[0]) && !empty($arr[1])){
                        if($this->name_filter($arr[0])){
                            $this->set_value($arr[0],$arr[1]);
                        }
                    }
                }
            }
        }

    }
    /*
     *  Возвращает правильную строку значения фильтра
     */
    function get_uri(){
        if(empty($this->data[$this->current_name]['string_out'])){
            $this->get_data();
        }
        if(!empty($this->data[$this->current_name]['string_out'])){
            return $this->data[$this->current_name]['string_out'];
        }
        return false;
    }

    /*
     *  Установка значения для данного фильтра
     *  arg: $field - имя поля фильтра
     *       $item   - значение фильтра
     *  return: boolean  true  - значение установлено
     *                   false - неудача     *
     */
    function set_value($field, $item){
       if($this->check_value($item)){
           if(!isset($this->data[$this->current_name]['data_out'][$field])){
                $this->data[$this->current_name]['data_out'][$field] = $this->data[$this->current_name]['data'][$field];
            }
            if($this->multi_value($field)){
                if(isset($this->data[$this->current_name]['data_out'][$field]['value']) && is_array($this->data[$this->current_name]['data_out'][$field]['value'])){
                    if(!in_array($item,$this->data[$this->current_name]['data_out'][$field]['value'])){
                        $this->data[$this->current_name]['data_out'][$field]['value'][] = $item;
                    }
                }else{
                    $this->data[$this->current_name]['data_out'][$field]['value'][] = $item;
                }
            }else{
                $this->data[$this->current_name]['data_out'][$field]['value'] = $item;
            }
            return true;
       }
       return false;
    }

    /*
     *  Проверка значения на запрещенные символы
     *  arg: $item - значение параметра
     *  return: boolean true  - всё хорошо
     *                  false - найдены запрещенные символы
     */
    function check_value($item){
        //print_r($item);
        $parent = '/^\W+/i';
        if(!preg_match($parent, $item)){
            return true;
        }
        return false;
    }

    /*
     *  Проверка на возможность содержания нескольких значений для фильтра
     *  arg: $filter - имя фильтра
     *  return: boolean  true  - есть возможность
     *                   false - нет возможности
     */
    function multi_value($filter){
        if(isset($this->data[$this->current_name]['data'][$filter])){
            if($this->data[$this->current_name]['data'][$filter]['compare'] == 'in'){
                return true;
            }
        }
        return false;
    }
    /*
     * Проверка на существование имени фильтра
     * arg: $name - имя фильтра
     * return: boolean
     */
    function name_filter($name){
       if(isset($this->data[$this->current_name]['data'][$name])){
          return true;
       }
       return false;
    }

    /*
     *  Конвертация GET запроса в строку фильтра
     */
    function convert_get_to_filter(){
        if(isset($_SERVER['QUERY_STRING'])){
            $query_string = $_SERVER['QUERY_STRING'];
            parse_str($query_string, $arr);
            $this->clean_query_string($arr);
        }
    }

    /*
     *  Обработка параметров строки запроса
     *  для записи в одну строку фильтра
     *  arg: $arr - массив из параметров get запроса
     *  return: boolean
     */
    function clean_query_string($arr){
        if(is_array($arr)){
            foreach($arr as $key=>$value){
                if(isset($this->data[$this->current_name]['data'][$key])){
                    if(is_array($value)){
                        foreach($value as $key2=>$value2){
                            $param = $this->get_str_param($key, $value2);
                            if($param){
                                $arr_param[] = $param;
                            }
                        }
                    }else{
                        $param = $this->get_str_param($key, $value);
                        if($param){
                            $arr_param[] = $param;
                        }
                    }
                }
            }
            if(isset($arr_param) && is_array($arr_param)){
                $str_filter = $this->get_str_filter($arr_param);

            }

        }
        if(!empty($str_filter)){
            $this->set_string ($str_filter);
            return true;
        }
        return false;
    }
    /*
     *  Возвращает строку сформированную из названия поля фильтра и значения
     *  выполняется проверка на правильность значения
     */
    function get_str_param($field, $value){
        if($this->check_value($value)){
            $str = $field.$this->data[$this->current_name]['equate'].$value;
        }
        if(isset($str)) return $str;
        return false;
    }

    /*
     *  Возвращает строку значения всего фильтра сформированную из массива готовых параметров фильтра
     *
     */
    function get_str_filter($arr){
        if(is_array($arr)){
            $str = implode($this->data[$this->current_name]['separator'], $arr);
        }
        if(isset($str)) return $str;
        return false;
    }

    /*
     *  Генерирует строку фильтра с правильными значениями
     */
    function generate_string_uri(){
        if(isset($this->data[$this->current_name]['data_out'])){
            foreach($this->data[$this->current_name]['data_out'] as $key=>$value){
                    if(is_array($value['value'])){
                        foreach($value['value'] as $key2=>$value2){
                            $param = $this->get_str_param($key, $value2);
                            if($param){
                                $arr_param[] = $param;
                            }
                        }
                    }else{
                        $param = $this->get_str_param($key, $value['value']);
                        if($param){
                            $arr_param[] = $param;
                        }
                    }
            }
            if(isset($arr_param) && is_array($arr_param)){
                $str_filter = $this->get_str_filter($arr_param);

            }
        }
        if(!empty($str_filter)){
            $this->set_string_out ($str_filter);
            return true;
        }
        return false;
    }
///======================================================================
//      Генерация запросов условия where для DB
//
//========================================================================

    /*
    //генерация условия запроса where для фильтров
    // arg: $filter - имя фильтра
    // return: true - если все условия сгенерировались правильно
    //         false - если условия содержат ошибки
     *
     */
    function generate_where(){

           //$this->current_name($filter);
           if(isset($this->data[$this->current_name]['data_out'])){
                foreach($this->data[$this->current_name]['data_out'] as $key=>$value){
                    if(!empty($value['or'])) $or = true; else $or = false;
                    if(!empty($value['not'])) $not = true; else $not = false;
                    $name = $value['table'].'.'.$value['field'];
                    $condition = $this->get_condition_filter($key, $or, $not);
                    $compare = $this->get_compare_filter($value['compare']);
                    $value_filter = $this->get_value_filter($key);

                    if($condition && $value_filter){
                        $this->db->$condition($name.$compare, $value_filter);
                    }

               }
               return true;
           }

       return false;
    }
    /*
    // возвращает знак сравнения для условия в зависимости от сравнения
    // arg: $compare - сравнение в данных
    // return: string - строка условия для генерации
    //
     *
     */
    function get_compare_filter($compare){
        $arr_add = array('<' , '>' , '<=' , '>=' , '!=');
        if(in_array($compare, $arr_add)){
            return " $compare";
        }
        return '';
    }
    /*
    // возвращает значение для условия в зависимости от сравнения
    // arg: $filter - имя фильтра
    // return: string || array - строка или массив значений для условия
    //
     *
     */
    function get_value_filter($filter){
        if(is_array($this->data[$this->current_name]['data_out'][$filter]['value'])){
           if(!$this->multi_value($filter)){
               return false;
           }
        }
        if(isset($this->data[$this->current_name]['data_out'][$filter]['func_in'])){
            $function_name = $this->data[$this->current_name]['data_out'][$filter]['func_in'];
            $result = $this->get_convert_function_in($function_name, $this->data[$this->current_name]['data_out'][$filter]['value']);
        }else{
            $result = $this->data[$this->current_name]['data_out'][$filter]['value'];
        }
        return $result;
    }

    /*
     *  Преобразование значения имеющейся функцией
     *  значение преобразуется только для выборки из базы
     *  arg: $function_name - имя функции
     *       $item - значение (аргумент)
     *  return: преобразованное значение если есть такая функция,
     *          если функции нет, возвращает неизмененные данные
     */
    function get_convert_function_in($function_name, $item){
        if(function_exists($function_name)){
            if(is_array($item)){
                foreach($item as $key=>$value){
                    $result[$key] = $function_name($value);
                }
            }else{
                $result = $function_name($item);
            }
        }
        if(!isset($result)){
            $result = $item;
        }
        return $result;
    }
    /*
    // возвращает условия для фильтра в зависимости от сравнения
    // arg: $compare - сравнение в данных
    // return: string - строка условия для генерации
    //         false - если условие не подходит
     *
     */
    function get_condition_filter($field, $or = false, $not = false){
        $compare = $this->data[$this->current_name]['data_out'][$field]['compare'];
        if(isset($this->compare[$compare])){
            if($this->compare[$compare] == 'where_in'){
                $str = $this->compare[$compare];
                if($not){
                    $str = 'where_not_in';
                }
                if($or){
                    $str = 'or_'.$str;
                }
            }
            if($this->compare[$compare] == 'like'){
                $str = $this->compare[$compare];
                if($not){
                    $str = 'not_like';
                }
                if($or){
                    $str = 'or'.$str;
                }
            }
            if(!empty($str)) {
                return $str;
            }else{
                return $this->compare[$compare];
            }
        }


        return false;
    }


    function get_test(){
        return $this->data;

    }
}