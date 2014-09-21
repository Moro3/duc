<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Класс контроля за передачей параметров в строке uri
// 1) Позволяет расширить способ передачи запроса
//    варианты: get    - методом get запросов ( name=value & name2=value2 )
//              method - разделителем будет являтся знак '/', т.е. как при передачи методов класса
//              dot    - разделителем является точка '.'
//
//  Все параметры запросов формируются в одном файле, который должен быть обязательно загружен после создания объекта
//  загрузка файла производится через метод set_index()
//  формат конфигурационного файла:
//              $arr['index'] = array('name' =>      имя параметра запроса (для get апросов будет являться ключом)
//                                    'uri_mode' =>  способ передачи uri('method' - разделитель '/'
//                                                                       'get' - в виде get запросов
//                                                                       'dot' - разделитель '.'
//                                                                      )
//                                         )
//  Все параметры должны иметь один способ передачи запроса,
//  исключение составляет способ 'get', он может использоваться совместно с любым
//
// 2) Позволяет автоматически создавать uri для проекта основываясь на сконфигурированным параметрах,
//    что позволяет строить uri запросы по вашему требованию не меняя отображения
//
// 3) Генерация запросов с параметрами доподленно с неизвестными значениями
//    Для того чтобы сформировать uri с неизвестным пока в контроллере значением (например если используется цикл в отображении)
//    при вызове генерации uri в поле значение неизвестного параметра ставится знак ?
//    далее чтобы изменить данное значение в отображении на нужное применить helper:uri
//    при помощи  функцией uri_replace() заменить в указанном uкi на нужные значения
//
// 4) Позволяет на основании переданных uri производить обработку данных в контроллере в автоматическом режиме
//    Чтобы использовать данную опцию необходимо будет сформировать массив с параметрами значений для каждого индекса запроса
//    В дальнейшем его передать через метод set_param()
//    и воспользоваться методом value_segment() получения значения нужного индекса
//
//

class Uri_generation {
  private $data = array();   // полученные данные
  private $flag_replace = '!'; // флаг замены неизвестных параметров

  // флаг неизвестного параметра
  // при замене известными значением данного параметра при генерации строки uri
  private $flag_param_unknown = '?';

  private $use_replace_index = true;   // заменять неуказанные индексы пустыми значениями
                               // (если при формировании uri при способе method или dot не указано значение какого либо индекс, оно будет заменено на пустое)
                               // если стоит false, значение будет проигнорировано и строка будет сконфигурирована без данного индекса
                               // !!! Будьте осторожны при значении false! Если после проигнорированого индекса идут ещё индексы с такими же способами передачи, интерпретация строки будет искажена
  private $flag_empty_index = '-';   // флаг замены неустановленных ндексов при формировании uri (param1/param2/-/param3)

  private $current_name;       // текущее имя(для порядка рекомендуется называть именем модуля)

  //возвращает объект с установленным текущим именем
  function __construct($name)
	{
    $this->current_name($name);

    return $this;
	}

  //установка текущего имени запроса
  function current_name($name){
    $this->current_name = $name;
    //$this->init();
  }

  /**
  * Инициализация
  * @param array - массив с параметрами вызова
  *				uri - строка uri
  *				start_segment - точка начала сегмента для учёта
  */
  function init($config = array()){  	 if(is_array($config) && count($config) > 0){  	 	foreach($config as $key=>$value){  	 		$this->set_param($key, $value);
  	 	}
  	 }
  	 $this->parse_uri();
  }

	/**
	* Установка uri
	* Вызывается до инициализации, если нужно задать строку uri принудительно, а не через автоопределения из url
	* Применение для ajax запросов со скрытием координат маршрута
	*
	*/
	function set_uri($uri){		$this->data[$this->current_name]['uri_request'] = $uri;
	}
  /**
  * Установка параметра приложения
  */
  private function set_param($name, $value){  	$this->data[$this->current_name][$name] = $value;
  }

  /**
  * Возвращает значение параметра приложения
  */
  public function get_param($name){  	if(isset($this->data[$this->current_name][$name])){  		return $this->data[$this->current_name][$name];
  	}
  }
  //заменять неуказанные индексы пустыми значениями?
  // true - по умолчанию
  // false - отмена использования данной опции
  function replace_empty_index($use = true){
    if($use == false) $this->use_replace_index = false;

  }

