<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ���������� ����������� ����� ���� �� �� ��������
if( ! class_exists('duc')){
  include_once ('duc.php');
}

/*
 * ����� Duc_sections
 *
 */

class Duc_sections extends Duc {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_duc_sections';
    }

}
