<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Menus_types_model.php
 *
 * Модель создания и управления объектами
 */

class Menus_types_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'menus_types';

		$this->MY_primary_key = 'id';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'menus_types',
            'tree' => 'menus_trees',

		);

		$this->MY_has_many = array(
		    'tree' => array(
		    	'module' => 'menus',
		    	'controller' => 'menus_trees',
		    	'model' => 'menus_trees',
		    	'foreign_key' => 'type_id',
		    ),
		);
	}

}

