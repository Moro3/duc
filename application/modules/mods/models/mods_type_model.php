<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Mods_type_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Mods_type_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'mods_type';

		$this->MY_primary_key = 'id';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'mods_type',
            'mod' => 'mods',
		);

        $this->MY_has_many = array(
		    'mod' => array(
		    	'module' => 'mods',
		    	'controller' => 'mods',
		    	'model' => 'mods',
		    	'foreign_key' => 'mod_id',
		    ),
		);
	}

}

