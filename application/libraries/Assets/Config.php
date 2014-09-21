<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Класс для обработки конфигурационных файлов для приложений
 *
 * Поддержка модульной структуры
 *
 *
 */

class Assets_config
{

        /**
          // кешированные данные по модулям
                // общие атрибуты
                'type'                     => 'type', // тип приложения
                'module'                   => 'module', // модуль
                'type_content'             => 'type_content', // тип содержимого (file, text, url)
                'type_text'                => 'type_text', // тип текста содержимого (попытка вычислить к какому типу относится текст приложения (css,js,jquery и т.п.)))
                'link_site'                => 'link_site', // использовать ссылку с именем домена (boolean)
                'timeout'                  => 'timeout', // таймаут для кеширования
                'url_arr'                  => 'url_arr', // распарсенный url для  содержимого типа 'file' или 'url'
                'place_script'             => 'place_script', // место расположение в html документе скриптов (file, head)
                'place_style'              => 'place_style', // место расположение в html документе стилей (file, head)

                // атрибуты файлов
                'ext_file'                 => 'ext_file', // расширение файла
                'ext_allow'                => 'ext', // допустимые расширения
                'file_name'                => 'file_name', // имя файла с расширением
                'file_source'              => 'file_source', // имя файла указанного как источник
                'hash_source'              => 'hash_source', // хэш файла источника
                'hash_target'              => 'hash_target', // хэш файла назначения
                'time_create_target'       => 'time_create_target',  // время создания приложения назначения (файл или содержимое)

                // атрибуты расположения
                'domain_target'            => 'domain_target', // домен назначения
                'domain_donor'             => 'donor', // домен донор
                'source'                   => 'place_source', // место нахождения файла источника (remote, local, donor)
                'target'                   => 'place', // место положения файла назначения (local, donor)

                // атрибуты директорий
                'dir_type'                 => 'dir', // папки для размещения локальных файлов для каждого типа файла
                'dir_assets'               => 'dir_source_assets',
                'dir_source_assets'        => 'dir_source_assets', // папка размещения локального источника приложений модуля
                'dir_target_modules'       => 'dir_target_modules', // папка для размещения приложений модуля
                'dir_target_public'        => 'dir_target_public', // папка для размещения приложений корневого модуля

                // атрибуты путей
                'path_source_int'          => 'path_int', // полный внутренний путь до источника приложения
                'path_local_target'        => 'path_local_target', // локальный путь (относительный путь к сайту) к файлу назначения http://www.site.com/{path_local_target/}file.ext
                'path_replace_template'    => 'path_for_replace', // замена шаблонных путей в файлах стилей и скриптов
        */
        private $config;

        /**
         *  Зарегистрированные данные
         * @var array
         */
        private $register;

        private $assets;

        /**
         *  Текущий модуль
         * @var type
         */
        private $module;

        /**
         * Текущий тип файла
         * @var type
         */
        private $type;
        private $_dir;

        // Папки источников
        private $_dir_assets;

        // папка для размещения приложений модуля
        private $_dir_target_modules;

        // папка для размещения общих приложений
        private $_dir_target_public;


        public $path_root_target;
        public $path_root_source;

        /**
         *  Публичное имя для данных всех типов
         * @var string
         */
        private $type_public = 'public';

        /**
         *  Публичное имя для данных общего модуля
         * @var string
         */
        private $module_public = '000';

        /**
         *  Разрешенные имена параметров конфигурационных данных
         *  и их значния по умолчанию
         *  Все недопустимые будут игнорироваться
         *
         */
        private $allow_config = array(
                'ext_allow' => array(),
                'domain_target' => 'local',
                'domain_donor' => false,
                'dir_type' => '',
                'dir_source_assets' => 'assets',
                'dir_target_modules' => 'modules',
                'dir_target_public' => 'public',
                'path_replace_template' => array('{assets}/', '{assets}'),
                'place_content' => 'local',
                'split_head' => true,
                'link_site' => true,
                'timeout' => 0,
                'out_file' => true,
        );
        /**
         *  Псевдонимы для конфигурационных переменных
         *  key - имя используемое в программе
         *  value - имя ассоциируемое в файле конфигурации
         * @var type
         */
        private $alias_config = array(
                'ext_allow'                => 'ext', // допустимые расширения
                'domain_target'            => 'domain', // домен назначения
                'domain_donor'             => 'donor', // домен донор
                'dir_type'                 => 'dir', // папки для размещения локальных файлов для каждого типа файла
                'dir_source_assets'        => 'dir_source_assets', // папка размещения локального источника приложений модуля
                'dir_target_modules'       => 'dir_target_modules', // папка для размещения приложений модуля
                'dir_target_public'        => 'dir_target_public', // папка для размещения общих приложений
                'path_replace_template'    => 'path_for_replace', // замена шаблонных путей в файлах стилей и скриптов
                'place_content'            => 'place',     // место нахождения содержимого источника (remote, local, donor)
                'split_head'               => 'split_head', // разделять скрипты в теле страницы или заключать в общий тег
                'out_file'                 => 'out', //выводить или нет файл при запросе на странице (обычное использования для пакетных файлов скриптов и стилей, где требуется вывод только одного или нескольких)
        );

