<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('pages')){
  include_once ('pages.php');
}

/*
 * Класс Duc_groups
 *
 */

class Pages_seo extends Pages {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_pages_seo';
    }


}