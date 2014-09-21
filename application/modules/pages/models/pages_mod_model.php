<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Pages_model.php
 *
 * Модель создания и управления страницами сайта
 */

class Pages_mod_model extends CI_Model
{

    private $key_cache;
    private $cache = array();

    function __construct() {

        parent::__construct();
        //$this->key_cache = '12345';
        // все параметры используемые в работе
        $this->set_arr = array(
                           'table',
                           'id_field',
                           'id_type',
                           'id_page_field',
                           'id_module_tpl_field',
                           'sort_field',
                           'condition_field',
                           'selection_field'
                           );

    }


    function set_params($param){
      if(is_array($param)){
        $this->clear_setting();

        $rnd_str = '';
        foreach($param as $key=>$value){
          switch ($key){
            case 'table':
              $this->$key = $value;
              $rnd_str .= $value;
              break;
            case 'id_field':
              $this->$key = $value;
              $rnd_str .= $value;
              break;
            case 'id_type':
              $this->$key = $value;
              $rnd_str .= $value;
              break;
            case 'id_page_field':
              $this->$key = $value;
              $rnd_str .= $value;
              break;
            case 'id_module_tpl_field':
              $this->$key = $value;
              $rnd_str .= $value;
              break;
            case 'sort_field':
              $this->$key = $value;
              $rnd_str .= $value;
              break;
            case 'condition_field':
                foreach($value as $key_cond=>$value_cond){
                  $rnd_str .= $key_cond.$value_cond;
                }
              $this->$key = $value;
              break;
            case 'selection_field':
                foreach($value as $key_cond=>$value_cond){
                  $rnd_str .= $key_cond.$value_cond;
                }
              $this->$key = $value;
              break;
          }
        }
        $this->key_cache = md5($rnd_str);
        unset($rnd_str);
      }else{
        echo "не установлены необходимые параметры выборки страниц модуля \"pagesmod\"";
        exit();
      }

    }

    function clear_setting(){
      foreach ($this->set_arr as $key=>$value){
        if(isset($this->$value)){
          $this->$value = null;
        }
      }
    }


    function get_id_modules_tpl ($id_pages){
      if(!is_array($id_pages)){
        if (isset($this->cache[$this->table][$this->key_cache]['id_page'][$id_pages])){
          $id_mod[$id] = $this->cache[$this->table][$this->key_cache]['id_page'][$id];
          return $id_mod;
        }
        $id_pages = array($id_pages);
      }
      foreach($id_pages as $item){
        if (isset($this->cache[$this->table][$this->key_cache]['id_page'][$item])){
          $arr_id[$item] = $this->cache[$this->table][$this->key_cache]['id_page'][$item][$this->name_field];
        }else{
          $id_for_query[] = $item;
        }
      }
      if(isset($id_for_query) && is_array($id_for_query)){
        $this->db->select($this->table.'.'.$this->id_field.','.$this->table.'.'.$this->id_page_field.','.$this->table.'.'.$this->id_module_tpl_field.','.$this->table.'.'.$this->sort_field, FALSE);

        $this->db->from($this->table);
        $this->db->where_in($this->table.'.'.$this->id_page_field,$id_for_query);
        $this->_add_condition($this->table);
        $query = $this->db->get();

        $arr_query = $query->result_array();
        if(is_array($arr_query)){
          foreach($arr_query as $key=>$value){
            $arr_id[$value[$this->id_page_field]] = $value;
            $this->cache[$this->table][$this->key_cache]['id_pages'][$value[$this->id_page_field]] = $value;
          }
        }else{
          return false;
        }
      }
        if(isset($arr_id)){
          return $arr_id;
        }else{
          return false;
        }

    }

    function get_name_mod_content ($name_tpl, $arg = ''){
        if (isset($this->cache[$this->table][$this->key_cache]['mod_content'])){
          $id_mod = $this->cache[$this->table][$this->key_cache]['mod_content'];
          return $id_mod;
        }

        $this->db->select($this->table.'.'.$this->id_field.','.$this->table.'.'.$this->id_module_tpl_field.','.$this->table.'.'.$this->sort_field, FALSE);

        $this->db->from($this->table);
        $this->db->where_in($this->table.'.'.$this->id_page_field,$id_for_query);
        $this->_add_condition($this->table);
        $query = $this->db->get();

        $arr_query = $query->result_array();
        if(is_array($arr_query)){
          foreach($arr_query as $key=>$value){
            $arr_id[$value[$this->id_page_field]] = $value;
            $this->cache[$this->table][$this->key_cache]['mod_content'][$value[$this->id_page_field]] = $value;
          }
        }else{
          return false;
        }

        if(isset($arr_id)){
          return $arr_id;
        }else{
          return false;
        }
    }

    /*//////////////////////////////////////////////
| Работа с дополнительными параметрами
|
*///////////////////////////////////////////////

    // дополнительные условия к запросам
    function _add_condition($table, $add_join = false){
      if (isset($this->condition_field)){
        if(is_array($this->condition_field)){
          $k = 0;
          $this->condition_join = '';
          foreach($this->condition_field as $key=>$value){
            if($add_join == 'join'){
              if($k == 1){
                $this->condition_join .= ' AND ';
              }
              $this->condition_join .= '`'.$key.'` = `'.$value.'`';
              $k = 1;
            }else{
              if($table != $this->table){
                $this->db->where($table.'.'.$key, $value);
              }else{
                $this->db->where($table.'.'.$key, $value);
              }
            }
          }
        }
      }
    }

    // дополнительные условия SELECT
    function _add_select($table, $add_join = false){
      if(isset($this->selection_field)){
        if($table != $this->table){
          $table_field = $table;
        }else{
          $table_field = $this->table;
        }
        foreach($this->selection_field as $key=>$value){
          $this->db->select($table_field.'.'.$value);
        }
      }
    }

    // дополнительные условия к SET
    function _add_set ($table){
      if (isset($this->condition_field)){
        if(is_array($this->condition_field)){
          foreach($this->condition_field as $key=>$value){
             $this->db->set($key, $value);
          }
        }
      }
    }

}




