<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Класс для сбора и обработке данных
 *
 * Класс содержит только информацию о файлах,
 *
 */


class Assets_data_collect
{

    /**
     * Записанные данные о приложениях модуля
     */
    private $config;

    /**
     *  Данные текущего приложения
     * @var type
     */
    private $data_current;

    private $module_public = '000';

    private $assets;
        /**
         *  Конструктор принимает основной объект для взаимодействий с ним
         * @param type $obj
         */
        function __construct($obj) {
            if(is_object($obj)){
                $this->assets = $obj;
            }else{
                exit('Основной объект библиотеки "ASSETS" не передан в класс "data_collect"');
            }
        }

        /**
         *  Установка параметра
         * @param type $name
         * @param type $value
         */
        function set($name, $value){
            if(!empty($name)){
                $this->data_current[$name] = $value;
            }
        }

        /**
         *  Получение параметра
         * @param type $name
         */
        function get($name = false){
            if($name !== false){
                if(isset($this->data_current[$name])) return $this->data_current[$name];
                return null;
            }else{
                return $this->data_current;
            }

        }
        /**
         *  Получение записанных данных
         * @return array
         */
        function get_config(){
            return $this->config;
        }

        /**
         * Очистка текущих данных
         */
        function clear_current(){
            $this->data_current = array();
        }

        /**
         * Запись текущих данных в память
         */
        function write(){
            //echo "<b>Запись файла</b> <span style='color:red'>".$this->get('file_name')."</span><br/>";
            //echo "<pre>";
            //print_r($this->get());
            //echo "</pre>";

            $module = $this->get_correct_module($this->get('module'));
            if($module){
                if($this->get('type')){
                    if($this->get('path_local_target')){
                        $this->config[$module][$this->get('type')][$this->get('path_local_target')][] = $this->get();
                        //$this->clear_current();
                        return true;
                    }else{
                        $this->assets->set_error(__CLASS__,__METHOD__,'debug', 'не обнаружен параметр "path_local_target" при записи данных assets');
                    }
                }else{
                    $this->assets->set_error(__CLASS__,__METHOD__,'debug', 'не обнаружен параметр "type" при записи данных assets');
                }
            }else{
                $this->assets->set_error(__CLASS__,__METHOD__,'debug', 'не обнаружен параметр "module" при записи данных assets: файл - '.$this->get('file_name').'');
            }
            return false;
        }

        /**
        *  Возвращает md5 хэш локального файла источника
        */
        function hash_local_source(&$data){
            $path_source = $this->get_path_local_source_full($data);
            return md5($path_source.$data['file_name']);
        }
        /**
        *  Возвращает md5 хэш удаленного файла источника
        */
        function hash_remote_source(&$data){
            $path_source = $this->get_path_remote_source_full($data);
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

        function search($file, $module, $type, $path_local){
            $module = $this->get_correct_module($module);

            if(isset($this->config[$module][$type][$path_local])){
                foreach($this->config[$module][$type][$path_local] as $item){
                    if($item['file_name'] == $file){
                        return true;
                    }

                }
            }
            return false;
        }

        function get_correct_module($module){
            if(empty($module)){
                $module = $this->module_public;
            }
            return $module;
        }

        function get_params($file, $module, $type, $path){

        }
        /**
         *  Возвращает параметры файла/ов
         * @param type $file - имя файла, если false, то все файлы
         * @param type $module - имя модуля
         * @param type $type - тип файла
         * @param type $path - путь файла
         * @return type
         */
        function get_data($file, $module, $type, $path){
            if(empty($type)) return false;

            if($file === false){
                // если модуль задан как false(т.е. корневой модуль), присваиваем соответствующее имя
                if($module === false) $module = $this->module_public;
                // если модуль не указан, проходимся по всем модулям
                if($module === ''){
                    foreach($this->config as $key_module=>$params){
                        foreach($params as $key_type=>$key_path){
                            if($type == $key_type){
                                foreach($key_path as $items){
                                    foreach($items as $item){
                                        $arr[] = $item;
                                    }
                                }
                            }
                        }
                    }
                }else{
                    if(isset($this->config[$module])){
                        foreach($this->config[$module] as $key_type=>$key_path){
                            if($type == $key_type){
                                foreach($key_path as $items){
                                    foreach($items as $item){
                                        $arr[] = $item;
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                // если модуль не указан присваиваем корневой модуль , т.к. файл известен
                $module = $this->get_correct_module($module);
                if(isset($this->config[$module][$type][$path])){
                    $arr[] = $this->get_param($file, $module, $type, $path);
                }
            }
//            echo "Get_data collect<br>";
//            echo "<pre>";
//            print_r($this->config);
//            echo "</pre>";
            if(isset($arr)) return $arr;

            return false;
        }

        function get_param($file, $module, $type, $path){
            $module = $this->get_correct_module($module);
            if(isset($this->config[$module][$type][$path])){
                foreach($this->config[$module][$type][$path] as $params){
                    if($params['file_name'] == $file){
                        return $params;
                    }
                }
            }
            return false;
        }
}