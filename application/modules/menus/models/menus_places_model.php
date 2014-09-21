<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Menus_places_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Menus_places_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'menus_places';

		$this->MY_primary_key = 'id';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'menus_places',
            'tree' => 'menus_trees',

		);

		$this->MY_has_many = array(
		    'tree' => array(
		    	'module' => 'menus',
		    	'controller' => 'menus_trees',
		    	'model' => 'menus_trees',
		    	'foreign_key' => 'place_id',
		    ),
		);
	}

}

