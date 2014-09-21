<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Feedback_theme extends Feedback {

  function __construct(){
    parent::__construct();
  }

  //вывод темы по её id
  function admin_theme_id ($id){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;
    $data['uri']['module'] = $this->load->module('feedback/feedback')->uri_point();

    $data['uri']['index'][1] = $this->control_uri->guri('feedback')->value_segment('index1');
    $data['uri']['index'][2] = $this->control_uri->guri('feedback')->value_segment('index2');
    $data['uri']['index'][3] = $this->control_uri->guri('feedback')->value_segment('index3');
    $this->control_uri->guri('feedback')->replace_empty_index(false);

    $config = array('index1' => $data['uri']['index'][1],'index2' => $data['uri']['index'][2], 'index3' => '?');
    $data['uri']['id'] = $this->control_uri->guri('feedback')->get_uri($config);

    $config_delete = array('index1' => $data['uri']['index'][1],'index2' => 'delete');
    $data['uri']['delete'] = $this->control_uri->guri('feedback')->get_uri($config_delete);
    $tpl = '';
    $tpl .= $this->load->module('feedback/feedback_update')->admin_update_theme($id);

    $data['theme_arr'] = $this->feedback_model->get_theme($id);

    //print_r($data['language_arr']);
    $tpl .= $this->load->view('admin_theme_id', $data, true);
    return $tpl;
  }
  // вывод всех тем
  function admin_theme(){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;
    $data['uri']['module'] = $this->load->module('feedback/feedback')->uri_point();

    $index = $this->load->module('feedback/feedback')->get_index();

    $this->control_uri->guri('feedback')->replace_empty_index(false);
    $data['message']['count'] = $this->feedback_model->get_count_theme();
    $data['pagination'] = $this->load->module('feedback/feedback')->pagination_data($data['message']['count'],$this->setting['admin']['per_page'],$index['index4']);

    $config = array('index1' => $index['index1'],'index2' => $index['index2'], 'index3' => '?');
    $data['uri']['theme'] = $this->control_uri->guri('feedback')->get_uri($config);

    $config_delete = array('index1' => $index['index1'],'index2' => 'delete');
    $data['uri']['delete'] = $this->control_uri->guri('feedback')->get_uri($config_delete);

    // limit
    $limit_rows = $this->setting['admin']['per_page'];
    $limit_offset = $this->setting['admin']['per_page'] * ($data['pagination']['cur_page'] - 1);
    if(!empty($limit_offset)) $limit = $limit_rows.','.$limit_offset; else $limit = $limit_rows;

    //order_by
    if(!empty($index['index5'])) $order_by = $index['index5']; else $order_by = 'id';
    if(!empty($index['index6'])) $order_direction = $index['index6']; else $order_direction = 'asc';
    $data['order']['by'] = $order_by;
    $data['order']['direction'] = $order_direction;
    $config_order = array('index1' => $index['index1'],'index2' => $index['index2'], 'index3' => $index['index3'], 'index4' => $index['index4'], 'index5' => '?', 'index6' => '?');
    $data['uri']['order'] = $this->control_uri->guri('feedback')->get_uri($config_order);

    // темы
    $data['theme_arr'] = $this->feedback_model->get_all_theme($limit, $order_by, $order_direction);

    //print_r($data['language_arr']);
    return $this->load->view('admin_theme_list', $data, true);
  }
  // добавление темы
  function admin_add_theme(){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;

    $config = array('index1' => $this->get['menu']['theme']['link'],
                    'index2' => $this->get['sub_menu']['theme']['add_theme']['link']
                    );
    $data['uri']['form'] = $this->control_uri->guri('feedback')->get_uri($config);
    $data['uri']['module'] = $this->load->module('feedback/feedback')->uri_point();

    $tpl = '';
    $tpl .= $this->load->module('feedback/feedback_theme')->action_add_theme();

    $tpl .= $this->load->view('admin_add_theme', $data, true);
    return $tpl;
  }

  function action_add_theme(){

    $field_change = array(
                          'name',
                          'email',
                          'user_name'
                          );
      $field_config = array(
                          'name' => array(
                                                               'field' => 'name',
                                                               'label' => $this->lang->line('form_theme_name'),
                                                               'rules' => 'trim|required|alpha_space_ru|min_length[3]|max_length[50]',
                                                                ),

                          'email' => array(
                                                               'field' => 'email',
                                                               'label' => $this->lang->line('form_theme_email'),
                                                               'rules' => 'trim|required|valid_emails|max_length[50]',
                                                                ),
                          'user_name' => array(
                                                               'field' => 'user',
                                                               'label' => $this->lang->line('form_theme_user'),
                                                               'rules' => 'trim|required|alpha_space_ru|min_length[2]|max_length[30]',
                                                                ),
                          );

      $field_form = array(
                         'name' => 'name',
                         'email' => 'email',
                         'user_name' => 'user',
                        );
      // проверка на валидность данных
      $this->form_validation->valid_control($field_form, $field_config);

      $this->form_validation->set_error_delimiters('<div style="color:#ff0000;font-size:14px;">', '</div>');
      if ($this->form_validation->run($this)){

        $this->load->library('change');
        $this->change->set_id(1);
        $this->change->set_field($field_change);
        $this->change->set_form_name($field_form);

        $this->change->run('insert');
        //echo "Есть изменения";
        if($this->change->change()){
           $field = $this->change->change();
           $hidden_field = array('date_create' => time(),
                                  'ip_create' => $this->input->ip_address(),
                                  );
          // если все верно записываем в базу
          if($this->feedback_model->insert_theme($field, $hidden_field) != false){
            $this->change->set_status('insert', true);
            // если записалось выводим форму 'ок'
            return $this->admin_add_form_ok();
          }else{
            $this->change->set_status('insert', false);
            // если НЕ записалось выводим форму 'error'
            return $this->admin_add_form_error();
          }
        }else{
          echo "Класс Change не сработал";
        }
      }
  }

  //*********************************************//
  //***********  Формы  *************************//
  //*********************************************//
  function admin_add_form_ok(){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;

    $config = array('index1' => $this->get['menu']['theme']['link'],
                    'index2' => $this->get['sub_menu']['theme']['theme']['link'],
                    'index3'     => $this->feedback_model->get_last_id(),
                    );
    $data['uri']['edit'] = $this->control_uri->guri('feedback')->get_uri($config);
    $config2 = array('index1' => $this->get['menu']['theme']['link'],
                    'index2' => $this->get['sub_menu']['theme']['add_theme']['link'],
                    );
    $data['uri']['add'] = $this->control_uri->guri('feedback')->get_uri($config2);
    $data['uri']['module'] = $this->load->module('feedback/feedback')->uri_point();

    return $this->load->view('admin_add_theme_ok', $data, true);
  }

  function admin_add_form_error(){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;

    $config2 = array('index1' => $this->get['menu']['theme']['link'],
                    'index2' => $this->get['sub_menu']['theme']['add_theme']['link'],
                    );
    $data['uri']['add'] = $this->control_uri->guri('feedback')->get_uri($config2);
    return $this->load->view('admin_add_theme_error', $data, true);
  }

  // вывод статуса модификации данных
  function status_change(){
    $this->load->library('change');
    $data['status'] = $this->change->get_status();

    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;

    if($data['status']){
      return $this->load->view('admin_status_change_view', $data, true);
    }

  }

  //постраничная навигация для списка сообщений
  function pagination_theme(){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;
    $data['uri']['module'] = $this->load->module('feedback/feedback')->uri_point();

    $index = $this->load->module('feedback/feedback')->get_index();
    $this->control_uri->guri('feedback')->replace_empty_index(true);
    $data['message']['count'] = $this->feedback_model->get_count_theme();

    $data['pagination'] = $this->load->module('feedback/feedback')->pagination_data($data['message']['count'],$this->setting['admin']['per_page'],$index['index4']);

    $config = array('index1' => $index['index1'],'index2' => $index['index2'], 'index3' => $index['index3'], 'index4' => '?','index5' => $index['index5'],'index6' => $index['index6']);
    $data['uri']['message'] = $this->control_uri->guri('feedback')->get_uri($config);
    //print_r($data['pagination']);
    return $this->load->view('admin_pagination_message', $data, true);
  }
  //постраничная навигация для одного сообщения
  function pagination_theme_id(){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;
    $data['uri']['module'] = $this->load->module('feedback/feedback')->uri_point();

    $index = $this->load->module('feedback/feedback')->get_index();
    $this->control_uri->guri('feedback')->replace_empty_index(true);
    $data['message']['count'] = $this->feedback_model->get_count_theme();

    $data['pagination'] = $this->load->module('feedback/feedback')->pagination_data($data['message']['count'],$this->setting['admin']['per_page'],$index['index4']);

    $config = array('index1' => $index['index1'],'index2' => $index['index2'], 'index4' => '?','index5' => $index['index5'],'index6' => $index['index6']);
    $data['uri']['message'] = $this->control_uri->guri('feedback')->get_uri($config);
    //print_r($data['pagination']);
    return $this->load->view('admin_pagination_message', $data, true);
  }

}



