<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Duc_sections_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Duc_sections_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'duc_sections';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'duc_sections',

		);
	}

}

