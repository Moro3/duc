<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Menus_groups_places_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Menus_trees_images_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'menus_trees_images';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'menus_trees_images',
            'tree' => 'menus_trees',
            'image' => 'menus_images',
		);
		$this->MY_belongs_to = array(
        	'trees' => array(
        		'module' => 'menus',
        		'controller' => 'menus_trees',
        		'model' => 'menus_trees',
        		'foreign_key' => 'tree_id',
        		'far_key' => 'id' //���� � ������� �� ������
        	),
        	'images' => array(
        		'module' => 'menus',
        		'controller' => 'menus_images',
        		'model' => 'menus_images',
        		'foreign_key' => 'image_id',
        	),
        );
	}

}

