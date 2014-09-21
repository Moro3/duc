<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$config['index_request'] = array(
                     'object' => array(
                                     'menu' => array('name' => 'm',
                                                           'form_value' => 'lang:first_name',
                                                          'uri_mode' => 'method',
                                                          ),
                                     'action' => array('name' => 'object',
                                                           'form_value' => 'объект',
                                                          'uri_mode' => 'method',
                                                          ),

                                     'page' => array('name' => 'page',
                                                           'form_value' => 'страница',
                                                           'uri_mode' => 'get',
                                                          ),
                                     'id' => array('name' => 'go',
                                                           'form_value' => 'ID',
                                                           'uri_mode' => 'method',
                                                          ),

                     ),

    );



