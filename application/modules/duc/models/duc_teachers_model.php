<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Duc_teachers_model.php
 *
 * Модель создания и управления объектами
 */

class Duc_teachers_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'duc_teachers';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'duc_teachers',
            'qualification' => 'duc_qualifications',
            'rank' => 'duc_ranks',
		);

		$this->MY_belongs_to = array(
        	'qualification' => array(
        		'module' => 'duc',
        		'controller' => 'duc_qualifications',
        		'model' => 'duc_qualifications',
        		'foreign_key' => 'id_qualification',
        	),
        	'rank' => array(
        		'module' => 'duc',
        		'controller' => 'duc_ranks',
        		'model' => 'duc_ranks',
        		'foreign_key' => 'id_rank',
        	),
		);
		$this->MY_has_many = array(
			'groups' => array(
        		'module' => 'duc',
        		'controller' => 'duc_groups',
        		'model' => 'duc_groups_model',
        		'foreign_key' => 'id_teacher',
        	),
        	'concertmaster' => array(
		    	'module' => 'duc',
		    	'controller' => 'duc_concertmasters',
		    	'through' => 'duc_concertmasters',
		    	'foreign_key' => 'id_teacher',
		    ),
		);
	}

}

