<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Duc_directions_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Duc_directions_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'duc_directions';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'duc_directions',

		);
	}

}