        /**
         * Разрешенные параметры для перезаписи конфигурационных переменных
         * из соства модулей
         * Эти параметры прямо зависят от типа файла, т.к. каждый тип может иметь различные параметры
         * Данное свойство является важным параметром при распределении записи в кеш
         * т.е. данные параметры будут записаны с учетом типа файла.
         * Все остальные параметры записываются с учетом только модуля,
         * т.к. они относятся к модулю в целом и не зависят от типа файла
         *
         * @var type
         */
        private $allow_reconfig = array(
                'link_site',
                'timeout',
                'domain_donor',
                'domain_target',
                'place_content',
                'split_head',
                'path_replace_template',
                'out_file',
        );

        /**
         *  Конструктор принимает основной объект для взаимодействий с ним
         * @param type $obj
         */
        function __construct($obj) {
            if(is_object($obj)){
                $this->assets = $obj;
            }else{
                exit('Основной объект библиотеки "ASSETS" не передан в класс "config"');
            }

            $this->path_root_target = rtrim($_SERVER['DOCUMENT_ROOT'],DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            $this->path_root_source = rtrim($_SERVER['DOCUMENT_ROOT'],DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

            //$this->set_module(false);
            $this->init(false);
        }



        /**
         *  установка параметров
         * @param array $config
         */
        function init($module = false){
            //$allow = array();
            /*
            if(is_array($config)){
               foreach($config as $key=>$value){
                   $name = $this->get_name_alias($value);
                   if($this->is_dependent_type($name)){
                       $allow[$key] = $value;
                   }
               }
           }
             *
             */
           $this->set_module($module);
           $this->_load_config();
//           echo "<pre>";
//           print_r($this->config);
//           echo "</pre>";
        }

        function set_module($_module = ''){
           if($_module !== false){
               $_module OR $_module = CI::$APP->router->current_module();
           }

           $this->module = $_module;
           //echo "<b>Установлен модуль</b> <u>!$_module!</u><br>";
        }

        function get_module(){
            if( ! isset($this->module)){
               $this->set_module();
            }
            return $this->module;
        }

        function set_type($name){
            if(in_array('assets_'.$name, $this->assets->valid_drivers)){
                $this->type = $name;
            }else{
                $this->type = false;
            }
        }

        function get_type(){
            if(isset($this->type)) return $this->type;
            return false;
        }



        /**
         * Загрузка конфигурационного файла относительно модуля
         *
         * @param string $module - имя модуля (false = основной файл)
         *
         */
        function _load_config($config = false){
//            echo "Загрузка файла конфиг<br>";
            $module = $this->get_module();

//            echo "Текущий модуль: ";
//            var_dump($module);
//            echo "<br>";
            if($module === false){
                $default_module = $this->module_public;
            }else{
                $default_module = $module;
            }

            if( ! isset($this->config[$default_module])){
                $arr_config = CI::$APP->config->load($file = 'assets', $use_sections = true, $fail_gracefully  = FALSE, $module);
                //$arr_config = $this->config[$default_module];
            }else{
               //$arr_config = CI::$APP->config->load($file = 'assets', $use_sections = true, $fail_gracefully  = FALSE, $module);
            }

            if(isset($arr_config) && is_array($arr_config) && count($arr_config) > 0){
//            echo "<pre>Конфиг из файла модуля<br>";
//            print_r($arr_config);
//            echo "</pre>";
                foreach($arr_config as $key=>$value){
                    $this->set_type(false);
                    $name = $this->get_name_alias($key);

                    if($key == 'setting' && is_array($value)){
                        foreach($value as $type=>$items){
                            $this->set_type($type);
                            foreach($items as $key_param=>$value_param){
                                $name = $this->get_name_alias($key_param);
                                if($this->is_allow_param($name)){
                                    $this->set($name, $value_param);
                                }
                            }
                        }
                    }else{
                        if($this->is_allow_param($name)){
                            $this->set($name, $value);
                        }
                    }
                }
            }
//            echo "<pre>Весь конфиг<br>";
//            print_r($this->config);
//            echo "</pre>";
        }
    /*
     * Устанавливаем значение параметра
     */
    function set($name, $value){
        if(!empty($name)){
            $module = $this->get_module();
            if($module === false){
                $module = $this->module_public;
            }
            $name = $this->get_name_alias($name);
            if($this->is_allow_param($name)){
                    $type = $this->get_type();
                    if($type){
                        $this->config[$module][$type][$name] = $value;
                    }else{
                        $this->config[$module][$this->type_public][$name] = $value;
                    }
            }else{
                $this->assets->set_error(__CLASS__,__METHOD__,'debug', 'Не удалось установить значение параметра "'.$name.'", т.к. он не разрешен.');
            }
        }else{
            $this->assets->set_error(__CLASS__,__METHOD__,'debug', 'Не удалось установить значение параметра, т.к. имя параметра не задано');
        }
    }
    /*
     * Возвращаем значение параметра
     */
    function get($name){
        $module = $this->get_module();
        if($module === false){
             $module = $this->module_public;
        }
        $name = $this->get_name_alias($name);
        $type = $this->get_type();
        //echo 'модуль: '.$module.'<br>';
        //echo 'Тип: '.$type.'<br>';
        // проверяем, разрешен ли данный параметр
        if($this->is_allow_param($name)){
            // если параметров данного модуля нет назначаем основной модуль
            if(!isset($this->config[$module])){
                //$this->assets->set_error(__CLASS__,__METHOD__,'debug', 'Не удалось найти значение параметра "'.$name.'" для модуля '.$module);
                $module = $this->module_public;
            }
            // если параметра нет и в основном модуле завершаем работу
            if(!isset($this->config[$module])){
                $this->assets->set_error(__CLASS__,__METHOD__,'fatal', 'Не удалось найти значение параметра "'.$name.'" и в основном модуле! Параметр '.$name.' должен обязательно присутствовать хотя бы в основном модуле системы!');
                return;
            }
            // возвращаем значение если оно зависит от типа файла
            if($type){
                if(isset($this->config[$module][$type][$name])) return $this->config[$module][$type][$name];
                //$this->assets->set_error(__CLASS__,__METHOD__,'debug', 'Не удалось вернуть значение параметра "'.$name.'", т.к. он не найден в конфигурационном файле. Файл зависит от типа '.$type);

            }
            // возвращаем значение, если оно не зависит от типа файла
            if(isset($this->config[$module][$this->type_public][$name])){
                return $this->config[$module][$this->type_public][$name];
            }
            $this->assets->set_error(__CLASS__,__METHOD__,'debug', 'Не удалось вернуть значение параметра "'.$name.'", т.к. он не найден в конфигурационном файле основного модуля и общего типа');

            // возвращаем значение по умолчанию, если оно не указано в настройках
            return $this->default_value($name);

        }else{
            $this->assets->set_error(__CLASS__,__METHOD__,'debug', 'Не удалось вернуть значение параметра "'.$name.'", т.к. он не разрешен. Файл зависит от типа '.$type);
        }
        return;
    }
    /**
     *  Возвращает значение параметра по умолчанию
     * @param type $name
     */
    function default_value($name){
        foreach($this->allow_config as $type=>$value){
            if($type == $name){
                return $value;
            }
        }
        return null;
    }
    /**
     * Возвращает параметр по имени модуля и типу файла
     * @param $name - имя параметра
     * @param $module - имя модуля
     * @param $type - тип файла
     */
    function get_param($name, $module, $type){
        $module = $this->set_module($module);
        $type = $this->set_type($type);
        $name = $this->get_name_alias($name);

        return $this->get($name);
    }

    /**
     * Возвращает ВСЕ параметры по имени модуля и типу файла
     *
     * @param $module - имя модуля
     * @param $type - тип файла
     */
    function get_params($module, $type){
        foreach($this->allow_config as $key=>$value){
            $params[$key] = $this->get_param($key, $module, $type);
        }
        return $params;
    }


    /*
     *  Определяет является ли параметр допустимым
     */
    function is_allow_param($name){
        if(isset($this->allow_config[$name])){
            return true;
        }
        return false;
    }

    /**
     *  Определяет зависит ли данный параметр от типа файла
     * @param string $name
     * @return boolean
     */
    function is_dependent_type($name){
        if(in_array($name, $this->allow_reconfig)){
            return true;
        }else{
            return false;
        }
    }
    /**
     *  Возвращает оригинальное имя параметра если оно является псевдонимом
     *  Имеется ввиду если имя параметра в конфигурационном файле отличается от имени используемого в программе
     *
     * @param string $name
     * @return string
     */
    function get_name_alias($name){
        if(in_array($name, $this->alias_config)){
            $arr = array_keys($this->alias_config, $name);
            $name = $arr[0];
        }
        return $name;
    }



    function get_path_root_target(){
        return $this->path_root_target;
    }
    function get_path_root_source(){
        return $this->path_root_source;
    }

    /**
    * Возвращает локальный путь к источнику
    *	@param $module - имя модуля
    *	@param $type - тип файла, если false вернёт путь без учета типа.
    *
    *	@return string - путь до папки assets указанного модуля + (папка типа, если указан)
    *
    */
    function get_path_module_source($module = false, $type = false){   		$path_app = $this->assets->get_path_application();
   		if($module === false){   			$path = $path_app.$this->get('dir_source_assets').'/';
   		}else{   			$path = $path_app.'modules/'.$module.'/'.$this->get('dir_source_assets').'/';
   		}
   		if($type !== false){   			$path .= $type.'/';
   		}
   		return $path;
    }

    // вывод информации конфига
    function print_config(){    	echo '<pre>';
    	print_r($this->config);
    	echo '</pre>';
    }
}