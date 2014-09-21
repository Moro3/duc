<?php (defined('BASEPATH')) OR exit('No direct script access allowed');



class Help_request extends CI_Driver_Library
{
    public $valid_drivers;
    public $CI;

    function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->config->load('help_request', TRUE);
        $this->valid_drivers = $this->CI->config->item('drivers', 'help_request');

        log_message('debug', "Help_request Class Initialized");
    }

}