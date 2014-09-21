<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Feedback extends MX_Controller {
  protected $index_request = array();
  protected $path;
  protected $img;
  protected $get;
  protected $setting;
  protected $lang_default;
  protected $lang_current;

  function __construct()
	{
    parent::__construct();
    $this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->load->library('control_uri');
		$this->load->library('pagination');
    $this->load->model('feedback_model');
    $this->lang->load('feedback');
    $this->load->helper('language');
    $this->load->helper('text');

    $file_config = rtrim(rtrim(dirname(__FILE__),DIRECTORY_SEPARATOR),"controllers")."/config.php";
    if (is_file($file_config)){
     include_once($file_config);
    }else{
      exit('Не найден конфигурационный файл языкового модуля');
    }
    $arr = &get_setting_feedback();
    $this->load_config($arr);
    $this->control_uri->guri('feedback')->set_index($this->index_request);
    //$this->control_uri->guri('feedback_user')->set_index($this->index_request);
    //$this->control_uri->guri('feedback')->set_start_segment(2);
	}

  // загрузка файла конфигурации
	function load_config($arr){
    if(is_array($arr)){
      foreach($arr as $key=>$value){
        if(is_array($value)){
          foreach($value as $key_item=>$item){
            if($key == 'path') $this->path[$key_item] = $item;
            if($key == 'img') $this->img[$key_item] = $item;
            if($key == 'get') $this->get[$key_item] = $item;
            //if($key == 'setting') $this->setting[$key_item] = $item;
            if($key == 'index_request') $this->index_request[$key_item] = $item;
            //echo "$key!!!$key_item = $item<br /> ";
          }
        }
      }
    }
    $this->setting = $this->get_setting();
  }

  // загрузка файла установленных параметров
	function get_setting(){
    $file_setting = rtrim(rtrim(dirname(__FILE__),DIRECTORY_SEPARATOR),"controllers")."/setting.php";
    if (is_file($file_setting)){
      include($file_setting);
    }else{
      exit('Не найден установочный файл \"feedback\" модуля');
    }
    if(isset($config)) return $config;
    return false;
  }

  // Маршрутизация модуля
  function admin_index(){

    $this->control_uri->guri('feedback')->set_start_segment(4);
    //print_r($this->index_request);
    //echo $this->control_uri->guri('language')->value_segment('index1');
    //$this->control_uri->guri('language')->set_index($this->index_request);
    $buf = '';
    $buf .= $this->admin_menu_default();
    if($this->control_uri->guri('feedback')->value_segment('index1')){
      $index2 = $this->control_uri->guri('feedback')->value_segment('index2');
      $index3 = $this->control_uri->guri('feedback')->value_segment('index3');
      switch ($this->control_uri->guri('feedback')->value_segment('index1')){
        case 'theme':
          if($index2 == $this->get['sub_menu']['theme']['add_theme']['link']){
            $buf .= $this->load->module('feedback/feedback_theme')->admin_add_theme();
          }elseif($index2 == $this->get['sub_menu']['theme']['theme']['link']){
            if(is_numeric($index3)){
              $buf .= $this->load->module('feedback/feedback_theme')->admin_theme_id($index3);
            }else{
              $buf .= $this->load->module('feedback/feedback_theme')->admin_theme();
            }
          }
          break;
        case 'message':
          if($index2 == $this->get['sub_menu']['message']['list']['link']){
            if(is_numeric($index3)){
              $buf .= $this->load->module('feedback/feedback_message')->admin_message_id($index3);
            }else{
              $buf .= $this->load->module('feedback/feedback_message')->admin_message();
            }
          }
          break;
        default:
          $buf .= $this->admin_menu_default();
          break;
      }

    }

    return $buf;

  }
  // административное меню модуля
  function admin_menu_default(){
    $data['get'] = $this->get;
    $data['uri']['point'] = $this->load->module('feedback/feedback')->uri_point();

    $data['uri']['index'][1] = $this->control_uri->guri('feedback')->value_segment('index1');
    $data['uri']['index'][2] = $this->control_uri->guri('feedback')->value_segment('index2');
    $this->control_uri->guri('feedback')->replace_empty_index(false);
    $config = array('index1' => '?');
    $data['uri']['menu'] = $this->control_uri->guri('feedback')->get_uri($config);
    $config2 = array('index1' => $data['uri']['index'][1], 'index2' => '?');
    $data['uri']['sub_menu'] = $this->control_uri->guri('feedback')->get_uri($config2);


    return $this->load->view('admin_menu_default', $data, true);
  }
  // точка старта запроса uri
  function uri_point($name = ''){
    $uri = $this->control_uri->get_full_segment('admin','mod');
    $uri .= 'feedback/';
    return $uri;
  }

  // возвращает массив с данными пагинации
  // @arg: total_rows - кол-во строк
  //       per_page   - кол-во строк на странице
  //       cur_page   - текущая страница, если не задана то по умолчанию первая
  // @return: array - total_rows - кол-во строк
  //                  per_page   - кол-во строк на странице
  //                  cur_page   - текущая страница
  //                  total_page - всего страниц
  //                  first_page - первая страница
  //                  end_pаge   - последняя страница
  //                  prev_page  - предыдущая страница
  //                  next_page  - следующая страница
  function pagination_data($total_rows, $per_page, $cur_page = ''){
    if(is_numeric($total_rows) && !empty($per_page) && is_numeric($per_page)){
      // всего страниц
      $data['total_page'] = ceil($total_rows/$per_page);
      // текущая страница
      if(!empty($cur_page)){
        if($cur_page > $data['total_page']) {
          $data['cur_page'] = $data['total_page'];
        }elseif($cur_page < 1){
          $data['cur_page'] = 1;
        }else{
          $data['cur_page'] = $cur_page;
        }
      }else{
        $data['cur_page'] = 1;
      }
      // первая страница
      $data['first_page'] = 1;
      // последняя страница
      $data['end_page'] = $data['total_page'];
      // предыдущая страница
      if(($data['cur_page'] - 1) < $data['first_page']){
        $data['prev_page'] = '';
      }else{
        $data['prev_page'] = $data['cur_page'] - 1;
      }
      // следующая страница
      if(($data['cur_page'] + 1) > $data['total_page']){
        $data['next_page'] = '';
      }else{
        $data['next_page'] = $data['cur_page'] + 1;
      }
    }
    if(isset($data)) return $data;
    return false;
  }

  //возвращает значения индексов запросов
  function get_index(){
    if(isset($this->index_request) && is_array($this->index_request)){
      foreach($this->index_request as $key=>$value){
        $data[$key] = $this->control_uri->guri('feedback')->value_segment($key);
      }
    }

    if(isset($data)) return $data;
    return false;
  }


}




