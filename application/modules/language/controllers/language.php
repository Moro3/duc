<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Language extends MX_Controller {
  protected $index_request = array();
  protected $path;
  protected $img;
  protected $get;
  protected $lang_default;
  protected $lang_current;
  function __construct()
	{
    parent::__construct();
        $this->load->helper(array('form', 'url'));
	$this->load->library('form_validation');
	$this->load->library('control_uri');
        $this->load->model('language/language_model');
        $this->lang->load('language');
        $this->load->helper('language');

    $file_config = rtrim(rtrim(dirname(__FILE__),DIRECTORY_SEPARATOR),"controllers")."/config.php";
    if (is_file($file_config)){
     include_once($file_config);
    }else{
      exit('Не найден конфигурационный файл языкового модуля');
    }
    $arr = &get_setting_language();
    $this->load_setting($arr);
    $this->control_uri->guri('language')->set_index($this->index_request);
    $this->control_uri->guri('language')->set_start_segment(2);

    $this->set_default();
	}

  //установка языка по умолчанию
  function set_default(){
    $this->lang_default = 1;
  }
  //возвращает язык по умолчанию
  function get_default(){
    return $this->lang_default;
  }
  //установка текущего языка
  function set_current($id_lang){
    if(!empty($id_lang) && is_numeric($id_lang)){
      if($this->language_model->is_lang($id_lang)){
        $this->lang_current = $id_lang;
        return;
      }
    }
    $this->lang_current = $this->get_default();
  }
  //возвращает текущий язык
  function get_current(){
    $lang = $this->lang_current;
    if(!$lang){
      $lang = $this->lang_default;
    }
    return $lang;
  }

  function load_setting($arr){
    if(is_array($arr)){
      foreach($arr as $key=>$value){
        if(is_array($value)){
          foreach($value as $key_item=>$item){
            if($key == 'path') $this->path[$key_item] = $item;
            if($key == 'img') $this->img[$key_item] = $item;
            if($key == 'get') $this->get[$key_item] = $item;
            if($key == 'index_request') $this->index_request[$key_item] = $item;
            //echo "$key!!!$key_item = $item<br /> ";
          }
        }
      }
    }
    
    $this->assets->style->load('language.css');
  }




  function admin_index(){
    $this->control_uri->guri('language')->set_start_segment(4);

    //print_r($this->index_request);

    //echo $this->control_uri->guri('language')->value_segment('index1');
    //$this->control_uri->guri('language')->set_index($this->index_request);
    $buf = '';
    $buf .= $this->admin_menu_default();
    if($this->control_uri->guri('language')->value_segment('index1')){
      switch ($this->control_uri->guri('language')->value_segment('index1')){
        case 'lang':
          $index2 = $this->control_uri->guri('language')->value_segment('action');
          $index3 = $this->control_uri->guri('language')->value_segment('id');

          if($index2 == $this->get['sub_menu']['lang']['add_lang']['link']){

            $buf .= $this->load->module('language/language_create')->admin_add_language();
          }elseif($index2 == $this->get['sub_menu']['lang']['lang']['link']){
            if(is_numeric($index3)){
              $buf .= $this->load->module('language/language_update')->admin_id_language($index3);
            }else{
              $buf .= $this->load->module('language/language_update')->admin_update_language();
            }
          }

          break;
        case 'setting':
          $buf .= $this->admin_setting();
          break;
        default:
          $buf .= $this->admin_menu_default();
          break;
      }

    }
    return $buf;

  }

  //вывод списка всех языков
  function admin_list(){
    $data['arr_language'] = $this->language_model->get_all_info();
    $data['uri']['point'] = $this->uri_point();
    return $this->load->view('admin_language_list', $data, true);
  }


  //вывод списка всех языков
  function admin_setting(){
    $data['arr_language'] = $this->language_model->get_all_info();
    $data['uri']['point'] = $this->uri_point();
    return $this->load->view('admin_setting', $data, true);
  }

  function admin_menu_default(){
    $data['get'] = $this->get;
    $data['uri']['point'] = $this->load->module('language/language')->uri_point();

    $data['uri']['index'][1] = $this->control_uri->guri('language')->value_segment('index1');
    $data['uri']['index'][2] = $this->control_uri->guri('language')->value_segment('action');
    $this->control_uri->guri('language')->replace_empty_index(false);
    $config = array('index1' => '?');
    $data['uri']['menu'] = $this->control_uri->guri('language')->get_uri($config);
    $config2 = array('index1' => $data['uri']['index'][1], 'action' => '?');
    $data['uri']['sub_menu'] = $this->control_uri->guri('language')->get_uri($config2);


    return $this->load->view('admin_menu_default', $data, true);
  }

  function uri_point($name = ''){
    $uri = $this->control_uri->get_full_segment('admin','mod');
    $uri .= 'language/';
    return $uri;
  }

  // изменение размеров изображения
  function img_resize($img, $rewrite = false){
    //$path_root = rtrim($_SERVER['DOCUMENT_ROOT'],DIRECTORY_SEPARATOR);
    $path_root = $this->path['dir_root'];
    if(is_array($this->path['thumb'])){
      if(is_file($path_root."/".$this->path['img_original']."/".$img)){
        foreach($this->path['thumb'] as $key=>$folder){
          if(!empty($folder)){
            if(!is_dir($path_root."/".$folder)){
              mkdir($path_root."/".$folder);
            }
            $write_file = true;
            if(is_file($path_root."/".$folder."/".$img)){
              if($rewrite == false) {
                $write_file = false;
              }else{
                $write_file = true;
              }
            }
            if($write_file){
                if(isset($this->img['size'][$key])){
                  //$sss[] = $path_root."/".$folder;
                  list($width,$height) = explode('x',$this->img['size'][$key]);
                  $config['image_library'] = 'gd2'; // выбираем библиотеку
                  $config['source_image'] = $path_root."/".$this->path['img_original']."/".$img;
                  //$config['create_thumb'] = TRUE; // ставим флаг создания эскиза
                  $config['maintain_ratio'] = TRUE; // сохранять пропорции
                  $config['new_image'] = $path_root."/".$folder;
                  $config['width'] = $width; // и задаем размеры
                  $config['height'] = $height;
                  $this->load->library('image_lib'); // загружаем библиотеку
                  $this->image_lib->initialize($config);
                  $this->image_lib->resize();
                  $this->image_lib->clear();
                }else{

                }
            }
          }
        }
      }
    }
  }

  // удаляет файл изображения
  function del_file_img($name_file){
    if(!is_array($name_file)){
      $names = array($name_file);
    }else{
      $names = $name_file;
    }
    $flag_del = true;
    $path_root = rtrim($_SERVER['DOCUMENT_ROOT'],DIRECTORY_SEPARATOR);
    //print_r($names);
    foreach($names as $key_id=>$value){
      if(is_array($this->path['thumb'])){
          foreach($this->path['thumb'] as $key=>$folder){
            if(!empty($folder)){
              if(is_dir($path_root."/".$folder)){
                if(is_file($path_root."/".$folder."/".$value)){
                  if(!unlink($path_root."/".$folder."/".$value)){
                    $flag_del = false;
                  }
                }else{
                  echo $this->lang->line('form_language_not_file')." \"/".$folder."/".$value."\"";
                }
              }else{
                echo $this->lang->line('form_language_not_dir')." \"$folder\"";
              }
            }
          }
        if(is_file($path_root."/".$this->path['img_original']."/".$value)){
          if(!unlink($path_root."/".$this->path['img_original']."/".$value)){
             $flag_del = false;
          }
        }else{
          echo $this->lang->line('form_language_not_file')." \"/".$this->path['img_original']."/".$value."\"";
        }
      }
    }
    if($flag_del) return true;
    return false;
  }
  //получение путей для модуля языки
  function get_path(){
    return $this->path;
  }
  //получение путей изображений для модуля языки
  function get_img(){
    return $this->img;
  }
  //получение всей информации о всех языках
  function get_all_language(){
    return $this->language_model->get_all_info();
  }
  // получение id по аббревиатуре
  function get_id_on_abbr($abbr){
    return $this->language_model->get_id_on_abbr($abbr);
  }
}









