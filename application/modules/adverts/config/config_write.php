<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['uri'] = 'advert';
$config['admin'] = array(
       'per_page' => '10',
       'step_page' => '5',
       'allow_per_page' => array(3,5,6,7,8,9,10,15,20,30,50,120,150,200),

);
$config['path'] = array(
       'images' => '/adverts',

);
$config['user']['per_page'] = '5';
$config['user']['step_page'] = '5';

// варианты кол-ва строк на странице
$config['user']['allow_per_page'] = array(3,5,7,10,20,50,100);
$config['user']['modal'] = array(
       'time_on' => '60',
       'speed' => '45',
       'active' => '1',
       'switch' => '1',
       'switch_default' => '1',

);

