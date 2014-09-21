<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Duc_addresses_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Duc_addresses_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'duc_addresses';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'duc_addresses',

		);
		$this->MY_has_many = array(
			'groups_address' => array(
        		'module' => 'duc',
        		'controller' => 'duc_groups_address',
        		'model' => 'duc_groups_address_model',
        		'foreign_key' => 'id_address',
        	),
		);
	}

}

