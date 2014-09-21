<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Router_modules
*
* Author: Цымбал Василий
* 		  531@mail.ru
*
* Created:  07.11.2011
*
* Description:  Библиотека загружает данные маршрутизации модуля.
*   Установка представляется в виде массива в файле route.php в папке config каждого модуля
*
* Use: Применяется для автозагрузки класса и методов в зависимости от индексов запросов (index_request).
*      Применяется в тандеме с библеотекой index_request для сравнениязначений индексов.
*      Инициализируется объект $this->route
*
* Requirements: PHP5 or above
*
*/

class Router_modules
{
    /**
    * Кеш для полученных результатов
    *
    */
    private $cache = array();

    //Текущие имя маршрута
    private $current_route;

    //Текущие имя группы маршрута
    private $current_group;

    //текущий модуль
    private $current_module;

    //разделитель ссылки на имя индекса в аргументах
    private $separator_arg_index = 'index:';

    //разделитель значения имени маршрута, если оно является производной от языка
    private $separator_name_lang = 'lang:';

	//разделитель в GET запросе
	private $sep_query = '~';

    //разделитель равенства параметра
    private $sep_equality = '=';

    //имя переменной ресурса
    private $param_resource = 'resource';

    //имя переменной аргумента
    private $param_arg = 'arg';

    //разделитель значений ресурса
    private $sep_resource = '/';

    //разделитель значений аргументов
    private $sep_arg = '/';

    private $str_start_uri = 'ajax/?';

    // флаг неизвестного параметра
   // при замене известными значением данного параметра при генерации строки uri
   private $flag_param_unknown = '?';

    function __construct(){
        $this->ci =& get_instance();
        $this->ci->load->library('index_request','','index_get');
        //print_r($this->ci->index_get);
    }

	/**
	* Возвращает автоматически определенный модуль
	* Если имя не задано, то возвращает имя модуля из которого вызывно приложение
	* @param $module string - имя модуля (если опущено, то модуль считается основным)
	*
	* @return string - имя модуля (если модуль основной, то возвратит false)
	*/
    function get_define_module($module = ''){
    	 $module OR $module = CI::$APP->router->current_module();
    	 return $module;
    }
    /**
    *  Установка имени текущего маршрута для модуля
    *
    */
    function set_route($name){

    }
    /**
    *   Загрузка файла конфигурации маршрута для модуля
    *
    */
    function load($_module = ''){
        $_module OR $_module = $this->get_define_module();
		    list($path, $file) = Modules::find('route', $_module, 'config/');

        if($path === FALSE){
            //echo "не обнаружен файл псевдонимов: модуль - ".$_module.", путь: $path, файл: $file<br />";
        }else{
            //echo "!! Обнаружен файл псевдонимов: модуль - ".$_module.", путь: $path, файл: $file<br />";
            $this->current_module = $_module;
            if(!isset($this->cache[$this->current_module])){
                if($route = Modules::load_file($file, $path, 'config')){
                    //print_r($route);
                    if(isset($route['route'])){
                        $this->cache[$this->current_module] = $route['route'];
                    }
                }
            }
        }
    }

	/**
	* Установка стартового сегмента для данного маршрута
	* @param number $num номер стартового сегмента
	* @param string $route имя маршрута
	* @param string $group имя группы маршрута
	* @param string $module ия модуля маршрута
	*/
    function set_start_segment($num, $route, $group, $module){
       if(!isset($this->cache[$module][$group][$route]['index_name'])) return false;
       $index_name = $this->cache[$module][$group][$route]['index_name'];
       $_module = $this->get_ajax_module($route, $group, $module);
       $this->ci->index_get->set_start_segment($index_name, $num, $_module);
    }

    /**
    * Установка строки uri
    *
    */
    function set_uri($uri, $route, $group, $module){
       if(!isset($this->cache[$module][$group][$route]['index_name'])) return false;
       $index_name = $this->cache[$module][$group][$route]['index_name'];
       $_module = $this->get_ajax_module($route, $group, $module);
       $this->ci->index_get->set_uri($index_name, $uri, $_module);
    }

