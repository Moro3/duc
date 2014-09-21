<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * File: models/Pages_model.php
 *
 * Модель создания и управления страницами сайта
 */

class Feedback_model extends CI_Model
{

    private $key_cache;
    private $cache = array();
    private $tables;
    private $fields;
    private $condition_field;
    private $selection_field;
    private $order;

    function __construct() {
        parent::__construct();
        // все параметры используемые в работе
        $this->tables['message'] = 'feedback_message';
        $this->fields['message'] = array(
                           'id' => 'id',
                           'id_theme' => 'id_theme',
                           'name' => 'name',
                           'email' => 'email',
                           'message' => 'message',
                           'date' => 'date',
                           'ip' => 'ip'
                           );
       $this->condition_field['message'] = array();

       $this->tables['theme'] = 'feedback_theme';
       $this->fields['theme'] = array(
                           'id' => 'id',
                           'name' => 'name',
                           'email' => 'email',
                           'user' => 'user_name',
                           'show' => 'show_i',
                           'sort' => 'sort_i',
                           );
       $this->condition_field['theme'] = array();
       $this->selection_field['theme'] = array('date_create' => 'date_create',
                                      'date_update' => 'date_update'
                                     );
       $this->key_cache();
       $this->order = $this->load->library('order_num');


    }

    function key_cache(){
      $k = '';
      foreach($this->fields as $key=>$value){
        $k .= $value;
      }
      foreach($this->tables as $key=>$value){
        $k .= $value;
      }
      $this->key_cache = md5($k);
    }

    //получение всех сообщений
    function get_all_message($limit = '', $order_by = 'email', $direct = 'desc'){

      $this->db->select($this->tables['message'].'.'.$this->fields['message']['id']);
      $this->db->select($this->tables['message'].'.'.$this->fields['message']['id_theme']);
      $this->db->select($this->tables['message'].'.'.$this->fields['message']['name']);
      $this->db->select($this->tables['message'].'.'.$this->fields['message']['email']);
      $this->db->select($this->tables['message'].'.'.$this->fields['message']['message']);
      $this->db->select($this->tables['message'].'.'.$this->fields['message']['date']);
      $this->db->select($this->tables['message'].'.'.$this->fields['message']['ip']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['name'].' as name_theme');

      $this->db->from($this->tables['message']);
      $this->db->from($this->tables['theme']);


      if(!empty($limit)){
        $limit_arr = explode(',',$limit);
        if(isset($limit_arr[0]) && is_numeric($limit_arr[0])){
          $limit_rows = $limit_arr[0];
        }else{
          $limit_rows = '';
        }
        if(isset($limit_arr[1]) && is_numeric($limit_arr[1])){
          $limit_offset = $limit_arr[1];
        }else{
          $limit_offset = '';
        }
        $this->db->limit($limit_rows, $limit_offset);
      }


        if(!in_array($order_by, $this->fields['message'])){
          $order_by = $this->fields['message']['id'];
        }
        switch($direct){
            case 'desc':
              $direct = 'desc';
              break;
            case 'asc':
              $direct = 'asc';
              break;
            case 'random':
              $direct = 'random';
              break;
            default:
              $direct = 'asc';
              break;
        }
        $this->db->order_by($order_by, $direct);

      $this->db->where($this->tables['message'].'.'.$this->fields['message']['id_theme'],$this->tables['theme'].'.'.$this->fields['theme']['id'], false);

      $query = $this->db->get();

        $arr_query = $query->result_array();

        if(is_array($arr_query)){
          foreach($arr_query as $key=>$value){
            $arr_id[$value[$this->fields['message']['id']]] = $value;
              $this->cache[$this->key_cache]['message'][$value[$this->fields['message']['id']]] = $value;
          }
        }
      if(isset($arr_id)) {
        return $this->get_array_order($arr_id, $order_by, $direct);
      }
      return false;

    }

