<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ���������� ����������� ����� ���� �� �� ��������


if( ! class_exists('mods')){
  include_once ('mods.php');
}

/*
 * ����� Mods_type
 *
 */

class Mods_type extends Mods {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_mods_type';
    }

}
