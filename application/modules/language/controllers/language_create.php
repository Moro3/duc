<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Language_create extends Language {

  function __construct(){
    parent::__construct();
  }

  function admin_add_language(){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;

    $config = array('index1' => $this->get['menu']['lang']['link'],
                    'action' => $this->get['sub_menu']['lang']['add_lang']['link']
                    );
    $data['uri']['form'] = $this->control_uri->guri('language')->get_uri($config);
    $data['uri']['module'] = $this->load->module('language/language')->uri_point();

    $tpl = '';
    $tpl .= $this->load->module('language/language_create')->action_add_language();

    $tpl .= $this->load->view('admin_add_language', $data, true);
    return $tpl;
  }

  function action_add_language(){

    $field_change = array(
                          'name',
                          'abbr',
                          );
      $field_config = array(
                          'name' => array(
                                                               'field' => 'name',
                                                               'label' => $this->lang->line('form_language_name'),
                                                               'rules' => 'trim|required|alpha_space_ru|min_length[3]|max_length[20]',
                                                                ),

                          'abbr' => array(
                                                               'field' => 'abbr',
                                                               'label' => $this->lang->line('form_language_abbr'),
                                                               'rules' => 'trim|required|alpha|min_length[2]|max_length[2]|callback_abbr_check',
                                                                ),
                          );

      $field_form = array(
                         'name' => 'name',
                          'abbr' => 'abbr',
                        );
      // проверка на валидность данных
      $this->form_validation->valid_control($field_form, $field_config);

      $this->form_validation->set_error_delimiters('<div style="color:#ff0000;font-size:16px;">', '</div>');
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
          if($this->language_model->insert_language($field, $hidden_field) != false){
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

    $config = array('index1' => $this->get['menu']['lang']['link'],
                    'action' => $this->get['sub_menu']['lang']['lang']['link'],
                    'id'     => $this->language_model->get_last_id(),
                    );
    $data['uri']['edit'] = $this->control_uri->guri('language')->get_uri($config);
    $config2 = array('index1' => $this->get['menu']['lang']['link'],
                    'action' => $this->get['sub_menu']['lang']['add_lang']['link'],
                    );
    $data['uri']['add'] = $this->control_uri->guri('language')->get_uri($config2);
    $data['uri']['module'] = $this->load->module('language/language')->uri_point();

    return $this->load->view('admin_add_language_ok', $data, true);
  }

  function admin_add_form_error(){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;

    $config2 = array('index1' => $this->get['menu']['lang']['link'],
                    'action' => $this->get['sub_menu']['lang']['add_lang']['link'],
                    );
    $data['uri']['add'] = $this->control_uri->guri('language')->get_uri($config2);
    return $this->load->view('admin_add_language_error', $data, true);
  }

  // проверка на уже существующей аббревиатуры
  function abbr_check($abbr){
	  if (isset($abbr))
		{
      $number = $this->language_model->get_id_on_abbr($abbr);
      if($number){
        $this->form_validation->set_message('abbr_check', $this->lang->line('form_language_isset_abbr'));
			  return FALSE;
			}
		}
	}

}