  //установка параметров uri
  //arg: $arr - массив с конфигурационнными параметрами
  //            формат: $arr['index'] = array('name' =>      имя параметра запроса (для get апросов будет являться ключом)
  //                                          'uri_mode' =>  способ передачи uri('method' - разделитель '/'
  //                                                                             'get' - в виде get запросов
  //                                                                             'dot' - разделитель '.'
  //                                                                            )
  //                                         )
  //
  function set_index($arr){
    if(is_array($arr)){
      $num = 0;
      $nd = 0;
      foreach($arr as $key=>$value){

        if(isset($value['uri_mode'])){
          if($value['uri_mode'] == 'method'){
            $num++;
            $nn = $num;
          }elseif($value['uri_mode'] == 'dot'){
            $nd++;
            $nn = $nd;
          }elseif($value['uri_mode'] == 'get'){
            $nn = false;
          }
          $arr[$key] = array('name' => $value['name'],
                                'uri_mode' => $value['uri_mode'],
                                'num_segment' => $nn,
                                );
        }
      }
    }

    if(isset($arr)) $this->data[$this->current_name]['config']['index'] = $arr;
  }

  //получение разделителя для конкретного индекса
  function separator($index){
    if(isset($this->data[$this->current_name]['config']['index'])){
      foreach($this->data[$this->current_name]['config']['index'] as $key=>$value){
        if($index == $key && isset($value['uri_mode'])){
          switch($value['uri_mode']){
            case 'method':
              return '/';
              break;
            case 'get':
              return  '&'.$value['name'].'=';
              break;
            case 'dot':
              return '.';
              break;
          }
        }
      }
    }
    return false;
  }

  // устанавливает номер сегмента относительно которого начинать обработку uri
  // если не задан обработка идет с начала uri
  // arg: $segment - №  сегмента
  // Если в проекте uri модуля реализуется не сначало uri,
  // установите номер сегмента с которого следует начинать обработку
  function set_start_segment($segment){
    if(is_numeric($segment)){
      $this->data[$this->current_name]['config']['start_segment'] = $segment;
    }
  }

  // возвращает значение индекса если он присутствует в строке uri
  // arg: $index - имя индекса (параметра)
  // return: string - строка значение
  //         false - параметр в строке uri не обнаружен
  function value_segment($index){
    //инициализируем приложение, т.к. требуются значения uri строки
    $this->init();
    //print_r($this->data);
    if(isset($this->data[$this->current_name]['config']['index'][$index]['uri_mode'])){
      if($this->data[$this->current_name]['config']['index'][$index]['uri_mode'] == 'get'){
        return $this->value_segment_get($index);
      }else{
        $this->current_uri();
        if($this->data[$this->current_name]['config']['index'][$index]['uri_mode'] == 'method'){
          return $this->value_segment_method($index);
        }elseif($this->data[$this->current_name]['config']['index'][$index]['uri_mode'] == 'dot'){
          return $this->value_segment_dot($index);
        }
      }
    }
    return false;
  }

  //возвращает значение параметра индекса в запросе 'get' способом
  //arg : $index - имя индекса
  //return: tring - начение параметра
  //        false - если значение параметра не задано
  function value_segment_get($index){
    if(isset($this->data[$this->current_name]['uri_str_array'][$this->data[$this->current_name]['config']['index'][$index]['name']])){
       return $this->data[$this->current_name]['uri_str_array'][$this->data[$this->current_name]['config']['index'][$index]['name']];
    }else{
       return false;
    }
  }
  //возвращает значение параметра индекса в запросе 'dot' способом
  //arg : $index - имя индекса
  //return: tring - начение параметра
  //        false - если значение параметра не задано
  function value_segment_dot($index){
    $nn = $this->data[$this->current_name]['config']['index'][$index]['num_segment'] - 1; //номер сегмента в базе индексов (-1 ,т.к. массив индексов начинается с 0)
    if(isset($this->data[$this->current_name]['uri_dot_array'][$nn])){
       return $this->data[$this->current_name]['uri_dot_array'][$nn];
    }else{
       return false;
    }
  }
  //возвращает значение параметра индекса в запросе 'method' способом
  //arg : $index - имя индекса
  //return: tring - начение параметра
  //        false - если значение параметра не задано
  function value_segment_method($index){
    $nn = $this->data[$this->current_name]['config']['index'][$index]['num_segment'] - 1; //номер сегмента в базе индексов (-1 ,т.к. массив индексов начинается с 0)
    if(isset($this->data[$this->current_name]['uri_method_array'][$nn])){
       return $this->data[$this->current_name]['uri_method_array'][$nn];
    }else{
       return false;
    }
  }

