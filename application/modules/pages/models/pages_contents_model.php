<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Pages_contents_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Pages_contents_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'pages_contents';

		$this->MY_primary_key = 'id';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'pages_contents',
            'header' => 'pages_headers',
            'seo' => 'pages_seo',
		);

		$this->MY_belongs_to = array(
        	'header' => array(
        		'module' => 'pages',
        		'controller' => 'pages_headers',
        		'model' => 'pages_headers',
        		'foreign_key' => 'id_page_header',
        		'far_key' => 'id' //���� � ������� �� ������
        	),
		);
		$this->MY_has_many = array(
		    'seo' => array(
		    	'module' => 'pages',
		    	'controller' => 'pages_seo',
		    	'model' => 'pages_seo',
		    	'foreign_key' => 'id_page_content',
		    ),
		);
	}

}

