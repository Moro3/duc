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
            'group' => 'duc_groups',
		);
	}

}

