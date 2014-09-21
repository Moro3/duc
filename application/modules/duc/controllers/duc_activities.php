<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('duc')){
  include_once ('duc.php');
}

/*
 * Класс Duc_activities
 *
 */

class Duc_activities extends Duc {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_duc_activities';
    }

}
