<?php

/*
 *  Маршрутизация для модуля
 *
 *
 */
$config = array(
       'route' =>  array(

               'user' =>  array(
                     '!' => array(
                     		'index_name' => '1',
                     		'index' => array(),
                     		'start_segment' => 2,
                     		'module' => 'replace',
                     		'controller' => 'replace',
                     		'method' => 'index',
                     		'control_uri' => false,
                     ),
                     'replace' =>  array(
                            'index_name' => 'user_objects',
                            'index' =>  array(
                                   'name' => 'groups',
                                   'object' => false,

                            ),
                            'start_segment' => 2,
                            'name' => 'lang: replace_re',
                            'module' => 'replace',
                            'controller' => 'replace',
                            'method' => 'replace',
                            'menu' => true,
                     ),



               ),

       ),
       'control_uri' => 'admin',

);

