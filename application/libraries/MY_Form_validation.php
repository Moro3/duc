<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation
{
    function run($module = '', $group = '') {
        (is_object($module)) AND $this->CI =& $module;
        return parent::run($group);
    }

   // --------------------------------------------------------------------

	/**
	 * Alpha and cirilic
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function alpha_ru($str)
	{
		return ( ! preg_match('/^[A-яёЁA-z]+$/iu', $str)) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Alpha-numeric and cirilic
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function alpha_numeric_ru($str)
	{
		return ( ! preg_match("/^([A-z0-9А-яёЁ])+$/iu", $str)) ? FALSE : TRUE;
	}

	/**
	 * Alpha-numeric and space
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function alpha_space($str)
	{
		return ( ! preg_match("/^([A-z0-9 ])+$/iu", $str)) ? FALSE : TRUE;
	}

  /**
   * Alpha-numeric and space
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function alpha_space_ru($str)
  {
    return ( ! preg_match("/^([A-z0-9А-яёЁ ])+$/iu", $str)) ? FALSE : TRUE;
  }

	/**
	 * Alpha-maximum
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function alpha_max($str)
	{
		//echo "<br>Слово:";
		//var_dump($str);
		//echo "<br>";
		//exit();
		return ( ! preg_match("/^([A-z0-9А-яёЁ -=—+–:;!?@#%^«»(),.\r\n])+$/iu", $str)) ? FALSE : TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Alpha-numeric and cirilic with underscores and dashes
	 *
	 * @access	public
	 * @param	string
	 * @return	bool
	 */
	function alpha_dash_ru($str)
	{
		return ( ! preg_match("/^([-A-z0-9А-яёЁ_-])+$/iu", $str)) ? FALSE : TRUE;
	}

  /**
   * uri
   *
   * @access  public
   * @param string
   * @return  bool
   */
  function uri($str)
  {
    return ( ! preg_match("%^([A-z0-9/-])+$%iu", $str)) ? FALSE : TRUE;
  }

   // Проверяем на валидность при редактирование
   // arg: $fields - массив полей форм которые будут обязательно присутствовать в проверке
   //      $config - массив правил, кол-во и имена полей формы должны соответствовать полям $fields
   //      $multi_id - имя поля по которому будут разделены значения форм при мульти формы
   //                  обычно это идентификатор 'id', по умолчанию мультиформа не используется
   //      МУЛЬТИФОРМА подразумевает под собой что данные в POST удут поступать в виде массива
  function valid_control($fields, $config, $multi_id = false){
      // смотрим какая форма (мудьти или простая)
      if($multi_id !== false){
        // если мульти  запрашиваем массив идентификаторов посланых через POST
        $arr_id = $this->get_arrid_input($multi_id);
      }else{
        // если обычная, то ставим массив идентификаторов в false
        //$arr_id = $this->get_arrid_input();
        $arr_id = false;
      }

      // проходим списком по всем заданным полям формы
      foreach($fields as $field){
         // выбираем обработку в зависимости есть мультиформа или нет
         if($multi_id === false){
          // ставим указатель котроля правил
          $ctrl_rule = false;
          // проверяем массив с правилами
          foreach($config as $value){
            if(isset($value['field']) && $value['field'] == $field){
              $rule[] = $value;
              $ctrl_rule = true;
            }
          }
          // если для какого то поля не загружены правила валидации сообщаем это
          if($ctrl_rule === false) {
            // Save the error message
            $message = 'Не загружены правила валидации в форме для имени "'.htmlspecialchars($field).'"';
    	       if ( ! isset($this->_error_array[$field]))
            {
		    	    	$this->_error_array[$field] = $message;
            }
            //$this->set_message('system', 'Не загружены правила валидации для формы %s');
            return false;
          }
        }else{
          // в случае мультиформ
          if(is_array($arr_id)){
            foreach($arr_id as $id){
              $ctrl_rule = false;
              foreach($config as $value){
               // изменяем имя формы если оно обрабатывается в виде мультиформ
               // добавляем к концу имени массивный ключ со значением идентификатора
               if(isset($value['field']) && $value['field'] == $field){
                 $rule[] = array(
                            'field' => $field."[$id]",
                            'label' => $value['label'],
                            'rules' => $value['rules']
                            );
                 $ctrl_rule = true;

               }
              }
              // если для какого то поля не загружены правила валидации сообщаем это и возвращаем false
              if($ctrl_rule === false) {
                // Save the error message
                $message = 'Не загружены правила валидации в форме для имени "'.htmlspecialchars($field).'"';
    	          if ( ! isset($this->_error_array[$field]))
                {
		    	    	  $this->_error_array[$field] = $message;
                }
                //$this->set_message('system', 'Не загружены правила валидации для формы');
                return false;
              }
            }
          }else{
            // завершаем если мультиформа указана, а индентификатора нет или не в том формате
            // Save the error message
            $message = 'Мультиформа не соответствует запросу';
		    		if ( ! isset($this->_error_array[$field]))
		    		{
		    			$this->_error_array[$field] = $message;
		    		}
            //$this->set_message('system', 'Мультиформа не соответствует запросу');
            return false;
          }
        }
      }

      if(isset($rule)){
        //print_r($rule);
        $this->set_rules($rule);
      }
  }
  // вызывается только в тех случаях когда идет работа с мульти формой
  // возвращает массив значений идентификационного поля при запросе мульти формы
  // обычно это поле 'id', если другое укажите первым аргументом
  // имя поля указывается, то которое идет в форме!!! НЕ в таблице
  function get_arrid_input ($field = 'id'){
    $post_field = $this->CI->input->post($field);
    if($post_field == false){
      return false;
    }else{
      if(is_array($post_field)){
        $arr_id = array_keys($post_field);
      }else{
        return false;
      }
    }
    return $arr_id;
  }

  // возвращает данные из POST ввиде массива,
  // ключи массива являются идентитфикаторами если был задана мультиформа
  // значение массива тоже массив, где ключи - это оригинальными именами полей базы
  //                                значения - это параметры из POST
  // arg: $fields - массив полей формы, если названия полей в базе не совпадают с названиями в полях формы
  //                                    то ключами нужно поставить названия полей в базе
  //      $multi_id - мультиформа
  //                  имя поля формы по которому будет произведена выборка мультиформы
  //                  по умолчанию мультиформа не используется
  //
  function valid_data_db($fields, $multi_id = false){
    if(is_array($fields)){
      foreach($fields as $name_db=>$name_form){

        $arr[$name_db] = $this->CI->input->post($name_form);
      }
    }

  }

}
/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */


