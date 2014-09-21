<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *  Фильтры для запросов
 *
 *  @param array( 'name_filter' => array( 'key' => array( 'table' => 'name_table',
 *                                             'field' => 'name_field',
 *                                             'compare' => '',
 *                                           ),
 *                             ),
 *              );
 *
 *  name_filter - имя фильтра
 *  key         - имя параметра фильтра
 *  table       - имя таблицы
 *  field       - имя поля
 *  compare     - сравнение (in, =, <=, >=, !=, like)
 *  func_in     - имя входящей функции через которую нужно обработать значение перед тем как отправить запрос
 */


$config['filter'] = array(
              	'admin_adverts' => array(
                		'id' => array('table'   => 'adverts', //db:news  - если имя должно быть псевдонимом
                                         'field'    => 'id',
                                         'compare'  => 'in',
                  		),
                        'name' => array('table'    => 'adverts',
                                         'field'    => 'name',
                                         'compare'  => 'like',
                        ),
                        'show' => array('table'  => 'adverts',
                                         'field'    => 'show_i',
                                         'compare'  => '=',
                        ),
                        'vip' => array('table' => 'adverts',
                                         'field'    => 'vip',
                                         'compare'  => 'in',
                        ),
                        'd_in' => array('table'  => 'adverts',
                                         'field'    => 'date_create',
                                         'compare'  => '>=',
                                         'func_in'  => 'strtotime',
                        ),
                        'd_out' => array('table' => 'adverts',
                                         'field'    => 'date_create',
                                         'compare'  => '<=',
                                         'func_in'  => 'strtotime',
                        ),
                        'lang' => array('table'  => 'adverts',
                                         'field'    => 'id_language',
                                         'compare'  => '=',
                        ),
                    	'show_lang' => array('table'  => 'adverts',
                                         'field'    => 'show_i',
                                         'compare'  => '=',
                    	),
             	),
				'user_adverts' => array(
    					'id' => array('table'    => 'news',
                                        'field'    => 'id',
                                        'compare'  => 'in',
                        ),
                        'show' => array('table'  => 'news',
                                        'field'    => 'show_i',
                                        'compare'  => '=',
                        ),
    			),
);

