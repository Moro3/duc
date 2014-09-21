<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Duc_groups_model.php
 *
 * Модель создания и управления объектами
 */

class Duc_groups_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'duc_groups';

		$this->MY_primary_key = 'id';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'duc_groups',
            'direction' => 'duc_directions',
            'department' => 'duc_departments',
            'activity' => 'duc_activities',
            'section' => 'duc_sections',
            'teacher' => 'duc_teachers',
		);

		$this->MY_belongs_to = array(
        	'teacher' => array(
        		'module' => 'duc',
        		'controller' => 'duc_teachers',
        		'model' => 'duc_teachers',
        		'foreign_key' => 'id_teacher',
        		'far_key' => 'id' //ключ у таблице ко многим
        	),
        	'direction' => array(
        		'module' => 'duc',
        		'controller' => 'duc_directions',
        		'model' => 'duc_directions',
        		'foreign_key' => 'id_direction',
        	),
        	'department' => array(
        		'module' => 'duc',
        		'controller' => 'duc_departments',
        		'model' => 'duc_departments',
        		'foreign_key' => 'id_department',
        	),
        	'activity' => array(
        		'module' => 'duc',
        		'controller' => 'duc_activities',
        		'model' => 'duc_activities',
        		'foreign_key' => 'id_activity',
        	),
        	'section' => array(
        		'module' => 'duc',
        		'controller' => 'duc_sections',
        		'model' => 'duc_sections',
        		'foreign_key' => 'id_section',
        	),
		);
		$this->MY_has_many = array(
		    'concertmaster' => array(
		    	'module' => 'duc',
		    	'controller' => 'duc_concertmasters',
		    	'through' => 'duc_concertmasters',
		    	'foreign_key' => 'id_group',
		    ),
		    'address' => array(
		    	'module' => 'duc',
		    	'controller' => 'duc_addresses',
		    	'through' => 'duc_addresses',
		    	'foreign_key' => 'id_group',
		    ),
		    'durations' => array(
		    	'module' => 'duc',
		    	'controller' => 'duc_durations',
		    	'through' => 'duc_durations',
		    	'foreign_key' => 'id_group',
		    ),
		    'schedules' => array(
		    	'module' => 'duc',
		    	'controller' => 'duc_schedules',
		    	'through' => 'duc_schedules',
		    	'foreign_key' => 'id_group',
		    ),
		    'photos' => array(
		    	'module' => 'duc',
		    	'controller' => 'duc_photos',
		    	'through' => 'duc_photos',
		    	'foreign_key' => 'id_group',
		    ),
		);
	}

}

