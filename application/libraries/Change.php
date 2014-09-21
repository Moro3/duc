<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Класс Change
 * Позволяет делать автоматическое сравнения данных поступающих из запросов пользователей
 * с данными хранящимися в БД
 *
 * Поддержка:
 * 1) мультиформ (пакетная обработка данных)
 * 2) мультитабличность (сложные запросы из нескольких таблиц)
 * 3) дополнительные условия для выборки
 * 4)


*/


class CI_Change {
  var $CI;
  var $_field_data			= array();
  var $_error_array			= array();
	var $_error_messages		= array();
	var $_error_prefix			= '<p>';
	var $_error_suffix			= '</p>';

  //var $_config_rules			= array();
  var $_id                = array();
  var $_table             = '';
  var $_field             = array();
  var $_data_change       = array();
  var $_data_db           = array();
  var $_data_post         = array();
  var $_data_change_old   = array();
  var $_form_name         = array();
  var $_form_type         = array();
  var $_id_name           = 'id';
  var $_multi             = false;
  var $_status             = array();



  function __construct($id = array(), $table = '', $field = array())
	{
    $this->clear();
    $this->CI =& get_instance();

		// Automatically load the form helper
		$this->CI->load->helper('form');

    // Automatically load the db library
		$this->CI->load->database();
    //$this->db = $this->CI->db;

    // Automatically load the library input
		//$this->CI->load->library('input'); // in core CI 2.0

    //$this->input = $this->CI->input;

    // Change rules can be stored in a config file.
    $this->config($id, $table, $field);

		// Set the character encoding in MB.
		if (function_exists('mb_internal_encoding'))
		{
			mb_internal_encoding($this->CI->config->item('charset'));
		}

		log_message('debug', "Change Class Initialized");
	}

  // загрузка конфигурациии для поиска изменений
  // arg: $id - значения полей идентификатора по которому будет сверка полей,
  //            может быть массивом если полей несколько
  //      $table - таблица по которой будут сверятся поля
  //      $field - поля таблицы которые будут подвергаться ОБЯЗАТЕЛЬНОЙ сверки,
  //                          если каких то полей нет, значит они не участвуют в сравнении
  //
  function config($id, $table, $field){
    $this->set_id($id);
    $this->set_table($table);
    $this->set_field($field);
  }

  // очистка параметров
  function clear(){
     $this->_id                = array();
     $this->_table             = '';
     $this->_field             = array();
     $this->_data_change       = array();
     $this->_data_db           = array();
     $this->_data_post         = array();
     $this->_data_change_old   = array();
     $this->_form_name         = array();
     $this->_form_type         = array();
     $this->_id_name           = 'id';
     $this->_multi             = false;
  }


  // установка значения полей идентификатора по которому будет сверка полей
  function set_id($id){
    if(!empty($id)){
      if(!is_array($id)){
        $id = array($id);
      }
      $this->_id = array_merge($this->_id, $id);
    }
    $this->_id = array_unique($this->_id);
  }

  // установка таблицы по которой будут сверятся поля
  function set_table($table){
    if(!empty($table)){
      $this->_table = $table;
    }
  }

  // установка поля таблицы которые будут подвергаться ОБЯЗАТЕЛЬНОЙ сверки,
  // если каких то полей нет, значит они не участвуют в сравнении
  function set_field($field){
    if(!empty($field)){
      if(!is_array($field)){
        $field = array($field);
      }
      $this->_field = array_merge($this->_field, $field);
    }
    $this->_field = array_unique($this->_field);
  }

  // соответствие названий полей в элементах формы и реальных полей в таблицах
  // если поля элементов форм и таблиц одинаковы этот метод можно не использовать
  // arg: $field_db - имя поля таблицы,
  //                  если передается массив, то второй аргумент не задействуется
  //                  $field_db = array('name_table' => 'name_form')
  //      $field_form - имя поля в форме
  function set_form_name ($field_db, $field_form = ''){
    if (!is_array($field_db)){
       $fields = array($field_db => $field_form);
    }else{
      $fields = $field_db;
    }
    $this->_form_name = array_merge($this->_form_name, $fields);
  }

