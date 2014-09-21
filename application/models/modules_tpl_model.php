<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Modules_tpl_model extends CI_Model {

  private $key_cache;
  private $setting;
	function __construct(){
	   parent::__construct();
	   $this->set_param();
	}

	function params(){
    return array('table' => 'modules_tpl',
                   'id' => 'id',
                   'id_type' => 'id_type',
                   'name' => 'name',
                   'description' => 'description',
                   'module' => 'module',
                   'controller' => 'controller',
                   'method' => 'method',
                   'arg' => 'arg',
                   'tpl' => 'name_tpl',
                  );
	}

	function set_param(){
    $rnd_str = '';
    foreach($this->params() as $key=>$value){
	    $this->$key = $value;
	    $rnd_str .= $value;
	  }
	  $this->key_cache = md5($rnd_str);

	}

	function get_modules($id){
	  if( isset($this->setting[$this->key_cache]['modules'][$id])) {
      return $this->setting[$this->key_cache]['modules'][$id];
	  }
	  $this->_select($this->table);
    $this->db->from($this->table);
    $this->db->where($this->table.'.'.$this->id, $id);
    $this->db->where($this->table.'.'.$this->id_type, 1);
    $query = $this->db->get();
    $row = $query->row_array(0);
    $this->setting[$this->key_cache]['modules'][$id] = $row;
    return $row;
	}


	// условия SELECT
    function _select($table){
      $this->db->select($table.'.'.$this->id);
      $this->db->select($table.'.'.$this->id_type);
      $this->db->select($table.'.'.$this->name);
      $this->db->select($table.'.'.$this->description);
      $this->db->select($table.'.'.$this->module);
      $this->db->select($table.'.'.$this->controller);
      $this->db->select($table.'.'.$this->method);
      $this->db->select($table.'.'.$this->arg);
      $this->db->select($table.'.'.$this->tpl);
    }

	function get_modules_content ($name_tpl, $arg = ''){
	  if( isset($this->setting[$this->key_cache]['modules'])) {
      foreach($this->setting[$this->key_cache]['modules'] as $key=>$value){
        if($value[$this->tpl] == $name_tpl && $value[$this->arg] == $arg && $value[$this->id_type] == 2){
          return $this->setting[$this->key_cache]['modules'][$key];
        }
      }
	  }
	  $this->_select($this->table);
    $this->db->from($this->table);
    $this->db->where($this->table.'.'.$this->tpl, $name_tpl);
    $this->db->where($this->table.'.'.$this->arg, $arg);
    $this->db->where($this->table.'.'.$this->id_type, 2);
    $query = $this->db->get();
    $row = $query->row_array(0);
    if(isset($row[$this->id])){
      $this->setting[$this->key_cache]['modules'][$row[$this->id]] = $row;
    }
    return $row;
	}

}





