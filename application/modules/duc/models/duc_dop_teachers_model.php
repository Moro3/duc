<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Duc_dop_teachers_model.php
 *
 * Модель создания и управления объектами
 */

class Duc_dop_teachers_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'duc_dop_teachers';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
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
        		'far_key' => 'id' //ключ у таблице ко многим
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

