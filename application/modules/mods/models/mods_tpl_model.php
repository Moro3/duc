<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Mods_tpl_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Mods_tpl_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'mods_tpl';

		$this->MY_primary_key = 'id';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'mods_tpl',
            'mod' => 'mods',
		);

		$this->MY_belongs_to = array(
            'mod' => array(
		    	'module' => 'mods',
		    	'controller' => 'mods',
		    	'model' => 'mods',
		    	'foreign_key' => 'mod_id',
		    ),
		);
	}

}

