<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('duc')){
  include_once ('duc.php');
}

/*
 * Класс Duc_departments
 *
 */

class Duc_departments extends Duc {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_duc_departments';
    }

}
