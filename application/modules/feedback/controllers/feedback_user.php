<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// если класс не объявлен подключаем файл с данным классом
if( ! class_exists('feedback')){
  include_once ('feedback.php');
}

class Feedback_user extends Feedback {

  function __construct(){
    parent::__construct();
  }

  function user_feedback($theme){
    if(!empty($theme)){
      echo "Тема №$theme";
    }else{
      echo "Без темы!";
    }
    $insert = $this->load->module('feedback/feedback_update')->user_insert_message();
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;
    $data['uri']['module'] = $uri = $this->control_uri->get_full_segment('pages');

    $data['theme_arr'] = $this->feedback_model->get_all_theme_user();
    $data['theme_current'] = $theme;
    //print_r($data['language_arr']);

    if($insert == true){
      return $this->load->view('user_add_ok', $data, true);
    }else{
      return $this->load->view('user_add_message', $data, true);
    }
  }
}



