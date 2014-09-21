<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Control_uri {
  private $current_segment;
  private $uri_string;      //строка запроса
  private $url;             //полный url запроса
  private $request_uri;           //строка запроса
  private $query;           //строка параметров запроса
  private $query_string;    //строка параметров запроса (псевдоним)
  private $anchor;          //анкор запроса (то что после #)
  private $max_segments;
  private $arr_segments;
  private $params_segments;
  private $uri_use = array();
  protected $ci;

    function __construct(){
        $this->ci =& get_instance();
        //parent::__construct();
        //$this->load->library('uri');
        $this->parser_uri();
        //$this->load->library('uri_generation');

    }

    /**
    * Парсинг uri строки
    *
    */
    function parser_uri(){
		//$this->uri_string = $this->ci->uri->uri_string();
		$this->set_uri_string();
        $this->current_segment = 1;
        $this->_param_uri();
        //$this->max_segments = $this->ci->uri->total_segments();
        $this->max_segments = count($this->params_segments);
        $this->arr_segments = $this->ci->uri->segment_array();
    }

	/**
	* Установка строки запроса
	*	Вычисляется автоматически из текущей строки запроса
	*	@param $uri - строка запроса если требуется иная от текущей
	*
	*/
    function set_uri_string($uri = false){    	if($uri === false){
    		$uri = $_SERVER['REQUEST_URI'];
    	}
        //echo 'Uri из Control_uri set_uri_string: '.$uri;
        $http = 'http://';
        $server = $_SERVER['SERVER_NAME'];
        $full_url = $http.$server.$uri;
        $this->url = $full_url;
        $p = parse_url($full_url);
        //echo '<br>path: '.$p['path'];
        $this->uri_string = $p['path'];
        $this->request_uri = $this->uri_string;
        $this->query = (!empty($p['query'])) ? $p['query'] : '';
        $this->query_string = $this->query;
        $this->anchor = (!empty($p['fragment'])) ? $p['fragment'] : '';
        //$this->uri_string = $uri;
    }

    /**
    * Вычисление параметров строки uri
    *
    *
    */
    function _param_uri(){
    	$arr = explode('/', $this->uri_string);
    	$segments = array();
    	$n = 0;
    	$k = count($arr);
    	foreach($arr as $key=>$items){    		if($n == 0) {    			$n++;
    			continue;
    		}
    		if($n > 1){
    			if(empty($items)) $segments[$n - 1]['slash'] = false;
    			$segments[$n - 1]['slash'] = true;
    		}
    		if(empty($items) && $key >= ($k-1)) break;
    		$segments[$n]['string'] = $items;
    		$segments[$n]['slash'] = false;

    		$n++;
    	}
    	//print_r($segments);
    	$this->params_segments = $segments;
    	//exit;
    }

	/**
	* Возвращает текущий сегмент
	*
	*/
	function get_current(){
	  return $this->current_segment;
	}
	/**
	* Возвращает uri строку
	*/
	function get_uri($param = 'uri_string'){
	  if(isset($this->$param)) return $this->$param;
	  return false;
	}
	/**
	* Возвращает максимальное кол-во сегментов в строке uri (включая игнорируемые)
	*/
	function get_max_segments(){
	  return $this->max_segments;
	}
	/**
	* Возвращает массив из сегментов строки uri
	*/
	function get_all_segments(){
	  return $this->arr_segments;
	}
	/**
	* Возвращает установленные параметры для текущего uri
	*/
	function get_uri_use(){
	  return $this->uri_use;
	}

	// Установка сегмента uri
	// arg: $class - класс (контроллер)
  //      $method - метод класса (контроллера)
  //      $arg - аргументы метода
	function set_segment($class, $method = false, $arg = false){
    //echo "##";
    $class = $this->UpLow($class);
    $method = $this->UpLow($method);
    if(!empty($class)){
      if($this->_get_num_segment($class, $method, $arg) == false){
        if(!empty($arg)){
          if (!is_array($arg)){
            $arg = array($arg);
          }
        }
        $this->uri_use[$this->current_segment] = array(
                                                      'class' => $class,
                                                      'method' => $method,
                                                      'arg' => $arg
                                                      );


        $this->current_segment ++;
	    }
	  }
	}


  // возвращает порядковый номер сегмента
	function _get_num_segment($class, $method, $arg){
    $class = $this->UpLow($class);
    $method = $this->UpLow($method);
    if (count($this->uri_use) > 0){
	    foreach($this->uri_use as $key=>$value){
        if($value['class'] == $class){
          if(empty($method) && empty($value['method'])){
            //echo "{$value['method']} - ???<br />";
            return $key;
          }else{
            if(empty($arg)){
              if($value['method'] == $method){

                return $key;
              }
            }else{

              if(!is_array){
                $arg = array($arg);
              }
              if(in_array($arg, $value['arg'])){
                return $key;
              }
            }
          }
        }
	    }
	  }else{
	    return false;
	  }
	  return false;
	}

	// возвращает сегмент если он присутствует в uri
  // arg: $class - класс (контроллер)
  //      $method - метод класса (контроллера)
  //      $arg - аргументы метода
	function get_segment($class, $method = false, $arg = false){
    //print_r($this->arr_segments);
    $nn = $this->_get_num_segment($class, $method, $arg);

    if($nn != false){
      if(isset($this->arr_segments[$nn])){
        return $this->arr_segments[$nn];
      }
	  }else{
	    return false;
	  }
	}

	// возвращает зарегистрированный сегмент
  // arg: $class - класс (контроллер)
  //      $method - метод класса (контроллера)
  //      $arg - аргументы метода
	function get_regsegment($class, $method = false, $arg = false){
    $nn = $this->_get_num_segment($class, $method, $arg);
    if($nn != false){
      if(isset($this->arr_segments[$nn])){
        return $this->arr_segments[$nn];
      }
	  }else{
	    return false;
	  }
	}

	// возращает сегмент по его порядковому номеру перезаписанного URI
	function rsegment($n) {
	  return $this->ci->uri->rsegment($n);
	}

	// возращает сегмент по его порядковому номеру
	function segment($n) {
	  return $this->ci->uri->segment($n);
	}


	// возвращает номер сегмента в порядке регистрации
  // arg: $class - класс (контроллер)
  //      $method - метод класса (контроллера)
  //      $arg - аргументы метода
	function num_segment($class, $method = false, $arg = false){
    $nn = $this->_get_num_segment($class, $method, $arg);
    if($nn != false){
      return $nn;
	  }else{
	    return false;
	  }
	}

  // возвращает путь от корня сайта с учетом зарегестрированных сегментов
  // arg: $class - класс (контроллер)
  //      $method - метод класса (контроллера)
  //      $arg - аргументы метода
	function get_full_segment($class, $method = false, $arg = false){
    $nn = $this->_get_num_segment($class, $method, $arg);
    if($nn != false){
      //echo $nn;
      $path = '';
      if(isset($this->arr_segments[$nn])){
        foreach($this->arr_segments as $key=>$value){
          $path .= $value;
          if($nn == $key) break;
          $path .= "/";
        }
      }
    }else{
      $path = '';
      /*
      if(isset($this->arr_segments[$this->current_segment-1])){
        foreach($this->arr_segments as $key=>$value){
          $path .= $value;
          if($this->current_segment-1 == $key) break;
          $path .= "/";
        }
      }
      */
      $path .= "/";
	  }
	  $path = trim($path,"/");
    if(!empty($path)) $path = "/".$path."/"; else $path = "/";
    return $path;
	}

	/**
	* Возвращает путь от выбранного сегмента до последнего
	*
	*/
	public function get_segments_next($class, $method = false, $arg = false){		$nn = $this->_get_num_segment($class, $method, $arg);
	    if($nn != false){
	      //echo $nn;
	      $path = '';
	      if(isset($this->params_segments[$nn])){
	        foreach($this->params_segments as $key=>$value){
	          if($nn <= $key) {	          	$path .= $value['string'];
	          	if($value['slash'] == true)
	          	$path .= "/";
	          }
	        }
	      }
	    }else{	    	$path .= "/";
	    }
	  	//$path = trim($path,"/");
    	//if(!empty($path)) $path = "/".$path."/"; else $path = "/";
   		$path = '/'.ltrim($path,"/");
   		return $path;

	}
	// возвращает следующий сегмент
	function get_next_segment($class, $method = false, $arg = false){

	}

	// загрузка
	function load_link($modul, $config){

	}



   function UpLow($string,$registr='low'){
    $upper = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //$upper = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
    $lower = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяabcdefghijklmnopqrstuvwxyz';
    //$lower = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
    if($registr == 'up') $string = strtr($string,$lower,$upper);
    if($registr == 'low') $string = strtr($string,$upper,$lower);
    return $string;
  }

  function guri($name){
    $obj = $this->ci->load->library('uri_generation', $name);
    $obj->current_name($name);
    //return $this->load->library('uri_generation', $name);
    return $obj;
    //return $this->uri_generation->use($name);
  }

// возвращает объект фильтра
  function gfilter($name){

    $obj = $this->ci->load->library('filter_request', $name);
    $obj->current_name($name);
    //return $this->load->library('uri_generation', $name);
    return $obj;
    //return $this->uri_generation->use($name);
  }


}


