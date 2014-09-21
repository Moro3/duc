<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Language_user extends Language {

  function __construct(){
    parent::__construct();
  }

  function list_language(){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;

    $data['language_arr'] = $this->language_model->get_user_language();
    $data['lang_default'] = $this->get_default();
    //print_r($data['language_arr']);
    return $this->load->view('user_list_language', $data, true);

  }

}

