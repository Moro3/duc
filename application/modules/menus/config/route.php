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
                     		'module' => 'menus',
                     		'controller' => 'menus_trees',
                     		'method' => 'index',
                     		'control_uri' => 'admin',
                     ),
                     'menus' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'menus',
                                   'action' => 'all',
                                   'id' => false,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: menus_tree',
                            'module' => 'menus',
                            'controller' => 'menus_trees',
                            'method' => 'grid_admin_object',
                            'menu' => true,
                     ),

                     'menus_places' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'menus',
                                   'action' => 'all',
                                   'id' => true,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: menus_tree',
                            'module' => 'menus',
                            'controller' => 'menus',
                            'method' => 'menu_place',
                            'parent' => 'menus',
                            'menu' => false,
                     ),
                     'places' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'places',
                                   'action' => 'all',
                                   'id' => false,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: menus_places',
                            'module' => 'menus',
                            'controller' => 'menus_places',
                            'method' => 'grid_admin_object',
                            'menu' => true,
                     ),
                     'menus_groups' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'groups',
                                   'action' => 'all',
                                   'id' => false,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: menus_groups',
                            'module' => 'menus',
                            'controller' => 'menus_groups',
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
                            'name' => 'lang: menus_setting',
                            'module' => 'menus',
                            'controller' => 'menus_settings',
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
                            'name' => 'lang: menus_setting_admin',
                            'module' => 'menus',
                            'controller' => 'menus_settings',
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
                            'name' => 'lang: menus_setting_user',
                            'module' => 'menus',
                            'controller' => 'menus_settings',
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
                     'dataTypes' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 3,
                                   'action' => false,
                                   //'id' => true,
                            ),
                            'start_segment' => 2,
                            'name' => 'lang: menus',
                            'module' => 'menus',
                            'controller' => 'menus_api',
                            'method' => 'getSelectTypeForGrid',
                            'arg' => array('index:menu'),
                            //'parent' => 'teachers_list',
                            //'menu' => true,
                     ),
                     'dataPlaceInGroup' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 3,
                                   'action' => false,
                                   //'id' => true,
                            ),
                            'start_segment' => 2,
                            'name' => 'lang: menus',
                            'module' => 'menus',
                            'controller' => 'menus_groups',
                            'method' => 'get_places_is_group',
                            'arg' => array('index:menu'),
                            //'parent' => 'teachers_list',
                            //'menu' => true,
                     ),
               ),


       ),
       'control_uri' => 'admin',

);

