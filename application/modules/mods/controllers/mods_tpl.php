<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ���������� ����������� ����� ���� �� �� ��������


if( ! class_exists('mods')){
  include_once ('mods.php');
}

/*
 * ����� Mods_tpl
 *
 */

class Mods_tpl extends Mods {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_mods_tpl';
        $this->MY_table = 'mods_tpl';
    }

}
