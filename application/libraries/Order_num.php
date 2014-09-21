<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
/  Фильтрация массива методом естесственной нумерации(1,2,3..9,10,11 и т.д.)
/
/
*/

class Order_num {
  private $field_order;
  private $field_id;
  private $direct = 'asc';
  //private $set_array = array();
  private $array_order = array();
  //private $array_next = array();

  function __construct(){

  }
  // установка поля по которому будет сортировка
  function set_field_order($name){
    if(!empty($name)){
      $this->field_order = $name;
    }else{
      $this->field_order = '';
    }
  }
  // установка направления сортировки
  function set_direct($name){
    if(!empty($name)){
      $this->direct = $name;
    }else{
      $this->direct = 'asc';
    }
  }

  // установка поля ID в массиве
  function set_field_id($name){
    if(!empty($name)){
      $this->field_id = $name;
    }else{
      $this->field_id = 'id';
    }
  }

  // возвращает отсортированный массив
  function get_array($array){
    if(is_array($array)){
      if(!empty($this->field_order)) $this->_sort($array);
      if(count($this->array_order) > 0){
        return $this->array_order;
      }else{
        return $array;
      }

    }else{
      return false;
    }
  }
  // возвращает отсортированный многомерный массив
  function get_multi_array($array){
    if(is_array($array)){

    }
  }


  function _sort(&$array){
    $this->array_order = $array;
    switch($this->direct){
      case 'desc':
        uasort ($this->array_order, array($this,"cmp"));
        $this->array_order = array_reverse($this->array_order, true);
        break;
      case 'asc':
        uasort ($this->array_order, array($this,"cmp"));
        break;
      case 'random': // оставляем без изменений, т.к. при случайной сортировке не сохраняются индексы
        //shuffle($this->array_order, true);
        break;
      default:
        uasort ($this->array_order, array($this,"cmp"));
        break;
    }
  }

  function cmp ($a,$b)
  {
    //return strcmp($a[$this->field_order],$b[$this->field_order]);
    return strnatcmp($a[$this->field_order],$b[$this->field_order]);
  }

}


