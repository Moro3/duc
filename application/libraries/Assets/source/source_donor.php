<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Assets_source_donor
{
        private $assets;

        function __construct($obj) {
            if(is_object($obj)){
                $this->assets = $obj;
            }else{
                exit('Основной объект библиотеки "ASSETS" не передан в класс "source_donor"');
            }
        }
        /**
        *  Возвращает содержимое локального файла источника
        */
        function content(&$data){
            $path_source = $this->get_path_absolute();
            if(is_file($path_source.$data['file_name'])){
                return file_get_contents($path_source.$data['file_name']);
            }
            return false;
        }


        /**
        *  Возвращает относительный (от модуля) путь к файлу источника (без имени файла)
        */
        function get_path_relative($data){
            if($data['dir_type']){
                $path_file = $this->assets->_dir_assets.'/'.$data['dir_type'].'/'.$data['path_int'];
            }else{
                $path_file = $this->assets->_dir_assets.'/'.$data['path_int'];
            }

            return $path_file;
        }

        /**
        *  Возвращает корневой (до модуля) путь к файлу источника
        */
        function get_path_root($data){
            $path_app = $this->assets->get_path_application();

            if(!empty($data['module'])){
                $path = $path_app.'modules'.'/'.$data['module'].'/';
            }else{
                $path = $path_app;
            }
            return $path;
        }

        /**
        *  Возвращает абсолютный путь к файлу источника (без имени файла)
        */
        function get_path_absolute($data){
            return $this->get_path_root($data).$this->get_path_relative($data);
        }

        function get_path_local($data){
            if(!empty($data['path_int'])) return $data['path_int'];
            return false;
        }

        function parse_string($data){

            $file = trim(str_replace('\\', '/', trim($data['file_source'])),'/');

            // массив с компонентами пути файла (позволяет узнать содержит ли файл другое имя host и т.п.)
            $data['url_arr'] = parse_url($file);
            if(isset($data['url_arr']['path'])){
                $segments = explode('/', $data['url_arr']['path']);
            }else{
                return false;
            }
            $this->assets->data_collect->set('url_arr', $data['url_arr']);
            //$segments = explode('/', $file);

            $file_name = array_pop($segments);

            // полный внутренний путь
            $this->assets->data_collect->set('path_int',  ltrim(implode('/', $segments).'/', '/'));

            // расширение файла
            $file_name = str_replace(EXT, '', $file_name);
            $data['ext_file'] = $this->get_ext_file($file_name, $type);
            $this->assets->data_collect->set('ext_file', $data['ext_file']);
            $file_name_without_ext = $this->get_file_name_without_ext($file_name, $type);
            if(!isset($file_name_without_ext)) return false;
            // реальное имя файла с расширением
            $data['file_name'] = $file_name_without_ext.'.'.$data['ext_file'];
            $this->assets->data_collect->set('file_name', $file_name_without_ext.'.'.$data['ext_file']);
        }

        /**
        *  Возвращает md5 хэш локального файла источника
        */
        function hash($data){
            $path_source = $this->get_path_absolute($data);
            return md5_file($path_source.$data['file_name']);
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
            if(copy($this->get_path_local_source_full($data).$data['file_name'], $this->path_root_target.$data['path_local_target'].$data['file_name'])){
                return true;
            }else{
                return false;
            }
        }

        /** проверка на существование локального файла источника
        *
        *  return boolean
        */
        function is_file($data){
            $path_full = $this->get_path_absolute($data);
            if(is_file($path_full.$data['file_name'])){
              return true;
            }
            return false;
        }

}