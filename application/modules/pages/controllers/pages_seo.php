<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ���������� ����������� ����� ���� �� �� ��������
if( ! class_exists('pages')){
  include_once ('pages.php');
}

/*
 * ����� Duc_groups
 *
 */

class Pages_seo extends Pages {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_pages_seo';
    }


}