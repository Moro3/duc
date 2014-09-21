<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**********************************************************
 * Формирование схемы модулей сайта
 * Все данные хранятся в массиве в файле scheme_modules.php папке config вашего приложения
 *
 * Заключается в удобсьве для дальнейшего представления данных о модулях или других определений
 * которые могут формироваться в группы, группы соответственно должны быть сконфигурированы отдельным объектом
 * Каждый модуль может содержать список таблиц которые он использует
 * в дальнейшем это может быть использовано при программировании в самих модулях или при их построении
 *
 * $_scheme - представляет собой массив копия в файле scheme_modules.php
 * $_scheme_keys - массив схемы с информацией о модулях на основе массива _scheme где ключами являются изначальные ключи массивов
 * $_scheme_alias - массив схемы с информацией о модулях на основе массива _scheme где ключами являются иена массивов
 *
*/



class Scheme_modules {

  private $_scheme = array(); // загруженная схема как есть
  private $_scheme_keys = array(); // схема с натуральными ключами
  private $_scheme_alias = array(); // схема с псевдонимными ключами
  private $gp_modules;

  function __construct(){
    $CI =& get_instance(); // to access CI resources, use $CI instead of $this
    //$CI->load->library('nested_set');
    $CI->load->database();
    $CI->load->dbforge();
    $this->db = $CI->db;
    $this->dbforge = $CI->dbforge;
    if(is_file(APPPATH.'config/scheme_modules.php')){
      //$this->scheme = get_scheme(DIR_CONFIG.'/scheme.php');
      $this->load_file_array(APPPATH.'config/scheme_modules.php');
    }else{
      exit('Нет файла схемы модулей');
    }
    $CI->load->library('scheme_gp_modules');
    $this->gp_modules = $CI->scheme_gp_modules;
  }
  /*
  //////////////////////////////
  /  Варианты загрузки схемы
  //////////////////////////////
  /
  */
  // Загрузка файла в виде массива
  function load_file_array($file){
      if(is_file($file)){
        //echo "$file<br />";
        require ($file);
        //print_r ($scheme_base);
        if (isset($scheme_modules)){
          if(count($this->_scheme) > 0){
            $this->_scheme = array_merge($this->_scheme,$scheme_modules);
          }else{
            $this->_scheme = $scheme_modules;
          }
        }
      }

    $this->build_scheme();
  }

  /*
  /////////////////////////////////////////////////////////////////
  /  Построение схемы
  /  Использование: при загрузке новой схемы или изменении схемы
  /////////////////////////////////////////////////////////////////
  */
  function build_scheme(){
    $this->_build_scheme_keys();
    $this->_build_scheme_alias();

  }

  // построение схемы модулей на основе псевдоимен
  function _build_scheme_alias(){
    if(count($this->_scheme) > 0){
      foreach($this->_scheme as $key=>$value){
        if(!empty($value['name'])){
          $arr[$value['name']] = $value;
        }
      }
      $this->_scheme_alias = $arr;
    }
  }
  // построение схемы модулей на основе натуральных имен
  function _build_scheme_keys(){
    if(count($this->_scheme) > 0){
      $this->_scheme_keys = $this->_scheme;
    }
  }

  //получение имени по его ключу
  //arg: $key - ключ
  //return: string - имя модуля
  //        false - если ключ не обнаружен
  function _name($key){
    if(isset($this->_scheme_keys[$key]['name'])){
      return $this->_scheme_keys[$key]['name'];
    }
    return false;
  }
  //получение ключа по его имени
  //arg: $name - имя
  //return: string - ключ модуля
  //        false - если имя не обнаружено
  function _key($name){
    if(is_array($this->_scheme_keys)){
      foreach($this->_scheme_keys as $key=>$value){
        if($value['name'] == $name){
          return $key;
        }
      }
    }
    return false;
  }

  //получение параметров модуля по ключевому имени
  //arg: $key - имя ключа в схеме модулей по ключам
  //return: array - массив с параметрами модуля
  function params_keys($key){
    if(isset($this->_scheme_keys[$key]['name'])){
       return $this->params($this->_scheme_keys[$key]['name']);
       //return $this->_scheme_keys[$key]['name'];
    }
    return false;
  }
  //получение параметров модуля по псевдоименной схеме
  //arg: $name_module - имя модуля в схеме модулей по псевдоимени
  //return: array - массив с параметрами модуля
  function params($name_module){

    if(isset($this->_scheme_alias[$name_module]['name'])){

       return $this->_scheme_alias[$name_module];
    }
    return false;
  }

  //проверяет на существование модуля
  function isset_module($name){
    if(isset($this->_scheme_alias[$name])){
      return true;
    }
    return false;
  }

  //проверяет существование таблиц у модуля
  function isset_tables($name){
    if(isset($this->_scheme_alias[$name]['tables']) && is_array($this->_scheme_alias[$name]['tables'])){
      return true;
    }
    return false;
  }
  //возвращает кол-во таблиц у модуля
  function count_tables($name){
    if(isset($this->_scheme_alias[$name]['tables']) && is_array($this->_scheme_alias[$name]['tables'])){
      return count($this->_scheme_alias[$name]['tables']);
    }
    return 0;
  }

  //возвращает список таблиц модуля
  function tables($name){
    if(isset($this->_scheme_alias[$name]['tables']) && is_array($this->_scheme_alias[$name]['tables'])){
      return $this->_scheme_alias[$name]['tables'];
    }
    return false;
  }

  //возвращает имя группы в котором состоит модуль
  //arg: $key - ключевое имя модуля
  function name_group_key($key){
    $group = $this->gp_modules->name_group_key($key);
    if(!empty($group)){
      return $group;
    }
    return false;
  }
  //возвращает имя группы в котором состоит модуль
  //arg: $name - имя модуля
  function name_group($name){
    $group = $this->gp_modules->name_group_key($this->_key($name));
    if(!empty($group)){
      return $group;
    }
    return false;
  }

}
