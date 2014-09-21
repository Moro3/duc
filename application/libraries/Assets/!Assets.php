<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class CI_Assets {

	private $config = array();
	private $is_loaded = array();
	private $_config_paths = array();
	private $_type = array();
	private $_link_site = false; //вид ссылки на файл false -(без http://имя сайта/)
                           //                   true - (c http:/имя сайта/)

  // local - локально (будет формироваться ссылка на файл, файл будет записан в место назначения)
  // head  - в теле страницы (файл будет размещен в виде текств в заголовке страницы (только для типов - script, style), скрипт модуля будет записан в место назначения)
  // remote - ссылка на файл будет ссылаться на удаленный ресурс (если имя файла указано с полным url)
  private $_place_script = 'local'; // место размещения скрипта
  private $_place_style = 'local';  // место размещения файла стилей
  private $_place_img = 'local';    // место размещения изображения
  private $_place_doc = 'local';    // место размещения документа

  /**
  *  Домены назначения по умолчанию. Имена доменов на которые будут ссылаться ссылка,
  *  на тот случай если подгрузка будет осуществлятся с другого сервера.
  *  Однако файл будет записан локально, т.к. удаленный сервер будет черпать информацию с конечно размещенным файлом на локальном сервере
  *  Не совсем удобно в плане производительности (т.к. локальному серверу приходится перемещать файл к себе полюбому)
  *  Возможно это нужно будет подкорректировать для снижении нагрузки
  *  Возможно переопределение имени домена для каждого типа файла в каждом модуле
  *  Что бы переопределить домен обратитесь к методу set_domain_target($domain, $module),
  *  данный домен будет переопределен только для текущего модуля
  */
  private $_domain_script = '';
  private $_domain_style = '';
  private $_domain_img = '';
  private $_domain_doc = '';

  // массив с парамтрами имен домена для каждого модуля и типа файла
  private $domain_target;

  // массив с парамтрами таймаута на копирование файла в папку назначения для каждого модуля и типа файла
  private $timeout_target;
  private $timeout_default = 0;
  
  /**
  *   Все нижеприведенные пути отчитываются от пути шаблона
  *   т.е. если шаблон расположен по адресу /tpl/default, путь к изображениям будет: /tpl/default/dir_img
  *   модульные приложения будут отсчитываться в таком виде: /tpl/default/dir_modules/name_module/dir_img (name_module - будет вычислен автоматически, если он не указан)
  */
  // Папки источников
  private $_dir_assets = 'assets'; // папка размещения источника приложений модуля

  // Папки конечных данных
  //var $_dir_target_img     = 'img';  // папка размещения изображений
  //var $_dir_target_script  = 'js';   // папка размещения скриптов
  //var $_dir_target_style   = 'css';   // папка размещения таблиц стилей
  private $_dir_target_modules = 'modules';  // папка для размещения приложений модуля
  private $_dir_target_public = 'public';  // папка для размещения приложений модуля
  
  /**
  *  Параметры удаленного домена
  */
  private $_domain_donor = '';  // имя удаленного домена (http://site.ru)
  private $_donor_access = false; // разрешение на доступ к удаленному домену (false - доступа нет)
  
  /**
  *  Время таймаутов при перемещении
  */
  private $_time_local_script = 0;
  private $_time_local_style = 0;
  private $_time_local_img = 0;
  private $_time_local_doc = 0;
  
  // локальные корневые пути для файлов назначения и источников (обычно совпадают)
  private $path_root_target;
  private $path_root_source;
  
  // массив со значениями которые требуется заменять на основной (начальный) путь к файлам приложения
  // применяется для файлов типов файлов script и style
  private $path_for_replace = array('{assets}/',
                                    '{assets}'
                                    );
  
	/**
	 * Constructor
	 *
	 * Sets the $config data from the primary config.php file as a class variable
	 *
	 * @access   public
	 * @param   string	the config file name
	 * @param   boolean  if configuration values should be loaded into their own section
	 * @param   boolean  true if errors should just return false, false if an error message should be displayed
	 * @return  boolean  if the file was successfully loaded or not
	 */
	function __construct()
	{
            $this->config =& get_config();
            $this->_type = array(
                         'style' => array('css'),
                         'script'  => array('js'),
                         'images'  => array('jpg','jpeg','gif','png','tiff','bmp','ico'),
                         'img'  => array('jpg','jpeg','gif','png'),
                         'text' => array('doc','docx','xls','xlsx','txt','rtf','pdf','ppt'),
                         'arj'  => array('zip','rar','jar','bz2','gz','tar','rpm'),
                         'audio'  => array('mp3','wav','ogg','flac','midi','rm','aac','wma','mka','ape'),
                         'video'  => array('mpeg','mp4','ram','ra','avi','mpg','mov','divx','asf','qt','mwv','rv','vob','asx','ogm','',''),
                         'mixed' => array('js','css','jpg','gif','ico','xml','txt','csv'),
                        );
            $this->_dir = array(
                         'style' => 'css',
                         'script'  => 'js',
                         'images'  => 'img',
                         'img'  => 'img',
                         'text' => 'doc',
                         'arj'  => 'arj',
                         'audio' => 'audio',
                         'video' => 'video',
                         'mixed' => 'packed',
                        );

            $this->path_root_target = rtrim($_SERVER['DOCUMENT_ROOT'],DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            $this->path_root_source = rtrim($_SERVER['DOCUMENT_ROOT'],DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
            log_message('debug', "Assets Class Initialized");
		
	}
        


        /**
         *  Загрузка файла стилей
         * @param type $file
         * @param type $module
         * @return type 
         */
        public function load_style($file = 'style', $_module = '', $place = '') {                
                if($_module !== false){
                    $_module OR $_module = CI::$APP->router->current_module();
                }            
                
                if($this->load($file, $_module, 'style', $place)){
                    return true;
                }
                return false;
        }
        /**
         *  Загрузка файла java скрипта
         * @param type $file
         * @param type $module
         * @return type 
         */
         public function load_script($file , $_module = '', $place = '') {
		if($_module !== false){
                    $_module OR $_module = CI::$APP->router->current_module();
                }
                if($this->load($file, $_module, 'script', $place)){
                    return true;
                }		
                return false;
	}
        /**
         *  Загрузка файла изображения из состава модуля
         * @param string $file
         * @param string $module
         * @return boolean 
         */
         public function load_img($file , $_module = '') {		
		if($_module !== false){
                    $_module OR $_module = CI::$APP->router->current_module();
                }
                if($this->load($file, $_module, 'img')){
                    return true;
                }		
                return false;
	}
        
        /**
         *  Загрузка группы типа файлов из состава модуля
         * 
         * @param string $dir  - имя директории, которая будет скопирована
         * @param string $file_out - имя файла (или массив из файлов), которые должны быть опубликованы из состава
         * @param string $module - имя модуля
         * @param string $type - тип содержимого
         * @return boolean 
         */
         public function load_packed($dir , $type = 'mixed', $file_out, $_module = '' ) {		
		if($_module !== false){
                    $_module OR $_module = CI::$APP->router->current_module();
                }
                if($this->load($file, $_module, $type)){
                    return true;
                }		
                return false;
	}
        
        /**
         * Загрузка файла состава модуля
         *
         * @param string $file  name file without extention
         * @param string $module  name module
         * @param string $type    type (style, js, img)
         */
        private function load($file, $module = '', $type = 'style', $place = ''){
            // имя модуля
            if(!empty($module)){
                $data['module'] = trim($module, '/');
            }else{
                $data['module'] = '';
            }

            $type_content = $this->get_type_content($file, $type);
            // если тип содержания не распознан или указан не верно, прекращаем обработку
            if($type_content == false){
                $this->set_error(__CLASS__, __METHOD__, 'debug', 'тип содержания '.htmlspecialchars($type).' указан не верно или тип файла '.htmlspecialchars($file).' не соответствует разрешенному');
                return false;
            }
            
            $type_dir = $this->get_dir_type($type);
            if($type_content == 'file' || $type_content == 'url'){
                $file = trim(str_replace('\\', '/', trim($file)),'/');

                // массив с компонентами пути файла (позволяет узнать содержит ли файл другое имя host и т.п.)
                $data['url_arr'] = parse_url($file);
                if(isset($data['url_arr']['path'])){
                    $segments = explode('/', $data['url_arr']['path']);
                }else{
                    return false;
                }
                //$segments = explode('/', $file);

                $file_name = array_pop($segments);
		            $file_name = str_replace(EXT, '', $file_name);
                // полный внутренний путь
                $data['path_int'] = ltrim(implode('/', $segments).'/', '/');
                // расширение файла
                $data['ext_file'] = $this->get_ext_file($file_name, $type);
                $file_name_without_ext = $this->get_file_name_without_ext($file_name, $type);
                if(!isset($file_name_without_ext)) return false;
                // реальное имя файла с расширением
                $data['file_name'] = $file_name_without_ext.'.'.$data['ext_file'];
                $place = $this->get_place_target($place, $type);
            }else{
                $place = 'head';
            }


            if($this->_donor_access == true){
                if(!empty($this->_domain_donor)){
                    $domain_donor = $this->_domain_donor;
                }else{
                    $domain_donor = false;
                }
            }else{
                $domain_donor = false;
            }

            $data['file_source'] = $file;           // имя файла указанного как источник
            $data['place_target'] = $place;         // место положения файла назначения
            $data['type'] = $type;                  // тип файла
            $data['dir_type'] = $type_dir;          // папка для внутреннего размещения файлов данного типа

            $data['domain_donor'] = $domain_donor;  // домен донор
            $data['type_content'] = $type_content;  // тип содержимого (file, text, url)

            /*
            if(is_file($path.$path_file.$file_name)){
                $this->set_item($file, $path.$path_file, $type);
                return $path.$path_file;
            }
            */
            if($this->set_item($data)) return true;

            return false;            
        }
        
        /**
        *  вычисляет тип содержимого
        *  возможные значения: file - строка является файлом,
        *                      text - строка является текстом,
        *                      url  - строка является ссылкой
        *
        */
        function get_type_content($string, $type){
            if($this->check_path_file($string))  return 'file';
            if($this->check_url($string)) return 'url';
            if($this->check_is_jquery($string)){
                if($type == 'script')  return 'jQuery';
            }
            if($this->check_is_javascript($string)){
                if($type == 'script')  return 'javascript';
            }
            if($this->check_is_style($string)){
                if($type == 'style') return 'style';
            }
            return false;
        }
        
        /**
        *  Возвращает место назначения файла
        *  @param string
        *  @param string
        */
        function get_place_target($place, $type){
            if($place == 'head'){
                if($type == ('script' or 'style')){
                    return 'head';
                }
            }
            return 'local';
        }
        
        /**
        *  Возвращает имя файла без расширения на основе его типа
        *  @param type string
        *  @param type string
        *
        *  return string
        */
        function get_file_name_without_ext($file, $type){
            if(!isset($file)) return false;
            if(strpos($file, '.')){
                $seg = explode('.',$file);
                $ext = array_pop($seg);
                foreach($this->_type as $type_file=>$arr_exts){
                    if(in_array($ext, $arr_exts)){
                        if($type == $type_file){
                            return implode('.',$seg);
                        }
                    }
                }
            }
            return $file;
        }
        
        /**
         *  Возвращает возможное расширение файла
         * @param string $type
         * @return string
         */
        function get_ext_file($file, $type = ''){
            if(strstr($file,'.')){
                $arr_file = explode('.',$file);
                $seg = array_pop($arr_file);
                if(isset($this->_type[$type])){
                  if(in_array($seg, $this->_type[$type])){
                      $ext = $seg;
                  }else{
                      $ext = reset($this->_type[$type]);
                  }
                }
            }else{
                if(isset($this->_type[$type])){
                    $ext = reset($this->_type[$type]);
                }
            }
            if(!isset($ext)) return false;
            return $ext;
        }
        
        /**
         *  Установка параметра
         * @param type $file
         * @param type $path
         * @param type $type 
         */
        function set_item($data){
            $data['place_source'] = $this->get_place_source(&$data);
            $data['path_local_target'] = $this->get_path_local_target(&$data);
            if($this->search(&$data)){
                return true;
            }
            if($data['place_source'] == 'remote'){
                if($data['type_content'] == 'url'){
                    if($this->get_timeout($data['module'], $data['type']) < (time() - $this->time_create_file(&$data))){
                        if( ! $this->is_identical(&$data)){
                            $this->copy_remote_files(&$data);
                        }
                    }
                }
            }elseif($data['place_source'] == 'local'){
                if($data['type_content'] == 'file'){
                    if($this->get_timeout($data['module'], $data['type']) < (time() - $this->time_create_file(&$data))){
                        if( ! $this->is_identical(&$data)){
                            $this->copy_local_files(&$data);
                        }
                    }
                }
            }elseif($data['place_source'] == 'donor'){
                    if($this->get_timeout($data['module'], $data['type']) < (time() - $this->time_create_file(&$data))){
                        if( ! $this->is_identical(&$data)){
                            $this->copy_donor_files(&$data);
                        }
                    }
            }

            $this->_config_paths[$data['module']][$data['type']][$data['path_local_target']][] = $data;
            //echo "<pre>";
            //print_r($data);
            //echo "</pre>";

        }
        /**
        *  поиск идентичных файлов
        *
        */
        function search($data){
            if(isset($this->_config_paths[$data['module']][$data['type']][$data['path_local_target']])){
                foreach($this->_config_paths[$data['module']][$data['type']][$data['path_local_target']] as $items){
                    if($items['place_source'] == $data['place_source']){
                        if($items['type_content'] == $data['type_content']){
                            if($items['type_content'] == 'file' || $items['type_content'] == 'url' || $items['type_content'] == 'donor'){
                                if($items['file_name'] == $data['file_name']){
                                    return true;
                                }
                            }else{
                                if($items['file_source'] == $data['file_source']){
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        /**
        *  Вычисляет время создания файла
        *  Или текущее время если содержимое не является файлом
        *
        */
        function time_create_file($data){
            if($data['type_content'] == 'file' || $data['type_content'] == 'url'){
                if(is_file($this->path_root_target.$data['path_local_target'].$data['file_name'])){
                    return filectime($this->path_root_target.$data['path_local_target'].$data['file_name']);
                }
            }else{
                return time() + 1000; // !!!для содержания которые не явл. файлами с запасом на время выполнения скрипта , в случае отсутствия таймаута
            }
            return false;
        }
        
        /**
        *  Определяет место нахождения файла источника
        *
        */
        function get_place_source($data){
            if($data['type_content'] == 'url'){
                return 'remote';
            }else{
                if($this->_donor_access == true){
                    if($this->get_domain_donor()){
                        return 'donor';
                    }
                }
            }
            return 'local';
        }
        
        /**
        *  Возвращает имя домена донора
        */
        function get_domain_donor(){
            if($this->check_domain($this->_donor_domain)){
                return $this->_donor_domain;
            }
            return false;
        }
        

        /**
        *   Проверка на идентичность файлов
        *
        *
        */
        function is_identical($data){
            if($data['type'] == 'video' || $data['type'] == 'audio' || $data['type'] == 'arj' || $data['type'] == 'img' || $data['type'] == 'images' || $data['type'] == 'text'){

                if($data['type_content'] == 'url' || $data['type_content'] == 'file'){
                    if($data['place_source'] == 'local'){
                        $hash_source = $this->hash_local_source(&$data);
                    }elseif($data['place_source'] == 'remote'){
                        $hash_source = $this->hash_remote_source(&$data);
                    }elseif($data['place_source'] == 'donor'){
                        $hash_source = $this->hash_donor_source(&$data);
                    }
                    $hash_target = $this->hash_local_target(&$data);
                    if($hash_source == $hash_target && $hash_source !== false){
                      return true;
                    }else{
                      return false;
                    }
                }
            }elseif($data['type'] == 'script' || $data['type'] == 'style'){
                if($data['type_content'] == 'url' || $data['type_content'] == 'file'){
                    $content_source = $this->get_content_source_file(&$data);
                    if(isset($content)){
                        //замена путей на действующие
                        $content_source = $this->replace_path($content);
                    }
                    $content_target = $this->content_local_target(&$data);
                    if($content_source == $content_target && $content_source !== false){
                      return true;
                    }else{
                      return false;
                    }
                }

            }
            return false;
        }
        /**
        *  Замена путей где есть сылка на файл на действующие
        */
        function replace_path($content){
          if(isset($this->path_for_replace) && is_array($this->path_for_replace)){
              $path = $this->get_assets_path();
              foreach($this->path_for_replace as $item){
                  $content = str_replace($item, $path, $content);
              }
          }
          return $content;
        }
        /**
        *  Возвращает содержимое файла источника
        */
        function get_content_source_file($data){
            if($data['place_source'] == 'local'){
                $content_source = $this->content_local_source(&$data);
            }elseif($data['place_source'] == 'remote'){
                $content_source = $this->content_remote_source(&$data);
            }elseif($data['place_source'] == 'donor'){
                $content_source = $this->content_donor_source(&$data);
            }
            return $content_source;
        }
        /**
        *  Возвращает md5 хэш локального файла источника
        */
        function hash_local_source(&$data){
            $path_source = $this->get_path_local_source_full(&$data);
            return md5($path_source.$data['file_name']);
        }
        /**
        *  Возвращает md5 хэш удаленного файла источника
        */
        function hash_remote_source(&$data){
            $path_source = $this->get_path_remote_source_full(&$data);
            return md5($path_source.$data['file_name']);
        }
        /**
        *  Возвращает md5 хэш донорского файла источника
        */
        function hash_donor_source(&$data){
            //запрос на сервер донора
            return false;
        }
        /**
        *  Возвращает md5 хэш локального файла назначения
        */
        function hash_local_target(&$data){
            $path_target = $this->path_root_target.$data['path_local_target'];
            return md5($path_target.$data['file_name']);
        }
        /**
        *  Возвращает содержимое локального файла источника
        */
        function content_local_source(&$data){
            $path_source = $this->get_path_local_source_full(&$data);
            if(is_file($path_source.$data['file_name'])){
                return file_get_contents($path_source.$data['file_name']);
            }
            return false;
        }
        /**
        *  Возвращает содержимое удаленного файла источника
        */
        function content_remote_source(&$data){
            $path_source = $this->get_path_remote_source_full(&$data);
            return file_get_contents($path_source.$data['file_name']);
        }
        /**
        *  Возвращает содержимое донорского файла источника
        */
        function content_donor_source(&$data){
            //запрос на сервер донора
            return false;
        }
        /**
        *  Возвращает содержимое локального файла назначения
        */
        function content_local_target(&$data){
            $path_target = $this->path_root_target.$data['path_local_target'];
            if(is_file($path_target.$data['file_name'])){
                return file_get_contents($path_target.$data['file_name']);
            }
            return false;
        }
        /**
        *  Копирование удаленного файла в место назначение
        *
        */
        function copy_remote_files($data){
            if( ! is_dir($this->path_root_target.$data['path_local_target'])){
                if( ! $this->mk_dir($data['path_local_target'])) return false;
            }
            if($this->content_remote_source(&$data)){
                return true;
            }
            return false;
        }
        
        /**
        *  Копирование локального файла в место назначение
        *
        */
        function copy_local_files($data){
            if( ! is_dir($this->path_root_target.$data['path_local_target'])){
                if( ! $this->mk_dir($data['path_local_target'])) return false;
            }
            echo "скопирован: ".$data['file_name'];
            if(copy($this->get_path_local_source_full(&$data).$data['file_name'], $this->path_root_target.$data['path_local_target'].$data['file_name'])){
                return true;
            }else{
                return false;
            }
        }
        /**
        *  Копирование донорского файла в место назначение
        *
        */
        function copy_donor_files($data){

            //echo "<b>Файл иcточника <i>донора</i>:</b> ".$path_source.''.$data['file_name']." скопирован в<br />";
            //echo "Файл назначения: ".$path_target.''.$data['file_name']."<br />";

            //copy($path_source.'/'.$data['file_name'], $path_target.'/'.$data['file_name']);
        }

        /**
        *  Создает директорию назначения, если таковые отсутствуют
        *  Вложенные папки также создаются
        */
        function mk_dir($local_path){
            if(!empty($local_path)){
                $local_path = str_replace('\\','/',$local_path);
                $local_path = trim($local_path, '/');
                if(strstr($local_path, '/')){
                    $arr_path = explode('/', $local_path);
                }else{
                    $arr_path = array($local_path);
                }
            }
            if(isset($arr_path) && is_array($arr_path)){
                if(is_dir($this->path_root_target)){
                    $dir = '';
                    print_r($arr_path);

                    foreach($arr_path as $item){
                        if(!empty($item)){
                            $dir .= $item;
                            if (! is_dir($this->path_root_target.$dir)){
                                if( ! mkdir($this->path_root_target.$dir, 0644)) return false;
                            }
                            $dir .= DIRECTORY_SEPARATOR;
                        }
                    }
                }
                return true;
            }
            return false;
        }

        /** проверка на существование файла назначения
        *  arg $path - путь к файлу абсолютный !!!
        *      $file_name - имя файла
        *  return boolean
        */
        function is_file_target($data){
            $path_target = $this->path_root_target.$data['path_local_target'];
            if(is_file($path_target.$data['file_name'])){
              return true;
            }
            return false;
        }
        
        /** проверка на существование удаленного файла источника
        *
        *  return boolean
        */
        function is_file_source_remote($data){
            // файл, который мы проверяем
            $url = $this->get_path_remote_source_full($data).$data['file_name'];
            $headers = @get_headers($url);
            // проверяем ли ответ от сервера с кодом 200 - ОК
            //if(preg_match("|200|", $Headers[0])) { // - немного дольше :)
            if(strpos('200', $headers[0])) {
                return true;
            }else{
                return false;
            }
        }
        /** проверка на существование локального файла источника
        *
        *  return boolean
        */
        function is_file_source_local($data){
            $path_full = $this->get_path_local_source_full(&$data);
            if(is_file($path_full.$data['file_name'])){
              return true;
            }
            return false;
        }
        /** проверка на существование донорского файла источника
        *
        *  return boolean
        */
        function is_file_source_donor($data){
            //здесь должен быть сформирован запрос к сайту донору
            return false;
        }

        /*
        *  Возвращает основной путь к файлам приложения
        */
        function get_assets_path(){
            $path = trim($this->config['tpl_path'],'\/').'/'.trim($this->config['tpl_name'],'\/').'/';
            return $path;
        }
        /*
        *  Возвращает корректный путь к папке с приложением
        */
        function get_path_application(){
            $path_app = rtrim(APPPATH, '\/').'/';
            $path_root_source = $this->path_root_source;
            if(strstr($path_app, $path_root_source)){
              $path = str_replace($path_root_source, '', $path_root_source);
            }else{
              $path = $path_app;
            }
            return $path;
        }
        /**
        *  Возвращает локальный путь к файлу источника (без имени файла)
        */
        function get_path_local_source_full($data){
            return $this->path_root_source.$this->get_path_local_source(&$data);
        }
        /**
        *  Возвращает локальный путь (относительно корня сайта) к файлу источника (без имени файла)
        */
        function get_path_local_source($data){
            if($data['dir_type']){
                $path_file = $this->_dir_assets.'/'.$data['dir_type'].'/'.$data['path_int'];
            }else{
                $path_file = $this->_dir_assets.'/'.$data['path_int'];
            }
            $path_app = $this->get_path_application();

            if(!empty($data['module'])){
                $path = $path_app.'modules'.'/'.$data['module'].'/';
            }else{
                $path = $path_app;
            }
            return $path.$path_file;
        }
        /**
        *  Возвращает удаленный путь к файлу источника (без имени файла)
        */
        function get_path_remote_source_full($data){
            if($data['type_content'] == 'url'){
                $str = @$data['url_arr']['scheme'].'://';
                $str .= @$data['url_arr']['host'].'/';
                $str .= $this->get_path_remote_source(&$data);
                return $str;
            }
            return false;
        }
        /**
        *  Возвращает удаленный путь (относительно сайта источника) к файлу источника (без имени файла)
        */
        function get_path_remote_source($data){
            if($data['type_content'] == 'url'){
                $str = $data['path_int'];
                return $str;
            }
            return false;
        }

        /**
        *  Возвращает локальный ПОЛНЫЙ путь (включая абсолютный путь к сайту) к файлу назначения
        *
        */
        function get_path_local_target_full($data){
            $path = $this->path_root_target;
            $path .= $this->get_path_local_target(&$data);
            return $path;
        }
        /**
        *  Возвращает локальный путь (относительный путь к сайту) к файлу назначения
        *  http://www.site.com/{path_local_target/}file.ext
        *  Данный путь используется для хранения в кеше данных как один из ключей
        */
        function get_path_local_target($data){
            $path = $this->get_assets_path();
            if(!empty($data['module'])){
              $path .= trim($this->_dir_target_modules,'\/').'/'.$data['module'].'/';
            }else{
              $path .= trim($this->_dir_target_public,'\/').'/';
            }
            $path .= $data['dir_type'].'/';
            if($data['type_content'] == 'url'){
                $str = @$data['url_arr']['scheme'];
                $str .= @$data['url_arr']['host'];
                $str .= $data['path_int'];
                $str .= $data['file_name'];
                $str .= $data['module'];
                $str .= $data['dir_type'];
                $path .= md5($str).'/';
            }elseif($data['type_content'] == 'file'){
                if(!empty($data['path_int'])){
                  $path .= $data['path_int'];
                }
            }else{
                if(!empty($data['path_int'])){
                  $path .= $data['path_int'];
                }
                if(!empty($data['type_content'])){
                    $path .= $data['type_content'].'/';
                }
                //$path .= '/';
            }

            return $path;
        }

        /**
         *
         * @param string $type
         * @return boolean 
         */
        function get_dir_type($type){
            if(!empty($this->_dir[$type])){
                $dir = $this->_dir[$type];
                return $dir;
            }
            
            return false;
        }
        
        /**
        *   Переопределение домена назначения для скриптов для каждого модуля
        *
        */
        function set_domain_script($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'script');
            }
            return false;
        }
        
        /**
        *   Переопределение домена назначения для стилей для каждого модуля
        *
        */
        function set_domain_style($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'style');
            }
            return false;
        }
        
        /**
        *   Переопределение домена назначения для изображений для каждого модуля
        *
        */
        function set_domain_img($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'img');
            }
            return false;
        }
        
        /**
        *   Переопределение домена назначения для изображений для каждого модуля
        *
        */
        function set_domain_doc($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'text');
            }
            return false;
        }
        
        /**
        *   Переопределение домена назначения для архивных файлов для каждого модуля
        *
        */
        function set_domain_arj($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'arg');
            }
            return false;
        }
        
        /**
        *   Переопределение домена назначения для музыкальных файлов для каждого модуля
        *
        */
        function set_domain_audio($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'audio');
            }
            return false;
        }
        
        /**
        *   Переопределение домена назначения для видео файлов для каждого модуля
        *
        */
        function set_domain_video($domain, $module = ''){
            if($this->check_domain($domain)){
                $_module OR $_module = CI::$APP->router->fetch_module();
                $this->set_domain_target($domain, $_module, 'video');
            }
            return false;
        }
        
        /**
        *  Переопределение домена назначения для каждого модуля
        *
        */
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
        
        /**
        *  Получение имени домена назначения
        *
        */
        function get_domain_target($module = 0, $type = 0){
            if(!empty($this->domain_target[$module][$type])){
                return $this->domain_target[$module][$type];
            }
            return false;
        }
        /**
        *  Получение таймаута для файла опредленного модуля и типа
        *
        */
        function get_timeout($module = 0, $type = 0){
            if(!empty($this->timeout_target[$module][$type])){
                return $this->timeout_target[$module][$type];
            }else{
                if(!empty($this->timeout_default)) return $this->timeout_default;
            }
            return false;
        }
        
        /**
         *
         * @param type $dir
         * @param type $return
         * @return string 
         */
        function out_style($module = '', $return = false){
            if($module != '' && isset($this->_config_paths[$module])){
                $assets[] = $this->_config_paths[$module];
            }else{
                $assets = $this->_config_paths;
            }

            $fp = '';
            foreach($assets as $modules=>$types){
                if(isset($types['style']) && is_array($types['style'])){
                    foreach($types['style'] as $items){
                        if(is_array($items)){
                            foreach($items as $item){
                                if($item['type_content'] == 'file' || $item['type_content'] == 'url' || $item['type_content'] == 'donor'){
                                    if($item['place_source'] == 'local'){
                                        $fp .= "<link rel=\"stylesheet\" href=\"";
                                        if(isset($item['domain_target'])){
                                            $fp .= "http://".$item['domain_target'].$item['path_local_target'].$item['file_name'];
                                        }else{
                                            $fp .= '/'.$item['path_local_target'].$item['file_name'];
                                        }
                                        $fp .= "\" type=\"text/css\" />\r\n";
                                    }elseif($item['place_source'] == 'head'){
                                        $fp .= "<style type=\"text/css\">\r\n";
                                        $fp .= file_get_contents($this->path_root_target.$item['path_local_target'].$item['file_name']);
                                        $fp .= "\r\n</style>\r\n";
                                    }
                                }elseif($item['type_content'] == 'style'){
                                    $fp .= "<style type=\"text/css\">\r\n";
                                    $fp .= $item['file_source'];
                                    $fp .= "\r\n</style>\r\n";
                                }
                            }
                        }
                    }
                }
            }

            if($return === true){
                return $fp;
            }else{
                echo $fp;
            }
            
        }
        
        /**
         *
         * @param type $dir
         * @param type $return
         * @return string 
         */
        function out_script($module = '', $return = false){
            if($module != '' && isset($this->_config_paths[$module])){
                $assets[] = $this->_config_paths[$module];
            }else{
                $assets = $this->_config_paths;
            }

            $fp = '';
            foreach($assets as $modules=>$types){
                if(isset($types['script']) && is_array($types['script'])){
                    foreach($types['script'] as $items){
                        if(is_array($items)){
                            foreach($items as $item){
                                if($item['type_content'] == 'file' || $item['type_content'] == 'url' || $item['type_content'] == 'donor'){
                                    if($item['place_source'] == 'local'){
                                        $fp .= "<script src=\"";
                                        if(isset($item['domain_target'])){
                                            $fp .= "http://".$item['domain_target'].$item['path_local_target'].$item['file_name'];
                                        }else{
                                            $fp .= '/'.$item['path_local_target'].$item['file_name'];
                                        }
                                        $fp .= "\" type=\"text/javascript\"></script>\r\n";
                                    }elseif($item['place_source'] == 'head'){
                                        $fp .= "<script type=\"text/javascript\">\r\n";
                                        $fp .= file_get_contents($this->path_root_target.$item['path_local_target'].$item['file_name']);
                                        $fp .= "\r\n</script>\r\n";
                                    }
                                }elseif($item['type_content'] == 'javascript' || $item['type_content'] == 'jQuery'){
                                    $fp .= "<script type=\"text/javascript\">\r\n";
                                    $fp .= $item['file_source'];
                                    $fp .= "\r\n</script>\r\n";
                                }
                            }
                        }
                    }
                }
            }
            if($return === true){
                return $fp;
            }else{
                echo $fp;
            }
            
        }
        
        /**
         *
         * @param type $dir
         * @param type $return
         * @return string 
         */
        function out_img($dir = '', $return = false){
            
        }
        
        /**
        *  Проверка правильности имени домена
        */
        function check_domain($string){
            $pattern = '/^(http|https|ftp)://([0-9a-z]([0-9a-z\-])*[0-9a-z]\.)+[a-z]{2,4}$/i';
            if (strlen($string < 64) && preg_match($pattern, $string)){
              return true;
            }
            return false;
        }
        /**
        *  Проверка правильности имени url
        */
        function check_url($string){
            $pattern = "%^(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?%i";
            if(preg_match($pattern, $string)){
                return true;
            }
            return false;
        }
        /**
        *  Проверка правильности имени файла с путем
        */
        function check_path_file($string){
            $pattern = "%^[/]?[A-z0-9]+([A-z_0-9-/\.]*)$%";
            if(preg_match($pattern, $string)){
                return true;
            }
            return false;
        }
        /**
        *  Проверка правильности имени файла
        */
        function check_file($string){
            $pattern = "%^[A-z0-9]+([A-z_0-9-\.]*)$%";
            if(preg_match($pattern, $string)){
                return true;
            }
            return false;
        }
        /**
        *  Проверка наличия кода jQuery
        */
        function check_is_jquery($string){
            $pattern = "%[\$][A-z0-9_]?\([^)]*\)+%is";
            if(preg_match($pattern, $string)){
                return true;
            }
            return false;
        }
        /**
        *  Проверка наличия кода javascript
        */
        function check_is_javascript($string){
            $pattern = "%(function[A-z0-9_ ]?\([^)]\))+%is";
            if(preg_match($pattern, $string)){
                return true;
            }
            return false;
        }
        /**
        *  Проверка наличия строк таблицы стилей
        */
        function check_is_style($string){
            $pattern = "|^([^\{]+\{[^\}]+\})+|is";
            if(preg_match($pattern, $string)){
                return true;
            }
            return false;
        }
        
        /**
    *  Метод обработки ошибок
    *  @param string $class - класс
    *  @param string $method - метод
    *  @param string $level  - уровень ошибки
    *  @param string $message - сообщение
    */
        function set_error($class, $method, $type, $message ){
            
        }
        
        	/**
	*  Установка логов системы
	*  @param string $message
	*/
        function set_log($message){
            $this->logs[] = $message;
        }
}







