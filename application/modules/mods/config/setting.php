<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    $config['uri'] = 'mods';

    $config['admin']['per_page'] = '20';
    $config['admin']['step_page'] = '5';

    $config['admin']['allow_per_page'] = array(5,6,7,8,9,10,15,20,30,50,100,150,200);

    $config['path']['images'] = assets_uploads().'images/pages/objects/';
    $config['path']['icons'] = assets_uploads().'images/pages/icons/';
    $config['path']['img_fon'] = 'img/fon_header/';


    $config['path']['root'] = FCPATH;


    $config['user']['per_page'] = '7';
    $config['user']['step_page'] = '5';
    // варианты кол-ва строк на странице
    $config['user']['allow_per_page'] = array(3,5,7,10,20,50,100);

    // Кол-во спец предложений  на главной
    $config['user']['count_special_main'] = 4;
    // Кол-во спец предложений  на внутренней
    $config['user']['count_special'] = 6;
