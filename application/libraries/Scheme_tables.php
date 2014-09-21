<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scheme_tables {

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
    if(is_file(APPPATH.'config/scheme_tables.php')){
      //$this->scheme = get_scheme(DIR_CONFIG.'/scheme.php');
      $this->load_file_array(APPPATH.'config/scheme_tables.php');
    }else{
      exit('Нет файла схемы таблиц');
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
        if (isset($scheme_tables)){
          if(count($this->_scheme) > 0){
            $this->_scheme = array_merge($this->_scheme,$scheme_tables);
          }else{
            $this->_scheme = $scheme_tables;
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

  // построение схемы таблиц на основе псевдоимен
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
  // построение схемы таблиц на основе натуральных имен
  function _build_scheme_keys(){
    if(count($this->_scheme) > 0){
      $this->_scheme_key = $this->_scheme;
    }
  }

  //получение имени по его ключу
  //arg: $key - ключ
  //return: string - имя таблицы
  //        false - если ключ не обнаружен
  function _name($key){

  }
  //получение ключа по его имени
  //arg: $name - имя
  //return: string - ключ таблицы
  //        false - если имя не обнаружено
  function _key($name){

  }

}