    //получение ко-ва всех сообщений
    function get_count_message(){
      if (isset($this->cache[$this->key_cache]['count_message'])){
        return $this->cache[$this->key_cache]['count_message'];
      }
      $this->db->select('count(*) as count');
      $this->db->from($this->tables['message']);
      $query = $this->db->get();
      $result_arr = $query->result_array();
      if(isset($result_arr[0]['count'])){
        $this->cache[$this->key_cache]['count_message'] = $result_arr[0]['count'];
        return $result_arr[0]['count'];
      }
      return false;
    }
    // получение всей информации
    // @params: id || array(id1,id2,..,id3) - id или массив id
    //          cache - кэширование информации (true - кэшировать, false - нет)
    // @return: array(key_id => array) - ключ - id таблицы, array - массив информации
    function get_message($id, $cache = true){
      if(!is_array($id)){
        if (isset($this->cache[$this->key_cache]['message'][$id])){
          $page[$id] = $this->get_array_order($this->cache[$this->key_cache]['message'][$id]);
          return $page;
        }
        $id = array($id);
      }
      foreach($id as $item){
        if (isset($this->cache[$this->key_cache]['message'][$item])){
          $arr_id[$item] = $this->cache[$this->key_cache]['message'][$item];
        }else{
          $id_for_query[] = $item;
        }
      }

      if(isset($id_for_query) && is_array($id_for_query)){
        $this->db->select($this->tables['message'].'.'.$this->fields['message']['id']);
      $this->db->select($this->tables['message'].'.'.$this->fields['message']['id_theme']);
      $this->db->select($this->tables['message'].'.'.$this->fields['message']['name']);
      $this->db->select($this->tables['message'].'.'.$this->fields['message']['email']);
      $this->db->select($this->tables['message'].'.'.$this->fields['message']['message']);
      $this->db->select($this->tables['message'].'.'.$this->fields['message']['date']);
      $this->db->select($this->tables['message'].'.'.$this->fields['message']['ip']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['name'].' as name_theme');

      $this->db->from($this->tables['message']);
      $this->db->from($this->tables['theme']);

      $this->db->where($this->tables['message'].'.'.$this->fields['message']['id_theme'],$this->tables['theme'].'.'.$this->fields['theme']['id'], false);
      $this->db->where_in($this->tables['message'].'.'.$this->fields['message']['id'],$id);

      $query = $this->db->get();
        $arr_query = $query->result_array();

        if(is_array($arr_query)){
          foreach($arr_query as $key=>$value){
            $arr_id[$value[$this->fields['message']['id']]] = $value;
            if($cache){
              $this->cache[$this->key_cache]['message'][$value[$this->fields['message']['id']]] = $value;
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

    //получение ко-ва всех тем
    function get_count_theme(){
      if (isset($this->cache[$this->key_cache]['count_theme'])){
        return $this->cache[$this->key_cache]['count_theme'];
      }
      $this->db->select('count(*) as count');
      $this->db->from($this->tables['theme']);
      $query = $this->db->get();
      $result_arr = $query->result_array();
      if(isset($result_arr[0]['count'])){
        $this->cache[$this->key_cache]['count_theme'] = $result_arr[0]['count'];
        return $result_arr[0]['count'];
      }
      return false;
    }

    //получение всех тем
    function get_all_theme($limit = '', $order_by = 'id', $direct = 'desc'){

      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['id']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['name']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['email']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['user']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['show']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['sort']);

      if(!empty($limit)){
        $limit_arr = explode(',',$limit);
        if(isset($limit_arr[0]) && is_numeric($limit_arr[0])){
          $limit_rows = $limit_arr[0];
        }else{
          $limit_rows = '';
        }
        if(isset($limit_arr[1]) && is_numeric($limit_arr[1])){
          $limit_offset = $limit_arr[1];
        }else{
          $limit_offset = '';
        }
        $this->db->limit($limit_rows, $limit_offset);
      }


        if(!in_array($order_by, $this->fields['theme'])){
          $order_by = $this->fields['theme']['id'];
        }
        switch($direct){
            case 'desc':
              $direct = 'desc';
              break;
            case 'asc':
              $direct = 'asc';
              break;
            case 'random':
              $direct = 'random';
              break;
            default:
              $direct = 'asc';
              break;
        }
        $this->db->order_by($order_by, $direct);


      $this->db->from($this->tables['theme']);

      $query = $this->db->get();

        $arr_query = $query->result_array();

        if(is_array($arr_query)){
          foreach($arr_query as $key=>$value){
            $arr_id[$value[$this->fields['theme']['id']]] = $value;
              $this->cache[$this->key_cache]['theme'][$value[$this->fields['theme']['id']]] = $value;
          }
        }

      if(isset($arr_id)) {
        return $this->get_array_order($arr_id, $order_by, $direct);
      }
      return false;

    }

    // получение всей информации темы
    // @params: id || array(id1,id2,..,id3) - id или массив id
    //          cache - кэширование информации (true - кэшировать, false - нет)
    // @return: array(key_id => array) - ключ - id таблицы, array - массив информации
    function get_theme($id, $cache = true){
      if(!is_array($id)){
        if (isset($this->cache[$this->key_cache]['theme'][$id])){
          $page[$id] = $this->get_array_order($this->cache[$this->key_cache]['theme'][$id]);
          return $page;
        }
        $id = array($id);
      }
      foreach($id as $item){
        if (isset($this->cache[$this->key_cache]['theme'][$item])){
          $arr_id[$item] = $this->cache[$this->key_cache]['theme'][$item];
        }else{
          $id_for_query[] = $item;
        }
      }

      if(isset($id_for_query) && is_array($id_for_query)){
        $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['id']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['name']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['email']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['user']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['show']);
      $this->_add_select($this->tables['theme']);

      $this->db->from($this->tables['theme']);

      $this->db->where_in($this->tables['theme'].'.'.$this->fields['theme']['id'],$id);

      $query = $this->db->get();
        $arr_query = $query->result_array();

        if(is_array($arr_query)){
          foreach($arr_query as $key=>$value){
            $arr_id[$value[$this->fields['message']['id']]] = $value;
            if($cache){
              $this->cache[$this->key_cache]['message'][$value[$this->fields['message']['id']]] = $value;
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


    // дополнительные условия SELECT к теме
    function _add_select($table, $add_join = false){
      if(isset($this->selection_field['theme'])){
        if($table != $this->tables['theme']){
          $table_field = $table;
        }else{
          $table_field = $this->tables['theme'];
        }
        foreach($this->selection_field['theme'] as $key=>$value){
          $this->db->select($table_field.'.'.$value);
        }
      }
    }
    // добавление темы
    // @params: $field - массив с параметрами
    //          $hidden_field - дополнительный массив с параметрами который не требует проверки
    // @return: boolean
    function insert_theme($field, $hidden_field = false){
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
            if(!$this->db->insert($this->tables['theme'])){
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


    // динамическое обновление
  function update_theme($table, $field, $hidden_field = false){
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

    // возвращает последний id добавленного
  function get_last_id(){
    return $this->db->insert_id();
  }


  //получение всех тем для пользователя
    function get_all_theme_user(){

      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['id']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['name']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['email']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['user']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['show']);
      $this->db->select($this->tables['theme'].'.'.$this->fields['theme']['sort']);

      $this->db->where($this->tables['theme'].'.'.$this->fields['theme']['show'],1);

      $this->db->from($this->tables['theme']);

      $query = $this->db->get();

        $arr_query = $query->result_array();

        if(is_array($arr_query)){
          foreach($arr_query as $key=>$value){
            $arr_id[$value[$this->fields['theme']['id']]] = $value;
              $this->cache[$this->key_cache]['theme'][$value[$this->fields['theme']['id']]] = $value;
          }
        }

      if(isset($arr_id)) return $arr_id;
      return false;

    }

   // добавление сообщения
    // @params: $field - массив с параметрами
    //          $hidden_field - дополнительный массив с параметрами который не требует проверки
    // @return: boolean
    function insert_message($field, $hidden_field = false){
      print_r($field);
      if(is_array($field)){
         $this->db->set($field);
            if($hidden_field != false){
              if(is_array($hidden_field)){
                foreach($hidden_field as $key=>$value){
                  $this->db->set($key, $value);
                }
              }
            }
        if($this->db->insert($this->tables['message'])){
          return true;
        }
      }
      return false;
    }

   /*///////////////////////////////////////
| Методы для сортировки
|
*///////////////////////////////////////
   //  сортировка массива в случае заданного метода сортировки
    function get_array_order($array, $order_field = 'id', $direct = ''){
      //if(isset($this->order_field)){
        //if(isset($array[0][$this->order_field]) && isset($array[0][$this->id_field])) {
          if(is_object($this->order)){

            $this->order->set_field_order($order_field);
            if(!empty($direct)) $this->order->set_direct($direct);
            //$this->order->set_field_id($this->fields['message']['id']);
            //print_r($this->order->get_array($array));
            return $this->order->get_array($array);
          }
        //}
      //}
      return $array;
    }


}