  // установка значений типа поля и файл конфигурации для нештатного применения
  // arg: $field - имя поля в ФОРМЕ!!!
  //      $type - тип поля в форме
  //      $config - параметры поля
  function set_form_type($field, $type, $config){
    switch($type){
      case('checkbox'):
        $this->_form_type[$field] = array('type' => 'checkbox',
                                          'isset' => $config['isset'],
                                          'notset' => $config['notset'],
                                          );
        break;
      case('multiple'):
        $this->_form_type[$field] = array('type' => 'multiple',
                                          'isset' => $config['isset'],
                                          'notset' => $config['notset'],
                                          );
        break;
      case('image'):
        $this->_form_type[$field] = array('type' => 'image',
                                          'isset' => $config['isset'],
                                          'notset' => $config['notset'],
                                          );
        break;
      case('radio'):
        $this->_form_type[$field] = array('type' => 'radio',
                                          'isset' => $config['isset'],
                                          'notset' => $config['notset'],
                                          );
        break;
      default:
        break;
    }
  }

  // изменение названия идентификатора по которому будет производиться сравнение
  // по умолчанию 'id'
  function set_id_name ($name){
    if(!empty($name)){
      $this->_id_name = $name;
    }
  }
  // установка использование мульти формы
  // т.е. когда значения форм поступают в POST данные в виде массива в формате $field[$id]
  // где, $field - имя поля формы, $id - значение идентификатора по которому идет сверка
  // по умолчанию: FALSE (отключено)
  function set_multi (){
    $this->_multi = TRUE;
  }

  // запуск процесса сравнения
  function run($action = 'update'){
     if($action == 'update'){
        $this->_db_data();
        $this->_post_data();
        $this->_compare_data();
     }elseif($action == 'insert'){
       $this->_post_data();
       $this->_compare_insert();
     }

     /*
     echo "<br>_id: ";
     print_r($this->_id);
     echo "<br>_table: ";
     print_r($this->_table);
     echo "<br>_field: ";
     print_r($this->_field);
     echo "<br>_data_post: ";
     print_r($this->_data_post);
     echo "<br>_data_db: ";
     print_r($this->_data_db);
     echo "<br>_form_type: ";
     print_r($this->_form_type);
     */
  }

  // выборка из базы данных согласно фильтру
  function _db_data(){
    // проверяем на наличие поля id
    if(!is_array($this->_id) || count($this->_id) == 0){
      $this->set_message('error','Не установлен идентификатор при сравнении');
      echo 'Не установлен идентификатор при сравнении';
      return false;
    }
    foreach($this->_id as $items){
      // id не должно также быть равным 0 (идентификатор не должен быть нулевым)
      if(empty($items)){
        $this->set_message('error','Обнаружен нулевой идентификатор при сравнении');
        echo 'Обнаружен нулевой идентификатор при сравнении';
        return false;
      }
    }
    // проверяем установлена ли таблица
    if(empty($this->_table)){
      $this->set_message('error','Не установлена таблица при сравнении');
      echo 'Не установлена таблица при сравнении';
      return false;
    }
    // проверяем есть ли поля для сравнения
    if(is_array($this->_field) && count($this->_field) > 0){
      if(is_array($this->_field) && count($this->_field) > 1){
        $select = implode(",", $this->_field);
      }else{
        $s = array_values($this->_field);
        $select = $s[0];
      }
    }else{
      $this->set_message('error','Не установлены поля для сравнения');
      echo 'Не установлены поля для сравнения';
      return false;
    }
    $this->CI->db->select($select);
    if($this->_id_name != '') {
      $this->CI->db->where_in($this->_id_name, $this->_id);
    }
    $result = $this->CI->db->get($this->_table);
    foreach ($result->result_array() as $row)
    {
      $arr[$row[$this->_id_name]] = $row;
    }
    if(isset($arr)) $this->_data_db = $arr;
    return false;
  }

  // запись даных POST при условии действия режима 'insert'
  function _compare_insert(){
    if(is_array($this->_field)){
      foreach($this->_field as $field){
        foreach($this->_id as $id_field){
          // роверяем есть ли данное поле в переданных данных
          // проверяем не равно ли false в данных базы (именно false, т.к. NULL может быть)
          if(isset($this->_data_post[$id_field][$field])){
            $arr_change[$id_field][$field] = $this->_data_post[$id_field][$field];
          }else{
            $this->set_message('error','Ошибка данных полученных из POST при сравнении');
            echo 'Ошибка данных полученных из POST при сравнении';
            return;
          }
        }
      }
    }
    if(isset($arr_change)) $this->_data_change = $arr_change;
  }


