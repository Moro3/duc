<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
/  Фильтрация массива методом вложенных множеств
/
/
*/

class Order_arj {
  private $field_order;
  private $field_id;
  private $set_array = array();
  private $array_order = array();
  private $array_next = array();

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
      $this->_filter_short_array($array);
      //echo "<pre>array_order:<br />";
      //print_r($this->set_array);
      //echo "</pre>";
      if(count($this->set_array) > 0){
        foreach($this->set_array as $key=>$value){
          $this->_build_array($key);
        }
      }else{
        return false;
      }

      $array = $this->_sort_array($array);
      ksort($array);
      $this->set_array = array();
      $this->array_order = array();
      $this->array_next = array();

      return $array;


    }else{
      return false;
    }
  }
  // возвращает отсортированный многомерный массив
  function get_multi_array($array){
    if(is_array($array)){

    }
  }
  // приводит массив к сокращенному варианту
  // массив где ключ = параметр ID, значение = параметр сортировки
  function _filter_short_array(&$array){
    foreach ($array as $key=>$value){

      if(isset($value[$this->field_id],$value[$this->field_order])){
        $this->set_array[$value[$this->field_id]] = $value[$this->field_order];
      }
    }
  }
  // приводит массив к прежднему виду до сокращенного
  function _sort_array(&$array){
    if(count($this->array_order) > 0){
      //echo "<pre>array_order:<br />";
      //print_r($this->array_order);
      //echo "</pre>";
      foreach ($array as $key=>$value){
        if(in_array($value[$this->field_id], $this->array_order)){
          //echo "<pre>key - value:";
          //print_r($value);
          //echo "</pre>";
          $key_order = array_keys($this->array_order, $value[$this->field_id]);
          $arr_new[$key_order[0]] = $value;
        }
      }
      if(isset($arr_new)){
        return $arr_new;
      }else{
        return false;
      }
    }
  }
  // строительство основного массива
  function _build_array($id){
    //echo"$id<br/>";
    if(in_array($id, $this->array_order)){
      if(count($this->array_next) > 0){
        $this->array_next = array_reverse($this->array_next);
        $this->_add_array_next($id);
      }
    }else{
      //echo $id."<br/>";
      //$this->array_next[] = $id;
      //if(isset($this->set_array[$this->set_array[$id]])){
      if(isset($this->set_array[$id]) && !in_array($id,$this->array_next)){
        $this->array_next[] = $id;
        $this->_build_array($this->set_array[$id]);
      }else{
        //$this->array_next[] = $id;
        $this->array_next = array_reverse($this->array_next);
        $this->_add_array_next();
      }
    }

    //$this->array_next = array_reverse($this->array_next);
    //$this->_add_array_next();
    $this->array_next = array();
  }
  // добавление следующей части отсортированного массива в основной
  function _add_array_next($id = false){
    //echo "<pre>array_order id=$id:<br />";
    //print_r($this->array_order);
    //echo "</pre>";
    if($id != false) {

      foreach($this->array_order as $key=>$value){
        $arr[] = $value;
        if($id == $value){
          foreach($this->array_next as $value_next){
            $arr[] = $value_next;
          }
        }
      }
    }else{
      $arr = array_merge($this->array_order, $this->array_next);
    }
    $this->array_order = $arr;
    unset ($arr);
  }

