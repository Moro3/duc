<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Assets_source_remote
{
        private $assets;

        function __construct($obj) {
            if(is_object($obj)){
                $this->assets = $obj;
            }else{
                exit('Основной объект библиотеки "ASSETS" не передан в класс "source_remote"');
            }
        }
        /**
        *  Возвращает содержимое локального файла источника
        */
        function content(&$data){
            $path_source = $this->get_path_absolute($data);
            if($this->is_file(&$data)){
                return file_get_contents($path_source.$data['file_name']);
            }
            return false;
        }


        /**
        *  Возвращает относительный (от корня сайта) путь к файлу источника (без имени файла)
        */
        function get_path_relative($data){
            $path = $data['path_int'];
            return $path;
        }

        /**
        *  Возвращает корневой (имя сайта, без внутреннего пути) путь к файлу источника
        */
        function get_path_root($data){
            $str = @$data['url_arr']['scheme'].'://';
            $str .= @$data['url_arr']['host'].'/';
            return $str;
        }

        /**
        *  Возвращает абсолютный путь к файлу источника (без имени файла)
        */
        function get_path_absolute($data){
            return $this->get_path_root($data).$this->get_path_relative($data);
        }

        function get_path_local($data){
            $url_str = $data['url_arr'];
            $str = $url_str['scheme'];
            $str .= $url_str['host'];
            $str .= $data['path_int'];
            $str .= $data['file_name'];
            $str .= $data['module'];
            $str .= $data['dir_type'];
            $path = md5($str).'/';
            return $path;
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
            $data['ext_file'] = $this->get_ext_file($file_name);
            $this->assets->data_collect->set('ext_file', $data['ext_file']);
            $file_name_without_ext = $this->get_file_name_without_ext($file_name);
            if(!isset($file_name_without_ext)) return false;
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
        function get_ext_file($file){
            $exts = $this->assets->data_collect->get('ext_allow');
            if(!is_array($exts)) return false;
            if(strstr($file,'.')){
                $arr_file = explode('.',$file);
                $seg = array_pop($arr_file);
                if(in_array($seg,$exts)){

                    $ext = $seg;
                }
            }

            if(!isset($ext)){
                reset($exts);
                $ext = current($exts);
                //print_r($exts);
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
            if(!isset($file)) return false;
            if(strpos($file, '.')){
                $seg = explode('.',$file);
                $ext = array_pop($seg);
                $arr_exts = $this->assets->data_collect->get('ext_allow');
                //foreach($this->assets->data_collect->get('ext_allow') as $type_file=>$arr_exts){
                    //print_r($type_file);
                    if(in_array($ext, $arr_exts)){
                        //if($type == $type_file){
                            return implode('.',$seg);
                       // }
                    }
                //}
            }
            return $file;
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
            //echo "скопирован: ".$data['file_name'];
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

            $url = $path_full.$data['file_name'];
            $Headers = @get_headers($url);
            // проверяем ли ответ от сервера с кодом 200 - ОК
            //if(preg_match("|200|", $Headers[0])) { // - немного дольше :)
//            echo "<pre>";
//            print_r($Headers);
//            echo "</pre>";
            if(strpos($Headers[0],'200')) {
                return true;
            } else {
                return false;
            }
        }

}