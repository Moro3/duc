<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scheme {

  private $modules;
  private $gp_modules;
  private $tables;
  private $fields;

  private $scheme = array(); // массивная схема с реальными ключами
  private $_scheme = array(); // массивная схема с псевдонимными ключами
  private $_params = array();
  private $_fields = array(); // имена всех полей в исходном виде
  private $_tables = array(); // имена всех таблиц в исходном виде
  private $_modules = array(); // имена всех модулей
  private $_tables_param = array(); // параметры таблиц
  private $_fields_param = array(); // параметры полей
  private $_set_params_table = array();
  private $_set_params_field = array();
  private $_set_sample_tables = array();
  private $_set_sample_fields = array();
  private $_type_convert = array('int' => array('int',
                                              'integer',
                                              'tinyint',
                                              'smallint',
                                              'mediumint',
                                              'begint',
                                              ),
                                 'real' => array('float',
                                                  'numeric',
                                                  'decimal',
                                                  'dec',
                                                  'double',
                                                  'real',
                                                  'double precision',
                                                  ),
                                 'string' => array('char',
                                                 'varchar',
                                                 'text',
                                                 'tinytext',
                                                 'mediumtext',
                                                 'longtext',
                                              ),
                                 'blob' => array('blob',
                                                 'tinyblob',
                                                 'mediumblob',
                                                 'longblob',
                                              ),
                                );


  private $_sample_tables= array();

  private $_foreign_key = array(); //

  private $_table_dependent = array(); // зависимые таблицы
                                       // array($table =array($key=>$value))
                                       // $table -  таблица от  которой зависят другие
                                       // $key - поле таблицы от которой зависят остальные
                                       // $value -
  private $msg = array(); // журнал сообщений


  function __construct(){
    $CI =& get_instance(); // to access CI resources, use $CI instead of $this
    //$CI->load->library('nested_set');
    $CI->load->database();
    $CI->load->dbforge();
    $this->db = $CI->db;
    $this->dbforge = $CI->dbforge;
    $CI->load->library('scheme_modules');
    $this->modules = $CI->scheme_modules;
    $CI->load->library('scheme_gp_modules');
    $this->gp_modules = $CI->scheme_gp_modules;
    $CI->load->library('scheme_tables');
    $this->tables = $CI->scheme_tables;
    $CI->load->library('scheme_fields');
    $this->fields = $CI->scheme_fields;

  }


  function get_name(){
    echo "<pre>";
    echo "<b>Схема (scheme):</b><br />";
    print_r ($this->_scheme);
    echo "<b>Таблицы:</b><br />";
    print_r ($this->_tables);
    echo "<b>Поля:</b><br />";
    print_r ($this->_fields);
    echo "<b>Модули:</b><br />";
    print_r ($this->_modules);
    echo "<b>Параметры Таблиц:</b><br />";
    print_r ($this->_params);
    echo "<b>Свойства Таблиц:</b><br />";
    print_r ($this->_sample_tables);
    echo "<b>Все Таблицы:</b><br />";
    print_r ($this->get_tables());
    echo "<b>Журнал сообщений:</b><br />";
    print_r ($this->msg);
    echo "</pre>";
  }

  function modules_gp($group){
    $modules_gp = $this->gp_modules->modules($group);
    if(is_array($modules_gp)){
      foreach($modules_gp as $key=>$value){
        $modules[] = $this->modules->params_keys($value);
      }
    }
    if(isset($modules)) return $modules;
    return false;
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
        if (isset($scheme_base)){
          if(count($this->scheme) > 0){
            $this->scheme = array_merge($this->scheme,$scheme_base);
          }else{
            $this->scheme = $scheme_base;
          }
        }
      }
    $this->build_scheme();
  }
  // загрузка массива
  function load_array($arr){
    if (isset($arr)){
      if(count($this->scheme) > 0){
        $this->scheme = array_merge($this->scheme,$arr);
      }else{
        $this->scheme = $arr;
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
    $this->_build_scheme_alias();
    $this->_build_tables();
    $this->_build_fields();
    $this->_build_modules();
    $this->_build_params();
  }

  // Построение схемы с псевдоназваниями таблиц и полей
  function _build_scheme_alias(){
    $scheme_new = array();
    if (count($this->scheme) > 0){
      foreach ($this->scheme as $table=>$table_params){
        if(!empty($table_params['name'])){
          $key_tb_scheme = array_keys($scheme_new);
          if(!in_array($table_params['name'],$key_tb_scheme)){
            if(isset($table_params['field']) && is_array($table_params['field'])){
              foreach($table_params['field'] as $field=>$field_params){
                if(isset($scheme_new[$table_params['name']]['field'])){
                  $key_fl_scheme = array_keys($scheme_new[$table_params['name']]['field']);
                }else{
                  $key_fl_scheme = array();
                }
                if(!in_array($field_params['name'],$key_fl_scheme)){
                  $scheme_new[$table_params['name']]['field'][$field_params['name']] = $field_params;

                }else{
                  $this->msg(__CLASS__,__METHOD__,4,"Имя поля \"{$field_params['name']}\" для таблицы \"$table\" уже имеется в схеме, выберите другое");
                  //return false;
                }
              }
            }else{
              $this->msg(__CLASS__,__METHOD__,4,"Для таблицы \"$table\" не задано ни одного поля");
              //return false;
            }

            foreach($table_params as $key_tb=>$value_tb){
              if ($key_tb != 'field'){
                $scheme_new[$table_params['name']][$key_tb] = $value_tb;
              }
            }

          }else{
            $this->msg(__CLASS__,__METHOD__,4,"Имя \"{$table_params['name']}\" для таблицы \"$table\" уже имеется в схеме, выберите другое");
            //return false;
          }
        }else{
          $this->msg(__CLASS__,__METHOD__,4,"Не задано имя для таблицы \"$table\"");
          //return false;
        }
      }
    }else{
      $this->msg(__CLASS__,__METHOD__,5,"Не обнаружена предсхема таблиц");
      //return false;
    }
    $this->_scheme = $scheme_new;
  }

  // Построение таблиц
  function _build_tables(){
    if ($this->check_scheme()){
      $this->_tables = array_keys($this->_scheme);
    }
  }
  /*
  // Построение модулей
  function _build_modules(){
    if ($this->check_scheme()){
      foreach($this->_scheme as $table=>$params){
        if(!empty($params['module']) && !in_array($params['module'], $this->_modules)){
          $this->_modules[] = $params['module'];
        }
      }

    }
  }
  */
  // Построение модулей
  function _build_modules(){
    if ($this->check_scheme()){
      foreach($this->_scheme as $table=>$params){
        if(!empty($params['module']) && !in_array($params['module'], $this->_modules)){
          $this->_modules[] = $params['module'];
        }
      }

    }
  }

  // Построение полей таблиц
  function _build_fields(){
    if ($this->check_scheme()){
      foreach($this->_scheme as $key=>$value){
        if(isset($value['field']) && is_array($value['field'])){
          $this->_fields[$key] = array_keys($value['field']);
        }
      }
    }
  }
  // проверка на существование схемы
  function check_scheme(){
    if (isset($this->_scheme) && is_array($this->_scheme)){
      return true;
    }else{
      $this->msg(__CLASS__,__METHOD__,5,"Не обнаружена схема таблиц");
      return false;
    }
  }

  // Построение выделенных полей таблицы (опционально)
  function _build_params(){
    if ($this->check_scheme()){
      if(count($this->_set_params_field) > 0){
        foreach($this->_scheme as $table=>$value){
          if(isset($value['field']) && is_array($value['field'])){
            foreach($value['field'] as $field=>$param){
              foreach($this->_set_params_field as $set_param){
                if(isset($param[$set_param]) && is_array($param[$set_param])){
                  $this->_params[$table.'.'.$field][$set_param] = $param[$set_param];
                }
              }
            }
          }
        }
      }
    }
  }
  // Построение выборки для таблиц
  function _build_samples(){
    if ($this->check_scheme()){
      if(count($this->_set_sample_tables) > 0){
        foreach($this->_scheme as $table=>$value){
          foreach($this->_set_sample_tables as $set_sample){
            if(array_key_exists($set_sample,$value)){
              //echo"$set_sample<br />";
              if(!isset($this->_sample_tables[$set_sample][$value[$set_sample]])){
                $arr[$value[$set_sample]][] = $table;
              }
            }
          }
        }
      }
    }
    $this->_sample_tables[$set_sample] = $arr;
  }


  /*
  //////////////////////////////////////////////////////////////////////////////////////
  /  Методы для получения свойств таблиц и полей
  //////////////////////////////////////////////////////////////////////////////////////
  /
  /
  */
  // возвращает свойства заданного поля в схеме
  // Аргументы: $table - имя таблицы, $field - имя поля
  // НЕ Возвращает массивы свойств, для этого используйте выделенные свойства полей
  function get_field_param($table, $field){
    if(!is_array($field)){
      $field = array($field);
    }
    foreach($field as $item){
      if(@in_array($item, $this->_fields[$table])){
        foreach($this->_scheme[$table]['field'][$item] as $key=>$value){
          if (!is_array($value)){
            $arr[$item][$key] = $value;
          }
        }
      }
    }
    if (isset($arr)){
      return $arr;
    }else{
      return false;
    }
  }

  // возвращает свойства поля заданной таблицы  в схеме
  // Аргументы: $table - имя таблицы
  // НЕ Возвращает массивы свойств, для этого используйте выделенные свойства полей
  function get_fields_param($table){
    $fields = $this->get_fl_table($table);
    if(is_array($fields)){
      $params = $this->get_field_param($table, $fields);
      return $params;
    }
    return false;
  }

  // возвращает свойства заданной таблицы в схеме
  // Аргументы: $table - имя таблицы
  // return: array - все параметры таблицы (ключ = имя параметра, значение = значение параметра)
  //         false - если таблица не найдена
  // НЕ Возвращает массивы свойств, для этого используйте выделенные свойства таблиц
  function get_table_param($table){
    if(@in_array($table, $this->_tables)){
      foreach($this->_scheme[$table] as $key=>$value){
        if (!is_array($value)){
          $arr[$key] = $value;
        }
      }
      if (isset($arr)){
        return $arr;
      }else{
        $this->msg(__CLASS__,__METHOD__,1,"У таблицы \"$table\" не найден ни один параметр");
        return false;
      }
    }else{
      $this->msg(__CLASS__,__METHOD__,1,"Таблица \"$table\" не найдена в структуре схемы");
      return false;
    }
  }

  // возвращает все таблицы
  function get_tables(){
    if(count($this->_tables) > 0){
      return $this->_tables;
    }else{
      $this->msg(__CLASS__,__METHOD__,1,"В структуре схемы не найдено ни одной таблицы");
      return false;
    }
  }

  // возвращает список всех модулей
  // arg: ()
  // return: array - имена модулей
  function get_modules(){
    if(count($this->_modules) > 0){
      return $this->_modules;
    }else{
      $this->msg(__CLASS__,__METHOD__,1,"В структуре схемы не найдено ни одного модуля");
      return false;
    }
  }

  // проверка на существование модуля
  // arg: $module - имя модуля
  // return: boolean - true = найден,
  //                   false = не найден
  function check_module($module){
    if(count($this->_modules) > 0){
      if(in_array($module, $this->_modules)){
        return true;
      }else{
        $this->msg(__CLASS__,__METHOD__,5,"Модуль \"$module\" не найден в структуре схемы");
        return false;
      }
    }else{
      $this->msg(__CLASS__,__METHOD__,1,"В структуре схемы не найдено ни одного модуля");
      return false;
    }
  }

  // проверка на существование таблицы
  // arg: $table - имя таблицы
  // return: boolean - true = найден,
  //                   false = не найден
  function check_table($table){
    if(count($this->_tables) > 0){
      if(in_array($table, $this->_tables)){
        return true;
      }else{
        $this->msg(__CLASS__,__METHOD__,5,"Таблица \"$table\" не найдена в структуре схемы");
        return false;
      }
    }else{
      $this->msg(__CLASS__,__METHOD__,1,"В структуре схемы не найдено ни одной таблицы");
      return false;
    }
  }

  // возвращает список таблиц модуля
  // arg: $module - модуль
  // return: array - имена таблиц
  function get_tb_module($module){
    if(count($this->_modules) > 0){
      if($this->check_module($module)){
        foreach ($this->_scheme as $items_sch){
            if($items_sch['module'] == $module && !empty($items_sch['name'])){
              $tables[] = $items_sch['name'];
            }
        }

      }else{
        $this->msg(__CLASS__,__METHOD__,1,"Модуль \"$module\" не найден в структуре схемы");
        return false;
      }
    }else{
      $this->msg(__CLASS__,__METHOD__,1,"В структуре схемы не найдено ни одного модуля");
      return false;
    }

    if(isset($tables)) return $tables;
    $this->msg(__CLASS__,__METHOD__,1,"В структуре схемы не найдено таблиц соответствующих модулю \"$module\"");
    return false;
  }

  // возвращает список полей таблицы
  // arg: $table - таблица
  // return: array - имена полей
  function get_fl_table($table){
    if(count($this->_tables) > 0){
      if($this->check_table($table)){
        foreach ($this->_scheme as $items_sch){
            if($items_sch['name'] == $table && !empty($items_sch['field'])){
              foreach($items_sch['field'] as $items_fl){
                if(!empty($items_fl['name'])){
                  $fields[] = $items_fl['name'];
                }
              }

            }
        }

      }else{
        $this->msg(__CLASS__,__METHOD__,1,"Таблица \"$table\" не найден в структуре схемы");
        return false;
      }
    }else{
      $this->msg(__CLASS__,__METHOD__,1,"В структуре схемы не найдено ни одной таблицы");
      return false;
    }

    if(isset($fields)) return $fields;
    $this->msg(__CLASS__,__METHOD__,1,"В структуре схемы не найдено таблиц соответствующих модулю \"$table\"");
    return false;
  }

  // возвращает кол-во полей в таблицы
  // arg: $table - таблица
  // return: numeric - кол-во  полей в таблице
  //         false - если таблицы нет
  function count_fl_table($table){
    if($this->check_table($table)){
      $max_field = count($this->get_fl_table($table));
      return $max_field;
    }
    return false;
  }

  // возвращает кол-во таблиц в модуле
  // arg: $module - модуль
  // return: numeric - кол-во таблиц в модуле
  //         false - если модуля нет
  function count_tb_module($module){
    if($this->check_module($module)){
      $max_table = count($this->get_tb_module($module));
      return $max_table;
    }
    return false;
  }

  // возвращает кол-во модулей
  // arg:
  // return: numeric - кол-во модулей в системе
  //
  function count_modules(){
    $max_modules = count($this->_modules);
    if($max_modules >= 0){
      return $max_modules;
    }
    return false;
  }

  // возвращает кол-во таблиц в системе
  // arg:
  // return: numeric - кол-во таблиц в системе
  //
  function count_tables(){
    if($this->count_modules() > 0){
      $max_tables = 0;
      foreach($this->_modules as $items){
        //echo "$items<br>";
        $max_tables += $this->count_tb_module($items);
      }
    }
    return $max_tables;
  }

  // зависимые таблицы
  //
  function dependent_tb(){

  }

  // таблицы от которых зависят другие
  function tb_dependent(){

  }

  /*
  //////////////////////////////////////////////////////////////////////////////////////
  /  Манипуляция данных с базой данных
  //////////////////////////////////////////////////////////////////////////////////////
  /
  /
  */
  // инсталяция таблицы
  // Аргументы: $table - имя таблицы
  //            $overcreate - удаление таблицы если она есть и она пустая
  //            $overdata - удаление таблицы с данными если они есть
  function install_table($table, $overcreate = FALSE, $overdata = FALSE){
    if (@in_array($table, $this->_tables)){

    }
  }

  // проверка на установленную таблицу
  // arg: $table - имя таблицы
  // return: boolean - true - таблица установлена
  //                   false - таблица не установлена
  function check_install_tb ($table){
    if($this->db->table_exists($table)){
      return true;
    }
    return false;
  }

  // проверка таблицы на содержание данных
  // arg: $table - имя таблицы
  // return: boolean - true - таблица содержит данные
  //                   false - таблица НЕ содержит данные
  function check_data_tb ($table){
    if($this->check_install_tb ($table)){
      if($this->db->count_all($table) > 0){
        return true;
      }
    }
    return false;
  }

  // проверка на установленный модуль
  // arg: $module - имя модуля
  // return: boolean - true - модуль установлен
  //                   false - модуль не установлен
  function check_install_md ($module){
    if($this->check_module($module)){
      $tables = $this->get_tb_module($module);
      foreach($tables as $item){
        if(!$this->check_install_tb ($item)){
          return false;
        }
      }
      return true;
    }
    return false;
  }

  // проверка на соответствие имен полей в установленной таблице
  // arg: $table - имя таблицы
  // return: boolean - true - поля таблицы соответствуют схеме
  //                   false - поля таблицы НЕ соответствуют схеме
  function check_field_tb ($table){
     if(!$this->absent_field_tb($table) && !$this->excess_field_tb($table)) {
       return true;
     }
     return false;
  }

  // недостающие поля в установленной таблице в сравнение со схемой
  // arg: $table - имя таблицы
  // return: array - массив полей отсутствующих в таблице
  //         false - недостающие поля отсутствуют
  function absent_field_tb ($table){
    $field_scheme = $this->get_fl_table($table);
    $field_db = $this->list_fields_db ($table);
    $field_diff = array_diff($field_scheme, $field_db);
    if(count($field_diff) > 0){
      sort($field_diff);
      return $field_diff;
    }
    return false;
  }

  // излишние поля в установленной таблице в сравнение со схемой
  // arg: $table - имя таблицы
  // return: array - массив излишних полей в таблице
  //         false - излишние поля отсутствуют
  function excess_field_tb ($table){
    $field_scheme = $this->get_fl_table($table);
    $field_db = $this->list_fields_db ($table);
    $field_diff = array_diff($field_db, $field_scheme);
    if(count($field_diff) > 0){
      sort($field_diff);
      return $field_diff;
    }
    return false;
  }

  // возвращает список имен полей в установленной таблице
  // arg: $table - имя таблицы
  // return: array - массив имен полей таблицы
  //
  function list_fields_db ($table){
    if($this->check_install_tb ($table)){
      $fields = $this->db->list_fields($table);
      return $fields;
    }
    return false;
  }

  // проверка на существование поля в таблице
  // arg: $table - имя таблицы
  //      $field - имя поля ()
  // return: boolean - true - поля таблицы соответствуют схеме
  //                   false - поля таблицы НЕ соответствуют схеме
  function check_nstall_fl ($table, $field){
    if($this->check_install_tb ($table)){
      if($this->db->field_exists($field,$table)){
        return true;
      }
    }
    return false;
  }

  // возвращает массив с информацией о полях таблицы
  // arg: $table - имя таблицы
  // return: array - массив с информацией о полях
  function field_data($table){
    if($this->check_install_tb ($table)){
      $fields = $this->db->field_data($table);
      foreach ($fields as $field){
        $fields_arr[$field->name]['name'] = $field->name;
        $fields_arr[$field->name]['type'] = $field->type;
        $fields_arr[$field->name]['long'] = $field->max_length;
        $fields_arr[$field->name]['primary key'] = $field->primary_key;
        $fields_arr[$field->name]['default'] = $field->default;
      }
      if(isset($fields_arr)){
        return $fields_arr;
      }
    }
    return false;
  }

  // проверка на соответствие св-в поля в таблице
  // arg: $table - имя таблицы
  //      $field - имя поля
  // return: boolean - true - св-ва поля таблицы соответствуют схеме
  //                   false - св-ва поля таблицы НЕ соответствуют схеме
  function check_params_fl ($table, $field){
    $field_db = $this->field_data($table);
    $field_scheme = $this->get_fields_param($table);

    $field_db = $this->get_field_type_compare($field_db);
    $field_scheme = $this->get_field_type_compare($field_scheme);
    $field_diff = array_diff($field_scheme[$field], $field_db[$field]);
    if(count($field_diff) > 0){
      sort($field_diff);
      return $field_diff;
    }
    echo "Сокращенные поля<pre>";
    print_r($field_db);
    print_r($field_scheme);
    echo "</pre>";
  }

  function get_field_type_compare ($array){
    foreach($array as $key=>$value){
      if(!in_array($value['type'],array_keys($this->_type_convert))){
        foreach($this->_type_convert as $key_type=>$value_type){
          if(in_array($value['type'],$value_type)){
            $arr[$key]['type'] = $key_type;
          }
        }
      }else{
        $arr[$key]['type'] = $value['type'];
      }
      $arr[$key]['name'] = $value['name'];
      $arr[$key]['long'] = $value['long'];
      //$arr[$key]['primary key'] = $value['primary key'];
      $arr[$key]['default'] = $value['default'];
    }

    return $arr;
  }


  /******************* Конец ****************************************/

  /*
  //////////////////////////////////////////////////////////////////////////////////////
  /  Манипуляция с данными схемы
  //////////////////////////////////////////////////////////////////////////////////////
  /
  */

  // Установка имен свойств для полей
  // Применение: для более точного управления свойствами поля
  // Причина: поле содержит много разнородных параметров
  // аргументы: 1 - имя параметра в поле по которому вы хотите записать соответствия
  function set_param_field($name){
    if(!is_array($name)){
      $name = array($name);
    }
    $this->_set_params_field += $name;
    /*
    foreach
    if(!in_array($name_field,$this->_set_params_field)){
      $this->_set_params_field[] = $name_field;
    }
    */
    $this->_build_params();
  }

  // Установка имен свойств для таблиц
  // Применение: для более точного управления свойствами таблиц
  // Причина: таблица содержит много разнородных параметров
  // аргументы: 1 - имя параметра таблицы по которому вы хотите записать соответствия
  function set_param_table($name){
    if(!is_array($name)){
      $name = array($name);
    }
    $this->_set_params_table += $name;
    /*
    foreach
    if(!in_array($name_field,$this->_set_params_field)){
      $this->_set_params_field[] = $name_field;
    }
    */
    $this->_build_params();
  }

  // Установка имен свойств таблиц для выборки
  // Применение: производит выблорку всех таблиц по указанному свойству
  // Причина: позволяет объеденить множество таблиц для работы в одной связке
  // аргументы: 1 - имя параметра таблицы по которому вы хотите записать соответствия
  function set_sample_tables($name){
    if(!is_array($name)){
      $name = array($name);
    }
    $this->_set_sample_tables = array_merge($this->_set_sample_tables, $name);
    /*
    foreach
    if(!in_array($name_field,$this->_set_params_field)){
      $this->_set_params_field[] = $name_field;
    }
    */
    $this->_build_samples();
  }

  /***** Конец ****************************************/


  // Журнал сообщений
  // arg: $class - имя класс
  //      $method - имя метода откуда было вызвано сообщение
  //      $level - уровень сообщения (1-5) 1- легкий, 5- критический
  //      $msg - сообщение
  function msg($class,$method,$level,$msg){
    $this->msg[] = array('class' => $class,
                         'method' => $method,
                         'level'=> $level,
                         'msg' => $msg,
                         'time' => time(),
                         'file'=> __FILE__,
                         );
  }
}















