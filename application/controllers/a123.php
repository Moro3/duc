<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class A123 extends MX_Controller {

    var $data = array();
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');
        $this->load->config('ion_auth', TRUE);

    }

    //redirect if needed, otherwise display the user list
    function index()
    {

      if ($this->ion_auth->logged_in()) {
	    	//redirect them to the login page
			   redirect($this->config->item('page_in', 'ion_auth'), 'refresh');
    	}
    	else {
	        //set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

    		//list the users
    		$this->data['users'] = $this->ion_auth->get_users_array();

        echo Modules::run('auth/auth/login');
    	}
    }


}