  // сравнение даных POST с данными из базы
  function _compare_data(){
    if(is_array($this->_field )){
      foreach($this->_field as $field){
        foreach($this->_id as $id_field){
          // роверяем есть ли данное поле в переданных данных
          // проверяем не равно ли false в данных базы (именно false, т.к. NULL может быть)
          if(isset($this->_data_post[$id_field][$field]) && $this->_data_db[$id_field][$field] !== false){
            if($this->_data_post[$id_field][$field] != $this->_data_db[$id_field][$field]){
              $arr_change[$id_field][$field] = $this->_data_post[$id_field][$field];
              $arr_change_old[$id_field][$field] = $this->_data_db[$id_field][$field];
            }
          }else{
            $this->set_message('error','Ошибка данных полученных из POST или БАЗЫ при сравнении');
            echo 'Ошибка данных полученных из POST или БАЗЫ при сравнении';
            return;
          }
        }
      }
    }
    if(isset($arr_change)) $this->_data_change = $arr_change;
    if(isset($arr_change_old)) $this->_data_change_old = $arr_change_old;
  }

  // получение даных POST согласно конфигурации
  function _post_data(){
    if(is_array($this->_field )){
      foreach($this->_field as $field){
        if(isset($this->_form_name[$field])){
          $field_form = $this->_form_name[$field];
        }else{
          $field_form = $field;
        }
        if(isset($this->_form_type[$field_form]['type'])){
          $field_type = $this->_form_type[$field_form]['type'];
        }else{
          $field_type = '';
        }
        if(count($this->_id) == 1 && $this->_multi == false) {
          $id = array_values($this->_id);
          $id_field = $id[0];

          $post_field = $this->_input_post($field_form,$field_type);
          if($post_field !== false){
            $arr_post[$id_field][$field] = $post_field;
          }else{
            $this->set_message('error', 'ошибка получения данных POST при сравнении');
            echo "ошибка получения данных POST при сравнении<br />";
            return;
          }

        }elseif(count($this->_id) >= 1 && $this->_multi != false){

          foreach($this->_id as $id_field){
            if($this->_input_post($field_form,$field_type,$id_field) !== false){
              $arr_post[$id_field][$field] = $this->_input_post($field_form,$field_type,$id_field);
            }else{
              $this->set_message('error', 'ошибка получения данных POST при сравнении');
              echo "ошибка получения данных POST при сравнении<br />";
              return;
            }
          }
        }elseif(count($this->_id) > 1 && $this->_multi != false){
          $this->set_message('error', 'не включен режим проверки мультиформ');
          echo "не включен режим проверки мультиформ";
          return;
        }elseif(count($this->_id) == 0 && $this->_multi === false){
          $arr_post[1][$field] = $this->_input_post($field_form,$field_type);
        }
      }
    }
    if(isset($arr_post)) $this->_data_post = $arr_post;

  }

  // возвращает данные POST в отфильтрованном виде
  // arg: $post - поле из post
  //      $type - параметры типа формы
  //      $id - значение идентификатора (ключа формы) для мультиформ
  function _input_post($post, $type = '',$id = ''){
    $f_post = $this->CI->input->post($post);
    //print_r($f_post)."<br>";
    if(is_array($f_post)){
      if(!empty($id) && isset($f_post[$id])){
        $d_post = $f_post[$id];
      }else{
        $d_post = false;
      }
    }else{
      $d_post = $f_post;
    }
    switch ($type){
      case 'checkbox':
        if($d_post){
          if(isset($this->_form_type[$post]['isset'])){
            $res = $this->_form_type[$post]['isset'];
          }else{
            $res = $d_post;
          }
        }else{
          if(isset($this->_form_type[$post]['notset'])){
            $res = $this->_form_type[$post]['notset'];
          }else{
            $res = $d_post;
          }
        }
        break;
      case 'radio':
        if($d_post){
          if($d_post == $id){
            if(isset($this->_form_type[$post]['isset'])){
              $res = $this->_form_type[$post]['isset'];
            }else{
              $res = $d_post;
            }
          }else{
            if(isset($this->_form_type[$post]['notset'])){
              $res = $this->_form_type[$post]['notset'];
            }else{
              $res = '';
            }
          }
        }else{
          $res = false;
        }
        break;
      default:
        $res = $d_post;
        break;
    }

    //$post_d = ($this->CI->input->post());
    //print_r($post_d);
    //print_r($this->CI->input->post_array());
    //print_r($_POST);
    if($res === false){
      echo "НЕ ПРОШЕЛ $post<br>";
      log_message('error', "НЕ найден обязательный параметр POST  $post");
      $this->set_message('error',"НЕ найден обязательный параметр POST  $post");
    }

    if($res === false) return false;

    return $res;
  }


