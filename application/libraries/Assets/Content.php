<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Assets_content
{

        private $assets;
        /**
         *  Конструктор принимает основной объект для взаимодействий с ним
         * @param type $obj
         */
        function __construct($obj) {
            if(is_object($obj)){
                $this->assets = $obj;
            }else{
                exit('Основной объект библиотеки "ASSETS" не передан в класс "content"');
            }
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
        *  Возвращает содержимое файла источника
        */
        function get_content_source_file($data){
            if($data['place_source'] == 'local'){
                $content_source = $this->content_local_source($data);
            }elseif($data['place_source'] == 'remote'){
                $content_source = $this->content_remote_source($data);
            }elseif($data['place_source'] == 'donor'){
                $content_source = $this->content_donor_source($data);
            }
            return $content_source;
        }


        /**
        *  Возвращает содержимое удаленного файла источника
        */
        function content_remote_source(&$data){
            $path_source = $this->get_path_remote_source_full($data);
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
        *  Возвращает содержимое локального файла источника
        */
        function content_local_target(&$data){
            $path_target = $this->path_root_target.$data['path_local_target'];
            if(is_file($path_target.$data['file_name'])){
                return file_get_contents($path_target.$data['file_name']);
            }
            return false;
        }

        /**
        *  Замена путей где есть сылка на файл на действующие
        */
        function replace_path($replace = false, $replacement, $content){
            if(is_array($replace)){
                //$path = $this->assets->get_assets_path();
                foreach($replace as $item){
                    $target = trim($item);
                    $content = str_replace($target, $replacement, $content);
                }
            }

            return $content;
        }

        /**
        *  Проверка правильности имени домена
        */
        function check_domain($string){
            $pattern = '%^(http|https|ftp)://([0-9a-z]([0-9a-z\-])*[0-9a-z]\.)+[a-z]{2,4}$%i';
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
}