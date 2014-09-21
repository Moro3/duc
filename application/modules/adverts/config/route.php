<?php

/*
 *  Маршрутизация для модуля
 *
 *
 */
$config = array(
       'route' =>  array(
              'admin' =>  array(
                     'adverts' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'adverts',
                                   'action' => 'all',
                                   'id' => false,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: adverts_list',
                            'module' => 'adverts',
                            'controller' => 'adverts',
                            'method' => 'tpl_list',
                            'menu' => true,

                     ),
                     'adverts_all' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'adverts',
                                   'action' => 'all',
                                   'id' => false,

                            ),
                            'start_segment' => 4,
                            'name' => 'lang: adverts_all',
                            'module' => 'adverts',
                            'controller' => 'adverts',
                            'method' => 'tpl_list',
                            'menu' => true,
                            'parent' => 'adverts',
                     ),
                     'adverts_filter' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'adverts',
                                   'action' => 'all',
                                   'id' => false,
                                   'order' => true,
                                   'order_direct' => true,
                                   'page' => true,
                            ),
                            'start_segment' => 4,
                            'replace_empty_index' => true,
                            'name' => 'lang: adverts_all',
                            'module' => 'adverts',
                            'controller' => 'adverts',
                            'method' => 'tpl_list',
                            'menu' => false,
                            'parent' => 'adverts',
                     ),
                     'adverts_order' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'adverts',
                                   'action' => 'all',
                                   'id' => false,
                                   'order' => true,
                                   'order_direct' => true,
                                   'page' => false,
                            ),
                            'start_segment' => 4,
                            'replace_empty_index' => true,
                            'name' => 'lang: adverts_all',
                            'module' => 'adverts',
                            'controller' => 'adverts',
                            'method' => 'tpl_list',
                            'menu' => false,
                            'parent' => 'adverts',
                     ),
                     'adverts_pagination' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'adverts',
                                   'action' => 'all',
                                   'id' => false,
                                   'order' => false,
                                   'order_direct' => false,
                                   'page' => true,
                            ),
                            'start_segment' => 4,
                            'replace_empty_index' => true,
                            'name' => 'lang: adverts_all',
                            'module' => 'adverts',
                            'controller' => 'adverts',
                            'method' => 'tpl_list',
                            'menu' => false,
                            'parent' => 'adverts',
                     ),
                     'adverts_id' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'adverts',
                                   'action' => 'edit',
                                   'id' => true,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: adverts_id',
                            'module' => 'adverts',
                            'controller' => 'adverts',
                            'method' => 'tpl_id',
                            'arg' => array('index:id'),
                            'menu' => false,
                            'parent' => 'adverts',
                     ),
                     'adverts_add' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'adverts',
                                   'action' => 'add',
                                   'id' => false,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: adverts_add',
                            'module' => 'adverts',
                            'controller' => 'adverts',
                            'method' => 'tpl_add',
                            'menu' => true,
                            'parent' => 'adverts',
                     ),
                     'adverts_copy' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'adverts',
                                   'action' => 'add',
                                   'id' => true,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: adverts_copy',
                            'module' => 'adverts',
                            'controller' => 'adverts',
                            'method' => 'tpl_add',
                            'arg' => array('index:id'),
                            'menu' => false,
                            'parent' => 'adverts',
                     ),
                     'adverts_setting' =>  array(
                            'index_name' => 'object',
                            'index' =>  array(
                                   'menu' => 'adverts',
                                   'action' => 'setting',
                                   'id' => false,
                            ),
                            'start_segment' => 4,
                            'name' => 'lang: adverts_setting',
                            'module' => 'adverts',
                            'controller' => 'adverts_setting',
                            'method' => 'tpl_setting',
                            //'arg' => array('index:id'),
                            'menu' => true,
                            //'parent' => 'adverts',
                     ),
              ),
              'ajax' =>  array(
                     'uploads' =>  array(
                            'module' => 'lands',
                            'controller' => 'lands_photos',
                            'method' => 'upload_images_object',
                     ),
                     'print_images' =>  array(
                            'module' => 'lands',
                            'controller' => 'lands_photos',
                            'method' => 'print_images_object',
                     ),

               ),
               'user' =>  array(
                     'objects_list' =>  array(
                            'index_name' => 'user_objects',
                            'index' =>  array(
                                   'name' => 'objects',
                                   'object' => false,
                                   'category_object' => false,
                            ),
                            'start_segment' => 2,
                            'name' => 'lang: adverts_object',
                            'module' => 'adverts',
                            'controller' => 'adverts',
                            'method' => 'tpl_user_modal',
                            'menu' => true,
                     ),

                     'test' =>  array(
                            'index_name' => 'user_objects',
                            'index' =>  array(
                                   'name' => true,
                                   'object' => true,
                                   'page' => true,
                            ),
                            'start_segment' => 2,
                            'name' => 'lang: adverts_object',
                            'module' => 'adverts',
                            'controller' => 'adverts',
                            'method' => 'test',
                            'arg' => array(
                            			'index:name',
                            			'index:object',
                            			'index:page',
                            ),
                            'menu' => true,
                     ),
               ),

       ),
       'control_uri' => 'admin',

);

