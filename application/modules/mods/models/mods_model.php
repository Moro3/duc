<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Mods_model.php
 *
 * Модель создания и управления объектами
 */

class Mods_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'mods';

		$this->MY_primary_key = 'id';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'mods',
            'type' => 'mods_type',
            'tpl' => 'mods_tpl',
		);

		$this->MY_has_many = array(
		    'tpl' => array(
		    	'module' => 'mods',
		    	'controller' => 'mods_tpl',
		    	'model' => 'mods_tpl',
		    	'foreign_key' => 'mod_id',
		    ),
		);
		$this->MY_belongs_to = array(
        	'type' => array(
        		'module' => 'mods',
        		'controller' => 'mods_type',
        		'model' => 'mods_type',
        		'foreign_key' => 'type_id',
        	),
		);
	}

}

