<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/**
* Name:  WConfig
*
* Author: Vasily Tsimbal
* 	  531@mail.ru
*         tsimbal.ru*
*
*
* Created:  05.03.2012
*
* Description:  Библиотека позволяет создавать, изменять, добавлять и удалять файлы конфигурации CI framework.
*  Реализована возможность записывать массивы в формате 'array', 'json', ini, serialize
*
* Requirements: PHP5 or above
*
*/

class Wconfig {
    /**
	 * CodeIgniter global
	 *
	 * @var string
	 **/
	protected $ci;

        private $factory;

        private $type = 'array';

        private $module;

        private $dir_config = 'config/';

        private $dir_modules;

        function __construct($type = 'array') {
            $this->ci = & get_instance();
            switch ($type) {
                case 'array':
                    include_once 'driver_config.php';
                    $this->factory = new driver_config;
                    //$this->type('array');
                    $this->factory->set_start_string('if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');');
                    break;
                case 'serialize':
                    include_once 'driver_config.php';
                    $this->factory = new driver_config;
                    //$this->type('serialize');
                    break;
                case 'json':
                    include_once 'driver_config.php';
                    $this->factory = new driver_config;
                    //$this->type('json');
                    break;
                default:
                    include_once 'driver_config.php';
                    $this->factory = new driver_config;
                    //$this->type('array');
                    $this->factory->set_start_string('if ( ! defined(\'BASEPATH\')) exit(\'No direct script access allowed\');');
                    break;
            }
            // скидываем указатель на начало массива на случай если он уже сместился
            reset(Modules::$locations);
            // получаем текущий элемент массива с путем к модулю
            list($this->dir_modules, $val) = each(Modules::$locations);
            //$this->dir_modules = $dir_modules;
            if($this->factory == '') exit('Нет объекта взаимодействия с конфигурационным файлом');

        }

        function type($string){
            $this->factory->set_type($string);
        }

        function read($file, $module = ''){
            if($module !== false){
                    $module OR $module = CI::$APP->router->current_module();
            }else{
                $module = false;
            }
            $this->module = $module;
            list($path, $file) = Modules::find($file, $this->module, $this->dir_config);
            if ($path === FALSE) {
		if(!empty($module)){
                    $path_local = $this->dir_modules.trim($module,'\/').'/';
                }else{
                    $path_local = APPPATH;
                }
                $path = $path_local.trim($this->dir_config,'\/').'/';
            }
            $file_path = $path.$file.EXT;

            $this->factory->read($file_path);
        }

        function update($file, $module = ''){
            if($module !== false){
                    $module OR $module = CI::$APP->router->current_module();
            }else{
                $module = false;
            }
            $this->module = $module;
            list($path, $file) = Modules::find($file, $this->module, $this->dir_config);
            if ($path === FALSE) {
                if(!empty($module)){
                    $path_local = $this->dir_modules.trim($module,'\/').'/';
                }else{
                    $path_local = APPPATH;
                }
                $path = $path_local.trim($this->dir_config,'\/').'/';
            }
            $file_path = $path.$file.EXT;

            return $this->factory->update($file_path);
        }

        function insert($file, $module = ''){
            if($module !== false){
                    $module OR $module = CI::$APP->router->current_module();
            }else{
                $module = false;
            }
            $this->module = $module;
            list($path, $file) = Modules::find($file, $this->module, $this->dir_config);
            if ($path === FALSE) {
                //Modules::$locations;
                //list($this->dir_modules, $val) = each(Modules::$locations);

                //echo 'Путь к модулю альтернативный:'.$val.'<br>';
                if(!empty($module)){
                    $path_local = $this->dir_modules.trim($module,'\/').'/';
                }else{
                    $path_local = APPPATH;
                }
                $path = $path_local.trim($this->dir_config,'\/').'/';
            }

            $file_path = $path.$file.EXT;
            return $this->factory->insert($file_path);
        }

        function delete($file, $module = ''){
            if($module !== false){
                    $module OR $module = CI::$APP->router->current_module();
            }else{
                $module = false;
            }
            $this->module = $module;
            list($path, $file) = Modules::find($file, $this->module, $this->dir_config);
            if ($path === FALSE) {
                //Modules::$locations;
                //list($this->dir_modules, $val) = each(Modules::$locations);

                //echo 'Путь к модулю альтернативный:'.$val.'<br>';
                if(!empty($module)){
                    $path_local = $this->dir_modules.trim($module,'\/').'/';
                }else{
                    $path_local = APPPATH;
                }
                $path = $path_local.trim($this->dir_config,'\/').'/';
            }

            $file_path = $path.$file.EXT;
            return $this->factory->delete($file_path);
        }

        function get($file, $module = ''){
            if($module !== false){
                    $module OR $module = CI::$APP->router->current_module();
            }else{
                $module = false;
            }
            $this->module = $module;
            list($path, $file) = Modules::find($file, $this->module, $this->dir_config);
            if ($path === FALSE) {

            }
            $file_path = $path.$file.EXT;

            return $this->factory->get($file_path);
        }

        function set($key, $value = '', $replace = false){
            $this->factory->set($key, $value, $replace);
        }

        function set_array($key, $value = ''){
            $this->factory->set_array($key, $value);
        }

        function clear(){
                $this->factory->clear();
        }

        function convert($type_before, $type_after, $file, $module = ''){
            if($module !== false){
                    $module OR $module = CI::$APP->router->current_module();
            }else{
                $module = false;
            }
            $this->module = $module;
            list($path, $file) = Modules::find($file, $this->module, $this->dir_config);
            if ($path === FALSE) {
                if(!empty($module)){
                    $path_local = $this->dir_modules.trim($module,'\/').'/';
                }else{
                    $path_local = APPPATH;
                }
                $path = $path_local.trim($this->dir_config,'\/').'/';
            }

            $file_path = $path.$file.EXT;

            return $this->factory->convert($file_path, $type_before, $type_after);
        }

        function template($string){
            $this->factory->set_template($string);
        }


        /**
         * Вывод ошибок построчно
         *
         * @return string
         */
        function error(){
            return $this->factory->error();
        }

        /**
         *  Вывод статуса при записи построчно
         *
         * @return string
         */
        function status(){
            return $this->factory->status();
        }

        /**
         *  Вывод изменений значений построчно
         *
         * @return string
         */
        function change(){
            return $this->factory->change();
        }

        /**
         *  Вывод логов построчно
         *
         * @return string
         */
        function logs(){
            return $this->factory->logs();
        }
}
