<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    $config['uri'] = 'advert';

    $config['admin']['per_page'] = '10';
    $config['admin']['step_page'] = '5';

    $config['admin']['allow_per_page'] = array(3, 5,6,7,8,9,10,15,20,30,50,100,150,200);
    $config['admin']['allow_sorter'] = array(
   		'ids' => 'id', 'show' => 'show_i', 'sort' => 'sort_i', 'vip', 'name', 'date'=>'date_create'
    );
    $config['admin']['allow_direct'] = array('asc' => 'asc', 'desc' => 'desc');

    $config['path']['images'] = assets_uploads().'images/advert/';
    $config['path']['icons'] = assets_uploads().'images/advert/icons/';
    $config['path']['root'] = FCPATH;


    $config['user']['per_page'] = '7';
    $config['user']['step_page'] = '5';
    // варианты кол-ва строк на странице
    $config['user']['allow_per_page'] = array(3,5,7,10,20,50,100);

