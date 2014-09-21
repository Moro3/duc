<?php
  
class Testauth extends MX_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->driver('auth');
        $this->load->helper('auth');
        
        if( ! $this->auth->auth_check() ){
          // put your code here for example: redirect('Testauth/deny');
          // don't fotget to make 'Testauth/deny' allowed for all, else you will have infinte loop ;)
        }
    }
    
    public function index()
    {
        
        if (logged_in())
        {
            echo "Logged in!";
        }
        else
        {
            echo "Not logged baby";
            $this->load->view('users/login', array());
        }
    }
    
    public function login()
    {
        login('admin', 'password');
        $this->load->view('users/index', array());
    }
    
    public function add_group()
    {
        add_group('Users', 'Just your standard users group');
    }
    
    public function add_role_group()
    {
       add_role_to_group(1, 4);
    }
    
    public function add_user_group()
    {
        add_user_to_group(1, 2);
    }
    
    public function user_info()
    {
        $info = get_user_info(1);
    }
    
    public function deny()
    {
        echo "You try to access unauthorized area!";
    }
    
}


