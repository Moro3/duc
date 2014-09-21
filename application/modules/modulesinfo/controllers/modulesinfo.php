<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Modulesinfo extends MX_Controller {

	function __construct()
	{
		parent::__construct();

    $this->config->load('site', 'TRUE');
    $this->load->library('control_uri');
    $this->load->library('scheme');

	}

	function index()
	{
    echo "???????";
	}

	function segment_admin(){
    $data['uri']['admin'] = $this->control_uri->get_full_segment('Admin');
    return $data['uri']['admin'];
	}

	function segment_module(){
    $data['uri']['mod'] = 'mod';
    return $data['uri']['mod'];
	}

  function list_modules_menu(){

    $date_menu['modules'] = $this->scheme->modules_gp('modules');

    $date_menu['uri']['admin'] = $this->segment_admin();
	  $date_menu['uri']['mod'] = $this->segment_module();
    $date_menu['lang']['admin']['modules'] = $this->lang->line('admin_modules');
    //print_r($date['kodules']);
    //$this->load->view('list_modules_menu_views',$date_menu);
    $date_menu['title'] = "Загружен модуль МЕНЮ";
    $module = $this->control_uri->rsegment(3);
    $date_menu['menu']['select'] = $module;
    return $this->load->view('list_modules_menu_views', $date_menu, true);
	}

  function list_modules_menu2(){
	  $date_menu['kodules'] = $this->scheme->get_modules();
    $date_menu['uri']['admin'] = $this->segment_admin();
	  $date_menu['uri']['mod'] = $this->segment_module();
    $date_menu['lang']['admin']['modules'] = $this->lang->line('admin_modules');
    //print_r($date['kodules']);
    //$this->load->view('list_modules_menu_views',$date_menu);
    $date_menu['title'] = "Загружен модуль МЕНЮ";
    $module = $this->control_uri->rsegment(3);
    $date_menu['menu']['select'] = $module;
    return $this->load->view('list_modules_menu_views', $date_menu, true);
	}

	function list_modules_main(){
	  //$date_menu['kodules'] = $this->scheme->get_modules();
	  $date_menu['modules'] = $this->scheme->modules_gp('modules');
    $date_menu['uri']['admin'] = $this->segment_admin();
	  $date_menu['uri']['mod'] = $this->segment_module();
    $date_menu['lang']['admin']['modules'] = $this->lang->line('admin_modules');
    //$date_menu['lang']['admin']['modules'] = "123";
    //print_r($date_menu['modules']);
    //$this->load->view('list_modules_menu_views',$date_menu);
    $date_menu['title'] = "Загружен модуль МЕНЮ";

    $date_menu['set_site'] = $this->config->item('site');
    $date_menu['path']['img'] = "/".$this->config->item('tpl_path')."/".$this->config->item('tpl_name')."/img/";
    return $this->load->view('list_modules_main_views', $date_menu, true);
	}

    function list_config_menu(){
    }
}






