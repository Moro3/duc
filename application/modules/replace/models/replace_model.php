<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Replace_model.php
 *
 *
 */

class Replace_model extends MY_Model
{

    private $key_cache;
    private $cache = array();

    function __construct() {
        parent::__construct();
        $this->MY_table = 'replace_mod';

		$this->MY_primary_key = 'id';
		// дополнительные допустимые таблицы модели
		// key - псевдоним таблицы в случае сложных запросов
		$this->MY_table_access = array(
			'main' => 'replace_mod',
		);

		$this->MY_has_many = array(
		    'content' => array(
		    	'module' => 'pages',
		    	'controller' => 'pages_contents',
		    	'model' => 'pages_contents',
		    	'foreign_key' => 'id_page_header',
		    ),
		);
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



}




