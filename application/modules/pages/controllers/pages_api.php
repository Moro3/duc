<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('pages')){
  include_once ('pages.php');
}

/*
 * Класс Pages_api
 *
 */

class Pages_api extends Pages {

    function __construct(){
        parent::__construct();
        
    }


}