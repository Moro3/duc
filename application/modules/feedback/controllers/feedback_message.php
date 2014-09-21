<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Feedback_message extends Feedback {

  function __construct(){
    parent::__construct();
  }

  //вывод информации о всех сообщениях
  function admin_message (){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;
    $data['uri']['module'] = $this->load->module('feedback/feedback')->uri_point();

    $index = $this->load->module('feedback/feedback')->get_index();

    $this->control_uri->guri('feedback')->replace_empty_index(false);
    $data['message']['count'] = $this->feedback_model->get_count_message();
    $data['pagination'] = $this->load->module('feedback/feedback')->pagination_data($data['message']['count'],$this->setting['admin']['per_page'],$index['index4']);

    $config = array('index1' => $index['index1'],'index2' => $index['index2'], 'index3' => '?','index4' => $index['index4'], 'index5' => $index['index5'], 'index6' => $index['index6']);
    $data['uri']['message'] = $this->control_uri->guri('feedback')->get_uri($config);

    $config_delete = array('index1' => $index['index1'],'index2' => 'delete');
    $data['uri']['delete'] = $this->control_uri->guri('feedback')->get_uri($config_delete);

    // limit
    $limit_rows = $this->setting['admin']['per_page'];
    $limit_offset = $this->setting['admin']['per_page'] * ($data['pagination']['cur_page'] - 1);
    if(!empty($limit_offset)) $limit = $limit_rows.','.$limit_offset; else $limit = $limit_rows;

    //order_by
    if(!empty($index['index5'])) $order_by = $index['index5']; else $order_by = 'date';
    if(!empty($index['index6'])) $order_direction = $index['index6']; else $order_direction = 'asc';
    $data['order']['by'] = $order_by;
    $data['order']['direction'] = $order_direction;
    $config_order = array('index1' => $index['index1'],'index2' => $index['index2'], 'index3' => $index['index3'], 'index4' => $index['index4'], 'index5' => '?', 'index6' => '?');
    $data['uri']['order'] = $this->control_uri->guri('feedback')->get_uri($config_order);

    //сообщения
    $data['message_arr'] = $this->feedback_model->get_all_message($limit, $order_by, $order_direction);
    //$data['message']['count'] = count($data['message_arr']);

    //print_r($data['language_arr']);
    return $this->load->view('admin_message_list', $data, true);

  }
  //постраничная навигация для списка сообщений
  function pagination_message(){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;
    $data['uri']['module'] = $this->load->module('feedback/feedback')->uri_point();

    $index = $this->load->module('feedback/feedback')->get_index();
    $this->control_uri->guri('feedback')->replace_empty_index(true);
    $data['message']['count'] = $this->feedback_model->get_count_message();

    $data['pagination'] = $this->load->module('feedback/feedback')->pagination_data($data['message']['count'],$this->setting['admin']['per_page'],$index['index4']);

    $config = array('index1' => $index['index1'],'index2' => $index['index2'], 'index3' => $index['index3'], 'index4' => '?','index5' => $index['index5'],'index6' => $index['index6']);
    $data['uri']['message'] = $this->control_uri->guri('feedback')->get_uri($config);
    //print_r($data['pagination']);
    return $this->load->view('admin_pagination_message', $data, true);
  }
  //постраничная навигация для одного сообщения
  function pagination_message_id(){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;
    $data['uri']['module'] = $this->load->module('feedback/feedback')->uri_point();

    $index = $this->load->module('feedback/feedback')->get_index();
    $this->control_uri->guri('feedback')->replace_empty_index(true);
    $data['message']['count'] = $this->feedback_model->get_count_message();

    $data['pagination'] = $this->load->module('feedback/feedback')->pagination_data($data['message']['count'],$this->setting['admin']['per_page'],$index['index4']);

    $config = array('index1' => $index['index1'],'index2' => $index['index2'], 'index4' => '?','index5' => $index['index5'],'index6' => $index['index6']);
    $data['uri']['message'] = $this->control_uri->guri('feedback')->get_uri($config);
    //print_r($data['pagination']);
    return $this->load->view('admin_pagination_message', $data, true);
  }
  // вывод информации о конкретном сообщении
  function admin_message_id ($id){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;
    $data['uri']['module'] = $this->load->module('feedback/feedback')->uri_point();

    $index = $this->load->module('feedback/feedback')->get_index();
    //$this->control_uri->guri('feedback')->replace_empty_index(false);

    $config = array('index1' => $index['index1'],'index2' => $index['index2'], 'index3' => '?');
    $data['uri']['message'] = $this->control_uri->guri('feedback')->get_uri($config);

    $config_delete = array('index1' => $index['index1'],'index2' => 'delete');
    $data['uri']['delete'] = $this->control_uri->guri('feedback')->get_uri($config_delete);

    $data['message_arr'] = $this->feedback_model->get_message($id);

    //print_r($data['language_arr']);
    return $this->load->view('admin_message_id', $data, true);

  }
}


