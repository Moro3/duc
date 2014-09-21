<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Assets_source_content
{
        private $assets;

        function __construct($obj) {
            if(is_object($obj)){
                $this->assets = $obj;
            }else{
                exit('Основной объект библиотеки "ASSETS" не передан в класс "source_content"');
            }
        }
        /**
        *  Возвращает содержимое локального файла источника
        */
        function content(&$data){
            if(isset($data['file_source'])){
                return $data['file_source'];
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
            if(!empty($data['path_int'])){
                  $path .= $data['path_int'];
                }
                if(!empty($data['type_content'])){
                    $path .= $data['type_content'].'/';
                }
            return false;
        }

        function parse_string($data){

            $this->assets->data_collect->set('url_arr', false);
            //$segments = explode('/', $file);
            $file_name = $this->hash($data);
            // полный внутренний путь
            $this->assets->data_collect->set('path_int',  false);

            $data['ext_file'] = $this->get_ext_file();

            $file_name_without_ext = $this->get_file_name_without_ext($data['file_source']);

            // реальное имя файла с расширением
            if(!empty($data['ext_file'])){
                $data['file_name'] = $file_name_without_ext.'.'.$data['ext_file'];
            }else{
                $data['file_name'] = $file_name_without_ext;
            }

            $this->assets->data_collect->set('file_name', $data['file_name']);
        }

        /**
         *  Возвращает возможное расширение файла
         * @param string $type
         * @return string
         */
        function get_ext_file(){
            $exts = $this->assets->data_collect->get('ext_allow');

            if(!isset($ext)){
                reset($exts);
                $ext = current($exts);
            }

            if(!isset($ext)) return false;
            //print_r($ext);
            return $ext;
        }

        /**
        *  Возвращает имя файла без расширения на основе его типа
        *  @param type string
        *  @param type string
        *
        *  return string
        */
        function get_file_name_without_ext($file){
            $file = $this->hash(array('file_source' => $file));
            return $file;
        }

        /**
        *  Возвращает md5 хэш локального файла источника
        */
        function hash($data){
            if(!empty($data['file_source'])){
                return md5($data['file_source']);
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
            return true;
        }

}