<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Duc_durations_model.php
 *
 * Модель создания и управления объектами
 */

class Duc_durations_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'duc_durations';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'duc_durations',

		);
		$this->MY_belongs_to = array(
        	'groups' => array(
        		'module' => 'duc',
        		'controller' => 'duc_groups',
        		'model' => 'duc_groups',
        		'foreign_key' => 'id_group',
        	),
		);
	}

}

