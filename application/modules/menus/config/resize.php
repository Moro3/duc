<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	$config['images']['config'] = array(
    					'!' => array(
                        			'allowed_types' => 'gif|jpg|png|jpeg',
    								'max_size' => '20000',
    								'max_width' => '10000',
    								'max_height' => '10000',
    								'dir' => 'original',
    								'x' => '1600',
    								'y' => '1600',
    								'max_foto' => '20',
         				),
         				'trees' => array(
         							'path' => assets_uploads().'images/menus/trees/',
         							'path_resize' => assets_uploads().'images/menus/trees_size/',
    								'dir' => 'original',
    								'x' => '1600',
    								'y' => '1600',
    								'max_foto' => '20',
    								'name' => 'Дерево меню',
         				),
         				'places' => array(
         							'path' => assets_uploads().'images/menus/places/',
         							'path_resize' => assets_uploads().'images/menus/places/',
    								'dir' => 'ori',
    								'x' => '1600',
    								'y' => '1600',
    								'maintain_ratio' => true,
    								'max_foto' => '1',
    								'name' => 'Место расположения',
         				),
         				'groups' => array(
         							'path' => assets_uploads().'images/menus/groups/',
         							'path_resize' => assets_uploads().'images/menus/groups/',
    								'dir' => 'ori',
    								'x' => '1600',
    								'y' => '1600',
    								'maintain_ratio' => true,
    								'max_foto' => '1',
    								'name' => 'Группа',
         				),
    );
    $config['images']['resize'] = array(
    					'trees' => array(
                        			'big' =>	array(
										  	'dir' => 'big',
										  	'x' => '140',
										  	'y' => '120',
										  	'name' => 'Большое',
									),
									'middle' => array(
									  	'dir' => 'middle',
									  	'x' => '70',
									  	'y' => '60',
									  	'name' => 'Среднее',
									),
									'small' => array(
									  	'dir' => 'small',
									  	'x' => '35',
									  	'y' => '30',
									  	'name' => 'Маленькое',
									  	//'maintain_ratio' => false,
									  	//'type' => 'crop',
									  	//'master_dim' => 'fix',
									  	//'x_axis' => '100%',
									  	//'y_axis' => '100%',
									),
									'mini' => array(
									  	'dir' => 'mini',
									  	'x' => '20',
									  	'y' => '17',
									  	'name' => 'Миниатюра',
									),
                        ),
    					
    );