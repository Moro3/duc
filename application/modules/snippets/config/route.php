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
                     		'module' => 'orders',
                     		'controller' => 'orders',
                     		'method' => 'index',
                     		'control_uri' => 'admin',
                     ),
                     'snippets' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'snippets',
                                   'action' => 'all',
                                   'id' => false,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: snippets',
                            'module' => 'snippets',
                            'controller' => 'snippets',
                            'method' => 'grid_admin_object',
                            'menu' => true,
                     ),

                     'groups' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'groups',
                                   'action' => 'all',
                                   'id' => false,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: snippets_groups',
                            'module' => 'snippets',
                            'controller' => 'snippets_groups',
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
                            'name' => 'lang: snippets_setting',
                            'module' => 'snippets',
                            'controller' => 'snippets_settings',
                            'method' => 'grid_admin_object',
                            'menu' => true,
                     ),

                     'setting_admin' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'set',
                                   'action' => 'admin',
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: snippets_setting_admin',
                            'module' => 'snippets',
                            'controller' => 'snippets_settings',
                            'method' => 'grid_admin_object',
                            'menu' => true,
                            'parent' => 'setting',
                     ),

              ),

       ),
       'control_uri' => 'admin',

);

