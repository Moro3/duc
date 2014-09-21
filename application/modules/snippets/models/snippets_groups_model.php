<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Snippets_groups_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Snippets_groups_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'snippets_groups';

		$this->MY_primary_key = 'id';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'snippets_groups',
			'snippet' => 'snippets'
		);
        $this->MY_has_many = array(
		    'snippet' => array(
		    	'module' => 'snippets',
		    	'controller' => 'snippets',
		    	'model' => 'snippets',
		    	'foreign_key' => 'group_id',
		    ),
		);

	}

}

