<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Duc_schedules_model.php
 *
 * Модель создания и управления объектами
 */

class Duc_schedules_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'duc_schedules';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'duc_schedules',
            'groups' => 'duc_groups',
            'numgroup' => 'duc_numgroup',
            'teacher' => 'duc_teachers',
            'department' => 'duc_departments'
		);
		$this->MY_belongs_to = array(
        	'groups' => array(
        		'module' => 'duc',
        		'controller' => 'duc_groups',
        		'model' => 'duc_groups',
        		'foreign_key' => 'id_group',
        	),
        	'numgroups' => array(
        		'module' => 'duc',
        		'controller' => 'duc_numgroups',
        		'model' => 'duc_numgroups',
        		'foreign_key' => 'id_numgroup',
        	),
		);
	}

}

