<?php (defined('BASEPATH')) OR exit('No direct script access allowed');



class Assets extends CI_Driver_Library
{
    public $valid_drivers;
    public $CI;



    // объект для работы с содержимым
    public $content;

    // объект для хранения и обработки данных
    public $data_collect;

    // объект для обработки данных файла источника
    private $source;

    // объект для обработки данных файла назначения
    private $target;

    // объект для обработки данных конфигурации
    public $config;

    // объект для обработки места расположения содержимого
    private $place;

    private $logs = array();

    private $errors = array();

    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->config->load('assets', TRUE);
        $this->valid_drivers = $this->CI->config->item('type', 'assets');

        $this->include_library_config();
        $this->include_library_content();
        $this->include_library_data();
        $this->include_library_place();
        $this->include_library_source();
        $this->include_library_target();

        log_message('debug', "Assets Class Initialized");
    }
    /**
     * Подключение класса для работы конфигурационных данных
     */
    function include_library_config(){
        if( ! class_exists("Assets_config")){
            if(is_file(dirname(__FILE__).'/Config.php')){
                include_once dirname(__FILE__).'/Config.php';
            }else{
                $this->set_error(__CLASS__, __METHOD__, 'fatal', 'Не обнаружен файл "Config.php" для работы библиотеки Assets');

            }
        }
        $this->config = new Assets_config($this);
    }

    /**
     * Подключение класса для обработки содержимого
     */
    function include_library_content(){
        if( ! class_exists("Assets_content")){
            if(is_file(dirname(__FILE__).'/Content.php')){
                include_once dirname(__FILE__).'/Content.php';
            }else{
                $this->set_error(__CLASS__, __METHOD__, 'fatal', 'Не обнаружен файл "Content.php" для работы библиотеки Assets');

            }
        }
        $this->content = new Assets_content($this);
    }

    /**
     * Подключение класса для обработки и хранения данных
     */
    function include_library_data(){
        if( ! class_exists("Assets_data_collect")){
            if(is_file(dirname(__FILE__).'/Data_collect.php')){
                include_once dirname(__FILE__).'/Data_collect.php';
            }else{
                $this->set_error(__CLASS__, __METHOD__, 'fatal', 'Не обнаружен файл "Data_collect.php" для работы библиотеки Assets');

            }
        }
        $this->data_collect = new Assets_data_collect($this);
    }

    /**
     * Подключение класса для работы с файлом источника
     */
    function include_library_source(){
        if( ! class_exists("Assets_source")){
            if(is_file(dirname(__FILE__).'/Source.php')){
                include_once dirname(__FILE__).'/Source.php';
            }else{
                $this->set_error(__CLASS__, __METHOD__, 'fatal', 'Не обнаружен файл "Source.php" для работы библиотеки Assets');
            }
        }
        $this->source = new Assets_source($this);
    }

    /**
     * Подключение класса для работы с файлом назначения
     */
    function include_library_target(){
        if( ! class_exists("Assets_target")){
            if(is_file(dirname(__FILE__).'/Target.php')){
                include_once dirname(__FILE__).'/Target.php';
            }else{
                $this->set_error(__CLASS__, __METHOD__, 'fatal', 'Не обнаружен файл "Target.php" для работы библиотеки Assets');
            }
        }
        $this->target = new Assets_target($this);
    }

    /**
     * Подключение класса для обработки места расположения содержимого
     */
    function include_library_place(){
        if( ! class_exists("Assets_place")){
            if(is_file(dirname(__FILE__).'/Place.php')){
                include_once dirname(__FILE__).'/Place.php';
            }else{
                $this->set_error(__CLASS__, __METHOD__, 'fatal', 'Не обнаружен файл "Place.php" для работы библиотеки Assets');
            }
        }
        $this->place = new Assets_place($this);
    }



    /**
    *   Переопределение домена назначения для типа файлов для каждого модуля
    *
    */
     /*
    protected  function set_domain($domain, $module = '', $type){
        if($this->check_domain($domain)){
            $_module OR $_module = CI::$APP->router->current_module();
            $this->set_domain_target($domain, $_module, 'script');
        }
        return false;
    }
      *
      */

    /**
    *  Переопределение домена назначения для каждого модуля
    *
    */
    /*
    private function set_domain_target($domain, $module = 0, $type = 0){
        if(!empty($domain)){
            if(!strpos('http://', $domain)){
                $domain = 'http://'.$domain;
            }
            if(strpos('.',$domain)){
                if(!empty($module)){
                    $this->domain_target[$module][$type] = $domain;
                }
            }
        }
        $this->domain_target[$module][$type] = false;
    }
     *
     */

     /*
        *  Возвращает основной путь к файлам приложения
        */
        function get_assets_path($module = null){
            $path = trim($this->CI->config->item('tpl_path', 'assets'),'\/').'/'.trim($this->CI->config->item('tpl_name', 'assets'),'\/').'/';
            if($module === false){            	//$path .= trim($this->CI->config->item('dir_target_public', 'assets'),'\/').'/';
            	$path .= trim($this->data_collect->get('dir_target_public'),'\/').'/';
            }
            if(is_string($module)){
            	$path .= trim($this->data_collect->get('dir_target_modules'),'\/').'/'.$module.'/';
            }
            return $path;
        }

        /**
        * Возвращает путь до источника модуля
        *	@param $module - имя модуля
        *	@param $type - тип приложения (папка)
        *
        */
        function get_assets_path_source($module = false, $type = false){        	return $this->config->get_path_module_source($module, $type);
        }

        /*
        *  Возвращает путь к файлам для загрузки
        */
        function get_assets_uploads($file = false){
            $path = trim($this->CI->config->item('path_uploads', 'assets'),'\/').'/';
            if($file) $path .= $file;
            return $path;
        }

        /*
        *  Возвращает путь к визуальному редактору (wysiwyg)
        */
        function get_assets_wysiwyg(){
            $path = trim($this->CI->config->item('path_wysiwyg', 'assets'),'\/').'/';
            return $path;
        }

        /*
        *  Возвращает корректный путь к папке с приложением
        */
        function get_path_application(){
            $path_app = rtrim(APPPATH, '\/').'/';
            $path_root_source = $this->config->path_root_source;
            if(strstr($path_app, $path_root_source)){
              $path = str_replace($path_root_source, '', $path_root_source);
            }else{
              $path = $path_app;
            }
            return $path;
        }

    /**
        *  Получение имени домена назначения
        *
        */
        /*
        function get_domain_target($module = 0, $type = 0){
            if(!empty($this->domain_target[$module][$type])){
                return $this->domain_target[$module][$type];
            }
            return false;
        }
         *
         */

     /**
         * Загрузка файла состава модуля
         *
         * @param string $file  name file without extention
         * @param string $module  name module
         * @param string $type    type (style, js, img)
         */
        public function _load_file($file, $module = '', $type = 'style', $config = ''){
            //очистка параметров текущих данных
            $this->data_collect->clear_current();

            //установка обязательного параметра - модуля
            //echo "Загрузка файла приложений: модуль-$module, тип-$type, файл-$file<br>";
            $this->set_module($module);
            $_module = $this->get_module();
            //echo "Сгенерированный модуль: $_module<br>";
            // запись в кеш: имя модуля
            if(!empty($_module)){
                $this->data_collect->set('module', trim($_module, '/'));
            }else{
                $this->data_collect->set('module', '');
            }
            //echo "<b>Модуль указаный: ".$module."</b><br>";
            //echo "<b>Модуль определившийся: ".$_module."</b><br>";

            // установка обязательного параметра - типа
            $this->set_type($type);
            $_type = $this->get_type();

            // запись в кеш: тип файла
            $this->data_collect->set('type', $_type);

            // инициализация конфигурационных файлов
            $this->init($config);

            // запись в кеш: имя файла переданного
            $this->data_collect->set('file_source', $file);

            // установка типа содержимого строки
            $this->set_source();

            // вычисление и установка дополнительных параметров файла
            $this->set_param_file();

            // локальный путь (относительный путь к сайту) к файлу назначения
            $this->data_collect->set('path_local_target', $this->target->get_path_local());

            // поиск идентичного файла,
            // если уже имеется возвращаем true
            if($this->search()){
                $this->set_log('Файл приложения "'.$this->data_collect->get('file_name').'" уже существует');
                // загрузка параметров текущего файла из кэша
                //$this->load_current_file();
                return true;
            }

            // запись в кеш: папка типа
            $this->data_collect->set('dir_type', $this->config->get('dir_type'));

            // место назначения содержимого
            $this->data_collect->set('place_content', $this->place->get_place());



            // если таймаут не обнаружен проводим дальнейшую обработку
            if($this->timeout_finish()){
                // проверяем на существование файла источника
                if($this->isset_source()){
                    // проверяем на идентичность
                    if( ! $this->is_identical()){

                        $this->copy_file();
                    }else{

                        $this->set_timeout_file();
                    }
                }else{
                    $this->set_log('Файл "'.$this->data_collect->get('file_name').'" отсутсвует в источнике приложений модуля '.$this->data_collect->get('module'));
                    $this->set_error(__CLASS__, __METHOD__, 'fatal', 'Не найден файл "'.$this->data_collect->get('file_name').'" в источнике приложений модуля '.$this->data_collect->get('module'));

                }
            }


            // регистрация файла если он есть
            if($this->isset_target()){
                    if($this->data_collect->write()) return true;
            }else{
                $this->set_log('Файл '.$this->data_collect->get('file_name').' отсутсвует в месте назначения модуля '.$this->data_collect->get('module'));
                $this->set_error(__CLASS__, __METHOD__, 'fatal', 'Не найден файл "'.$this->data_collect->get('file_name').'" в месте назначения модуля '.$this->data_collect->get('module'));

            }

            return false;
        }
        /**
         *  Инициализация конфигурационных параметров
         * @param array $config
         */
        function init($config = array()){
            $_module = $this->get_module();
            $this->config->init($_module);
            //echo "<pre>Параметры в данных, модуль: ".$this->config->get_module()." <br>";
            $params = $this->get_params($_module,$this->data_collect->get('type'));

            //print_r($params);
            //echo "</pre>";
            foreach($params as $key=>$value){
                if(isset($config[$key]) && $config[$key] != $value){
                    $this->data_collect->set($key, $config[$key]);
                }else{
                    $this->data_collect->set($key, $value);
                }
            }
        }
        /**
         *  Получение всех конфигурационных параметров
         *  в зависимости от модуля и типа файла
         * @param string $module
         * @param string $type
         */
        function get_params($module, $type){
            return $this->config->get_params($module, $type);
        }

        /**
         * Установка таймаута для файла
         * Заключается в изменении времени создания файла на текущую
         */
        function set_timeout_file(){
            if($this->target->set_current_time_file()){
                $this->set_log('Изменено время создание файла "'.$this->data_collect->get('file_name').'" на текущее');
            }else{
                $this->set_error(__CLASS__, __METHOD__, 'fatal', 'Не удалось изменить время создания файла "'.$this->data_collect->get('file_name').'" на текущее');

            }
        }

        /**
         * Загрузка параметров файла из кэша
         */
        function load_current_file(){
            $this->data_collect->clear_current();
            $arr = $this->data_collect->search($module, $type, $path_local);
            foreach($arr as $key=>$value){
                $this->data_collect->set($key, $value);
            }
        }

        /**
         * Установка дополнительных параметров файла
         */
        function set_param_file(){
            // проверка правильновсти имени домена
            $domain = $this->data_collect->get('domain_target');
            if($domain){
                if( ! $this->content->check_domain($domain)){
                    $domain = false;
                }
            }
            if($domain === false){
                $domain = $this->CI->config->item('base_url');
                $this->data_collect->set('domain_target', $domain);
            }

            // парсинг строки файла, на определение принадлежности источника
            // и запись этих параметров
            $this->source->parse_string();

        }

        /**
        *  поиск идентичных файлов
        *
        */
        function search(){
            $file = $this->data_collect->get('file_name');
            $module = $this->data_collect->get('module');
            $type = $this->data_collect->get('type');
            $path_local = $this->data_collect->get('path_local_target');

            if(($this->data_collect->search($file, $module, $type, $path_local))){
                $this->set_log('Найден идентичный файл для файла "'.$this->data_collect->get('file_name').'"');
                return true;
            }
            return false;
        }

        function timeout_finish(){
            $timeout = $this->data_collect->get('timeout');
            if(!empty($timeout)){
                $time = time();
                $time_c = $this->target->time_create_file();
                $time_delta = $time - $time_c;
//                echo "Текущее время: ".date('d-m-Y H:i:s',$time).'<br>';
//                echo 'Время создания файла '.$this->data_collect->get('file_name').': '.date('d-m-Y H:i:s',$time_c).'<br>';
                if($time_delta < $timeout){
                    $this->set_log('Файл '.$this->data_collect->get('file_name').' имеет таймаут в '.$timeout.' сек.');
                    return false;
                }
            }
            return true;
        }

        // проверка на существование файла в месте назначения
        function isset_target(){
            if($this->target->is_file()){
                return true;
            }
            return false;
        }

        // проверка на существование файла в месте источника
        function isset_source(){
            if($this->source->is_file()){
                return true;
            }
            return false;
        }

        // проверка на идентичнность файла источника и назначения
        function is_identical(){
//            echo "Хеш файла ".$this->data_collect->get('file_name').' источника '.$this->source->hash_file().'<br>';
//            echo "Хеш файла ".$this->data_collect->get('file_name').' назначения '.$this->target->hash_file().'<br>';
            if($this->source->hash_file() == $this->target->hash_file()){
                $this->set_log('Файл источника и файл назначения "'.$this->data_collect->get('file_name').'" равны');
                return true;
            }

            $this->set_log('Файл источника и файл назначения "'.$this->data_collect->get('file_name').'" различны');
            return false;
        }

        /**
         * Устанавливает текущий модуль
         * @param type $module
         */
        function set_module($module){
           $this->config->set_module($module);
           $this->set_log('Установка модуля '.$module.' для конфигурациионных файлов');
        }

        /**
         * Возвращает текущий модуль
         * @param type $module
         */
        function get_module(){
            return $this->config->get_module();
        }

        /**
         *
         * @param type $name
         */
        function set_type($name){
            if(in_array('assets_'.$name, $this->valid_drivers)){
                $this->config->set_type($name);
            }else{
                $this->config->set_type(false);
            }
            $this->set_log('Установка типа файла '.$name.' для конфигурациионных файлов');
        }
        /**
         *  Возвращает текущий тип файла
         * @param type $name
         */
        function get_type(){
            return $this->config->get_type();
        }
        /**
         * Устанавливает тип введеной строки
         */
        function set_source(){
            // автоматическое определение типа строки
            $type_string = $this->source->auto_set();
            $this->data_collect->set('type_string', $type_string);
        }

        /**
         *  Возвращает внутренний путь файла
         * @param type $file_name
         */
        function get_path_local(){
            return $this->source->get_path_local();
        }

        function get_path_target_local_module(){
            return $this->target->get_path_local_module();
        }

        function copy_file(){
            $content = $this->source->get_content();
            $this->target->put_content($content);
        }
        /**
        *  Метод обработки ошибок
        *  @param string $class - класс
        *  @param string $method - метод
        *  @param string $level  - уровень ошибки
        *  @param string $message - сообщение
        */
        function set_error($class, $method, $type, $message ){
            $this->errors[] = array('class' => $class,
                                    'method' => $method,
                                    'type' => $type,
                                    'message' => $message,
                              );
            log_message($type, $message);
        }

        	/**
	*  Установка логов системы
	*  @param string $message
	*/
        function set_log($message){
            $this->logs[] = $message;
        }

        function logs(){
            echo "<h2>Лог ASSETS</h2>";
            foreach($this->logs as $item){
                echo $item;
                echo "<br />";
            }
        }

        function out_config(){
            $data = $this->data_collect->get_config();
            if($data){
                foreach($data as $module=>$items){
                    echo "<pre>";
                    echo "<b>Модуль: $module</b><br>";
                    print_r($items);
                    echo "</pre>";
                }
            }else{
                echo "Данных не записано!";
            }
        }

        function errors(){
            echo "<h2>Ошибки ASSETS</h2>";
            foreach($this->errors as $item){
                echo "<div style=\"padding:5px; border: 1px solid #000000\">";
                echo "Класс - ".$item['class']."<br />";
                echo "Метод - ".$item['method']."<br />";
                echo "Тип - ".$item['type']."<br />";
                echo "Сообщение - ".$item['message']."<br />";
                echo "</div>";
            }
        }

        /*
         * Загрузка файлов стиля
         * @param string - имя файла
         * @param string - имя модуля (false - основной модуль)
         * @param array - параметры файла
         */
        function load_style($file, $module = '', $config = array()){
             $this->style->load($file, $module, $config);
        }
        /*
         * Загрузка файлов скрипта
         * @param string - имя файла
         * @param string - имя модуля (false - основной модуль)
         * @param array - параметры файла
         */
        function load_script($file, $module = '', $config = array()){
            $this->script->load($file, $module, $config);
        }
        /*
         * Загрузка изображений
         * @param string - имя файла
         * @param string - имя модуля (false - основной модуль)
         * @param array - параметры файла
         */
        function load_img($file, $module = '', $config = array()){
             $this->img->load($file, $module, $config);
        }
        /*
         * Вывод файлов стилей
         * @param string - имя файла
         * @param string - имя модуля (false - основной модуль)
         * @param array - параметры файла
         * @param boolean - возвращать ли содержимое файла (true - содержимое файла, false - ссылка на файл)
         */
        function out_style($file = false, $module = '', $config = array(), $return = false){
            //$this->style->load($file, $module, $config);
            return $this->style->out($file, $module, $config, $return);
        }
        /*
         * Вывод файлов скрипта
         * @param string - имя файла
         * @param string - имя модуля (false - основной модуль)
         * @param array - параметры файла
         * @param boolean - возвращать ли содержимое файла (true - содержимое файла, false - ссылка на файл)

         */
        function out_script($file = false, $module = '', $config = array(), $return = false){
            //$this->script->load($file, $module, $config);
            return $this->script->out($file, $module, $config, $return);
        }
        /*
         * Вывод изображений
         * @param string - имя файла
         * @param string - имя модуля (false - основной модуль)
         * @param array - параметры файла
         * @param boolean - возвращать ли содержимое файла (true - содержимое файла, false - ссылка на файл)
         */
        function out_img($file, $module = '', $config = array(), $return = false){
            //$this->img->load($file, $module, $config);
            return $this->img->out($file, $module, $config, $return);
        }

        /**
        *	Вывод файлов с окружением (обычное применение, вывод скриптов и стилей)
        *	@param string - file, файл вывода(если не указан, все файлы данного типа)
        *	@param string - module, модуль вывода(если не указан, но файлы всех модулей)
        *	@param string - type, тип файла
        *	@param array - массив с конфигурацией для вывода файлов
        *
        *	@return string - строка с форматированными данными файлов
        */
        function _out($file = false, $module = '', $type = '', $config = array()){
            if( ! in_array('assets_'.$type, $this->valid_drivers)){
                $this->set_error(__CLASS__, __METHOD__, 'fatal', 'Не верно указан тип файла "'.$type.'" при выводе');

                return false;
            }
            if($file === false){
                if($module === false) $module = $this->config->get_module();
                $path = false;
            }else{
                if($this->_load_file($file, $module, $type)){
                    $file = $this->data_collect->get('file_name');
                    $module = $this->data_collect->get('module');
                    $type = $this->data_collect->get('type');
                    $path = $this->data_collect->get('path_local_target');
                }else{

                    return false;
                }

            }
            $data = $this->data_collect->get_data($file, $module, $type, $path);
            $result = $this->target->get_file_surround($data, $config);
            //echo "<pre>";
            // print_r($data);
            //echo "</pre>";
            return $result;
        }

        /*
         * Возвращает ссылку на файл/ы
         * @param $file - имя файла, если false то все файлы данного типа
         * @param $module - имя модуля, не влияет если первый параметр false
         * @param $type - тип файла
         * @param $path - путь файла
         * @param $config - окружение для файлов (tags_open, tags_close, tags_file)
         */
        function get_link($file, $module, $type, $path, $config = array()){
            return $this->target->get_link($file, $module, $type, $path, $config);
        }

        /**
        *	Возвращает содержимое файла
        *
        */
        function get_content($file, $module, $type, $path){
            return $this->target->get_content($file, $module, $type, $path);
        }

        /**
        *	Возвращает путь к файлу (включая сам файл)
        *	@param string - имя файла
        *	@param string - имя модуля
        *	@param string - тип файла
        *	@param array - массив с конфигурацией для загрузки (опционально)
        *	@param boolean - возвращать путь к файлу или выводить в браузер
        *
        *	@return string - путь к файлу (включая сам файл)
        */
        function _file($file = false, $module = '', $type = '', $config = array(), $return = false){
            if( ! in_array('assets_'.$type, $this->valid_drivers)){
                $this->set_error(__CLASS__, __METHOD__, 'fatal', 'Не верно указан тип файла "'.$type.'" при выводе');
                return false;
            }
            $registr = false;
            if($this->_load_file($file, $module, $type, $config)){
                $registr = true;
            }

            if( ! $registr) return false;
            //$this->$type->load($file, $module, $config);
            $file = $this->data_collect->get('file_name');
            $module = $this->data_collect->get('module');
            $type = $this->data_collect->get('type');
            $path = $this->data_collect->get('path_local_target');
            $data = $this->data_collect->get_data($file, $module, $type, $path);

            if($data){
                /*
                if($return){
                    $result = $this->get_content($file, $module, $type, $path);
                }else{
                    $result = $this->get_link($file, $module, $type, $path);
                }

                */
                $result = $this->get_link($file, $module, $type, $path);
                //echo "Файл <b>$file</b>, результат $result<br>";

                return $result;
            }
            return false;
        }

        /**
         *  Пакетная обработка файлов
         * @param type $dir - папка с файлами
         * @param type $file_out - файлы для публикации (остальные только копируются)
         * @param type $module - имя модуля
         * @param type $type - тип файлов
         * @param type $config - дополнительные параметры для загрузки
         */
        function _package($dir, $file_out, $module, $type, $config = array()){
            $files = $this->get_dir_files($dir, $module, $type, $config);
            if(!empty($file_out)){            	if( ! is_array($file_out)){            		$file_out = array($file_out);
            	}
            }
            //регистрируем сначало файлы указанные для вывода,
            //что бы сохранить очередность
            if(is_array($file_out) && is_array($files)){            	foreach($file_out as $item){            		if(in_array($item, $files)){            			$config['out_file'] = true;
                		$this->_load_file($item, $module, $type, $config);
            		}
            	}
            }
            if(is_array($files)){

                foreach($files as $key=>$value){
                	//echo 'Файл'.$value.'<br>';

                	//проверяем требуется ли публикация файла
                	$config['out_file'] = false;
                	if(is_array($file_out)){                   		if(in_array($value, $file_out)){                   			$config['out_file'] = true;
                   		}
                	}
                	if($file_out === true) $config['out_file'] = true;

                	if($this->_load_file($value, $module, $type, $config)){
                    	//echo 'Файл "'.$value.'" зарегистрирован';
                	}else{                		//echo 'Файл "'.$value.'" НЕ зарегистрирован';
                	}
                	//return false;
                }
            }else{            	//$this->set_log('Не найдены файлы приложений assets в папке "'.$dir.'" для регистрации.');
            	$this->set_error(__CLASS__, __METHOD__, 'error', 'Не найдены файлы приложений assets в папке "'.$dir.'" для регистрации.');
            }
            return true;

        }

		/**
		* Возвращает все файлы в каталоге
		*
		*
		*/
        private function get_dir_files($dir, $module, $type, $config){        	//очистка параметров текущих данных
            $this->data_collect->clear_current();

            //установка обязательного параметра - модуля
            //echo "Загрузка файла приложений: модуль-$module, тип-$type, файл-$file<br>";
            $this->set_module($module);
            $_module = $this->get_module();

            //загрузка конфигурационного файла для модуля, на случай если он не подгружен
            $this->config->init($_module);
            //echo "Сгенерированный модуль: $_module<br>";
            // запись в кеш: имя модуля
            if(!empty($_module)){
                $dir_module = 'modules/'.$_module.'/';
            }else{
                $dir_module = '';
            }
            // установка обязательного параметра - типа
            $this->config->set_type($type);
            $_type = $this->config->get_type();
            // инициализация конфигурационных файлов
            //$this->init($config);


            // папка типа
            $dir_type = $this->config->get('dir_type');

            $path_load = APPPATH.$dir_module.$this->config->get('dir_source_assets').'/'.$dir_type;

            //echo 'Переданный модуль: '.$_module.'<br />';
            //echo 'Переданный тип: '.$_type.'<br />';
            //echo 'Директория для типа: '.$dir_type.'<br />';
            //echo 'Путь к папке: '.$path_load.'<br />';
            if(is_dir($path_load.'/'.$dir)){            	//echo '<pre>';
            	//print_r($this->get_list_files($path_load, $dir));
                //echo '</pre>';
            	//echo 'такая директоря есть!<br>';
            	return $this->get_list_files($path_load, $dir);
            }else{            	//echo 'нет такой директории!<br>';
            	$this->set_error(__CLASS__, __METHOD__, 'error', 'Не найдена папка приложений assets "'.$path_load.'/'.$dir.'" для регистрации.');
                //$this->set_log('Не найдена папка приложений assets "'.$path_load.'/'.$dir.'" для регистрации.');
            }
            return false;
            $this->config->print_config();
        }

        /**
        * Возвращает список файлов из данной категории
        *	Включая вложенные папки
        * 	@param string - директория для сканирования
        *   @return array - массив со списком файлов
        *					файлы в папка возвращается в виде dir/dir2/dir3/file
        */
        private function get_list_files($path, $dir){        	$dir_open = $path.'/'.$dir;
        	$arr_files = array();
        	if(is_dir($dir_open)){        		$od = opendir ($dir_open);
				while ($file = readdir ($od)){
					if($file <> "." && $file <> ".."){
				    	if (is_file($dir_open.'/'.$file)){
                        	$arr_files[] = $dir.'/'.$file;
				    	}else{                            $arr_files = array_merge($arr_files, $this->get_list_files($path, $dir.'/'.$file));
				    	}
				   	}
				}
				closedir ($od);
        	}
        	if(isset($arr_files) && is_array($arr_files)){        		return $arr_files;
        	}
        	return false;
        }
}
