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
                     'configs' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'configs',
                                   'action' => 'all',
                                   'id' => false,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: configs',
                            'module' => 'configs',
                            'controller' => 'configs',
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
                            'name' => 'lang: configs_setting',
                            'module' => 'configs',
                            'controller' => 'configs_settings',
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
                            'name' => 'lang: configs_setting_admin',
                            'module' => 'configs',
                            'controller' => 'configs_settings',
                            'method' => 'grid_admin_object',
                            'menu' => true,
                            'parent' => 'setting',
                     ),

              ),

       ),
       'control_uri' => 'admin',

);

