<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен


if( ! class_exists('mods')){
  include_once ('mods.php');
}

/*
 * Класс Mods_type
 *
 */

class Mods_type extends Mods {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_mods_type';
    }

}
