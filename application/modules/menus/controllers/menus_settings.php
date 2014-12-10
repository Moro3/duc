<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 * Класс Menus_settings
 *
 */

class Menus_settings extends Menus {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_menus_settings';

    }
    

    /**
    * Возвращает весь конфиг из файлов конфигурации
    *
    */
    function getConfig(){
    	$this->load->library('wconfig');
		//print_r($this->config->item('wconfig'));
        $this->wconfig->type('array');
		//загружаем конфигурационный файл 'setting.php'
		//$setting_config = $this->wconfig->get('setting', 'duc');

		//загружаем конфигурационный файл 'resize.php'
		//$resize_config = $this->wconfig->get('resize', 'duc');
		//$this->setting = $this->config->item('setting');
		// объединчяем все конфиги в один
		//$config = array_merge_assoc_recursive_distinct($setting_config['config'], $resize_config['config']);
        //print_r($config);
        //exit;
        if(isset($this->setting) && is_array($this->setting) && count($this->setting)>0) return $this->setting; 

		$this->config->load('setting', true);
		$this->config->load('resize', true);
		$this->setting = array_merge_assoc_recursive_distinct($this->config->item('setting'), $this->config->item('resize'));
		//$config = $this->config->item('setting');
        
        return $this->setting;
    }

    /**
    *  Возвращает параметры нужного ресайза
    *  @param string $name_resize - имя ресайза
    *
    *  @return	array
    */
    public function get_param_resize($name_resize = false){
    	if(empty($name_resize) || $name_resize === '!') return false;
        if(isset($this->setting['images']['resize'][$name_resize])){
        	$config = $this->get_config_resize($name_resize);
        	$default['path'] = (isset($config['path_resize'])) ? $config['path_resize'] : $config['path'];
            unset($config['path_resize']);
        	$arr_config = array_merge($config, $default);
        	foreach($this->setting['images']['resize'][$name_resize] as $name=>$items){
        		$res[$name] = array_merge($arr_config, $items);
        	}
        }
        if(isset($res)) return $res;
        return false;
    }

    /**
    *  Возвращает настройки нужного ресайза
    *  @param string $name_resize - имя ресайза
    *
    *  @return	array
    */
    public function get_config_resize($name_resize = false){
    	if(empty($name_resize) || $name_resize === '!') return false;
        if(isset($this->setting['images']['config'][$name_resize])){
        	$res = array_merge($this->setting['images']['config']['!'], $this->setting['images']['config'][$name_resize]);
        	return $res;
        }
        return false;
    }


    /**
    * Шаблон настройки изображений
    *
    */
    public function tpl_photos(){
    	$this->load->view('admin/settings_photos');
    }

}