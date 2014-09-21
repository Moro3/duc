<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Menus_trees_model.php
 *
 * Модель создания и управления объектами
 */

class Menus_trees_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'menus_trees';

		$this->MY_primary_key = 'id';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'menus_trees',
            'place' => 'menus_places',
            'type' => 'menus_types',
		);

		$this->MY_belongs_to = array(
        	'place' => array(
        		'module' => 'menus',
        		'controller' => 'menus_places',
        		'model' => 'menus_places',
        		'foreign_key' => 'place_id',
        	),
        	'type' => array(
        		'module' => 'menus',
        		'controller' => 'menus_types',
        		'model' => 'menus_places',
        		'foreign_key' => 'type_id',
        	),
		);
	}

}

