<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Duc_dop_teachers_model.php
 *
 * ������ �������� � ���������� ���������
 */

class Duc_dop_teachers_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'duc_dop_teachers';
		// �������������� ���������� ������� ������
		// key - ��������� ������� � ������ ������� ��������
		$this->MY_table_access = array(
			'main' => 'duc_dop_teachers',
            'group' => 'duc_roups',
            'teacher' => 'duc_teachers',
		);
		$this->MY_belongs_to = array(
        	'groups' => array(
        		'module' => 'duc',
        		'controller' => 'duc_groups',
        		'model' => 'duc_groups',
        		'foreign_key' => 'id_group',
        		'far_key' => 'id' //���� � ������� �� ������
        	),
        	'teachers' => array(
        		'module' => 'duc',
        		'controller' => 'duc_teachers',
        		'model' => 'duc_teachers',
        		'foreign_key' => 'id_teacher',
        	),
        );
	}

}

