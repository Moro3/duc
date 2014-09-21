<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Duc_photos_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Duc_photos_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'duc_photos';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'duc_photos',
            'grouper' => 'duc_groups',
		);
		$this->MY_belongs_to = array(
        	'grouper' => array(
        		'module' => 'duc',
        		'controller' => 'duc_groups',
        		'model' => 'duc_groups',
        		'foreign_key' => 'id_group',
        	),
		);
	}

}