    /**
    *  Установка имени группы индексов для имени маршрута
    */
    function set_index_name($name_route, $name_index, $_module = ''){


    }
    /**
    *  Возвращает имя индексной группы
    */
    function get_index_name($route, $group, $module = ''){
    	if(empty($route)) return false;
        if(empty($module)) $module = $this->current_module;
        if(empty($group)){
            $group = $this->current_group;
        }

        if(isset($this->cache[$module][$group][$route])){
            $items = $this->cache[$module][$group][$route];
            if(isset($items['index_name'])){            	return $items['index_name'];
            }else{            	exit('Не установлен индекс имени для имени маршрута '.$route.', имени группы '.$group.', модуля '.$module);
            }
        }
        return false;
    }

    function get_ajax(){         $result_arr = $this->parse_ajax_str();
        	//if(!isset($arr_query)) return;
            //print_r($arr_query);


        	//print_r($result_arr);
            //если нет необходимых переменных выходим из функции
 			if( ! isset($result_arr[$this->param_resource])) return;
        	$resource = $result_arr[$this->param_resource];

        	if(isset($result_arr[$this->param_arg])){
        		$arg = $result_arr[$this->param_arg];
        	}else{
        		$arg = array();
        	}

			//вычисляем значения для маршрута
            if(strpos($resource, '/')){
            	$arr_resource = explode('/', $resource);
            	$route_name = ($arr_resource[0]) ? $arr_resource[0]	: false;
            	$route_group = ($arr_resource[1]) ? $arr_resource[1]	: false;
            	$route_module = ($arr_resource[2]) ? $arr_resource[2]	: false;
            }
            //загружаем конфигурацию маршрута для данного модуля
            $this->load($route_module);

            //получаем модуль, контроллер, метод для данного маршрута
            $module = $this->get_ajax_module($route_name, $route_group, $route_module);
            $controller = $this->get_ajax_controller($route_name, $route_group, $route_module);
            $method = $this->get_ajax_method($route_name, $route_group, $route_module);

			return Modules::run($module.'/'.$controller.'/'.$method);
            /*---------------------
            ---- Вариант запроса ссылки через исходный маршрут (т.е. с сохранением атрибутов и имен параметров)
            //получаем ссылку для данного маршрута
            $link = $this->generate_link($route_name, $route_group, $route_module);
            $link_ajax = $this->generate_link_ajax($route_name, $route_group, $route_module);
            //echo '@<br>';

			//получаем имя индексной группы
            $index_name = $this->get_index_name($route_name, $route_group, $route_module);
            if(empty($index_name)) return;

            $uri_replace = uri_replace($link, $arg,$index_name);
            echo '<br />Uri: '.$link.'<br />';
            echo '<br />Uri replace: '.$uri_replace.'<br />';
            echo '<br />Uri ajx: '.$link_ajax.'<br />';
            //устанавливаем uri для данного маршрута
            $this->set_uri($uri_replace, $route_name, $route_group, $route_module);

            //устанавливает отсчет начального сегмента
            $this->set_start_segment(1, $route_name, $route_group, $route_module);



            //получаем данные по указанному маршруту
            //$str_result = Modules::run($module.'/'.$controller.'/'.$method);
            //echo '<b>GET RESULT</b><br />';
            $str_result = $this->get_result($route_name, $route_group, $route_module);
            //echo '<b>END</b><br />';
        	/*
        	echo '<br />Ресурс: '.$resource.'<br />';
        	echo 'Uri: '.$uri.'<br />';
        	echo 'Arg: '.@$arg.'<br />';
        	echo 'Link: '.@$link.'<br />';
        	echo 'Имя индексной группы: '.$index_name.'<br />';
        	echo 'Link_OUT: '.uri_replace($link, array(1),$index_name).'<br />';
        	//echo 'Link_OUT: '.uri_replace($link, array('direction' => '2', 'order' => 'asc'),$index_name).'<br />';
        	echo 'Содержание модуля:<br/>';
        	*/
        	//echo $str_result;

    }

