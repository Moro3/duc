<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *  Имена полей базы данных
 *
 *
 */

$config['db']['tables'] =
                array(
                    'nnn'          => 'news_pages',
                    'nnn_name'     => 'news_name',
                    'nnn_contents' => 'news_contents',
                    'category'      => 'news_category',
                    'category_name' => 'news_category_name',
                    );
$config['db']['fields'] =
       array('nnn' =>
                    array(
                           'id'          => 'id',
                           'show'        => 'show_i',
                           'id_category' => 'id_category',
                           'uri'         => 'uri',
                           'date_create' => 'date_create',
                           'date_update' => 'date_update',
                           'ip_create'   => 'ip_create',
                           'ip_update'   => 'ip_update',
                         ),
            'nnn_name' =>
                    array(
                           'id'          => 'id',
                           'id_news'     => 'id_news',
                           'id_language' => 'id_language',
                           'show'        => 'show_i',
                           'sort'        => 'sort_i',
                           'img'         => 'img',
                           'name'        => 'name',
                           'date_create' => 'date_create',
                           'date_update' => 'date_update',
                           'ip_create'   => 'ip_create',
                           'ip_update'   => 'ip_update',
                         ),
            'nnn_contents' =>
                    array(
                           'id'             => 'id',
                           'id_news_name'   => 'id_news_name',
                           'sort'           => 'sort_id',
                           'name'           => 'name',
                           'short_description' => 'short_description',
                           'description'    => 'description',
                           'date_create' => 'date_create',
                           'date_update' => 'date_update',
                           'ip_create'   => 'ip_create',
                           'ip_update'   => 'ip_update',
                         ),
            'category' =>
                    array(
                           'id'          => 'id',
                           'show'        => 'show_i',
                           'sort'        => 'sort_i',
                           'uri'         => 'uri',
                           'icon'        => 'icon',
                           'date_create' => 'date_create',
                           'date_update' => 'date_update',
                           'ip_create'   => 'ip_create',
                           'ip_update'   => 'ip_update',
                         ),
            'category_name' =>
                    array(
                           'id'          => 'id',
                           'id_category' => 'id_news_category',
                           'show'        => 'show_i',
                           'id_language' => 'id_language',
                           'name'        => 'name',
                           'description' => 'description',
                           'date_create' => 'date_create',
                           'date_update' => 'date_update',
                           'ip_create'   => 'ip_create',
                           'ip_update'   => 'ip_update',
                        ),
             );