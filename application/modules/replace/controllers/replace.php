<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Класс Duc - основной класс для работы с недвижимостью
 *
 *
 */

class Replace extends MY_Controller {


    function __construct(){
        parent::__construct();
        $site = $this->config->item('site');

		$this->load->model('replace_model');
        //$this->load->library('replace');
        $this->load->driver('replaced');
    }

	function index($cont){    	return '!@#$'.$cont;
	}


	//возвращает контент с замененными фрагментами
	function get_content($content){
	  //$this->search_fragments($content);
	  //return $this->get_result();
	  return $this->replaced->get_content($content);
	}




	//возвращает сгенерированный шаблон модуля
	function run_modules($modules_tpl, $arg = false){
	    //print_r($modules_tpl);
	    if(!empty($modules_tpl)){
	      $cache_module = '';
	      //foreach($modules_tpl as $items_modules){
	        if(!empty($modules_tpl['module'])) $n_mod[] = $modules_tpl['module'];
	        if(!empty($modules_tpl['controller'])) $n_mod[] = $modules_tpl['controller'];
	        if(!empty($modules_tpl['method'])) $n_mod[] = $modules_tpl['method'];
	        if(is_array($n_mod)) $path_module = implode('/',$n_mod);

	        if(!empty($arg))  $argument = $arg; else $argument = $modules_tpl['arg'];
	        $cache_module .= Modules::run($path_module, $argument);
	      //}

	    }
	    if(isset($cache_module)){
	      return $cache_module;
	    }
	}


}