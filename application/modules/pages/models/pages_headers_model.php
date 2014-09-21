<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Pages_headers_model.php
 *
 * Модель создания и управления объектами
 */

class Pages_headers_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'pages_headers';

		$this->MY_primary_key = 'id';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'pages_headers',
            'content' => 'pages_contents',
            'seo' => 'pages_seo',
		);

		$this->MY_has_many = array(
		    'content' => array(
		    	'module' => 'pages',
		    	'controller' => 'pages_contents',
		    	'model' => 'pages_contents',
		    	'foreign_key' => 'id_page_header',
		    ),
		);
	}

}

