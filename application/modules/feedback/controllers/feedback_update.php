<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Feedback_update extends Feedback {

  function __construct(){
    parent::__construct();
  }

  function admin_update_theme ($id){

      if($this->input->post('save_id') || $this->input->post('save_id_x')){

      $field_change = array('id',
                          'show_i',
                          'name',
                          'email',
                          'user_name',
                          );
      $field_config = array(
                          'id' => array(
                                                               'field' => 'id',
                                                               'label' => $this->lang->line('form_language_id'),
                                                               'rules' => 'trim|required|is_natural_no_zero|max_length[5]',
                                                                ),
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
                          'show_i' => array(
                                           'field' => 'show',
                                           'label' => 'Показывать',
                                           'rules' => 'trim|is_natural|max_length[1]',
                                            ),
                          );

      $field_form = array('id'  => 'id',
                         'name' => 'name',
                         'email' => 'email',
                         'user_name' => 'user',
                         'show_i' => 'show',
                        );
      // проверка на валидность данных
      $this->form_validation->valid_control($field_form, $field_config);

      $this->form_validation->set_error_delimiters('<div style="color:#B52626;font-size:12px;">', '</div>');
      if ($this->form_validation->run($this)){

        $this->load->library('change');
        $this->change->config($id, 'feedback_theme', $field_change);
        $this->change->set_form_name($field_form);
        $this->change->set_form_type('show', 'checkbox', array('type' => 'checkbox',
                                                                  'isset' => 1,
                                                                  'notset' => 0,
                                                                  )
                                      );
        $this->change->run();
        //echo "Есть изменения";
        if($this->change->change()){
           $field = $this->change->change();
           $hidden_field = array('date_update' => time(),
                                  'ip_update' => $this->input->ip_address(),
                                  );
          // если все верно записываем в базу
          if($this->feedback_model->update_theme('feedback_theme',$this->change->change(), $hidden_field) != false){
              $this->change->set_status('update', true);
            }else{
              $this->change->set_status('update', false);
            }
        }else{
          $this->change->set_status('not_change', true);
          //echo "Класс Change не сработал";
        }
      }
    }

  }

  function user_insert_message(){
    if($this->input->post('add_message') || $this->input->post('add_message_x')){

      $field_change = array(
                          'name',
                          'email',
                          'id_theme',
                          'message',
                          );
      $field_config = array(
                          'name' => array(
                                                               'field' => 'name',
                                                               'label' => $this->lang->line('user_message_name'),
                                                               'rules' => 'trim|required|alpha_space_ru|min_length[3]|max_length[50]',
                                                                ),

                          'email' => array(
                                                               'field' => 'email',
                                                               'label' => $this->lang->line('user_message_email'),
                                                               'rules' => 'trim|required|valid_email|max_length[50]',
                                                                ),
                          'message' => array(
                                                               'field' => 'message',
                                                               'label' => $this->lang->line('user_message_message'),
                                                               'rules' => 'trim|required|alpha_space_ru|min_length[2]|max_length[1000]',
                                                                ),
                          'theme' => array(
                                           'field' => 'theme',
                                           'label' => 'Тема',
                                           'rules' => 'trim|is_natural|max_length[2]',
                                            ),
                          );

      $field_form = array(
                         'name' => 'name',
                         'email' => 'email',
                         'id_theme' => 'theme',
                         'message' => 'message',
                        );
      // проверка на валидность данных
      $this->form_validation->valid_control($field_form, $field_config);

      $this->form_validation->set_error_delimiters('<div style="color:#B52626;font-size:12px;">', '</div>');
      if ($this->form_validation->run($this)){

           $field = array('name'    => $this->input->post('name'),
                          'email'   => $this->input->post('email'),
                          'id_theme'   => $this->input->post('theme'),
                          'message' => $this->input->post('message'),
                          );
           $hidden_field = array(
                          'date'    => time(),
                          'ip'      => $this->input->ip_address(),
                          );
          // если все верно записываем в базу
          if($this->feedback_model->insert_message($field, $hidden_field) != false){
            $id_theme = $this->input->post('theme');
            $thema = $this->feedback_model->get_theme($id_theme);
            if(!empty($thema[$id_theme]['email'])){
              $this->load->library('email');

              $config['mailtype'] = 'html';
              $config['charset'] = 'windows-1251';
              //$config['wordwrap'] = true;
              $this->email->initialize($config);

              $this->email->from($field['email'], $field['name']);
              $this->email->to($thema[$id_theme]['email']);
              //$this->email->cc('another@another-example.com');
              //$this->email->bcc('them@their-example.com');

              $this->email->subject($thema[$id_theme]['name']);

              $text = "<h3>Письмо с сайта:</h3>
                       <b>Тема</b>:  <u>".$thema[$id_theme]['name']."</u><br /><br />
                       <b>От кого</b>:  ".$field['name']."<br />
                       <b>E-mail</b>:  ".$field['email']."<br />
                       <b>Сообщение</b>:  ".$field['message']."<br />
                      ";
              $this->email->message($text);

              $this->email->send();

              echo $this->email->print_debugger();
            }
            return true;
          }

      }
    }
    return false;
  }

}






