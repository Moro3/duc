<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/language_model.php
 *
 * Модель создания и управления страницами сайта
 */

class Language_model extends CI_Model
{
    private $condition_field;
    private $selection_field;
    private $key_cache;
    private $cache = array();
    private $tables = array();
    private $fields = array();

    function __construct() {

        parent::__construct();
        //$this->key_cache = '12345';
        // все параметры используемые в работе
        $this->tables['main'] = 'languages';
        $this->fields['main'] = array(
                           'id' => 'id',
                           'show' => 'show_i',
                           'name' => 'name',
                           'abbr' => 'abbr',
                           'img' => 'flag',
                           'img2' => 'arms',
                           'description' => 'description'
                           );
       $this->condition_field;
       $this->selection_field = array('date_create' => 'date_create',
                                      'date_update' => 'date_update'
                                     );
       $this->key_cache();
    }

    function key_cache(){
      $k = '';
      foreach($this->fields['main'] as $key=>$value){
        $k .= $value;
      }
      $k .= $this->tables['main'];

      $this->key_cache = md5($k);
    }

    //проверяет существование языка
    // @params: id - id языка
    // @return: boolean
    function is_lang($id){
      if (isset($this->cache[$this->key_cache]['info'][$id])){
        return true;
      }

        $this->_select();
        $this->_add_select($this->tables['main']);
        $this->db->from($this->tables['main']);
        $this->db->where_in($this->tables['main'].'.'.$this->fields['main']['id'],$id);
        $this->_add_condition($this->tables['main']);
        $query = $this->db->get();

        $arr_query = $query->result_array();
        if(is_array($arr_query) && count($arr_query) > 0){
          foreach($arr_query as $key=>$value){
            $this->cache[$this->key_cache]['info'][$value[$this->fields['main']['id']]] = $value;

          }
          return true;
        }
        return false;

    }

