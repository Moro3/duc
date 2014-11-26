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
                     		'module' => 'mods',
                     		'controller' => 'mods',
                     		'method' => 'index',
                     		'control_uri' => 'admin',
                     ),
                     'mods' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'mods',
                                   'action' => 'all',
                                   'id' => false,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: mods',
                            'module' => 'mods',
                            'controller' => 'mods',
                            'method' => 'grid_admin_object',
                            'menu' => true,
                     ),

                     'mods_route' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'mods_route',
                                   'action' => 'all',
                                   'id' => false,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: mods_route',
                            'module' => 'mods',
                            'controller' => 'mods_route',
                            'method' => 'grid_admin_object',
                            'menu' => true,
                     ),

                     'mods_tpl' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'mods_tpl',
                                   'action' => 'all',
                                   'id' => false,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: mods_tpl',
                            'module' => 'mods',
                            'controller' => 'mods_tpl',
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
                            'name' => 'lang: mods_setting',
                            'module' => 'mods',
                            'controller' => 'mods_settings',
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
                            'name' => 'lang: mods_setting_admin',
                            'module' => 'mods',
                            'controller' => 'mods_settings',
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
                            'name' => 'lang: mods_setting_user',
                            'module' => 'mods',
                            'controller' => 'mods_settings',
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
                     		'module' => 'mods',
                     		'controller' => 'mods',
                     		'method' => 'index',
                     		'control_uri' => 'admin',
                     ),

                     'pages_foto_delete' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                  'menu' => 'mods',
                                   'action' => 'delete_foto',
                                   'id' => false,
                            ),
                            'start_segment' => 2,
                            'name' => 'lang: mods',
                            'module' => 'mods',
                            'controller' => 'mods',
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
                     		'module' => 'mods',
                     		'controller' => 'mods',
                     		'method' => 'index',
                     		'control_uri' => false,
                     ),


               ),

       ),
       'control_uri' => 'admin',

);