  //**************ПОЛУЧЕНИЕ ДАННЫХ*************//

  // получения массива измененных данных
  // ключи массива являются id значениями
  function change(){
    if(count($this->_data_change) > 0) {
      return $this->_data_change;
    }
    return false;
  }

  // получения массива данных которые подверглись изменениям
  // ключи массива являются id значениями
  // функция обратно противоположна change()
  function change_old(){
    if(count($this->_data_change_old) > 0) {
      return $this->_data_change_old;
    }
    return false;
  }

  // получения ко-ва измененных данных
  // return: numeric - кол-во изменившихся данных
  function count_change(){
    $i = 0;
    if(count($this->_data_change) > 0) {
      foreach($this->_data_change as $items){
        if(count($items) > 0){
          $i += count($items);
        }
      }
    }
    return $i;
  }

  // получения ко-ва данных подвергшихся изменению
  // return: numeric - кол-во данных подвергшихся изменению
  function count_change_old(){
    $i = 0;
    if(count($this->_data_change_old) > 0) {
      foreach($this->_data_change_old as $items){
        if(count($items) > 0){
          $i += count($items);
        }
      }
    }
    return $i;
  }

  // получения ко-во идентификаторов которые изменились
  // return: numeric - кол-во id
  function count_change_id(){
    if(count($this->_data_change) > 0) {
      return count($this->_data_change);
    }
    return 0;
  }
  // установка статуса изменения данных
  // регулируется вручную из контроллеров
  function set_status($action = 'not_change', $state = true){
    switch ($action){
      case'not_change':
        if($state === true){
          $this->_status[$action] = true;
        }
        break;
      case'update':
        if($state === true){
          $this->_status[$action] = true;
        }else{
          $this->_status[$action] = false;
        }
        break;
      case'insert':
        if($state === true){
          $this->_status[$action] = true;
        }else{
          $this->_status[$action] = false;
        }
        break;
      case'delete':
        if($state === true){
          $this->_status[$action] = true;
        }else{
          $this->_status[$action] = false;
        }
        break;
    }
  }

  // получения статуса
  function get_status(){
    if(count($this->_status) > 0){
      foreach ($this->_status as $key=>$value){
        $arr[$key] = $value;
      }
    }
    if(isset($arr)) return $arr;
    return false;
  }


  	// --------------------------------------------------------------------

	/**
	 * Set Error Message
	 *
	 * Lets users set their own error messages on the fly.  Note:  The key
	 * name has to match the  function name that it corresponds to.
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	function set_message($lang, $val = '')
	{
		if ( ! is_array($lang))
		{
			$lang = array($lang => $val);
		}

		$this->_error_messages = array_merge($this->_error_messages, $lang);
	}

  // --------------------------------------------------------------------

	/**
	 * Set The Error Delimiter
	 *
	 * Permits a prefix/suffix to be added to each error message
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	function set_error_delimiters($prefix = '<p>', $suffix = '</p>')
	{
		$this->_error_prefix = $prefix;
		$this->_error_suffix = $suffix;
	}

	// --------------------------------------------------------------------

	/**
	 * Get Error Message
	 *
	 * Gets the error message associated with a particular field
	 *
	 * @access	public
	 * @param	string	the field name
	 * @return	void
	 */
	function error($field = '', $prefix = '', $suffix = '')
	{
		if ( ! isset($this->_field_data[$field]['error']) OR $this->_field_data[$field]['error'] == '')
		{
			return '';
		}

		if ($prefix == '')
		{
			$prefix = $this->_error_prefix;
		}

		if ($suffix == '')
		{
			$suffix = $this->_error_suffix;
		}

		return $prefix.$this->_field_data[$field]['error'].$suffix;
	}

	// --------------------------------------------------------------------

	/**
	 * Error String
	 *
	 * Returns the error messages as a string, wrapped in the error delimiters
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	str
	 */
	function error_string($prefix = '', $suffix = '')
	{
		// No errrors, validation passes!
		if (count($this->_error_array) === 0)
		{
			return '';
		}

		if ($prefix == '')
		{
			$prefix = $this->_error_prefix;
		}

		if ($suffix == '')
		{
			$suffix = $this->_error_suffix;
		}

		// Generate the error string
		$str = '';
		foreach ($this->_error_array as $val)
		{
			if ($val != '')
			{
				$str .= $prefix.$val.$suffix."\n";
			}
		}

		return $str;
	}
}










