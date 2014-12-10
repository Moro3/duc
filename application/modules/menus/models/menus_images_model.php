<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Menus_types_model.php
 *
 * ћодель создани€ и управлени€ объектами
 */

class Menus_images_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'menus_images';

		$this->MY_primary_key = 'id';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'menus_images',
            'tree' => 'menus_trees',

		);

		// дно изображение
		$this->MY_belongs_to = array(
        	'tree' => array(
        		'module' => 'duc',
        		'controller' => 'menus_trees',
        		'model' => 'menus_trees',
        		'foreign_key' => 'image_id',
        	),        	
		);

		// множественные изображени€
		/*
		$this->MY_has_many = array(
		    'tree' => array(
		    	'module' => 'menus',
		    	'controller' => 'menus_trees',
		    	'model' => 'menus_trees',
		    	'foreign_key' => 'tree_id',
		    ),
		);
		*/
	}

}

