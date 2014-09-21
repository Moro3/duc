<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
                            'id' => array(
                                           'field' => 'id',
                                           'label' => lang('adverts_id'),
                                           'rules' => 'trim|is_natural_no_zero|max_length[10000]',
                            ),
                            'vip' => array(
                                           'field' => 'vip',
                                           'label' => lang('adverts_vip'),
                                           'rules' => 'trim|max_length[1]',
                                           'sorter' => true,
                            ),
    						'date_create' => array(
                                           'field' => 'date',
                                           'label' => lang('adverts_date_create'),
                                           'rules' => 'trim|required|max_length[10]',
                                           'sorter' => true,
                                           'func_in' => 'strtotime',
                                           'func_out' => array(
                                           					array(
                                           						'function' => 'date',
                                           						'arg' => array('m/d/Y',	'!'),
                                           					),
                                           ),
                                           'form' => array(
                                           				'type' => 'text',
                                           				'options' => array(
                                           					'class' => '',
                                           					'id' => '',
                                           				),
                                           				'error' => true,
                                           				'error_options' => array(

                                           				),
                                           ),
     						),
     						'date_update' => array(
                                           'field' => 'date_update',
                                           'label' => lang('adverts_date_update'),
                                           'rules' => 'trim|required|max_length[10]',

     						),
     						'show_i' => array(
                                           'field' => 'show',
                                           'label' => lang('adverts_show'),
                                           'rules' => 'trim|max_length[1]',
                                           'sorter' => true,
                            ),
                            'sort_i' => array(
                                           'field' => 'sort',
                                           'label' => lang('adverts_sort'),
                                           'rules' => 'trim|required|is_natural|max_length[3]',
                                           'sorter' => true,
                            ),
                            'name' => array(
                                           'field' => 'name',
                                           'label' => lang('adverts_name'),
                                           'rules' => 'trim|required|max_length[150]',
                                           'sorter' => true,
                            ),
                            'description' => array(
                                           'field' => 'description',
                                           'label' => lang('adverts_description'),
                                           'rules' => 'trim|required|max_length[50000]',
                            ),
                            'ip_create' => array(
                                           'field' => 'ip_create',
                                           'label' => lang('adverts_ip_create'),
                                           'rules' => 'trim|valid_ip|max_length[15]',

     						),
     						'ip_update' => array(
                                           'field' => 'ip_update',
                                           'label' => lang('adverts_ip_update'),
                                           'rules' => 'trim|valid_ip|max_length[15]',

     						),
     	);