<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Mods_route_model.php
 *
 * Модель создания и управления объектами
 */

class Mods_route_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'mods_route';

		$this->MY_primary_key = 'id';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'mods_route',
            'mod' => 'mods',
		);

        $this->MY_has_many = array(
		    'mod' => array(
		    	'module' => 'mods',
		    	'controller' => 'mods',
		    	'model' => 'mods',
		    	'foreign_key' => 'mod_id',
		    ),
		);
	}

}

