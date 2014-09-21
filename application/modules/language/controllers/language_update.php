<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Language_update extends Language {

  function __construct(){
    parent::__construct();
  }

  function admin_update_language(){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;

    $config = array('index1' => $this->get['menu']['lang']['link'],
                    'action' => $this->get['sub_menu']['lang']['lang']['link'],
                    'id'       => '?',
                    );
    $data['uri']['edit'] = $this->control_uri->guri('language')->get_uri($config);
    $config2 = array('index1' => $this->get['menu']['lang']['link'],
                    'action' => $this->get['sub_menu']['lang']['lang']['link'],
                    );
    $data['uri']['delete'] = $this->control_uri->guri('language')->get_uri($config2);
    $data['uri']['module'] = $this->load->module('language/language')->uri_point();

    $tpl = '';

    $data['arr_language'] = $this->language_model->get_all_info();
    $tpl .= $this->load->view('admin_language_list', $data, true);
    return $tpl;
  }

  function admin_id_language($id){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;

    $config = array('index1' => $this->get['menu']['lang']['link'],
                    'action' => $this->get['sub_menu']['lang']['lang']['link'],
                    'id'       => $id,
                    );
    $data['uri']['id'] = $this->control_uri->guri('language')->get_uri($config);
    $config2 = array('index1' => $this->get['menu']['lang']['link'],
                    'action' => $this->get['sub_menu']['lang']['lang']['link'],
                    );
    $data['uri']['delete'] = $this->control_uri->guri('language')->get_uri($config2);
    $data['uri']['module'] = $this->load->module('language/language')->uri_point();

    $data['flag_language'] = $this->load->module('language/language_update')->load_img_flag($id);
    $data['arms_language'] = $this->load->module('language/language_update')->load_img_arms($id);
    $tpl = '';
    $tpl .= $this->load->module('language/language_update')->action_update_language($id);

    $data['arr_language'] = $this->language_model->get_info($id);
    $tpl .= $this->load->view('admin_edit_id', $data, true);
    return $tpl;
  }

  function action_update_language($id){
    if($this->input->post('edit_id') || $this->input->post('edit_id_x')){

      $field_change = array('id',
                          'name',
                          'abbr',
                          'description',
                          );
      $field_config = array(
                          'id' => array(
                                                               'field' => 'id',
                                                               'label' => $this->lang->line('form_language_id'),
                                                               'rules' => 'trim|required|is_natural_no_zero|max_length[5]',
                                                                ),
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
                          'description' => array(
                                                               'field' => 'description',
                                                               'label' => $this->lang->line('form_language_description'),
                                                               'rules' => 'trim|alpha|max_length[100]',
                                                                ),
                          );

      $field_form = array('id'  => 'id',
                         'name' => 'name',
                         'abbr' => 'abbr',
                         'description' => 'description',
                        );
      // проверка на валидность данных
      $this->form_validation->valid_control($field_form, $field_config);

      $this->form_validation->set_error_delimiters('<div style="color:#ff0000;font-size:16px;">', '</div>');
      if ($this->form_validation->run($this)){

        $this->load->library('change');
        $this->change->config($id, 'languages', $field_change);
        $this->change->set_form_name($field_form);

        $this->change->run();
        //echo "Есть изменения";
        if($this->change->change()){
           $field = $this->change->change();
           $hidden_field = array('date_update' => time(),
                                  'ip_update' => $this->input->ip_address(),
                                  );
          // если все верно записываем в базу
          if($this->language_model->update_languages('languages',$this->change->change(), $hidden_field) != false){
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


  // загрузка img flag
  function load_img_flag($id){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;

    $config = array('index1' => $this->get['menu']['lang']['link'],
                    'action' => $this->get['sub_menu']['lang']['lang']['link'],
                    'id'       => $id,
                    );
    $data['uri']['id'] = $this->control_uri->guri('language')->get_uri($config);
    $data['uri']['module'] = $this->load->module('language/language')->uri_point();

    $file_name_db = 'flag';

    if($this->input->post('load_'.$file_name_db)){
        $config['upload_path'] = "./".$this->path['img_original'];
		    $config['allowed_types'] = $this->img['allow'];
		    $config['max_size']	= $this->img['max_size'];
		    $config['max_width']  = '3800';
		    $config['max_height']  = '3200';
		    $config['change_name']  = $id."_language_".$file_name_db;
		    $config['overwrite_new_file']  = true;
		    $config['registr'] = 'low';

        $this->load->library('upload', $config);

          $file_name = $file_name_db;
          if ($this->upload->do_upload($file_name)){
		      	$data['upload_data'] = $this->upload->data();
            $photo[$file_name_db] = $data['upload_data']['file_name'];
            $hidden_field = array('date_update' => time(),
                                  'ip_update' => $this->input->ip_address(),
                                  );
            $set[$id] = array('id' => $id, 'flag' => $photo[$file_name_db]);
            if($this->language_model->update_languages('languages',$set, $hidden_field)){
              $this->load->module('language/language')->img_resize($photo[$file_name_db],true);
              //$this->img_resize($photo['file']);
              $data['resize_error'] = $this->image_lib->display_errors('<div style="color:#E50000;font-size:18px;padding:10px;">', '</div>');
            }
		      }

		    $data['error'] = $this->upload->display_errors('<div style="color:#E50000;font-size:18px;padding:10px;">', '</div>');
    }
    if($this->input->post('del_'.$file_name_db) || $this->input->post('del_'.$file_name_db.'_x')){
      $update_data[$file_name_db] = '';
      $inf = $this->language_model->get_img($id);
      foreach($inf as $items){
        $name_files[] = $items[$file_name_db];
      }
      $hidden_field = array('date_update' => time(),
                                  'ip_update' => $this->input->ip_address(),
                                  );
      $set[$id] = array('id' => $id, 'flag' => $update_data[$file_name_db]);
      if($this->language_model->update_languages('languages',$set, $hidden_field)){
        //$name_files = $this->katalog_model->get_model($id);
        if(is_array($name_files)){
          if($this->load->module('language/language')->del_file_img($name_files)){
            $data['status']['ok'] = $this->lang->line('form_language_status_save_ok');
          }else{
            $data['status']['error'] = $this->lang->line('form_language_status_del_file_error');
          }
        }
      }else{
        $data['status']['error'] = $this->lang->line('form_language_status_del_base_error');
      }
    }


    $data['arr_language'] = $this->language_model->get_img($id);
    $data['id'] = $id;
    $data['file_name_db'] = $file_name_db;
    //return(var_dump($data['photo_arr']));
		return $this->load->view('admin_edit_img_flag', $data, true);
  }

  // загрузка img arms
  function load_img_arms($id){
    $data['img'] = $this->img;
    $data['path'] = $this->path;
    $data['get'] = $this->get;

    $config = array('index1' => $this->get['menu']['lang']['link'],
                    'action' => $this->get['sub_menu']['lang']['lang']['link'],
                    'id'       => $id,
                    );
    $data['uri']['id'] = $this->control_uri->guri('language')->get_uri($config);
    $data['uri']['module'] = $this->load->module('language/language')->uri_point();

    $file_name_db = 'arms';

    if($this->input->post('load_'.$file_name_db)){
        $config['upload_path'] = "./".$this->path['img_original'];
		    $config['allowed_types'] = $this->img['allow'];
		    $config['max_size']	= $this->img['max_size'];
		    $config['max_width']  = '3800';
		    $config['max_height']  = '3200';
		    $config['change_name']  = $id."_language_".$file_name_db;
		    $config['overwrite_new_file']  = true;
		    $config['registr'] = 'low';

        $this->load->library('upload', $config);

          $file_name = $file_name_db;
          if ($this->upload->do_upload($file_name)){
		      	$data['upload_data'] = $this->upload->data();
            $photo[$file_name_db] = $data['upload_data']['file_name'];
            $hidden_field = array('date_update' => time(),
                                  'ip_update' => $this->input->ip_address(),
                                  );
            $set[$id] = array('id' => $id, 'arms' => $photo[$file_name_db]);
            if($this->language_model->update_languages('languages',$set, $hidden_field)){
              $this->load->module('language/language')->img_resize($photo[$file_name_db],true);
              //$this->img_resize($photo['file']);
              $data['resize_error'] = $this->image_lib->display_errors('<div style="color:#E50000;font-size:18px;padding:10px;">', '</div>');
            }
		      }

		    $data['error'] = $this->upload->display_errors('<div style="color:#E50000;font-size:18px;padding:10px;">', '</div>');
    }
    if($this->input->post('del_'.$file_name_db) || $this->input->post('del_'.$file_name_db.'_x')){
      $update_data[$file_name_db] = '';
      $inf = $this->language_model->get_img($id);
      foreach($inf as $items){
        $name_files[] = $items[$file_name_db];
      }
      $hidden_field = array('date_update' => time(),
                                  'ip_update' => $this->input->ip_address(),
                                  );
      $set[$id] = array('id' => $id, 'arms' => $update_data[$file_name_db]);
      if($this->language_model->update_languages('languages',$set, $hidden_field)){
        //$name_files = $this->katalog_model->get_model($id);
        if(is_array($name_files)){
          if($this->load->module('language/language')->del_file_img($name_files)){
            $data['status']['ok'] = $this->lang->line('form_language_status_save_ok');
          }else{
            $data['status']['error'] = $this->lang->line('form_language_status_del_file_error');
          }
        }
      }else{
        $data['status']['error'] = $this->lang->line('form_language_status_del_base_error');
      }
    }


    $data['arr_language'] = $this->language_model->get_img($id);
    $data['id'] = $id;
    $data['file_name_db'] = $file_name_db;
    //return(var_dump($data['photo_arr']));
		return $this->load->view('admin_edit_img_arms', $data, true);
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
          if($number != $this->input->post('id')){
            $this->form_validation->set_message('abbr_check', $this->lang->line('form_language_isset_abbr'));
			      return FALSE;
          }
			}
		}
	}

}



