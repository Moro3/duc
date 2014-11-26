<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен

if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 * Класс Menus_places
 *
 */

class Menus_places extends Menus {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_menus_places';
    }
}