  // возвращает значение индекса с разделителем на основе способа передачи если он присутствует в строке uri
  // arg: $index - имя индекса (параметра)
  // return: string - строка
  //         false - параметр в строке uri не обнаружен
  function uri_segment($index){
    $value = $this->value_segment($index);
    if($value){
      return $this->separator($index).$value;
    }
  }

  //возвращает порядковый номер следования индекса
  function get_num_index($index){
    if(!empty($this->data[$this->current_name]['config']['index'][$key]['num_segment'])){
      return $this->data[$this->current_name]['config']['index'][$key]['num_segment'];
    }
    return false;
  }

  //возвращает номер начального отчета сегментов
  function get_start_segment(){
    if (isset($this->data[$this->current_name]['config']['start_segment'])){
      return $this->data[$this->current_name]['config']['start_segment'];
    }
    return false;
  }

  //возвращает сгенерированную строку запроса uri
  function get_uri($index, $value = ''){
    $this->parse_uri();
    if( ! is_array($index)){
      $index = array($index=>$value);
    }

    foreach($this->data[$this->current_name]['config']['index'] as $index_key=>$index_value){
      foreach($index as $index_in=>$value_in){
        if($index_key == $index_in){
          $arr[$index_key] = $value_in;
        }
      }
    }

    $uri = $this->generate_uri($arr);

    $this->data[$this->current_name]['uri_base'][$uri] = $arr;

    return $uri;
  }

  // генерация запроса uri для всей строки
  private function generate_uri($index_arr){

    foreach($index_arr as $key=>$value){
      if(isset($this->data[$this->current_name]['config']['index'][$key])){
        if($this->data[$this->current_name]['config']['index'][$key]['uri_mode'] == 'get'){
          $arr_get[$key] = $value;
        }elseif($this->data[$this->current_name]['config']['index'][$key]['uri_mode'] == 'method'){
          $arr_method[$key] = $value;
        }elseif($this->data[$this->current_name]['config']['index'][$key]['uri_mode'] == 'dot'){
          $arr_dot[$key] = $value;
        }
      }
    }
    $uri = '';

    //print_r($arr_method);
    if(isset($arr_method)) $uri .= $this->generate_method($arr_method);
    if(isset($arr_dot))    $uri .= $this->generate_dot($arr_dot);
    if(isset($arr_get)){
        $str_get = $this->generate_get($arr_get);
        if($str_get){
           $uri .= '?'.$str_get;
        }
    }

    return $uri;
  }
  //генерация строки запроса для 'method' способа
  private function generate_method($arr){

    foreach($arr as $key=>$value){
      if(isset($this->data[$this->current_name]['config']['index'][$key]['num_segment'])){
        if($value == '?'){
          $arr_new[$this->data[$this->current_name]['config']['index'][$key]['num_segment']] = $this->flag_replace;
        }else{
          $arr_new[$this->data[$this->current_name]['config']['index'][$key]['num_segment']] = $value;
        }
      }
    }
    if($this->use_replace_index){
      foreach($this->data[$this->current_name]['config']['index'] as $key=>$value){
        if(!isset($arr_new[$value['num_segment']]) && $value['uri_mode'] == 'method'){
          $arr_new[$value['num_segment']] = $this->flag_empty_index;
        }
      }
    }
    if(isset($arr_new)){
      ksort($arr_new);
      $res = implode('/',$arr_new);
      $res = trim($res,'/').'/';
    }
    if(isset($res)) return $res;
    return false;
  }
  //генерация строки запроса для 'dot' способа
  private function generate_dot($arr){
    foreach($arr as $key=>$value){
      if(isset($this->data[$this->current_name]['config']['index'][$key]['num_segment'])){
        if($value == '?'){
          $arr_new[$this->data[$this->current_name]['config']['index'][$key]['num_segment']] = $this->flag_replace;
        }else{
          $arr_new[$this->data[$this->current_name]['config']['index'][$key]['num_segment']] = $value;
        }
      }
    }
    if($this->use_replace_index){
      foreach($this->data[$this->current_name]['config']['index'] as $key=>$value){
        if(!isset($arr_new[$value['num_segment']]) && $value['uri_mode'] == 'dot'){
          $arr_new[$value['num_segment']] = $this->flag_empty_index;
        }
      }
    }
    if(isset($arr_new)){
      ksort($arr_new);
      $res = implode('.',$arr_new);
    }
    if(isset($res)) return $res;
    return false;
  }

