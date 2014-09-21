<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 *  Имена полей базы данных
 *
 *
 */

$config['tables'] =
                array(
                    'object'          => 'mods',

                    );
$config['fields'] =
       array('mods' =>
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

             );