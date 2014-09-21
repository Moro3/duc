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
                                                           'mission' => 'page',
                                                          ),
                                     'id' => array('name' => 'go',
                                                           'form_value' => 'ID',
                                                           'uri_mode' => 'method',
                                                          ),
                                     'id_content' => array('name' => 'con_id',
                                                           'form_value' => 'ID_content',
                                                           'uri_mode' => 'get',
                                                          ),
                                     'per_page' => array('name' => 'per_page',
                                                           'form_value' => 'Кол-во строк',
                                                           'uri_mode' => 'get',
                                                          ),

                                     'lang' => array('name' => 'lng',
                                                           'form_value' => 'язык',
                                                           'uri_mode' => 'get',
                                                          ),
                                     'order' => array('name' => 'order',
                                                           'form_value' => 'сортировка',
                                                           'uri_mode' => 'get',
                                                           'mission' => 'order_by',
                                                          ),
                                     'order_direct' => array('name' => 'direction',
                                                           'form_value' => 'направление сортировки',
                                                           'uri_mode' => 'get',
                                                           'mission' => 'order_direct',
                                                          ),
                                     'filter' => array('name' => 'flt',
                                                           'form_value' => 'фильтр',
                                                           'uri_mode' => 'get',
                                                           'mission' => 'filter',
                                                          ),
                                   ),
                     'user_objects' => array(
                                     'name' => array('name' => 'name',
                                                           'form_value' => 'lang:first_name',
                                                          'uri_mode' => 'method',
                                     ),
                                     'object' => array('name' => 'object',
                                                           'form_value' => 'объект',
                                                          'uri_mode' => 'method',
                                     ),
                                     'page' => array('name' => 'page',
                                                           'form_value' => 'страница',
                                                           'uri_mode' => 'get',
                                     ),
                                     'per_page' => array('name' => 'per_page',
                                                           'form_value' => 'Кол-во строк',
                                                           'uri_mode' => 'get',
                                     ),
                                     'order' => array('name' => 'order',
                                                           'form_value' => 'сортировка',
                                                           'uri_mode' => 'get',
                                     ),
                                     'order_direct' => array('name' => 'direction',
                                                           'form_value' => 'направление сортировки',
                                                           'uri_mode' => 'get',
                                     ),
                                     'filter' => array('name' => 'flt',
                                                           'form_value' => 'фильтр',
                                                           'uri_mode' => 'get',
                                     ),
                     ),
                     'user_objects_id' => array(
                                     'name' => array('name' => 'action',
                                                           'form_value' => 'lang:first_name',
                                                          'uri_mode' => 'method',
                                     ),
                                     'object' => array('name' => 'object',
                                                           'form_value' => 'объект',
                                                          'uri_mode' => 'method',
                                     ),
                     				 'id' => array('name' => 'id',
                                                           'form_value' => 'id объекта',
                                                          'uri_mode' => 'method',
                                     ),

                     ),


    );



