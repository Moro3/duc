<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Duc_sections_model.php
 *
 * Модель создания и управления объектами
 */

class Duc_sections_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'duc_sections';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'duc_sections',

		);
	}

}

