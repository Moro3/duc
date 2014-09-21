<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Menus_groups_places_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Menus_groups_places_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'menus_groups_places';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'menus_groups_places',
            'group' => 'menus_groups',
            'place' => 'menus_places',
		);
		$this->MY_belongs_to = array(
        	'groups' => array(
        		'module' => 'menus',
        		'controller' => 'menus_groups',
        		'model' => 'menus_groups',
        		'foreign_key' => 'group_id',
        		'far_key' => 'id' //���� � ������� �� ������
        	),
        	'places' => array(
        		'module' => 'menus',
        		'controller' => 'menus_places',
        		'model' => 'menus_places',
        		'foreign_key' => 'place_id',
        	),
        );
	}

}

