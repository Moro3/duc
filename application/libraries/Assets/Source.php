<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Assets_source
{
        // Основной объект
        private $assets;

        // возможные виды источников
        private $var_sources = array('local',
                               'remote',
                               'donor',
                               'content'
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
                exit('Основной объект библиотеки "ASSETS" не передан в класс "source"');
            }
        }


        /**
        *  Автоматическое определение место нахождения файла источника
        *
        */
        function auto_set(){
            $file = $this->assets->data_collect->get('file_source');
            $type = $this->get_type_string($file);

            if($type == 'url'){
                $this->set_source('remote');
                return 'remote';
            }
            if($type == 'content'){
                $this->set_source('content');
                return 'content';
            }
            if($type == 'file'){
                $this->set_source('local');
                return 'local';
            }

            $this->set_source('local');
            return 'local';
        }

        /**
        *  вычисляет тип входной строки
        *  возможные значения: file - строка является файлом,
        *                      content - строка является текстом,
        *                      url  - строка является ссылкой
        *
        */
        function get_type_string($string){
            if($this->assets->content->check_path_file($string))  return 'file';
            if($this->assets->content->check_url($string)) return 'url';
            if(!empty($string)) return 'content';
            return false;
        }

        function set_source($name){
            if(empty($name)) $name = 'local';
            if(is_array($this->var_sources)){
                foreach($this->var_sources as $source){
                    if($source === $name){
                        $class_source = 'Assets_source_'.$source;
                        if( ! class_exists($class_source)){
                            if(is_file(dirname(__FILE__)."/source/source_$source".EXT)){
                                include_once dirname(__FILE__)."/source/source_$source".EXT;
                            }else{
                                $this->assets->set_error(__CLASS__,__METHOD__, 'fatal', 'Не найден файл source_'.$source.' с классом '.$class_source);
                                exit('файл не найден '.dirname(__FILE__)."/source/source_$source".EXT);
                            }
                        }
                        $this->factory = new $class_source($this->assets);
                    }
                }
            }else{
                $this->assets->set_error(__CLASS__,__METHOD__, 'fatal', 'Не указан ни один из источников файлов приложений assets');
            }
        }

        /**
        *  Возвращает содержимое файла источника
        */
        function get_content(){
            $data = $this->assets->data_collect->get();
            $content = $this->factory->content($data);
            if(isset($data['path_replace_template'])){
                $path_replace = $this->assets->get_path_target_local_module();
                $content = $this->assets->content->replace_path($data['path_replace_template'], $path_replace, $content);
            }
            return $content;
        }

        /**
        *  Возвращает md5 хэш файла источника
        */
        function hash_file(){
//            $data = $this->assets->data_collect->get();
//            return $this->factory->hash($data);

            $content = $this->get_content();
            return md5($content);
        }

        /** проверка на существование файла источника
        *
        *  return boolean
        */
        function is_file(){
            $data = $this->assets->data_collect->get();
            if($this->factory->is_file($data)){
              return true;
            }
            return false;
        }

        /**
         * Возвращает размер файла источника
         */
        function size_file(){
            $data = $this->assets->data_collect->get();
            if($size = $this->factory->size_file($data)){
                return $size;
            }
            return false;
        }

        /**
        *  Возвращает относительный (от модуля) путь к файлу источника (без имени файла)
        */
        function get_path_relative(){
            $data = $this->assets->data_collect->get();
            return $this->factory->get_path_relative($data);
        }

        /**
        *  Возвращает корневой (до модуля) путь к файлу источника
        */
        function get_path_root(){
            $data = $this->assets->data_collect->get();
            return $this->factory->get_path_root($data);
        }

        /**
        *  Возвращает абсолютный путь к файлу источника (без имени файла)
        */
        function get_path_absolute(){
            return $this->get_path_root().$this->get_path_relative();
        }

        /**
         * Возвращает локальную часть пути сформированную на основе параметров файла источника
         */
        function get_path_local(){
            $data = $this->assets->data_collect->get();
            return $this->factory->get_path_local($data);
        }

        /**
         * Парсит имя файла и записывает параметры
         * @return type
         */
        function parse_string(){
            $data = $this->assets->data_collect->get();
            return $this->factory->parse_string($data);
        }


}
