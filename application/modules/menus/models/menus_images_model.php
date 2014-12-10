<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Menus_types_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Menus_images_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'menus_images';

		$this->MY_primary_key = 'id';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'menus_images',
            'tree' => 'menus_trees',

		);

		// ��� �����������
		$this->MY_belongs_to = array(
        	'tree' => array(
        		'module' => 'duc',
        		'controller' => 'menus_trees',
        		'model' => 'menus_trees',
        		'foreign_key' => 'image_id',
        	),        	
		);

		// ������������� �����������
		/*
		$this->MY_has_many = array(
		    'tree' => array(
		    	'module' => 'menus',
		    	'controller' => 'menus_trees',
		    	'model' => 'menus_trees',
		    	'foreign_key' => 'tree_id',
		    ),
		);
		*/
	}

}

