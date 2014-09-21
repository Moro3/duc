<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class parse_phparray{


    /**
    *   Конечный результат распарсенного файла
    *   Отдельно html, код php разделен от комменитариев последовательно
    *
    */
    private $data_array;

    /**
    *   Новый результат
    *   Чистый массив
    */
    private $data_array_new;



    /**
     *  Содержимое файла
     * @var string
     */
    private $content;


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
     * Класс обработки массива php
     * @var object
     */
    public $driver;

    /**
     *  Класс построения строки из массива
     * @var object
     */
    private $build_string;

    /**
     *  Класс разделения строки на теги php
     * @var object
     */
    private $split_tags;

    /**
     *  Класс разделения строки кода PHP на комментарии
     * @var object
     */
    private $split_comments;

    function __construct($driver) {
        if(is_object($driver)){
            $this->driver = $driver;
        }else{
            exit('Не запущен драйвер для работы с конфигурационными данными массива PHP');
        }
        if( ! class_exists("phparray_build_string")){
            if(is_file(dirname(__FILE__)."/phparray/build_string.php")){
                include_once("phparray/build_string.php");
            }else{
                exit("Нет класса формирования массива в строки");
            }
        }
        $this->build_string = new phparray_build_string($this);

        if( ! class_exists("phparray_split_tags")){
            if(is_file(dirname(__FILE__)."/phparray/split_tags.php")){
                include_once("phparray/split_tags.php");
            }else{
                exit("Нет класса разделения строки на теги PHP");
            }
        }
        $this->split_tags = new phparray_split_tags($this);

        if( ! class_exists("phparray_split_comments")){
            if(is_file(dirname(__FILE__)."/phparray/split_comments.php")){
                include_once("phparray/split_comments.php");
            }else{
                exit("Нет класса разделения кода PHP на комментарии");
            }
        }
        $this->split_comments = new phparray_split_comments($this);
    }

    function get_content(){
        return $this->content;
    }

    function set_template($string){
        $this->build_string->set_template($string);
    }

    /**
     * Установка чистого массива для сравнения
     */
    function set_array_new($array){
        $this->data_array_new = $array;
        $this->set_log('Установлен новый "чистый" массив для анализа');
    }
    /**
     *  Проверяет синтаксис php из строки
     *
     * @param string $string
     * return boolean
     */
    function check_syntax_php($string){
        $str1 = 'return true;' . $string;
        // проверочная строка на случай если комментарий в конце и является не закрытым
        $str2 = 'return true;' . $string . '*/ $test = true;';

        if(@eval($str1)){
          if( ! @eval($str2)){
              return true;
          }
          //$this->set_error(__CLASS__, __METHOD__, 'info', 'Строка НЕ прошла проверку на синтаксис не закрытого комментария php');
        }else{
            //$this->set_error(__CLASS__, __METHOD__, 'info', 'Строка НЕ прошла проверку на синтаксис php');
        }
        return false;
    }

    /**
     *  Запуск процесса папрсинг файла
     *
     */
    function parse_file($filename){
        $this->content = file_get_contents($filename);
        $this->set_log('Запущен процесс парсинга файла '.$filename.' для масива с комментариями');

        if(strlen($this->content) > 0){
            $result_code = $this->split_tags->get_mark_split( $this->content);
            //$this->trim_separator( & $this->content);
            $this->data_array = $this->split_comments->get_array_split($result_code, $this->content);
        }

        $this->convert_php_code_array();

    }



    /**
     * Преобразование строки php кода в переменные
     */
    function convert_php_code_array(){
        $this->set_log('Запущен процесс преобразования строки кода PHP в переменные');
        if(is_array($this->data_array)){
            foreach($this->data_array as $key=>&$value){
                if(isset($value['php']) && is_array($value['php'])){
                    foreach($value['php'] as $key_data=>&$items){
                        if(isset($items['php'])){
                            $items['vars'] = $this->get_vars_string($items['php']);
                        }
                    }
                }
            }
        }
    }

    /**
     *  Возвращает в виде переменных строку php кода
     * @param string $T_string_T
     * @return array
     */
    function get_vars_string($T_string_T){
        @eval($T_string_T);
        unset($T_string_T);
        $arr = get_defined_vars();
        if(is_array($arr)) return $arr;
        return false;
    }




    /**
     *  Возвращает тип кода php
     * @param string $code
     */
    function get_type_code($string){
        return 'CODE';
    }

    /**
     *  Возвращает строку с за минусов начальных и конечных разделителей
     *  разделители удаляются только с начала и вконце строки, если они присутствуют
     *
     * @param string $string
     * @return string
     */
    function trim_separator($string){
        if( ! is_array($this->split_tags->start_separator)){
            $array1 = array($this->split_tags->start_separator);
        }else{
            $array1 = $this->split_tags->start_separator;
        }
        if( ! is_array($this->split_tags->end_separator)){
            $array2 = array($this->split_tags->end_separator);
        }else{
            $array2 = $this->split_tags->end_separator;
        }
        $array = array_merge($array1, $array2);
        //print_r($array);
        foreach($array2 as $key=>$value){
            if($pos = strpos($string, $value, strlen($string) - strlen($value))){
              $string = substr($string, 0, $pos);
            }
            //$string = trim($string, $value);
        }
        $string = ltrim($string, '<?php');
        return $string;
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

    /**
     * Формирование строки для записи в файл
     */
    function _build_string_save(){
        $this->set_log('Запущен процесс формирования массива данных в строку для записи');
        $this->_array_replace_vars();
        $arr = $this->get_array_new_collected();
        $arr_remainder = $this->array_diff_assoc_recursive($this->data_array_new, $arr);
        if(is_array($arr_remainder)){
            $this->array_push_remainder($arr_remainder);
        }
        return $this->build_string->generate_to_string($this->data_array);
    }

    /**
     *  Определяет является ли массив скалярный и идут ли числа по порядку
     *  Так же массив НЕ должен быть многоуровним
     *
     * @param array $array
     */
    function is_sequence_array_numeric($array){
        $count = count($array);
        //if((max($array) + 1) != $count) return false;
        $ii = 0;
        foreach($array as $key=>$value){
            if( ! is_numeric($key) || $key !== $ii){
                return false;
            }
            if(is_array($value)) return false;
            $ii++;
        }
        return true;
    }



    /**
     *  Добавление новых элементов в конец распарсенного массива
     * @param array $array
     */
    function array_push_remainder($array){
        $this->data_array[] = array('php' => array( array('vars_new' => $array)
                                                   ),
                                    'type' => $this->get_type_code($array),
                                    );
    }

    /**
     *  Замена переменных в массиве данных, данными из масива назначения
     */
    function _array_replace_vars(){
        foreach($this->data_array as &$items){
            if(isset($items['php']) && is_array($items['php'])){
                foreach($items['php'] as &$val){
                    if(isset($val['vars']) && is_array($val['vars'])){
                        //$arr[] = $this->_array_change($val['vars']);
                        $val['vars_new'] = $this->array_intersect_keys_recursive($val['vars'], $this->data_array_new);
                        //echo $val['vars_new']."<br>";
                    }
                }
            }
        }
    }


    // dwarven Differences:
// * Replaced isset() with array_key_exists() to account for keys with null contents

// 55 dot php at imars dot com Differences:
// Key differences:
// * Removed redundant test;
// * Returns false bool on exact match (not zero integer);
// * Use type-precise comparison "!==" instead of loose "!=";
// * Detect when $array2 contains extraneous elements;
// * Returns "before" and "after" instead of only "before" arrays on mismatch.

function _array_compare($array1, $array2) {
            $diff = false;
            // Left-to-right
            foreach ($array1 as $key => $value) {
                if (!array_key_exists($key,$array2)) {
                    $diff[0][$key] = $value;
                } elseif (is_array($value)) {
                     if (!is_array($array2[$key])) {
                            $diff[0][$key] = $value;
                            $diff[1][$key] = $array2[$key];
                     } else {
                            $new = $this->_array_compare($value, $array2[$key]);
                            if ($new !== false) {
                                 if (isset($new[0])) $diff[0][$key] = $new[0];
                                 if (isset($new[1])) $diff[1][$key] = $new[1];
                            }
                     }
                } elseif ($array2[$key] !== $value) {
                     $diff[0][$key] = $value;
                     $diff[1][$key] = $array2[$key];
                }
         }
         // Right-to-left
         foreach ($array2 as $key => $value) {
                if (!array_key_exists($key,$array1)) {
                     $diff[1][$key] = $value;
                }
                // No direct comparsion because matching keys were compared in the
                // left-to-right loop earlier, recursively.
         }
         return $diff;
    }

    function array_intersect_assoc_recursive(&$arr1, &$arr2) {
        if (!is_array($arr1) || !is_array($arr2)) {
            return $arr1 == $arr2; // or === for strict type
        }
        $commonkeys = array_intersect(array_keys($arr1), array_keys($arr2));
        $ret = array();
        foreach ($commonkeys as $key) {
            $ret[$key] =& $this->array_intersect_assoc_recursive($arr1[$key], $arr2[$key]);
        }
        return $ret;
    }

    /**
     *  Возвращает массив с параметрами из массива 1 которые присутствуют в массиве 2
     *  Если параметр является числовым массивом, возвращается весь список
     * @param array $arr1
     * @param array $arr2
     * @return array
     */
    function array_intersect_keys_recursive($arr1, $arr2) {
        if(is_array($arr2)){
            if(is_array($arr1)){
                if($this->array_numeric($arr1) && $this->array_numeric($arr2)){
                    $arr = $arr2;
                }else{
                    foreach($arr1 as $key=>$value){
                          if(isset($arr2[$key])){
                              $sub_arr = $this->array_intersect_keys_recursive($value, $arr2[$key]);
                              //if($sub_arr !== false){
                                    $arr[$key] = $sub_arr;
                              //}
                          }
                    }
                }
            }else{
              $arr = $arr2;
            }
        }else{
            if(isset($arr1)){
                $arr = $arr2;
            }
        }
        if(isset($arr)) return $arr;
        return false;
    }

    /**
    *  Проверяет являются ли все ключи массива числами
    *
    *
    */
	function array_numeric($array){
		if(is_array($array)){
			foreach($array as $key=>$value){
				if( ! is_numeric($key)) return false;
			}
			return true;
		}
		return false;
	}

    /**
     * Возвращает чистый собранный массив со старыми данными из распарсенного массива
     *
     * @return array
     */
    function get_array_collected(){
        $arr = array();
        if(is_array($this->data_array)){
        foreach($this->data_array as $items){
            if(isset($items['php']) && is_array($items['php'])){
                foreach($items['php'] as $val){
                    if(isset($val['vars']) && is_array($val['vars'])){
                        $arr = $this->array_merge_recursive_distinct($arr, $val['vars']);
                    }
                }
            }
        }
        }
        return $arr;
    }

    /**
     * Возвращает чистый собранный массив с новыми данными из распарсенного
     *
     * @return array
     */
    function get_array_new_collected(){
        $arr = array();
        foreach($this->data_array as $items){
            if(isset($items['php']) && is_array($items['php'])){
                foreach($items['php'] as $val){
                    if(isset($val['vars_new']) && is_array($val['vars_new'])){
                        $arr = $this->array_merge_recursive_distinct($arr, $val['vars_new']);
                    }
                }
            }
        }
        return $arr;
    }

	/**
     * Позволяет определить присутствуют ли в данных html вне тегов PHP
     *
     * @return array
     */
    function isset_html(){

        foreach($this->data_array as $items){
            if(isset($items['html'])){
                return true;
            }
        }
        return false;
    }

	/**
     * Позволяет определить присутствуют ли в данных комментарии php
     *
     * @return array
     */
	function isset_comments_php(){
		foreach($this->data_array as $items){
            if(isset($items['php']) && is_array($items['php'])){
                foreach($items['php'] as $val){
                    if(isset($val['comment'])){
                        return true;
                    }
                }
            }
        }
        return false;
	}

    /**
     *  Функция принимает несколько массивов и объеденяет их по принципу array_merge_recursive,
     *  но с заменой старых значений
     * @return array
     */
    function array_merge_recursive_distinct(){
        $arrays = func_get_args();
        $base = array_shift($arrays);

        if (!is_array($base)) $base = empty($base) ? array() : array($base);

        foreach ($arrays as $append) {
            if (!is_array($append)){
              $base = $append;
              //$append = array($append);
            }else{
              foreach ($append as $key => $value) {
                  if (!array_key_exists($key, $base) and !is_numeric($key)) {
                      $base[$key] = $append[$key];
                      continue;
                  }
                  if (is_array($value) or is_array($base[$key])) {
                      $base[$key] = $this->array_merge_recursive_distinct($base[$key], $append[$key]);
                  } else if (is_numeric($key)) {
                      if (!in_array($value, $base)) $base[] = $value;
                      //$base[$key] = $value;
                  } else {
                      $base[$key] = $value;
                  }
              }
            }
        }
        return $base;
    }



    function array_diff_assoc_recursive($array1, $array2)
    {
        foreach($array1 as $key => $value)
        {
            if(is_array($value))
            {
                  if(!isset($array2[$key]))
                  {
                      $difference[$key] = $value;
                  }
                  elseif(!is_array($array2[$key]))
                  {
                      $difference[$key] = $value;
                  }
                  else
                  {
                      $new_diff = $this->array_diff_assoc_recursive($value, $array2[$key]);
                      if($new_diff != FALSE)
                      {
                            $difference[$key] = $new_diff;
                      }
                  }
              }
              elseif(!isset($array2[$key]) || $array2[$key] != $value)
              {
                  $difference[$key] = $value;
              }
        }
        return !isset($difference) ? 0 : $difference;
    }

    /**
     *  запись логов
     * @param string $string
     */
    function set_log($string){
        $this->driver->driver->set_log($string);
    }
    /**
    *  Метод обработки ошибок
    *  @param string $class - класс
    *  @param string $method - метод
    *  @param string $level  - уровень ошибки
    *  @param string $message - сообщение
    */
    function set_error($class, $method, $level, $message){
         $this->driver->driver->set_error($class, $method, $level, $message);
    }
}




