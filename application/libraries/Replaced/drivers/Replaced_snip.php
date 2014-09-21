<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Replaced_snip extends CI_Driver
{
    private $prefix = 'snip';          // префикс модуля
    private $params = array();          // имена параметров
    private $params_input;              // данные запроса
    private $f_start = '{';             // начальный флаг фрагмента
  	private $f_end = '}';               // конечный флаг фрагмента
    private $access;                    //разрешения для параметров
    private $separate_argument;       //разделители аргументов, если их больше 1

    /** метка, который указывает что аргументы ввода параметров могут применяються
    *   если его нет, то никакие аргументы при вызове не применяются
    *	если нужен аргумент по умолчанию, после знака он записывается один или через разделители
    *	пример:  ?arg1||arg2
    */
    private $mark_argumet = '?';

    public $CIC;

    function __construct()
	{
    	$this->load_data();
        $this->CIC = & get_instance();

    }

    /**
    * Загрузка переменных по умолчанию
    *
    */
    function load_data(){
    	$this->params = array('name', 'value');
		$this->access = array(
		                 'value' => array('id', 'name', 'alias', 'content', 'active'),
		);

		$this->separate_argument = ';';
		$this->params_input = array();
    }

    /**
    * возвращает список допустимых значений
    */
    public function get_allow_value(){    	if(isset($this->access['value'])) return $this->access['value'];
    	return false;
    }

    /**
    * Возвращает замененое содержимое если такое найдено в блоках
    * param - params - массив fragment - фрагмент строки под замену
                              params - имена с параметрами которые найдены в фрагменте
                              value - значения параметров. индексы соответвуют параметрам.

    */
    function get_content($params){
    	$this->params_input = $params;

    	//получаем порядковый номер ключа "name" во фрагменте
    	$key_name_tpl = array_keys($params['params'],'name');
        if(isset($key_name_tpl[0]) && !empty($params['value'][$key_name_tpl[0]])){
            //получаем данные шаблона модуля по значению ключа "name"
            $data = $this->get_data($params['value'][$key_name_tpl[0]]);
        }
		/*
		echo 'класс: '.__CLASS__.'<br>метод: '.__METHOD__.'<br>';
		var_dump($data);
		exit;
        */
        //получаем параметр value
        $key_value_tpl = array_keys($params['params'],'value');
        //по умолчанию записываем параметр value = uri
        //если параметр value задан, проверяем на доступ и записываем данное значение
        $value = 'content';
        if(isset($key_value_tpl[0]) && !empty($params['value'][$key_value_tpl[0]])){
            if(in_array($params['value'][$key_value_tpl[0]], $this->access['value']))
            $value = $params['value'][$key_value_tpl[0]];
            $this->uri_correct = false;
        }
        //проверяем есть ли данное поле в данных и присваиваем его в результат
        if(is_array($data)){            if(isset($data[$value])) {
        		$result = $data[$value];
        		//$result = Modules::run('replace/replace/get_content', $data[$value]);
        	}
        }

        $this->load_data();

        //var_dump($result);
        //exit;
        //если результат пустой, выходим
        if(empty($result)) return;
        return $result;
    }

    /**
    * Возвращает данные шаблона модулей по полю name
    * @param string||numeric $name - id или name
    *
    * @return array - возвращает данные с разрешенными параметрами
    */
    private function get_data($name){    	return Modules::run('snippets/snippets/get_data_template', $name);
    }

	/**
	*	Возвращает шаблон для поиска фрагментов вставки
	*
	*/
    function get_pattern_split_fragment(){    	return '|'.$this->f_start.''.$this->prefix.' ([^'.$this->f_end.$this->f_start.']*)'.$this->f_end.'|Uis';
    }

	/**
	*	Возвращает шаблон для поиска фрагментов параметров
	*
	*/

    function get_pattern_parse_fragment(){
    	//$pattern = '';
		if(count($this->params) > 0){
		  foreach($this->params as $item){
		    //$pattern .= '[]*'.$item.'[]*=[]*[\"]?([^'.$this->f_end.$this->f_start.'\"\']*)[\"]?';
		  }
		  $params = implode("|",$this->params);
		}
		if(!empty($params)){
		  $pattern = '%('.$params.')[ ]*=[ ]*["|\']*[ ]*([\w-_\.:;]*)[ ]*["|\']*[ ]*%is';
		  //$pattern = '%('.$params.')[ ]*=[ ]*["|\']([\w-_\. :;]*)["|\'][ ]*%is';

		}else{
		  $pattern = '%%is';
		}
		return $pattern;
    }

    /** возвращает массив из аргументов, которые используются по умолчанию
    *	$param $field_arg - значение поля arg
    *	return array
    */
    function get_argument_default($field_arg){       if($this->is_argument($field_arg)){       		$pos = strpos($field_arg, $this->mark_argumet);
       		$rest = substr($field_arg, $pos + strlen($this->mark_argumet));
       }else{       	    $rest = $field_arg;
       }
       if(!empty($rest)){       	  $arr_rest = $this->convert_argument_string_in_array($rest);
       }else{       	 $arr_rest = array();
       }

       if(!empty($arr_rest)) return $arr_rest;
       return array();
    }

    /**
    *  Преобразует строку из аргументов в массив
    */
    function convert_argument_string_in_array($args){    	$arr = explode($this->separate_argument, $args);
    	foreach($arr as &$arg){    		trim($arg);
    	}
    	return $arr;
    }

    /** возвращает массив из аргументов, которые введены в запросе
    *	$param $field_arg - значение поля arg
    *	return array
    */
    function get_argument_input(){
       $args = $this->get_param_input('arg');

       if(!empty($args)){   			$arr_args = $this->convert_argument_string_in_array($args);
       }

       if(isset($arr_args)) return $arr_args;
       return array();
    }

    /** используются аргументы или нет
    *	$param $field_arg - значение поля arg
    *	return boolean
    */
    function is_argument($field_arg){       $pos = strpos($field_arg, $this->mark_argumet);
       if($pos === 0) return true;
       return false;
    }

    /** используются аргументы по умолчанию или нет
    *	$param $field_arg - значение поля arg
    *	return boolean
    */
    function is_argument_default($field_arg){
         $arr = $this->get_argument_default($field_arg);
         if(is_array($arr) && count($arr) > 0) return true;
         return false;
    }

    /**
    *  Получение нужного параметра из запроса
    *	если он разрешен и введен
    *	$param $name - имя параметра
    *	$return text - значение параметра
    *			false - если параметр не задан или запрещен
    */
    function get_param_input($name){    	//получаем параметр
        $key_value_tpl = array_keys($this->params_input['params'],$name);

        if(isset($key_value_tpl[0]) && !empty($this->params_input['value'][$key_value_tpl[0]])){
            if(in_array($name, $this->access['value']))
            $value = $this->params_input['value'][$key_value_tpl[0]];
        }

        if(isset($value)) return $value;
        return false;
    }
}