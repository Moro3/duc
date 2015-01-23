<?php (defined('BASEPATH')) OR exit('No direct script access allowed');



class Forms extends CI_Driver_Library
{
    public $valid_drivers;
    public $CI;    

    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->config->load('forms', TRUE);
        $this->valid_drivers = $this->CI->config->item('type', 'forms');        

        log_message('debug', "Forms Class Initialized");
    }
    
}