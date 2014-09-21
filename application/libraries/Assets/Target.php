<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Assets_target
{
        // Основной объект
        private $assets;

        // возможные виды источников
        private $var_targets = array('local',
                               'head',
                                );
        // текущий источник
        private $factory;
        /**
         *  Конструктор принимает основной объект для взаимодействий с ним
         * @param type $obj
         */
        function __construct($obj) {
            if(is_object($obj)){
                $this->assets = $obj;
            }else{
                exit('Основной объект библиотеки "ASSETS" не передан в класс "target"');
            }
        }

        /**
        *  Возвращает место назначения файла
        *  @param string
        *  @param string
        */
        function get_place($place, $type){
            if($place == 'head'){
                if($type == ('script' or 'style')){
                    return 'head';
                }
            }
            return 'local';
        }

        /**
        *  Возвращает локальный путь (относительный путь к сайту) к файлу назначения
        *  http://www.site.com/{path_local_target/}file.ext
        *  Данный путь используется для хранения в кеше данных как один из ключей
        */
        function get_path_local(){
            $path = $this->get_path_local_module();
            $path .= $this->assets->data_collect->get('dir_type').'/';
            //$type_content = $this->assets->data_collect->get('type_content');
            //$path_int = $this->assets->data_collect->get('path_int');

            $path .= $this->assets->get_path_local();

            return $path;
        }

        /**
         * Возвращает локальный путь(относительный путь от сайта) до модуля к файлу назначения
         * http://www.site.com/{path_local_module/}/img/file.ext
         *  Данный путь не включает строку пути до типа файла и его внутреннего пути
         * Это применяется для формирования пути для замены в css и js файлах
         */
        function get_path_local_module(){
            $path = $this->assets->get_assets_path();
            $module = $this->assets->data_collect->get('module');
            if(!empty($module)){
              $path .= trim($this->assets->data_collect->get('dir_target_modules'),'\/').'/'.$module.'/';
            }else{
              $path .= trim($this->assets->data_collect->get('dir_target_public'),'\/').'/';
            }
            return $path;
        }

        /**
        *  Вычисляет время создания файла
        *  Или текущее время если содержимое не является файлом
        *
        */
        function time_create_file(){
            $type_content = $this->assets->data_collect->get('type_string');
            if($type_content == 'content'){
                return time() + 1000; // !!!для содержания которые не явл. файлами с запасом на время выполнения скрипта , в случае отсутствия таймаута
            }else{
                $file = $this->assets->config->get_path_root_target().$this->assets->data_collect->get('path_local_target').$this->assets->data_collect->get('file_name');
                if(is_file($file)){
                    return filemtime($file);
                }
            }
            return false;
        }

        /**
        *  Возвращает md5 хэш локального файла назначения
        */
        function hash_file(){
            $type_content = $this->assets->data_collect->get('type_string');
            if($type_content == 'content'){
                return md5($this->assets->data_collect->get('file_source'));
            }else{
                $path_target = $this->assets->config->get_path_root_target().$this->assets->data_collect->get('path_local_target');
                if(is_file($path_target.$this->assets->data_collect->get('file_name'))){
                    return md5_file($path_target.$this->assets->data_collect->get('file_name'));
                }else{
                    return false;
                }
            }
        }

        /**
         * Проверяет на существование файла
         */
        function is_file(){
            $type_string = $this->assets->data_collect->get('type_string');
            if($type_string == 'content'){
                return true;
            }
            $path_target = $this->assets->config->get_path_root_target().$this->assets->data_collect->get('path_local_target');
            return is_file($path_target.$this->assets->data_collect->get('file_name'));
        }

        function put_content($content){
            if($this->assets->data_collect->get('place_content') != 'head'){
                $path_assets = $this->assets->config->get_path_root_target();
                $path_local = $this->assets->data_collect->get('path_local_target');
                $file = $this->assets->data_collect->get('file_name');
                if( ! is_dir($path_assets.$path_local)){
                    $this->mk_dir($path_local);
                }
                if(is_dir($path_assets.$path_local)){
                    if(file_put_contents($path_assets.$path_local.$file, $content)){
                        //echo "Файл ".$path_assets.$path_local.$file." записан!<br />";
                        return true;
                    }
                }else{
                    $this->assets->set_error(__CLASS__, __METHOD__, 'fatal', 'Не удалось создать директорию для файла назначения "'.$file.'"');
                }
                return false;
            }
        }
        /**
         * Установка таймаута для файла
         * Изменение времени создания на текущее
         */
        function set_current_time_file(){
            $file = $this->assets->config->get_path_root_target().$this->assets->data_collect->get('path_local_target').$this->assets->data_collect->get('file_name');
            if(is_file($file)){
                touch($file);
                return true;
            }
            return false;
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
                if(is_dir($this->assets->config->get_path_root_target())){
                    $dir = '';
                    //print_r($arr_path);

                    foreach($arr_path as $item){
                        if(!empty($item)){
                            $dir .= $item;
                            if (! is_dir($this->assets->config->get_path_root_target().$dir)){
                                if( ! mkdir($this->assets->config->get_path_root_target().$dir, 0755)) return false;
                            }
                            $dir .= DIRECTORY_SEPARATOR;
                        }
                    }
                }
                return true;
            }
            return false;
        }

        /**
        *	Возвращает путь к файлу (включая сам файл)
        *	@param string - имя файла
        *	@param string - имя модуля
        *	@param string - тип файла
        *	@param string - локальный путь до файла
        *	@param array - массив с конфигурацией для вывода !не путать с конфигурацией загрузки файла(опционально)
        *
        *	@return string - путь к файлу (включая сам файл)
        */
        function get_link($file, $module, $type, $path, $config){
            $data = $this->assets->data_collect->get_data($file, $module, $type, $path);
//            echo "Файл: $file<br>";
//            echo "Модуль: $module<br>";
//            echo "Тип: $type<br>";
//            echo "Путь: $path<br>";
//            echo "Конфиг: ";
//            print_r($config);

            if(isset($data) && is_array($data)){
                //var_dump($data);
                foreach($data as $items){
                    //if($items['out_file'] == true){                    	//print_r($items);
						if(isset($items['file_name']) && $items['path_local_target']){
		                            if($items['link_site'] == true && !empty($items['domain_target'])){
		                                $site = $items['domain_target'];
		                            }elseif(isset($items['domain_target']) && $items['domain_target'] != $this->assets->CI->config->item('base_url')){
		                                $site = $items['domain_target'];
		                            }else{
		                                $site = '';
		                            }
		                            $path = $site.'/'.$items['path_local_target'].$items['file_name'];
		                            $arr_local[] = $path;
		                }

                    //}
                }
            }
            $result = '';
            if(isset($arr_local)){
                foreach($arr_local as $value){
	                $result .= $value;
                }
            }
//            echo "<pre>";
//            print_r($result);
//            echo "</pre>";
            return $result;
        }

        function get_file_surround($data, $config){            //$data = $this->assets->data_collect->get_data($file, $module, $type, $path);
//            echo "Файл: $file<br>";
//            echo "Модуль: $module<br>";
//            echo "Тип: $type<br>";
//            echo "Путь: $path<br>";
//            echo "Конфиг: ";
//            print_r($config);

			//echo '<pre>';
			//echo '<b>Конфиг</b>';
				//print_r($config);
			//echo '<b>Данные</b>';
				//print_r($data);
			//echo '</pre>';
            if(isset($data) && is_array($data)){
                //var_dump($data);
                foreach($data as $items){
                    if($items['out_file'] == true){
                    	//print_r($items);
                       	if(isset($config['ext_file_out'])){
                       		if( ! is_array($config['ext_file_out'])) $config['ext_file_out'] = array($config['ext_file_out']);


							//если расширение у файла отсутствует, вычисляем его по имени файла и сравниваем полученное
							if(empty($items['ext_file'])){								if(isset($items['file_name'])){									$ext = $this->get_ext_file($items['file_name']);

								}else{									$ext = false;
								}
							}else{								$ext = $items['ext_file'];
							}
							//print_r($ext);
							//сравниваем доступные расширения для тегов с расширением у файла
							if(!empty($ext) && in_array($ext, $config['ext_file_out'])){
		                        if($items['type_string'] == 'content'){
		                            if($items['split_head']){
		                                $arr_head_split[] = $items['file_source'];
		                            }else{
		                                $arr_head[] = $items['file_source'];
		                            }

		                        }elseif($items['place_content'] == 'head'){
		                            if($items['split_head']){
		                                $arr_head_split[] = $this->get_content($file, $module, $type, $path);
		                            }else{
		                                $arr_head[] = $this->get_content($file, $module, $type, $path);
		                            }
		                        }else{
		                            if($items['link_site'] == true && !empty($items['domain_target'])){
		                                $site = $items['domain_target'];
		                            }elseif(isset($items['domain_target']) && $items['domain_target'] != $this->assets->CI->config->item('base_url')){
		                                $site = $items['domain_target'];
		                            }else{
		                                $site = '';
		                            }
		                            $path = $site.'/'.$items['path_local_target'].$items['file_name'];
		                            if(isset($items['ext_file']) && in_array($items['ext_file'],$config['ext_file_out'])){
		                            	$arr_local[] = $path;
		                            }
		                        }
		                  	}
                        }
                    }
                }
            }
            $result = '';
            if(isset($arr_local)){
                foreach($arr_local as $value){
	                    if(isset($config['tags_file'])){
	                        $result .= str_replace('{file}',$value, $config['tags_file']);
	                    }else{
	                        $result .= $value;
	                    }
                }
            }
            if(isset($arr_head)){
                //print_r($arr_head);
                if(isset($config['tags_open']) && isset($config['tags_close'])){
                    $result .= $config['tags_open'];
                }
                foreach($arr_head as $value){
                    $result  .= $value;
                }
                if(isset($config['tags_open']) && isset($config['tags_close'])){
                    $result .= $config['tags_close'];
                }
            }
            if(isset($arr_head_split)){
                //print_r($arr_head_split);
                foreach($arr_head_split as $value){
	                    if(isset($config['tags_open']) && isset($config['tags_close'])){
	                        $result .= $config['tags_open'];
	                    }
	                    $result  .= $value;
	                    if(isset($config['tags_open']) && isset($config['tags_close'])){
	                        $result .= $config['tags_close'];
	                    }
                }
            }
//            echo "<pre>";
//            print_r($result);
//            echo "</pre>";
            return $result;
        }

        function get_file_script_surround($data, $config){
        }

        function get_file_style_surround($data, $config){

        }

        /**
        *  Возвращает расширение файла
        *  если расширения нет, возвращает false
        */
        function get_ext_file($file){        	if(strpos($file, '.')){        		$ext_arr = explode('.', $file);
        		$ext = array_pop($ext_arr);
        	}
        	if(isset($ext)) return $ext;
        	return false;
        }
}
