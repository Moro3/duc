<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    $config['uri'] = 'pages';

    $config['admin']['per_page'] = '20';
    $config['admin']['step_page'] = '5';

    $config['admin']['allow_per_page'] = array(5,6,7,8,9,10,15,20,30,50,100,150,200);

    $config['path']['images'] = assets_uploads().'images/pages/objects/';
    $config['path']['icons'] = assets_uploads().'images/pages/icons/';
    $config['path']['img_fon'] = 'img/fon_header/';


    $config['path']['root'] = FCPATH;
    $config['image_config'] = array('allowed_types' => 'gif|jpg|png|jpeg',
    								'max_size' => '20000',
    								'max_width' => '10000',
    								'max_height' => '10000',
    								'dir' => 'original',
    								'x' => '1600',
    								'y' => '1600',
    								'max_foto' => '20',

    );
	$config['images'] = array('big' =>	array(
										  	'dir' => 'big',
										  	'x' => '1280',
										  	'y' => '1024',
										  	'name' => 'Большое',
										),
										'middle' => array(
										  	'dir' => 'middle',
										  	'x' => '800',
										  	'y' => '600',
										  	'name' => 'Среднее',
										),
										'small' => array(
										  	'dir' => 'small',
										  	'x' => '320',
										  	'y' => '240',
										  	'name' => 'Маленькое',
										),
										'mini' => array(
										  	'dir' => 'mini',
										  	'x' => '80',
										  	'y' => '60',
										  	'name' => 'Миниатюра',
										),

	);



    $config['user']['per_page'] = '7';
    $config['user']['step_page'] = '5';
    // варианты кол-ва строк на странице
    $config['user']['allow_per_page'] = array(3,5,7,10,20,50,100);

    // Кол-во спец предложений  на главной
    $config['user']['count_special_main'] = 4;
    // Кол-во спец предложений  на внутренней
    $config['user']['count_special'] = 6;