/*//////////////////////////////////////////////////
| Работа сравнения порядка следования массива
|
*//////////////////////////////////////////////////
  // возвращает поля массива, которые должны подвергнуться изменениям
  // params: $array - массив сравнения
  //         $node_source - узел(лы) источника следования
  //                       (параметр id или массив из id в той последовательности которой ддолжны идти)
  //         $node_target - узел на основании которого будут производится ихменения
  //         $direct - [after, before] - default [after] - направление смещения относительно узла основания
  //                  after - после узла (по умолчанию), before -перед узлом
  // return: массив - с данными которые требуются менять
  function get_change_assign($array, $node_source, $node_target, $direct = 'after'){
    if (!is_array($node_source)){
      $node_source = array($node_source);
    }
    // конвертация в одномерный массив
    $arr_single = $this->array_multi_to_single($array);
    if (!is_array($arr_single)) {
      echo "не удалось собрать одномерный массив";
      return false;
    }

    // проверка наличие узла назначения в основном массиве
    if (!in_array($node_target, $arr_single)){
      echo "узел назначения <b>$node_target</b> не найден в основном массиве<br />";
      return false;
    }

    //проверка на наличие всех узлов источников в основном массиве
    foreach ($node_source as $item_order){
      if (!in_array($item_order, $arr_single)){
        echo "узел источника <b>$item_order</b> не найден в основном массиве<br />";
        return false;
      }
      if($item_order == $node_target) {
        echo "параметр узла назначения assign <b>$node_target</b> совпадает с узлом источника<br />";
        return false;
      }
    }
    //echo "<pre>";
    //print_r($arr_single);
    //echo "</pre>";
    $arr_order_single = $this->get_order_array_single($arr_single, $node_source, $node_target, $direct);

    $arr_id_order = $this->get_convert_id_order($arr_order_single);

    $arr_multi_result = $this->array_single_to_multi($arr_id_order, $array);
    if($arr_multi_result){
      return  $arr_multi_result;
    }else{
      return false;
    }
  }
  // конвертация многомерного массива в одномерный
  // return: array($key=>$value)  $key = порядковый номер $value = id
  function array_multi_to_single($array){
    if(is_array($array)){
      foreach ($array as $key=>$value){
        if(isset($value[$this->field_id]) && isset($value[$this->field_order])){
          $arr_single[$key] = $value[$this->field_id];
        }else{
          echo "значения необходимых полей не найдены в основном массиве<br />";
          return false;
        }
      }
    }else{
      echo "Это не массив";
      return false;
    }
    if (isset($arr_single)) return $arr_single;
    return false;
  }
  // конвертация скалярного отсортированного массива в ассоциативный
  // return: array($key=>$value) $key = id, $value = order
  function get_convert_id_order($array){
    $parent = 0;
    foreach($array as $key=>$value){
      $arr_new[$value] = $parent;
      $parent = $value;
    }
    if(isset($arr_new)){
      return $arr_new;
    }else{
      return false;
    }
  }
  // сортировка одномерного массива
  // params: $array - массив сравнения
  //         $node_source - узел(лы) источника следования
  //                       (параметр id или массив из id в той последовательности которой ддолжны идти)
  //         $node_target - узел на основании которого будут производится ихменения
  //         $direct - [after, before] - default [after] - направление смещения относительно узла основания
  //                  after - после узла (по умолчанию), before -перед узлом
  // return: массив - с данными которые требуются менять
  function get_order_array_single($array, $node_source, $node_target, $direct = 'after'){
    //echo "массив на сортировку: ";
    //print_r($array);
    if(!is_array($node_source)) $node_source = array($node_source);
    $first_source = 1;
    $array = array_values($array);
    foreach ($node_source as $item){
      $key_source = array_keys($array, $item);
      $key_source = $key_source[0];
      $key_target = array_keys($array, $node_target);
      $key_target = $key_target[0];

      unset($array[$key_source]);
      if ($direct == 'before') {
        $key_increment = $key_target - '0.5';
      }else{
        $key_increment = $key_target + '0.5';
      }
      $array["$key_increment"] = $item;
      ksort($array);
      $array = array_values($array);
      if($first_source == 1) {
        $direct = 'after';
        $first_source == 0;
      }
      $node_target = $item;

    }
    //echo "массив на изменения: ";
    //print_r($array);
    return $array;
  }
  // сравнение 2 массивов на поряд следование ключей
  // params: $array - массив основной
  //         $array_compare - массив сравнения
  // return: массив со значениями если есть различия
  function get_array_compare($array_source, $array_target){
    if(count($array_source) != count($array_target)) return false;

    foreach($array_source as $key=>$value){
      if($value != $array_compare[$key]){
        $arr_new[$key] = $array_compare[$key];
      }
    }
    if(isset($arr_new)){
      return $arr_new;
    }else return false;
  }
  // возвращает преобразование одномерного массива в многомерный
  function array_single_to_multi($arr_single, $arr_multi){
    foreach($arr_multi as $key=>$value){
      if(isset($arr_single[$value[$this->field_id]])){
        if($value[$this->field_order] != $arr_single[$value[$this->field_id]]){
          $value[$this->field_order] = $arr_single[$value[$this->field_id]];
          $arr_new[$key] = $value;
        }
      }
    }
    if(isset($arr_new)){
      return $arr_new;
    }else{
      return false;
    }
  }
  // смещение указанных полей в верх массива
  // params: $array - массив
  //         $node_source - узел(лы) источника следования которые сместятся вверх
  //                       (параметр id или массив из id в той последовательности которой должны идти)
  // return: массив сравнения в отсортированном виде
  function get_change_top($array, $node_source){
    if (!is_array($node_source)){
      $node_source = array($node_source);
    }
    // конвертация в одномерный массив
    $arr_single = $this->array_multi_to_single($array);
    if (!is_array($arr_single)) return false;
    // задаем направление смещения
    $direct = 'before';
    // назначаем узел назначения равным первому элементу массива
    if(isset($arr_single[0])) $node_target = $arr_single[0]; else return false;

    //проверка на наличие всех узлов источников в основном массиве
    foreach ($node_source as $item_order){
      if (!in_array($item_order, $arr_single)){
        echo "узел источника <b>$item_order</b> не найден в основном массиве<br />";
        return false;
      }
      if($item_order == $node_target) {
        echo "параметр узла назначения top <b>$node_target</b> совпадает с узлом источника<br />";
        return false;
      }
    }
    $arr_order_single = $this->get_order_array_single($arr_single, $node_source, $node_target, $direct);
    $arr_id_order = $this->get_convert_id_order($arr_order_single);
    $arr_multi_result = $this->array_single_to_multi($arr_id_order, $array);
    if($arr_multi_result){
      return  $arr_multi_result;
    }else{
      return false;
    }
  }
  // смещение указанных полей в верх массива
  // params: $array - массив
  //         $node_source - узел(лы) источника следования которые сместятся вниз
  //                       (параметр id или массив из id в той последовательности которой должны идти)
  // return: массив сравнения в отсортированном виде
  function get_change_end($array, $node_source){
    if (!is_array($node_source)){
      $node_source = array($node_source);
    }
    // конвертация в одномерный массив
    $arr_single = $this->array_multi_to_single($array);
    if (!is_array($arr_single)) return false;
    // задаем направление смещения
    $direct = 'after';
    // назначаем узел назначения равным последнему элементу массива
    $node_target = end($arr_single);
    //проверка на наличие всех узлов источников в основном массиве
    foreach ($node_source as $item_order){
      if (!in_array($item_order, $arr_single)){
        echo "узел источника <b>$item_order</b> не найден в основном массиве<br />";
        return false;
      }
      if($item_order == $node_target) {
        echo "параметр узла назначения end <b>$node_target</b> совпадает с узлом источника<br />";
        //return false;
      }
    }
    $arr_order_single = $this->get_order_array_single($arr_single, $node_source, $node_target, $direct);
    $arr_id_order = $this->get_convert_id_order($arr_order_single);
    $arr_multi_result = $this->array_single_to_multi($arr_id_order, $array);
    if($arr_multi_result){
      return  $arr_multi_result;
    }else{
      return false;
    }
  }
  function _sort(&$array){
    $this->array_order = $array;
    uasort ($this->array_order, $this, "cmp");
  }

  function cmp ($a,$b)
  {
    if(isset($this->array_order[$b])){
      return 1;
    }else{

    }
    return strcmp($a["sort"],$b["sort"]);
  }

}








