<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gate extends MX_Controller {

	function __construct() {
            parent::__construct();
            //$this->output->enable_profiler(TRUE);
            //$this->load->helper('file');
            //$this->config->load('filters', 'TRUE');
            //$this->load->library('control_uri');
            //$this->load->library('meta');
            $this->load->driver('assets');
            
            $this->load->helper(array('form', 'url', 'debug'));
            $this->config->load('site', 'TRUE');
            $this->load->library('session');
            $this->load->library('control_uri');
            $this->load->library('scheme');
            $this->load->library('form_validation');
	}

        /*
         *  Возвращает данные по фильтру\
         *  Реализация по технологии ajax для контроля запросов
         *  arg: $name_route - имя маршрута для фильтра
         *
         *  !!! подразумевается что каждый фильтр имеет свой маршрут, т.е. Modules, Controller, Method
         *      сейчас эти данные хранятся в файле filters.php d кофигурационной папке config
         *      в дальнейшем если кол-во фильтров достигает очень большого кол-ва и требуется управление через админ панель
         *      эту информацию можно хранить в БД
         *
         *  структура хранения filters:
         *      Поля:
         *           route - string, имя маршрута,
         *           active - boolean, активация фильтра
         *           modules - string, имя модуля
         *           controllers - string, имя контроллера
         *           method - string, имя метода
         *
         *  return: data - данные в произвольном формате
         *          false - в случае неудачи
         */
        function filter($name_route){

        }

        /*
         *  Произвольные запросы к модулям
         *  arg: $module     - string - модуль
         *       $controller - string - контроллер
         *       $method     - string - метод (если не задан по умолчанию index)
         *       $arg        - string || array() - один или массив из аргументов в порядке следования, если требуется (по умолчанию false)
         */
        /*
         * !!! работает медленней чем следующая за ней
        function __reguest($module, $controller, $method = 'index', $arg = false){
            if(!empty($module) && !empty($controller) && !empty($method)){
                //$result = Modules::run($path_module, $params);
                //$result = $this->load->module($module."/".$controller)->call_user_func_array($method,$arg);
                $result = call_user_func_array(array($this->load->module($module."/".$controller),$method),$arg);
            }
            if ($result){
                return $result;
            }
            return false;
        }
        */
        function _request($module, $controller, $method = 'index', $arg = false){
            if( Modules::run('auth/auth/is_logged')){
	            if($module === false){
	                $module = '';
	            }else{
	                $module .= '/';
	            }
	            if(!empty($controller) && !empty($method)){
	                //$result = Modules::run($module/$controller/$method, $arg);
	                $count_arg = (is_array($arg)) ? count($arg) : false;

	                switch ($count_arg){
	                    case 1:
	                        $result = Modules::run($module.$controller.'/'.$method, $arg[0]);
	                        break;
	                    case 2:
	                        $result = Modules::run($module.$controller.'/'.$method, $arg[0], $arg[1]);
	                        break;
	                    case 3:
	                        $result = Modules::run($module.$controller.'/'.$method, $arg[0], $arg[1], $arg[2]);
	                        break;
	                    case 4:
	                        $result = Modules::run($module.$controller.'/'.$method, $arg[0], $arg[1], $arg[2], $arg[3]);
	                        break;
	                    case 5:
	                        $result = Modules::run($module.$controller.'/'.$method, $arg[0], $arg[1], $arg[2], $arg[3], $arg[4]);
	                        break;
	                    case 6:
	                        $result = Modules::run($module.$controller.'/'.$method, $arg[0], $arg[1], $arg[2], $arg[3], $arg[4], $arg[5]);
	                        break;
	                    default:
	                        $result = Modules::run($module.$controller.'/'.$method, $arg);
	                }
	                //$result = Modules::run($module.'/'.$controller.'/'.$method, $arg);
	                //$result = $this->load->module($module."/".$controller)->;
	                //$result = call_user_method($method, $this->load->module($module."/".$controller), $arg);
	                //$result = call_user_method_array($method, $this->load->module($module."/".$controller), $arg);
	                //$result = call_user_func_array(array($this->load->module($module."/".$controller), $method), $arg);
	            }
	            if (isset($result)){

	                return $result;
	            }
	            return false;
	        }else{
            	echo 'Функция "request" не доступна!';
            }
        }

        /*
         *  Возвращает шаблоны в html формате
         *  arg: $module     - string - модуль
         *       $controller - string - контроллер
         *       $method     - string - метод (если не задан по умолчанию index)
         *       $arg        - string || array() - один или массив из аргументов в порядке следования, если требуется (по умолчанию false)
         */
        function html($module, $controller, $method = 'index', $arg = false){

        }

        /*
         *  Возвращает шаблоны в html формате
         *  arg: $module     - string - модуль
         *       $controller - string - контроллер
         *       $method     - string - метод (если не задан по умолчанию index)
         *       $arg        - string || array() - один или массив из аргументов в порядке следования, если требуется (по умолчанию false)
         */
        function ajax2($module = false, $segment1 = false, $segment2 = false, $arg = false){

            //echo "!!!";
            $this->router_modules->load($module);
            $module = $this->router_modules->get_ajax_module($segment1, $segment2);
            $controller = $this->router_modules->get_ajax_controller($segment1, $segment2);
            $method = $this->router_modules->get_ajax_method($segment1, $segment2);
            echo "Модуль: ".$module.'<br>';
            echo "Контроллер: ".$controller.'<br>';
            echo "Метод: ".$method.'<br>';
            $result = $this->_request($module, $controller, $method, $arg);
            echo $result;
            //if(isset($result)) return $result;
            //return false;

        }

        /**
        * Возвращет результат работы ajax приложений
        * на основе модульных маршрутов (router_modules)
        * @param string - строка параметров запроса
        *              формат строки в виде URI_STRING
        *              ?resource=name/group/module  - ресурс запроса (имя маршрута, группа маршрута, модуль)
        *              &params=arg1/arg2/...        - аргументы ресурса
        *              &uri=string                  - целевая строка запроса
        *
        */
        function ajax ($string = false){
            /*
            if( Modules::run('auth/auth/is_logged')){
            	echo $this->router_modules->get_ajax();
            }else{
            	echo 'Функция "ajax" не доступна!';
            }
            */
            $result = $this->router_modules->get_ajax();

            if(is_array($result)){
                $result = json_encode($result);
            }

            echo $result;
        	//$arg = array(3,4);
        	//$uri = 'ajax/?resource=objects_list/user/adverts~~arg=!/!';

        	//$uri_replace = uri_replace($uri, $arg, 'object');

        	//echo $uri_replace;
        	//echo $str_result;

        }

        function ajax_script(){
            if( Modules::run('auth/auth/is_logged')){
	            echo $this->load->view('admins/tpl2/index_load_script', array(
                                                                            'content' => $this->router_modules->get_ajax()),
                                                                             true
                );	             
            }else{
            	echo 'Функция "ajax+script" не доступна!';
            }
        }

        function grid($controller, $module = false, $method = false){
            //$this->load->add_package_path(APPPATH.'third_party/Grid/');
            //$this->load->library('grid');
            if( Modules::run('auth/auth/is_logged')){
	            if(empty($controller)) return false;
	            //$result = $this->_request($module, $controller, 'grid');
	            if($module === false){
	                $module = '';
	            }else{
	                $module .= '/';
	            }
	            if(empty($method)){
	                $method = 'grid';
	            }
	            //echo "$module$controller/$method";
	            $result = Modules::run($module.$controller.'/'.$method);
	            echo $result;
            }else{
            	echo 'Функция "grid" не доступна!';
            }
        }

        /*
         *  Возвращает содержание страницы по его id
         *  arg: $id_content     - integer - id содержимого (content)
         *
         *  return: string - строка содержимого
         *          false - если не обнаружено
         */
        function get_content($id_content){

            if(is_numeric($id_content)){
               $res = $this->_request('pages', 'pages', 'get_content', $id_content);
            }
            if(!empty($res)) return $res;
            return false;
        }

        /*
         *  Возвращает id содержание страницы по id_page
         *  arg: $id_page     - integer - id страницы
         *       $pg          - integer - номер подстраницы
         *       $config      - array   - дополнительная информация для извлечения
         *  return: numeric - id содержимого страницы
         *          false - если не обнаружено
         */
        function get_id_content($id_page,$pg,$config){

            if(is_numeric($id_page) && isset($pg)){
               //$res = $this->load->module('pages/pages')->id_content($id_page, $pg, $config);
               $params = array($id_page, $pg, $config);

               $res = $this->_request('pages', 'pages', 'id_content', $params);
            }
            if(!empty($res)) return $res;
            return false;
        }

        /*
         *  Возвращает содержимое модулей для страницы по id_page
         *  arg: $id_page     - integer - id страницы
         *
         *  return: string - содержимое модуля
         *          false - если не обнаружено
         */
        function get_modules_content($id_page){
            //$modules_tpl = Modules::run('pages/pages_mod/get_modules_tpl',$id_page);
            //echo "<pre>";
            //print_r($modules_tpl);
            //echo "</pre>";
            $res = '';
            if(!empty($modules_tpl)){
              foreach($modules_tpl as $items){
                if(!empty($items['module']) && !empty($items['controller']) && !empty($items['method']) && isset($items['arg'])){
                  $res .= $this->_reguest($items['module'], $items['controller'], $items['method'], $items['arg']);
                }
              }
            }
            if(!empty($res)) return $res;
            return false;
        }

        /*
         *  Возвращает информацию о странице по его id
         *  arg: $id_page     - integer - id страницы
         *
         *  return: array - массив параметров
         *          false - если не найдено
         */
        function get_pages($id_page){
            if(is_numeric($id_page)){
               $res = $this->_reguest('pages', 'pages', 'get_pages', $id_page);
            }
            if(!empty($res)) return $res;
            return false;
        }

        /*
         *  Возвращает информацию о странице по его id
         *  arg: $id_page     - integer - id страницы
         *
         *  return: array - массив параметров
         *          false - если не найдено
         */
        function id_is_uri($uri){
            if(isset($uri)){
               $res = $this->_request('pages', 'pages', 'id_is_uri', $uri);
            }

            if(!empty($res)) return $res;
            return false;
        }

        /*
         *  Заменяет теги модулей на шаблоны в содержимом
         *  arg: $content     - text - содержимое
         *
         *  return: text - содержимое с заменой
         *
         */
        function replace_mod_content($content){
            if(!empty($content)){
               //$content = $this->_request('pages', 'pages_mod', 'get_content', $content);
               $content = Modules::run('replace/replace/get_content', $content);
            }
            return $content;
        }
        //доступ к визуальному редактору
        //проверка по аутентификации
        //устанавливаем в сессию переменную wysiwyg_filemanager = enable если разрешено
        function _auth_wysiwyg(){

        	if( Modules::run('auth/auth/is_logged')){
              	$this->session->set_userdata('wysiwyg_filemanager', 'enable');
              	//echo 'Установлен wysiwyg_filemanager в сессию';
              	//exit;
            }else{
            	//echo 'НЕ Установлен wysiwyg_filemanager в сессию';
              	//exit;
            }
        	//$this->session->set_userdata('wysiwyg_filemanager', 'enable');
        	//return 'Hello';
		}
}