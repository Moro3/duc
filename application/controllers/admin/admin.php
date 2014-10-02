<?php

class Admin extends MX_Controller {

	function __construct()
	{
		parent::__construct();
            //$this->output->enable_profiler(TRUE);
            $this->load->library('session');
            $this->load->helper(array('form', 'url', 'debug'));
            if($this->input->post('exit_admin') && $this->input->post('close') == 'door'){
              $this->session->sess_destroy();
              header('location: /adminka');
            }
            Modules::run('auth/auth/admin_auth');
            $this->config->load('site', 'TRUE');
            $this->load->library('control_uri');
            $this->load->library('scheme');
            $this->load->driver('assets');

            $this->lang->load('admin');

            //$this->control_uri->set_segment(__class__);
            $this->_generation_segment();

            $this->load->library('form_validation');
            include_once(rtrim($_SERVER['DOCUMENT_ROOT'],DIRECTORY_SEPARATOR)."/wysiwyg/spaw2/spaw.inc.php");
            //$this->load->helper('englich');

            //if( ! $this->check_user()){
              //header('location: /a123');
            //}


	}

	function check_user(){
	  	$session_user = $this->session->userdata('login');
    	$session_pass = $this->session->userdata('password');
    	if($session_user == '531' && $session_pass == '531'){
     		return true;
    	}
    	return false;
	}



	function _generation_segment(){
	  $this->control_uri->set_segment(__class__);
	  //$this->control_uri->set_segment(__class__,'mod');
	  //$this->control_uri->set_segment(__class__,'configuration');
	}

	function index(){
      $data['content'] = Modules::run('modulesinfo/modulesinfo/list_modules_main', true);

      //$data['uri']['modules'] = $this->control_uri->get_full_segment(__class__, 'page_manager');
      $this->_template($data);

	}

	function _get_tpl_admin(){
	  	$data['config'] = $this->config->item('site');

	    $data['img_path'] = assets_path();

	    $data['modules'] = $this->scheme->get_modules();
	    $data['uri']['admin'] = $this->control_uri->get_full_segment(__class__);
	    $data['uri']['mod'] = $this->control_uri->get_segment(__class__,'mod');
	    $date_menu['lang']['admin']['modules'] = $this->lang->line('admin_modules');

	    //echo $data['uri']['mod'];
	    return $data;
	}



    /**
    *  Работа модулей
    */
  	function mod(){
		$this->control_uri->set_segment(__class__, "mod");
		$mod_segment = $this->control_uri->num_segment(__class__, "mod");
		//echo "<br>uri: ".$this->control_uri->guri('language')->separator('action');

		$data = $this->_get_tpl_admin();
		$data['content'] = "";
		if($this->control_uri->rsegment($mod_segment + 1)){
			$module = $this->control_uri->rsegment($mod_segment + 1);
			//$controller = $this->control_uri->rsegment($mod_segment + 2);
			//$method = $this->control_uri->rsegment($mod_segment + 2);
			if(!empty($module)){
			  if(!empty($controller)){
			    //$module = $module.'/'.$controller;
			  }
			}
			if(empty($method)){
			  $method = 'admin_index';
			}
			if($this->load->module($module)){
			  //$data['content'] .= $this->$module->$method();
			  //echo $module."<br>";
			  $data['content'] .= Modules::run("$module/$method", true);

			}

		}

        $this->_template($data);
		//$this->load->view('admin/admin_view',$data);
  	}
	/**
     *  Шаблон вывода
     * @param type $vars
     */
    function _template($vars){
        $data = $this->_get_tpl_admin();
        $data['header'] = $this->load->module('gate')->replace_mod_content($this->load->view('admins/tpl2/header', '', true));
       	$data['nav_modules'] = Modules::run('modulesinfo/list_modules_menu');
       	$data['nav_config'] = Modules::run('modulesinfo/list_config_menu');
        $data['navigation'] = $data['nav_modules'].$data['nav_config'];

        $data['content'] = $vars['content'];
        $data['footer'] = $this->load->module('gate')->replace_mod_content($this->load->view('admins/tpl2/footer', '', true));
        //$this->load->module('gate')->replace_mod_content();
        $this->load->view('admins/admin_view',$data);
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */





