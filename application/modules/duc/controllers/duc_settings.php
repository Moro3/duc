<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('duc')){
  include_once ('duc.php');
}

/*
 * Класс Duc_settings
 *
 */

class Duc_settings extends Duc {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_duc_settings';
    }

    //возвращает список возрастов обучающихся
    function listAges(){
         for($i=2; $i<=18; $i++){
         	$res[$i] = $i;
         }
         return $res;
    }

     //возвращает список годов обучения
    function listYears(){
         for($i=1; $i<=8; $i++){
         	$res[$i] = $i;
         }
         return $res;
    }

    //возвращает список вариантов полов обучающихся
    function listSexs(){
         $res[1] = 'мужской';
         $res[2] = 'женский';
         $res[3] = 'обоюдный';
         return $res;
    }

    /**
    * Возвращает весь конфиг из файлов конфигурации
    *
    */
    function getConfig(){    	$this->load->library('wconfig');
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

		$this->config->load('setting', true);
		$this->config->load('resize', true);
		$config = array_merge_assoc_recursive_distinct($this->config->item('setting'), $this->config->item('resize'));
		//$config = $this->config->item('setting');
        return $config;
    }

    /**
    *  Возвращает параметры нужного ресайза
    *  @param string $name_resize - имя ресайза
    *
    *  @return	array
    */
    public function get_param_resize($name_resize = false){    	if(empty($name_resize) || $name_resize === '!') return false;
        if(isset($this->setting['images']['resize'][$name_resize])){        	$config = $this->get_config_resize($name_resize);
        	$default['path'] = (isset($config['path_resize'])) ? $config['path_resize'] : $config['path'];
            unset($config['path_resize']);
        	$arr_config = array_merge($config, $default);
        	foreach($this->setting['images']['resize'][$name_resize] as $name=>$items){        		$res[$name] = array_merge($arr_config, $items);
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
    public function tpl_photos(){    	$this->load->view('admin/settings_photos');
    }

}