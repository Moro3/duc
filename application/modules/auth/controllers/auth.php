<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MX_Controller {

    var $data = array();
    protected $uri_start = '';
    function __construct()
    {
        parent::__construct();

        $this->load->config('ion_auth', TRUE);
        $this->load->library('session');
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');

        $this->uri_start = $this->uri_point();
        $this->data['uri']['point'] = $this->uri_start;
    }

    //redirect if needed, otherwise display the user list
    function index()
    {

      if (!$this->ion_auth->logged_in()) {
	    	//redirect them to the login page
			   redirect($this->config->item('page_auth', 'ion_auth'), 'refresh');
    	}
    	elseif ($this->ion_auth->is_admin()) {
    		//redirect them to the home page because they must be an administrator to view this
			  redirect($this->config->item('page_auth', 'ion_auth'), 'refresh');
    	}
    	else {
	        //set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

    		//list the users
    		$this->data['users'] = $this->ion_auth->get_users_array();
    		return $this->load->view('auth/index', $this->data, true);
    	}
    }

    public function is_logged(){    	if ($this->ion_auth->logged_in()) {    		return true;
    	}    	return false;

    }

    function admin_index(){
      $this->control_uri->set_segment(__class__);
      if($this->control_uri->get_segment('admin')){
        $auth_segment = $this->control_uri->num_segment(__class__);
        if($auth_segment){
          $method = $this->control_uri->rsegment($auth_segment + 1);
          $arg1 = $this->control_uri->rsegment($auth_segment + 2);
        }else{
          $method = false;
          $arg1 = false;
        }
      }
      if(!empty($method)){
        return $this->load->module('auth')->$method($arg1);
      }
      //если не админ ничего не делаем
      if ( ! $this->ion_auth->is_admin()) {
    		//redirect them to the home page because they must be an administrator to view this
			  //redirect($this->config->item('page_auth', 'ion_auth'), 'refresh');
    	}
      //если группа администраторов выдаем право управления
      else {
	        //set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

    		//list the users
    		$this->data['users'] = $this->ion_auth->get_users_array();
    		return $this->load->view('auth/auth/index', $this->data, true);
    	}
    }

    function admin_auth(){
      	if (!$this->ion_auth->logged_in()) {      	//redirect them to the login page

         //echo $this->uri_start;
         redirect($this->config->item('page_auth', 'ion_auth'), 'refresh');
    	}

    }
    //log the user in
    function login()
    {
      $this->data['title'] = "Login";
      $this->data['uri']['page_auth'] = $this->config->item('page_auth', 'ion_auth');
        //validate form input
    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
	    $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run($this) == true) { //check to see if the user is logging in
        	//check for "remember me"
        	if ($this->input->post('remember') == 1) {
        		$remember = true;
        	}
        	else {
        		$remember = false;
        	}

        	if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember)) { //if the login is successful
	        	//redirect them back to the home page
	        	$this->session->set_flashdata('message', $this->ion_auth->messages());
	        	redirect($this->config->item('page_in', 'ion_auth'), 'refresh');
	        }
	        else { //if the login was un-successful
	        	//redirect them back to the login page
	        	$this->session->set_flashdata('message', $this->ion_auth->errors());
	        	redirect($this->config->item('page_auth', 'ion_auth'), 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
	        }
        }
		    else {  //the user is not logging in so display the login page
	            //set the flash data error message if there is one
	            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		    	$this->data['email']      = array('name'    => 'email',
                                                  'id'      => 'email',
                                                  'type'    => 'text',
                                                  'value'   => $this->form_validation->set_value('email'),
                                                 );
                $this->data['password']   = array('name'    => 'password',
                                                  'id'      => 'password',
                                                  'type'    => 'password',
                                                 );

        		return $this->load->view('auth/auth/login', $this->data, true);
		    }
    }

    //log the user out
	function logout()
	{
        $this->data['title'] = "Logout";

        //log the user out
        $logout = $this->ion_auth->logout();

        //redirect them back to the page they came from
        redirect($this->config->item('page_auth', 'ion_auth'), 'refresh');
    }

    //change password
	function change_password()
	{
	    $this->form_validation->set_rules('old', 'Old password', 'required');
	    $this->form_validation->set_rules('new', 'New Password', 'required|min_length['.$this->config->item('min_password_length', 'ion_auth').']|max_length['.$this->config->item('max_password_length', 'ion_auth').']|matches[new_confirm]');
	    $this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

	    if (!$this->ion_auth->logged_in()) {
	    	redirect($this->uri_start.'/auth/login', 'refresh');
	    }
	    $user = $this->ion_auth->get_user($this->session->userdata('user_id'));

	    if ($this->form_validation->run($this) == false) { //display the form
	        //set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

	        $this->data['old_password']           = array('name'    => 'old',
		                                               	  'id'      => 'old',
		                                              	  'type'    => 'password',
		                                                 );
	        $this->data['new_password']           = array('name'    => 'new',
		                                               	  'id'      => 'new',
		                                              	  'type'    => 'password',
		                                                 );
        	$this->data['new_password_confirm']   = array('name'    => 'new_confirm',
                                                      	  'id'      => 'new_confirm',
                                                      	  'type'    => 'password',
        												 );
        	$this->data['user_id']                = array('name'    => 'user_id',
                                                      	  'id'      => 'user_id',
                                                      	  'type'    => 'hidden',
        												  'value'   => $user->id,
        												 );

        	//render
        	return $this->load->view($this->uri_start.'/auth/change_password', $this->data, true);
	    }
	    else {
	        $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

	        $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

    		if ($change) { //if the password was successfully changed
    			$this->session->set_flashdata('message', $this->ion_auth->messages());
    			$this->logout();
    		}
    		else {
    			$this->session->set_flashdata('message', $this->ion_auth->errors());
    			redirect($this->uri_start.'/auth/change_password', 'refresh');
    		}
	    }
	}

	//forgot password
	function forgot_password()
	{
		$this->form_validation->set_rules('email', 'Email Address', 'required');
	    if ($this->form_validation->run($this) == false) {
	    	//setup the input
	    	$this->data['email'] = array('name'    => 'email',
                                         'id'      => 'email',
        						   	    );
	    	//set any errors and display the form
        	$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
    		return $this->load->view('auth/forgot_password', $this->data, true);
	    }
	    else {
	        //run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));

			if ($forgotten) { //if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
	            redirect($this->uri_start."/auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else {
				$this->session->set_flashdata('message', $this->ion_auth->errors());
	            redirect($ths->uri_start."/auth/forgot_password", 'refresh');
			}
	    }
	}

	//reset password - final step for forgotten password
	public function reset_password($code)
	{
		$reset = $this->ion_auth->forgotten_password_complete($code);

		if ($reset) {  //if the reset worked then send them to the login page
			$this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect($this->uri_start."/auth/login", 'refresh');
		}
		else { //if the reset didnt work then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect($this->uri_start."/auth/forgot_password", 'refresh');
		}
	}

	//activate the user
	function activate($id, $code=false)
	{
		$activation = $this->ion_auth->activate($id, $code);

        if ($activation) {
			//redirect them to the auth page
	        $this->session->set_flashdata('message', $this->ion_auth->messages());
	        redirect($this->config->item('page_auth', 'ion_auth'), 'refresh');
        }
        else {
			//redirect them to the forgot password page
	        $this->session->set_flashdata('message', $this->ion_auth->errors());
	        redirect($this->uri_start."/auth/forgot_password", 'refresh');
        }
    }

    //deactivate the user
	function deactivate($id = NULL)
	{
		// no funny business, force to integer
		$id = (int) $id;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('confirm', 'confirmation', 'required');
		$this->form_validation->set_rules('id', 'user ID', 'required|is_natural');

		if ( $this->form_validation->run($this) == FALSE )
		{
			// insert csrf check
			$this->data['csrf']	=	$this->_get_csrf_nonce();
			$this->data['user']	=	$this->ion_auth->get_user($id);
    	return	$this->load->view('auth/auth/deactivate_user', $this->data, true);
		}
		else
		{
			// do we really want to deactivate?
			if ( $this->input->post('confirm') == 'yes' )
			{
				// do we have a valid request?
				if ( $this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id') )
				{
					show_404();
				}

				// do we have the right userlevel?
				if ( $this->ion_auth->logged_in() && $this->ion_auth->is_admin() )
				{
					$this->ion_auth->deactivate($id);
				}
			}

			//redirect them back to the auth page
			redirect($this->config->item('page_auth', 'ion_auth'),'refresh');
		}
    }

    //create a new user
	function create_user()
	{
        $this->data['title'] = "Create User";

		if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {
			redirect($this->config->item('page_auth', 'ion_auth'), 'refresh');
		}

        //validate form input
    	$this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
    	$this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
    	$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
    	$this->form_validation->set_rules('phone1', 'First Part of Phone', 'required|xss_clean|min_length[3]|max_length[3]');
    	$this->form_validation->set_rules('phone2', 'Second Part of Phone', 'required|xss_clean|min_length[3]|max_length[3]');
    	$this->form_validation->set_rules('phone3', 'Third Part of Phone', 'required|xss_clean|min_length[4]|max_length[4]');
    	$this->form_validation->set_rules('company', 'Company Name', 'required|xss_clean');
    	$this->form_validation->set_rules('password', 'Password', 'required|min_length['.$this->config->item('min_password_length', 'ion_auth').']|max_length['.$this->config->item('max_password_length', 'ion_auth').']|matches[password_confirm]');
    	$this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

        if ($this->form_validation->run($this) == true) {
            $username  = strtolower($this->input->post('first_name')).' '.strtolower($this->input->post('last_name'));
            $email     = $this->input->post('email');
            $password  = $this->input->post('password');

            $additional_data = array('first_name' => $this->input->post('first_name'),
             				        'last_name'  => $this->input->post('last_name'),
        					'company'    => $this->input->post('company'),
        					'phone'      => $this->input->post('phone1') .'-'. $this->input->post('phone2') .'-'. $this->input->post('phone3'),
        				       );
        }
        if ($this->form_validation->run($this) == true && $this->ion_auth->register($username,$password,$email,$additional_data)) { //check to see if we are creating the user
                //redirect them back to the admin page
        	$this->session->set_flashdata('message', "User Created");
       		redirect($this->config->item('page_auth', 'ion_auth'), 'refresh');
		}
		else { //display the create user form
	        //set the flash data error message if there is one
	        $this->data['message'] = (validation_errors()) ? ($this->ion_auth->errors() ? $this->ion_auth->errors() : validation_errors()) : $this->session->flashdata('message');

			$this->data['first_name']          = array('name'   => 'first_name',
		                                              'id'      => 'first_name',
		                                              'type'    => 'text',
		                                              'value'   => $this->form_validation->set_value('first_name'),
		                                             );
            $this->data['last_name']           = array('name'   => 'last_name',
		                                              'id'      => 'last_name',
		                                              'type'    => 'text',
		                                              'value'   => $this->form_validation->set_value('last_name'),
		                                             );
            $this->data['email']              = array('name'    => 'email',
		                                              'id'      => 'email',
		                                              'type'    => 'text',
		                                              'value'   => $this->form_validation->set_value('email'),
		                                             );
            $this->data['company']            = array('name'    => 'company',
		                                              'id'      => 'company',
		                                              'type'    => 'text',
		                                              'value'   => $this->form_validation->set_value('company'),
		                                             );
            $this->data['phone1']             = array('name'    => 'phone1',
		                                              'id'      => 'phone1',
		                                              'type'    => 'text',
		                                              'value'   => $this->form_validation->set_value('phone1'),
		                                             );
            $this->data['phone2']             = array('name'    => 'phone2',
		                                              'id'      => 'phone2',
		                                              'type'    => 'text',
		                                              'value'   => $this->form_validation->set_value('phone2'),
		                                             );
            $this->data['phone3']             = array('name'    => 'phone3',
		                                              'id'      => 'phone3',
		                                              'type'    => 'text',
		                                              'value'   => $this->form_validation->set_value('phone3'),
		                                             );
		    $this->data['password']           = array('name'    => 'password',
		                                              'id'      => 'password',
		                                              'type'    => 'password',
		                                              'value'   => $this->form_validation->set_value('password'),
		                                             );
            $this->data['password_confirm']   = array('name'    => 'password_confirm',
                                                      'id'      => 'password_confirm',
                                                      'type'    => 'password',
                                                      'value'   => $this->form_validation->set_value('password_confirm'),
                                                     );
          return $this->load->view('auth/auth/create_user', $this->data, true);
		}
    }

    function _get_csrf_nonce()
    {
	    $this->load->helper('string');
		$key	= random_string('alnum', 8);
		$value	= random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key=>$value);
	}

	function _valid_csrf_nonce()
	{
			if ( $this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
				 $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
	}

	function uri_point(){
	  $uri = '/adminka/mod';
	  return $uri;
	}
}





