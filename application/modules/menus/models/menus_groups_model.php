<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Menus_groups_model.php
 *
 * Модель создания и управления объектами
 */

class Menus_groups_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'menus_groups';

		$this->MY_primary_key = 'id';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'menus_groups',
            'tree' => 'menus_trees',
            'place' => 'menus_places',
		);


	}

}