  //генерация строки запроса для 'get' способа
  private function generate_get($arr){
    foreach($arr as $key=>$value){
      //var_dump($this->data[$this->current_name]['config']['index'][$key]['num_segment']);
      if(isset($this->data[$this->current_name]['config']['index'][$key]['num_segment'])){
          if($value == '?'){
              $arr_new[] = $this->data[$this->current_name]['config']['index'][$key]['name'].'='.$this->flag_replace;
          }elseif($value !== false){          	  $arr_new[] = $this->data[$this->current_name]['config']['index'][$key]['name'].'='.$value;
          }
      }
    }
    if(isset($arr_new)){
      ksort($arr_new);
      $res = implode('&',$arr_new);
    }
    if(isset($res)) return $res;
    return false;
  }

  /**
  * Парсер строки запроса
  *
  */
  function parse_uri(){
    if(!isset($this->data[$this->current_name]['uri_request'])){    	$this->data[$this->current_name]['uri_request'] = $_SERVER['REQUEST_URI'];
    }
    //echo 'строка запроса для ('.$this->current_name.'): ';
    //echo $this->data[$this->current_name]['uri_request'].'<br />';
    //$this->data[$this->current_name]['uri_request'] = $uri;
    $parse = parse_url($this->data[$this->current_name]['uri_request']);
    $uri_path = trim(@$parse['path'],'/');
    if(isset($parse['query'])) $uri_query = $parse['query']; else $uri_query = '';
    $this->data[$this->current_name]['uri_path'] = $uri_path;
    $this->data[$this->current_name]['uri_query'] = $uri_query;
    //парсим строку запроса если она есть
    if(!empty($this->data[$this->current_name]['uri_query'])){
      parse_str($this->data[$this->current_name]['uri_query'], $arr_str);

      $this->data[$this->current_name]['uri_str_array'] =  $arr_str;

    }

    //echo "path: ".$uri_path."<br>";
    //echo "query: ".$uri_query."<br>";
  }
  // формирование части uri для способов передачи 'dot' и 'method'
  function current_uri(){
    $uri_method = $this->data[$this->current_name]['uri_path'];
    $uri_method_arr = explode('/',$uri_method);

    if(isset($this->data[$this->current_name]['config']['start_segment'])){
      if($this->data[$this->current_name]['config']['start_segment'] <= count($uri_method_arr)){
        $segments = array_slice($uri_method_arr, $this->data[$this->current_name]['config']['start_segment'] - 1);
      }else{
        $segments = array();
      }
    }else{
      $segments = $uri_method_arr;
    }

    $uri_current = implode("/",$segments);
    //echo "<pre>";
    //print_r($this->data[$this->current_name]);
    //echo "</pre>";
    $uri_current_method = explode('/',$uri_current);
    $this->data[$this->current_name]['uri_method_array'] =  $uri_current_method;
    //print_r($uri_current_method);
    $uri_current_dot = explode('.',end($uri_current_method));
    $this->data[$this->current_name]['uri_dot_array'] =  $uri_current_dot;
    //print_r($uri_current_dot);
  }

  //замена неизвестных значений в строке uri
  //arg: $uri - строка запроса
  //     $replace - заменяемое значение
  //                если значений несколько, то массив
  //                порядок следования в массиве должен соответствовать порядку следования индексов в конфигурационном файле
  //     $name    - имя нужного запроса, если не указано, то текущее.
  //                !!! рекомендовано всегда указывать имя запроса,
  //                    т.к. текущий запрос может менятся и несоответствовать правильному
  //                    в зависимости от места вызова в проекте
  function uri_replace($uri, $replace, $name = ''){

    if(!empty($name)){
      $current_name = $name;
      $this->current_name = $current_name;
    }else{
      $current_name = $this->current_name;
    }
    if(empty($current_name)){    	echo 'Не определено имя запроса для замены uri';
    	return false;
    }
    if( ! is_array($replace)){
      $replace = array($replace);
    }
    reset($replace);

    //echo $this->current_name;
    //echo "<pre>";
    //print_r($this->data);
    //echo "</pre>";
    if(isset($this->data[$current_name]['uri_base'][$uri])){
      foreach($this->data[$current_name]['uri_base'][$uri] as $key=>$value){
        if($value == $this->flag_param_unknown){
          $arr[$key] = current($replace);
          next($replace);
        }else{
          $arr[$key] = $value;
        }
      }
    }else{    	echo 'Не найдена строка запроса '.$uri.' в текущей базе uri';
    	exit();
    }
    //print_r($arr);
    //print_r($replace);

    //генерация uri и запись в кеш
    return $this->get_uri($arr);
    //генерация uri
    //return $this->generate_uri($arr);

  }
}









