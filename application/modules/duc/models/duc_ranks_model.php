<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Duc_ranks_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Duc_ranks_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'duc_ranks';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'duc_ranks',

		);
	}


}

