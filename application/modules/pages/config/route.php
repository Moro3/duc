<?php

/*
 *  Маршрутизация для модуля
 *
 *
 */
$config = array(
       'route' =>  array(
              'admin' =>  array(
                     '!' => array(
                     		'index_name' => '11',
                     		'index' => array(),
                     		'start_segment' => 4,
                     		'module' => 'duc',
                     		'controller' => 'duc',
                     		'method' => 'index',
                     		'control_uri' => 'admin',
                     ),
                     'pages' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'pages',
                                   'action' => 'all',
                                   'id' => false,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: pages_headers',
                            'module' => 'pages',
                            'controller' => 'pages_headers',
                            'method' => 'grid_admin_object',
                            'menu' => true,
                     ),

                     'setting' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'set',
                                   'action' => 'all',
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: pages_setting',
                            'module' => 'pages',
                            'controller' => 'pages_settings',
                            'method' => '_html_admin_object_id',
                            'menu' => true,
                     ),

                     'setting_admin' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'set',
                                   'action' => 'admin',
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: pages_setting_admin',
                            'module' => 'pages',
                            'controller' => 'pages_settings',
                            'method' => '_html_admin_setting_a',
                            'menu' => true,
                            'parent' => 'setting',
                     ),
                     'setting_user' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'set',
                                   'action' => 'user',
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: pages_setting_user',
                            'module' => 'pages',
                            'controller' => 'pages_settings',
                            'method' => '_html_admin_setting_u',
                            'menu' => true,
                            'parent' => 'setting',
                     ),

              ),
              'ajax' =>  array(
                     '!' => array(
                     		'index_name' => '1',
                     		'index' => array(),
                     		'start_segment' => 4,
                     		'module' => 'pages',
                     		'controller' => 'pages',
                     		'method' => 'index',
                     		'control_uri' => 'admin',
                     ),

                     'pages_foto_delete' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                  'menu' => 'pages',
                                   'action' => 'delete_foto',
                                   'id' => false,
                            ),
                            'start_segment' => 2,
                            'name' => 'lang: pages',
                            'module' => 'pages',
                            'controller' => 'pages_headers',
                            'method' => 'delete_foto',
                            //'arg' => array('index:object'),
                            //'parent' => 'teachers_list',
                            //'menu' => true,
                     ),

               ),
              'user' =>  array(
                     '!' => array(
                     		'index_name' => '1',
                     		'index' => array(),
                     		'start_segment' => 2,
                     		'module' => 'duc',
                     		'controller' => 'duc',
                     		'method' => 'index',
                     		'control_uri' => false,
                     ),
                     'groups_list' =>  array(
                            'index_name' => 'user_objects',
                            'index' =>  array(
                                   'name' => 'groups',
                                   'object' => false,

                            ),
                            'start_segment' => 2,
                            'name' => 'lang: duc_groups',
                            'module' => 'duc',
                            'controller' => 'duc_groups',
                            'method' => 'tpl_groups_list',
                            'menu' => true,
                     ),
                     'groups_id' =>  array(
                            'index_name' => 'user_objects',
                            'index' =>  array(
                                   'name' => 'groups',
                                   'object' => true,
                            ),
                            'start_segment' => 2,
                            'name' => 'lang: duc_groups',
                            'module' => 'duc',
                            'controller' => 'duc_groups',
                            'method' => 'tpl_groups_id',
                            'arg' => array('index:object'),
                            'parent' => 'groups_list',
                            'menu' => true,
                     ),
                     'teacher_list' =>  array(
                            'index_name' => 'user_objects',
                            'index' =>  array(
                                   'name' => 'teachers',
                                   'object' => false,

                            ),
                            'start_segment' => 2,
                            'name' => 'lang: duc_teachers',
                            'module' => 'duc',
                            'controller' => 'duc_teachers',
                            'method' => 'tpl_teachers_list',
                            'menu' => true,
                     ),

                     'teachers_id' =>  array(
                            'index_name' => 'user_objects',
                            'index' =>  array(
                                   'name' => 'teachers',
                                   'object' => true,
                            ),
                            'start_segment' => 2,
                            'name' => 'lang: duc_teachers',
                            'module' => 'duc',
                            'controller' => 'duc_teachers',
                            'method' => 'tpl_teachers_id',
                            'arg' => array('index:object'),
                            'parent' => 'teachers_list',
                            'menu' => true,
                     ),



               ),

       ),
       'control_uri' => 'admin',

);

