<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Duc_qualifications_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Duc_qualifications_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'duc_qualifications';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'duc_qualifications',

		);
	}


}

