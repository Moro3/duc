<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('duc')){
  include_once ('duc.php');
}

/*
 * Класс Duc_numgroups
 *
 */

class Duc_numgroups extends Duc {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_duc_numgroups';
        $this->MY_table = 'duc_numgroups';
    }

    //возвращает список годов обучения
    function listYears(){
         $res = array_merge(array(0=>'нет'), range(1,8));
         return $res;
    }

    //возвращает список номеров групп
    function listGroups(){
         $res = array_merge(array(0=>'нет'), range(1,12));
         return $res;
    }

    function grid_render(){

   		parent::grid_render();
        //$this->load->view('grid/navigator/active');
        $this->load->view('grid/sorter/sortrows',
        				 array('grid' => $this->table,
        				 		'url' => '/grid/'.$this->MY_table.'/'.$this->MY_module.'/grid/'
        				 )
        );

    }

}
