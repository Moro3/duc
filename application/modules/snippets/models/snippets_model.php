<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Snippets_model.php
 *
 * Модель создания и управления объектами
 */

class Snippets_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'snippets';

		$this->MY_primary_key = 'id';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'snippets',
            'grouper' => 'snippets_groups'
		);

		$this->MY_belongs_to = array(
        	'grouper' => array(
        		'module' => 'snippets',
        		'controller' => 'snippets_groups',
        		'model' => 'snippets_groups',
        		'foreign_key' => 'group_id',
        		'far_key' => 'id' //ключ у таблице ко многим
        	),
        );
	}

}