	/**
	*	Парсит строку ajax
	*
	*
	*/
	function parse_ajax_str(){		//парсим строку запроса
        	$str = parse_url($_SERVER['REQUEST_URI']);
        	$str_query = @$str['query'];
        	//echo $str_query;
        	//если строка есть, делим на переменные
        	//и вычисляем имена переменных
        	if(!empty($str_query)){
        		//if(strpos($str_query, $this->sep_query)){
                	$arr_query = explode($this->sep_query, $str_query);
                	foreach($arr_query as $key=>$value){
		            	if(strpos($value, $this->sep_equality)){
		                	$arr_sep = explode($this->sep_equality, $value);
		                	$result_arr[$arr_sep[0]] = $arr_sep[1];
		        		}
		        	}
        		//}
        	}
        if(isset($result_arr[$this->param_arg])){        	$result_arr[$this->param_arg] = explode($this->sep_arg, $result_arr[$this->param_arg]);
        }
        if(isset($result_arr)) return $result_arr;
        return false;
	}
    /**
    *  Запуск процесса маршрута длдя модуля
    */
    function run($name_group = '', $_module = '', $return = false){

        $_module OR $_module = $this->get_define_module();
        $status_run = false;
        if(!empty($_module)){
            if(!isset($this->cache[$_module])){
               $this->load($_module);
            }

            if(empty($name_group)){
               return false;
            }

            if( ! isset($this->cache[$_module][$name_group])){
               return false;
            }else{
                $this->current_module = $_module;
                $this->current_group = $name_group;
            }

            foreach($this->cache[$this->current_module][$this->current_group] as $name_route=>$items){

                if($this->is_route_uri($name_route)){
                   //echo "<br>маршрут найден - $name_route<br>";

                   $this->current_route = $name_route;
                   $status_run = true;
                   break;
                }

            }
        }

        if($status_run === true){
            $result = $this->get_result($this->current_route, $this->current_group, $this->current_module);

        }

        if(isset($result)){
            //var_dump($result);
            if($return) {
                return $result;
            }else{
                echo $result;
            }
        }
        return false;
    }

