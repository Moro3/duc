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
         				'groups' => array(
         							'path' => assets_uploads().'images/duc/objects/',
    								'dir' => 'original',
    								'x' => '1600',
    								'y' => '1600',
    								'max_foto' => '20',
    								'name' => 'Коллективы',
         				),
         				'teachers' => array(
         							'path' => assets_uploads().'images/duc/teachers/',
         							'path_resize' => assets_uploads().'images/duc/teachers/',
    								'dir' => 'ori',
    								'x' => '1600',
    								'y' => '1600',
    								'maintain_ratio' => true,
    								'max_foto' => '1',
    								'name' => 'Педагоги',
         				),
    );
    $config['images']['resize'] = array(
    					'groups' => array(
                        			'big' =>	array(
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
									  	'x' => '360',
									  	'y' => '240',
									  	'name' => 'Маленькое',
									  	'maintain_ratio' => false,
									  	//'type' => 'crop',
									  	'master_dim' => 'fix',
									  	//'x_axis' => '100%',
									  	//'y_axis' => '100%',
									),
									'mini' => array(
									  	'dir' => 'mini',
									  	'x' => '80',
									  	'y' => '60',
									  	'name' => 'Миниатюра',
									),
                        ),
    					'teachers' => array(
                            'big' => array(
							  	'dir' => 'big',
								'x' => '1280',
							  	'y' => '1024',
							  	'name' => 'Большое',
							),
							'small' => array(
							  	'dir' => 'small',
							  	'x' => '320',
							  	'y' => '240',
							  	'name' => 'Маленькое',
							),
							'mini' => array(
							  	'dir' => 'mini',
							  	'x' => '60',
							  	'y' => '100',
							  	'name' => 'Миниатюра',
							  	'maintain_ratio' => false,
							  	'master_dim' => 'fix',
							),
                        ),
    );