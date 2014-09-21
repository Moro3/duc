<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    $config['uri'] = 'menus';

    $config['admin']['per_page'] = '20';
    $config['admin']['step_page'] = '5';

    $config['admin']['allow_per_page'] = array(5,6,7,8,9,10,15,20,30,50,100,150,200);

    $config['path']['images'] = assets_uploads().'images/menus/objects/';
    $config['path']['icons'] = assets_uploads().'images/menus/icons/';


    $config['path']['root'] = FCPATH;
