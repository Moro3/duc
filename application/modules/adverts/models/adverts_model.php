<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/adverts_model.php
 *
 * Модель создания и управления объектами
 */

class Adverts_model extends MY_Model
{
	function __construct(){
		parent::__construct();
		$this->MY_table = 'adverts';

	}

}

