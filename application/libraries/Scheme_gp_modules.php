<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scheme_gp_modules {

  private $_scheme = array(); // загруженная схема как есть
  private $_scheme_key = array(); // схема с натуральными ключами
  private $_scheme_alias = array(); // схема с псевдонимными ключами


  function __construct(){
    $CI =& get_instance(); // to access CI resources, use $CI instead of $this
    //$CI->load->library('nested_set');
    $CI->load->database();
    $CI->load->dbforge();
    $this->db = $CI->db;
    $this->dbforge = $CI->dbforge;
    if(is_file(APPPATH.'config/scheme_gp_modules.php')){
      //$this->scheme = get_scheme(DIR_CONFIG.'/scheme.php');
      $this->load_file_array(APPPATH.'config/scheme_gp_modules.php');
    }else{
      exit('Нет файла схемы групп модулей');
    }
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
        if (isset($scheme_gp_modules)){
          if(count($this->_scheme) > 0){
            $this->_scheme = array_merge($this->_scheme,$scheme_gp_modules);
          }else{
            $this->_scheme = $scheme_gp_modules;
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

  // построение схемы группы модулей на основе псевдоимен
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
      $this->_scheme_key = $this->_scheme;
    }
  }

  //получение имени по его ключу
  //arg: $key - ключ
  //return: string - имя группы модуля
  //        false - если ключ не обнаружен
  function _name($key){

  }
  //получение ключа по его имени
  //arg: $name - имя
  //return: string - ключ группы модуля
  //        false - если имя не обнаружено
  function _key($name){

  }

  //получение списка модулей для указанной группы
  //arg: $group - имя группы
  //return: array - массив с именами ключей модулей
  function modules($group){
    if(isset($this->_scheme_alias[$group]['modules'])){
      return $this->_scheme_alias[$group]['modules'];
    }
    return false;
  }
  //получение списка модулей для указанной группы
  //arg: $group - ключевое имя группы
  //return: array - массив с именами ключей модулей
  function modules_keys($group){
    if(isset($this->_scheme_keys[$group]['modules'])){
      return $this->_scheme_keys[$group]['modules'];
    }
    return false;
  }
  //получение имени группы для указанного модуля
  //arg: $name_module - ключевое имя модуля
  //return: array - массив с именами ключей модулей
  function name_group_key($name_module){
    if(is_array($this->_scheme_keys)){
      foreach($this->_scheme_keys as $key=>$value){
        if(in_array($name_module,$value['modules'])){
          return $value['name'];
        }
      }
    }
    return false;
  }
}