    // получение всей информации о языках
    // @params: id || array(id1,id2,..,id3) - id или массив id
    //          cache - кэширование информации (true - кэшировать, false - нет)
    // @return: array(key_id => array) - ключ - id таблицы, array - массив информации
    function get_info($id, $cache = true){
      if(!is_array($id)){
        if (isset($this->cache[$this->key_cache]['info'][$id])){
          $page[$id] = $this->get_array_order($this->cache[$this->key_cache]['info'][$id]);
          return $page;
        }
        $id = array($id);
      }
      foreach($id as $item){
        if (isset($this->cache[$this->key_cache]['info'][$item])){
          $arr_id[$item] = $this->cache[$this->key_cache]['info'][$item];
        }else{
          $id_for_query[] = $item;
        }
      }

      if(isset($id_for_query) && is_array($id_for_query)){
        $this->_select();
        $this->_add_select($this->tables['main']);
        $this->db->from($this->tables['main']);
        $this->db->where_in($this->tables['main'].'.'.$this->fields['main']['id'],$id_for_query);
        $this->_add_condition($this->tables['main']);
        $query = $this->db->get();

        $arr_query = $query->result_array();

        if(is_array($arr_query)){
          foreach($arr_query as $key=>$value){
            $arr_id[$value[$this->fields['main']['id']]] = $value;
            if($cache){
              $this->cache[$this->key_cache]['info'][$value[$this->fields['main']['id']]] = $value;
            }
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
    // получение всей информации о языках
    // @params:
    // @return: array(key_id => array) - ключ - id таблицы, array - массив информации
    function get_all_info(){

        $this->_select();
        $this->_add_select($this->tables['main']);
        $this->db->from($this->tables['main']);

        $this->_add_condition($this->tables['main']);
        $query = $this->db->get();

        $arr_query = $query->result_array();

        if(is_array($arr_query)){
          foreach($arr_query as $key=>$value){
            $arr_id[$value[$this->fields['main']['id']]] = $value;
            $this->cache[$this->key_cache]['info'][$value[$this->fields['main']['id']]] = $value;
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
    // получение поля arms и flag без кеширования
    // @params: id || array(id1,id2,..,id3) - id или массив id
    //
    // @return: array(key_id => array) - ключ - id таблицы, array - массив информации
    function get_img($id){
      if(!is_array($id)){
        $id = array($id);
      }

      if(is_array($id)){
        $this->db->select($this->fields['main']['img2'].", ".$this->fields['main']['img'].", ".$this->fields['main']['id']);
        $this->_add_select($this->tables['main']);
        $this->db->from($this->tables['main']);
        $this->db->where_in($this->tables['main'].'.'.$this->fields['main']['id'],$id);
        $this->_add_condition($this->tables['main']);
        $query = $this->db->get();

        $arr_query = $query->result_array();

        if(is_array($arr_query)){
          foreach($arr_query as $key=>$value){
            $arr_id[$value[$this->fields['main']['id']]] = $value;

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

    // получение поля id по аббревиатуре
    // @params: abbr - аббревиатура
    //
    // @return: array(key_id => array) - ключ - id таблицы, array - массив информации
    function get_id_on_abbr($abbr){

        $this->db->select($this->fields['main']['id']);
        $this->_add_select($this->tables['main']);
        $this->db->from($this->tables['main']);
        $this->db->where_in($this->tables['main'].'.'.$this->fields['main']['abbr'],$abbr);
        $this->_add_condition($this->tables['main']);
        $query = $this->db->get();

        $arr_query = $query->row_array();
        if(isset($arr_query)){
          if(isset($arr_query['id'])){
            return $arr_query['id'];
          }
        }
        return false;
    }

    // добавление языка
    // @params: $field - массив с параметрами
    //          $hidden_field - дополнительный массив с параметрами который не требует проверки
    // @return: boolean
    function insert_language($field, $hidden_field = false){
      if(is_array($field)){
        foreach($field as $value){
          if(is_array($value)){
            $array = $value;
            $this->db->set($array);
            if($hidden_field != false){
              if(is_array($hidden_field)){
                foreach($hidden_field as $key=>$value){
                  $this->db->set($key, $value);
                }
              }
            }
            if(!$this->db->insert($this->tables['main'])){
              return false;
            }
          }else{
            return false;
          }
        }
        return true;
      }
      return false;
    }

    // возвращает последний id добавленного
  function get_last_id(){
    return $this->db->insert_id();
  }
  // динамическое обновление
  function update_languages($table, $field, $hidden_field = false){
    if(is_array($field)){
      foreach($field as $key_id=>$value){
        if(is_array($value) && is_numeric($key_id)){
          $array = $value;
          $this->db->set($array);
          if($hidden_field != false){
            if(is_array($hidden_field)){
              foreach($hidden_field as $key=>$value){
                $this->db->set($key, $value);
              }
            }
          }

          $this->db->where_in('id', $key_id);
          if(!$this->db->update($table)){
            return false;
          }
        }else{
          return false;
        }
      }
      return true;
    }
    return false;
  }
/*//////////////////////////////////////////////
| Работа с дополнительными параметрами
|
*///////////////////////////////////////////////

    function _select(){
      $this->db->select($this->tables['main'].'.'.$this->fields['main']['id']);
      $this->db->select($this->tables['main'].'.'.$this->fields['main']['name']);
      $this->db->select($this->tables['main'].'.'.$this->fields['main']['abbr']);
      $this->db->select($this->tables['main'].'.'.$this->fields['main']['img']);
      $this->db->select($this->tables['main'].'.'.$this->fields['main']['img2']);
      $this->db->select($this->tables['main'].'.'.$this->fields['main']['description']);
    }

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
              if($table != $this->tables['main']){
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
        if($table != $this->tables['main']){
          $table_field = $table;
        }else{
          $table_field = $this->tables['main'];
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
             $this->db->set($table.'.'.$key, $value);
          }
        }
      }
    }

    // дополнительные условия к WHERE
    function _add_where ($table){
      if (isset($this->condition_field)){
        if(is_array($this->condition_field)){
          foreach($this->condition_field as $key=>$value){
             $this->db->where($table.'.'.$key, $value);
          }
        }
      }
    }


/*--------- конец работы с дополнительными параметрами ----------*/

    /*///////////////////////////////////////
| Методы для сортировки
|
*///////////////////////////////////////
   //  сортировка массива в случае заданного метода сортировки
    function get_array_order($array){
      if(isset($this->order_field)){
        if(isset($array[0][$this->order_field]) && isset($array[0][$this->id_field])) {
          if(is_object($this->order)){
            $this->order->set_field_order($this->order_field);
            $this->order->set_field_id($this->id_field);
            //print_r($this->order->get_array($array));
            return $this->order->get_array($array);
          }
        }
      }
      return $array;
    }




    //пользовательские функции
   function get_user_language(){
        $this->_select();
        $this->_add_select($this->tables['main']);
        $this->db->from($this->tables['main']);
        $this->db->where($this->tables['main'].'.'.$this->fields['main']['show'], 1);
        $this->_add_condition($this->tables['main']);
        $query = $this->db->get();

        $arr_query = $query->result_array();

        if(is_array($arr_query)){
          foreach($arr_query as $key=>$value){
            $arr_id[$value[$this->fields['main']['id']]] = $value;
            $this->cache[$this->key_cache]['info'][$value[$this->fields['main']['id']]] = $value;
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