    /**
    * Возвращает результат работы маршрута
    *
    */
    function get_result($route, $group, $module){    	$module = $this->module($route, $group, $module);
        $controller = $this->controller($route, $group, $module);
        $method = $this->method($route, $group, $module);
        $arg = $this->argument($route, $group, $module);
        //echo 'get_result:<br/>';
        //print_r($arg);
        if(isset($module) && isset($controller) && isset($method)){
            //echo "$module - $controller - $method - ";
            //print_r($arg);
            //echo "<br>";
            if(isset($arg) && count($arg) > 0){
                switch(count($arg)){
                    case 1:
                      $result = Modules::run("$module/$controller/$method", $arg[0]);
                    break;
                    case 2:
                      $result = Modules::run("$module/$controller/$method", $arg[0], $arg[1]);
                    break;
                    case 3:
                      $result = Modules::run("$module/$controller/$method", $arg[0], $arg[1], $arg[2]);
                    break;
                    case 4:
                      $result = Modules::run("$module/$controller/$method", $arg[0], $arg[1], $arg[2], $arg[3]);
                    break;
                    case 5:
                      $result = Modules::run("$module/$controller/$method", $arg[0], $arg[1], $arg[2], $arg[3], $arg[4]);
                    break;
                    case 6:
                      $result = Modules::run("$module/$controller/$method", $arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5]);
                    break;
                    default:
                      $result = Modules::run("$module/$controller/$method", $arg[0]);
                }
            }else{
                $result = Modules::run("$module/$controller/$method");
            }
        }
        if(isset($result)) return $result;
        return ;
    }

    /**
    *  Возвращает имя установочного модуля маршрута из конфигурации
    *	@param string $route - имя маршрута
    *   @param string $group - группа маршрута
    *   @param string $module - модуль маршрута
    *
    *	@param string - имя модуля маршрута или false если такого маршрута нет
    */
    function module($route, $group, $module){
        if(!empty($this->cache[$module][$group][$route]['module'])){
            return  $this->cache[$module][$group][$route]['module'];
        }
        return false;
    }
    /**
    *  Возвращает имя установочного контроллера маршрута из конфигурации
    *	@param string $route - имя маршрута
    *   @param string $group - группа маршрута
    *   @param string $module - модуль маршрута
    *
    *	@param string - имя контроллера маршрута или false если такого маршрута нет
    */
    function controller($route, $group, $module){
        if(!empty($this->cache[$module][$group][$route]['controller'])){
            return  $this->cache[$module][$group][$route]['controller'];
        }
        return false;
    }
    /**
    *  Возвращает имя установочного метода маршрута из конфигурации
    *	@param string $route - имя маршрута
    *   @param string $group - группа маршрута
    *   @param string $module - модуль маршрута
    *
    *	@param string - имя метода маршрута или false если такого маршрута нет
    */
    function method($route, $group, $module){
        if(!empty($this->cache[$module][$group][$route]['method'])){
            return  $this->cache[$module][$group][$route]['method'];
        }
        return false;
    }
    /**
    *  Возвращает массив с аргументами маршрута, если они есть
    *  если агрументов нет возвращает null
    *  @param $route string - имя маршрута
    *  @param $group string - имя группы маршрута
    *  @param $module string - имя модуля маршрута
    *
    *  @return array - массив с аргументами маршрута (null если аргументы не заданы)
    */
    function argument($route, $group, $module){
        if(!empty($this->cache[$module][$group][$route]['arg'])){
            if(!is_array($this->cache[$module][$group][$route]['arg'])){
                $args = array($this->cache[$module][$group][$route]['arg']);
            }else{
                $args = $this->cache[$module][$group][$route]['arg'];
            }
            // преобразование индексов в значения если они являются ссылками на индексы
            foreach($args as $key=>$value){
                //если значение аргумента ссылается на индекс,
                // то производим вычисление на основе значения данного индекса
                if($this->is_arg_index($value)){
                    $index =  $this->get_index_arg($value);
                    $index_name = $this->cache[$module][$group][$route]['index_name'];
                    if(isset($index) && !empty($index_name)){
                        $arg_index_arr[] = $this->get_arg($index_name, $index, $module);
                    }else{
                        exit('Не установлен индекс для аргумента '.$key.', имени маршрута '.$route.', имени группы '.$group.', модуля '.$module);
                    }
                }else{
                   $arg_index_arr[] = $value;
                }
            }
        }
        if(isset($arg_index_arr)) return $arg_index_arr;
        return null;
    }
    /*
    *  Проверяет присутствует ли в аргументе ссылка на индекс
    *	ссылка на индекс характеризуется начальной строкой параметра "separator_arg_index"
    *	@param string $index - имя аргумента
    *
    *	@param string - строка параметра без учета ссылки индекса
    */
    function is_arg_index($index){
        if(strpos($index, $this->separator_arg_index) !== false){
            $item = trim(substr($index, strpos($index, $this->separator_arg_index) + strlen($this->separator_arg_index)));
            return $item;
        }
        return false;
    }
    /*
    *  Возвращает правильное значение индекса в аргументе
    */
    function get_index_arg($index){
        if($item = $this->is_arg_index($index)){
            $arg = $item;
        }else{
            $arg = $index;
        }
        return $arg;
    }

    /*
    *  Возвращает значение аргумента, если он является производной от индекса
    *  @param string - имя индексной группы
    *  @param string - имя индекса
    *
    *  @return  - значение индекса или null если index не определён
    */
    function get_arg($index_name, $index_key, $module = ''){
        $index = $this->ci->index_get->get_index($index_name, $module);
        //echo 'Индексы - '.$index_name.', агрументы '.$index_key.'<pre>';
        //print_r($index);
        //echo '</pre>';
        if(isset($index[$index_key])){
           return $index[$index_key];
        }
        return null;
    }
    /**
    *  Проверка на соответствие параметрам имени маршрута параметрам строки uri
    *
    */
    function is_route_uri($name_route){
        if(isset($this->cache[$this->current_module][$this->current_group][$name_route])){

            //foreach($this->cache[$this->current_module][$this->current_group][$name_route] as $items){
            $items = $this->cache[$this->current_module][$this->current_group][$name_route];
                if(!is_array($items['index'])){
                   exit('не установлены индексы для маршрута '.$name_route.', в группе '.$this->current_group.', модуля '.$this->current_module);
                }
                if(empty($items['index_name'])){
                   exit('не установлено имя индекса для маршрута '.$name_route.', в группе '.$this->current_group.', модуля '.$this->current_module);
                }
                if(empty($items['start_segment'])){
                   exit('не установлено начало сегмента для маршрута '.$name_route.', в группе '.$this->current_group.', модуля '.$this->current_module);
                }

                $this->ci->index_get->load($this->current_module);

                $this->ci->index_get->set_start_segment($items['index_name'], $items['start_segment'],$this->current_module);
                $index = $this->ci->index_get->get_index($items['index_name'],$this->current_module);

                if(is_array($index)){
                    foreach($items['index'] as $name_index=>$item){
                        if(!isset($index[$name_index])) return false;
                        if($item === true && $index[$name_index] === false){
                            return false;
                        }elseif($index[$name_index] != $item){
                            return false;
                        }
                    }
                    $this->current_route = $name_route;
                    return true;
                }
            //}
        }

        return false;
    }

    /**
    *  Проверка на наличие данного маршрута
    *	@param string $name_route - имя маршрута
    *	@param string $name_group - группа маршрута
    *	@param string $_module - имя модуля маршрута
    *
    */
    function is_route($name_route, $name_group = '', $_module = ''){
        if(empty($name_route)) return false;
        if(empty($_module)) $_module = $this->current_module;
        if(!empty($name_group)){
            $group = $name_group;
        }else{
            $group = $this->current_group;
        }
        if(isset($this->cache[$_module][$group][$name_route])){
            return true;
        }
        return false;
    }
    /*
    *  Возвращает имя маршрута
    */
    function get_route($group, $_module = ''){
        $_module OR $_module = $this->get_define_module();
        if(isset($this->cache[$_module][$group])){
            foreach($this->cache[$_module][$group] as $key=>$value){
                if($this->is_route_uri($key)){
                    return $key;
                }
            }
        }
        return false;
    }

    function tree_menu($name_group = '', $_module = ''){
        $_module OR $_module = $this->get_define_module();

        if(!empty($_module)){
            if(!isset($this->cache[$_module])){
               $this->load($_module);
            }

            if(empty($name_group)){
               return false;
            }

            if( ! isset($this->cache[$_module][$name_group])){
               return false;
            }else{
                $this->current_module = $_module;
                $this->current_group = $name_group;
            }

            foreach($this->cache[$this->current_module][$this->current_group] as $name_route=>$items){
                if(isset($items['menu']) && $items['menu'] === true){

                    if(isset($items['parent']) && $items['parent'] !== false){
                        if($this->is_route($items['parent'])){
                            $menu[$items['parent']][$name_route] = array(
                                                      'name' => $this->get_name($name_route, $this->current_group, $this->current_module),
                                                      'link' => $this->generate_link($name_route, $this->current_group, $this->current_module),
                                                      //'active_link' => $this->active_link($name_route, $this->current_group, $this->current_module),
                                                      'active_link' => $this->is_route_uri($name_route),
                                                      'path_link'   => $this->path_link($name_route, $name_group),
                                                      );
                        }
                    }else{
                        $menu[0][$name_route] = array('name' => $this->get_name($name_route, $this->current_group, $this->current_module),
                                                      'link' => $this->generate_link($name_route, $this->current_group, $this->current_module),
                                                      //'active_link' => $this->active_link($name_route, $this->current_group, $this->current_module),
                                                      'active_link' => $this->is_route_uri($name_route),
                                                      'path_link'   => $this->path_link($name_route, $name_group),
                                                      );
                    }
                }

            }
        }
        if(isset($menu)){
           return $menu;
        }
        return false;
    }

    function get_name($name_route, $name_group = '', $_module = ''){
        if(empty($name_route)) return false;
        if(empty($_module)) $_module = $this->current_module;
        if(!empty($name_group)){
            $group = $name_group;
        }else{
            $group = $this->current_group;
        }
        if(isset($this->cache[$_module][$group][$name_route]['name'])){
            $name = $this->cache[$_module][$group][$name_route]['name'];
        }
        if(isset($name)){
            $name = $this->get_name_lang($name);
            return $name;
        }

        return false;
    }


    /*
    *  Возвращает правильное имя маршрута в зависимости от языка
    */
    function is_name_lang($name){
        if(strpos($name, $this->separator_name_lang) !== false){
            $item = trim(substr($name, strpos($name, $this->separator_name_lang) + strlen($this->separator_name_lang)));
            return $item;
        }
        return false;
    }
    /*
    *  Возвращает правильное значение имени маршрута
    */
    function get_name_lang($name){
        if($item = $this->is_name_lang($name)){
            $arg = $this->get_name_convert($item);
        }else{
            $arg = $name;
        }
        return $arg;
    }

    /*
    *  возвращает значение имени маршрута, если он является производной от языка
    */
    function get_name_convert($line){
        $this->ci->load->helper('language');
        $name = lang($line);
        if(!empty($name)){
           return $name;
        }
        return false;
    }

    /*
    *  Генерация ссылки
    */
    function generate_link($name_route, $name_group = '', $_module = ''){
        if(empty($name_route)) return false;
        if(empty($_module)) $_module = $this->current_module;
        if(!empty($name_group)){
            $group = $name_group;
        }else{
            $group = $this->current_group;
        }
        //echo $_module;
        //echo $name_route;
        //echo $group;
        //echo '<pre>';
        //print_r($this->cache);
        //exit;
        if( ! isset($this->cache[$_module])){        	$this->load($_module);
        }
        if(isset($this->cache[$_module][$group][$name_route])){
            $items = $this->cache[$_module][$group][$name_route];
                if(!is_array($items['index'])){
                   exit('не установлены индексы для генерации меню '.$name_route.', в группе '.$group.', модуля '.$_module);
                }
                if(empty($items['index_name'])){
                   exit('не установлено имя индекса для генерации меню '.$name_route.', в группе '.$group.', модуля '.$_module);
                }
                if(empty($items['start_segment'])){
                   exit('не установлено начало сегмента для генерации меню '.$name_route.', в группе '.$group.', модуля '.$_module);
                }

                //загружаем конфигурационные параметры индексов для модуля
                $this->ci->index_get->load($_module);
                //устанавливаем необходимые параметры для индексов
                $this->ci->index_get->set_start_segment($items['index_name'], $items['start_segment'], $_module);
                if(!empty($items['replace_empty_index'])){
                    $this->ci->index_get->replace_empty_index($items['index_name'], true, $_module);
                }else{
                    $this->ci->index_get->replace_empty_index($items['index_name'], false, $_module);
                }

                //получаем массив индексов запроса
                $index = $this->ci->index_get->get_index($items['index_name'],$_module);

				// устанавливаем конфиг нужных индексов для получения uri
                foreach($items['index'] as $key=>$value){
                    if(array_key_exists($key, $index)){
                        //если значение не равно false
                        if($value !== false){
                            //если равно true (т.е. оно обязательно присутстует в запросе)
                            //присваиваем ему неизвестное
                            if($value === true){
                                $config[$key] = $this->flag_param_unknown;
                                //$config[$key] = $this->ci->index_get->get_param($items['index_name'], 'flag_param_unknown');
                            //если значение указано явно, присваиваем данное значение
                            }else{
                                $config[$key] = $value;
                            }
                        }
                    }
                }
                //если конфиг сформирован, генерируем uri строку
                if(isset($config) && is_array($config)){
                    $uri = $this->ci->index_get->get_uri($items['index_name'], $config, $_module);
                    //$uri = $this->control_uri->guri('news_page')->get_uri($config);
                }
        }
        if(isset($uri)) return $uri;

        return false;
    }

	/**
	* Генерация ссылки для ajax запроса
	*
	*
	*/
	function generate_link_ajax($name_route, $name_group = '', $_module = ''){        if(empty($name_route)) return false;
        if(empty($_module)) $_module = $this->current_module;
        if(!empty($name_group)){
            $group = $name_group;
        }else{
            $group = $this->current_group;
        }
        $result_arr = $this->parse_ajax_str();

       	if(isset($result_arr[$this->param_arg])){
       		$arg = $result_arr[$this->param_arg];
       	}else{
       		$arg = array();
       	}

        //получаем ссылку для данного маршрута
            $link = $this->generate_link($name_route, $group, $_module);

			//получаем имя индексной группы
            $index_name = $this->get_index_name($name_route, $group, $_module);
            if(empty($index_name)) return;

            $uri_replace = uri_replace($link, $arg,$index_name);
            //устанавливаем uri для данного маршрута
            $this->set_uri($uri_replace, $name_route, $group, $_module);

            //устанавливает отсчет начального сегмента
            $this->set_start_segment(1, $name_route, $group, $_module);


        $args = $this->argument($name_route, $group, $_module);
        var_dump($args);
        $str_resource = $this->generate_str_resource($name_route, $group, $_module);
        $str_arg = $this->generate_str_arg($args);
        $str_uri = $this->generate_str_uri($str_resource, $str_arg);
        $str_uri = $this->get_uri_ajax($str_uri,$args);
        return $str_uri;
	}

	/**
	* Возвращает строку ресурса для части строки uri в ajax запросе
	*	@param string $route - имя маршрута
	*   @param string $group - имя группы маршрута
	*   @param string $module - имя модуля
	*
	*	@return string - строка ресурса
	*/
	private function generate_str_resource($route, $group, $module){		if(empty($route) || empty($group))	return false;
		$res = $route.$this->sep_resource.$group;
		if(!empty($module)) $res .= $this->sep_resource.$module;
		return $res;
	}

	/**
	* Возвращает строку из аргументов для части строки uri в ajax запросе
	*	@param string $route - имя маршрута
	*   @param string $group - имя группы маршрута
	*   @param string $module - имя модуля
	*
	*	@return string - строка ресурса
	*/
	private function generate_str_arg($args = false){
		if(empty($args))	return false;
		if(! is_array($args)) $args = array($args);
		$res = '';
		foreach($args as $key=>$value){			//$res .= $value.$this->sep_arg;
			//если значение не равно false
                        //if($value !== false){
                            //если равно true (т.е. оно обязательно присутстует в запросе)
                            //присваиваем ему неизвестное
                            if($value === true){
                                $res .= $this->flag_param_unknown.$this->sep_arg;
                                //$config[$key] = $this->ci->index_get->get_param($items['index_name'], 'flag_param_unknown');
                            //если значение указано явно, присваиваем данное значение
                            }else{
                                $res .= $value.$this->sep_arg;
                            }
                        //}
		}

		return $res;
	}

	/**
	* Возвращает постороенную строку uri в ajax запросе из параметров запроса
	*	@param string $route - имя маршрута
	*   @param string $group - имя группы маршрута
	*   @param string $module - имя модуля
	*
	*	@return string - строка ресурса
	*/
	private function generate_str_uri($resource, $arg){
		if(empty($resource))	return false;
		$res = $this->param_resource.$this->sep_equality.$resource;
		if(!empty($arg)) $res .= $this->sep_query.$this->param_arg.$this->sep_equality.$arg;

		return $res;
	}


	private function get_uri_ajax($uri, $config){
   		return $uri;
	}
    /**
    *  Возвращает массив с значениями индексов
    *
    */
    public function get_index($name_route, $name_group = '', $_module = ''){		if(empty($name_route)) return false;
        if(empty($_module)) $_module = $this->current_module;
        if(!empty($name_group)){
            $group = $name_group;
        }else{
            $group = $this->current_group;
        }
        //получаем индексное имя маршрута
        $index_name = $this->get_index_name($name_route, $name_group, $_module);
		//загружаем конфигурационные параметры индексов для модуля
        $this->ci->index_get->load($_module);
        //устанавливаем необходимые параметры для индексов
		if(isset($this->cache[$_module][$group][$name_route])){
            $items = $this->cache[$_module][$group][$name_route];
            if(!empty($items['start_segment'])){
                $this->ci->index_get->set_start_segment($index_name, $items['start_segment']);
            }
                if(!empty($items['replace_empty_index'])){
                    $this->ci->index_get->replace_empty_index($index_name, true);
                }else{
                    $this->ci->index_get->replace_empty_index($index_name, false);
                }
  		}
        //получаем массив индексов запроса
        $index = $this->ci->index_get->get_index($index_name);
        return $index;
    }

    /*
    *  Проверяет активна ли в данный момент ссылка
    */
    function path_link($name_route, $name_group){
        $route = $this->get_route($name_group);
        $path = $this->get_path($route);
        if(is_array($path)){
            if(in_array($name_route, $path)){
                return true;
            }
        }
        return false;
    }
    /*
    *  Возвращает путь текущий
    *  массив в виде названий маршрутов в порядке убывания
    */
    function get_path($route = '', $path = array()){
        if(empty($route)) $route = $this->current_route;
        if(isset($this->cache[$this->current_module][$this->current_group][$route])){
            if(in_array($route, $path)){
                return false;
            }
            $path[] = $route;
            if(!empty($this->cache[$this->current_module][$this->current_group][$route]['parent'])){
                return $this->get_path($this->cache[$this->current_module][$this->current_group][$route]['parent'], $path);
            }
        }else{
           return false;
        }
        return  $path;
    }

    /**
     *  Возвращает путь к методу модуля
     * @param type $segment1
     * @param type $segment2
     * @return type
     */
    function get_ajax_uri($segment1, $segment2){
        if(isset($this->cache[$this->current_module][$segment1][$segment2])){
            foreach($this->cache[$this->current_module][$segment1][$segment2] as $key=>$value){
                if(isset($value['module'])){
                    $module = $value['module'];
                }
                if(isset($value['controller'])){
                    $controller = $value['controller'];
                }
                if(isset($value['method'])){
                    $method = $value['method'];
                }
            }
        }
        if(!isset($module)) return false;
        if($module === false){
            $module = '';
        }else{
            $module .= '/';
        }
        if(!isset($controller)) return false;
        if(empty($method)) $method = 'index';

        return $module.$controller.'/'.$method;
    }

    function get_ajax_module($name, $group, $module){
        if(empty($name)) return false;
        if(empty($module)) $module = $this->current_module;
        if(empty($group)){
            $group = $this->current_group;
        }
        if(isset($this->cache[$this->current_module][$group][$name])){
            if(isset($this->cache[$this->current_module][$group][$name]['module'])){            	return 	$this->cache[$this->current_module][$group][$name]['module'];
            }
        }

        return false;
    }
    function get_ajax_controller($name, $group, $module){
        if(empty($name)) return false;
        if(empty($module)) $module = $this->current_module;
        if(empty($group)){
            $group = $this->current_group;
        }
        if(isset($this->cache[$this->current_module][$group][$name])){
            if(isset($this->cache[$this->current_module][$group][$name]['controller'])){
            	return 	$this->cache[$this->current_module][$group][$name]['controller'];
            }
        }
        return false;
    }
    function get_ajax_method($name, $group, $module){
        if(empty($name)) return false;
        if(empty($module)) $module = $this->current_module;
        if(empty($group)){
            $group = $this->current_group;
        }
        if(isset($this->cache[$this->current_module][$group][$name])){
            if(isset($this->cache[$this->current_module][$group][$name]['method'])){
            	return 	$this->cache[$this->current_module][$group][$name]['method'];
            }
        }
        return false;
    }

    /**
    * Генерируем ссылку маршрута
    *
    */
    function link_route($route, $group, $module){
    }
}