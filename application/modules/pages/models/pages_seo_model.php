<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Pages_seo_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Pages_seo_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'pages_seo';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'pages_seo',
            'content' => 'pages_contents',
            'header' => 'pages_headers',
		);

		$this->MY_belongs_to = array(
        	'content' => array(
        		'module' => 'pages',
        		'controller' => 'pages_contents',
        		'model' => 'pages_contents',
        		'foreign_key' => 'id_page_content',
        	),
		);

	}

}

