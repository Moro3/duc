<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('adverts')){
  include_once ('adverts.php');
}

/*
 * Класс Adverts_setting
 *
 */

class Adverts_setting extends Adverts {

    function __construct(){
        parent::__construct();
        $this->table = 'adverts';
    }

	/**
	*	вывод шаблона настройки параметров модуля
	*
	*/
	public function tpl_setting(){    	$this->action_save();
    	$data['config'] = $this->setting;
    	$data['uri']['point'] = $this->uri_point('admin');
        $data['uri']['adverts_setting'] = $this->get_uri_link('admin_adverts_setting');

        //$this->assets->mixed->load('sett');
        //$this->assets->mixed->package('sett', true);
    	$this->load->view('admin/setting', $data);
	}

	function action_save(){    	if($this->input->post('adverts_setting')){    		//print_r($this->input->post());


            $validation = array(
              				'uri' => array(
              							'field' => 'uri',
                     					'label' => lang('adverts_uri'),
                                        'rules' => 'trim|alpha_numeric|required|max_length[30]',
              				),
              				'path_images' => array(
              							'field' => 'path_images',
                     					'label' => lang('adverts_path_images'),
                                        'rules' => 'trim|alpha_max|required|max_length[30]',
              				),
              				'admin_per_page' => array(
              							'field' => 'admin_per_page',
                     					'label' => lang('adverts_per_page'),
                                        'rules' => 'trim|numeric|required|max_length[30]',
              				),
              				'admin_allow_per_page' => array(
              							'field' => 'admin_allow_per_page',
                     					'label' => lang('adverts_allow_per_page'),
                                        'rules' => 'trim|alpha_max|required|max_length[300]|callback_check_allow_per_page',
              				),
              				'user_per_page' => array(
              							'field' => 'user_per_page',
                     					'label' => lang('adverts_per_page'),
                                        'rules' => 'trim|alpha_numeric|required|max_length[30]',
              				),
              				'user_allow_per_page' => array(
              							'field' => 'user_allow_per_page',
                     					'label' => lang('adverts_allow_per_page'),
                                        'rules' => 'trim|alpha_max|required|max_length[30]|callback_check_allow_per_page',
              				),
              				'user_modal_time' => array(
              							'field' => 'modal_time',
                     					'label' => lang('adverts_modal_time'),
                                        'rules' => 'trim|numeric|required|max_length[1000]',
              				),
              				'user_modal_speed' => array(
              							'field' => 'modal_speed',
                     					'label' => lang('adverts_modal_speed'),
                                        'rules' => 'trim|numeric|required|max_length[3]|min_length[1]',
              				),
              				'user_modal_active' => array(
              							'field' => 'modal_active',
                     					'label' => lang('adverts_modal_active'),
                                        'rules' => 'numeric|max_length[1]',
              				),
              				'user_modal_switch' => array(
              							'field' => 'modal_switch',
                     					'label' => lang('adverts_modal_switch'),
                                        'rules' => 'numeric|max_length[1]',
              				),
            );

            $this->form_validation->set_rules($validation);
            //$this->form_validation->set_error_delimiters('<div style="color:#ff0000;font-size:14px;">', '</div>');
            if ($this->form_validation->run($this)){            	$this->wconfig->set('config->uri',$this->input->post('uri'));
            	$this->wconfig->set('config->admin->per_page',$this->input->post('admin_per_page'));
            	$this->wconfig->set('config->user->per_page',$this->input->post('user_per_page'));
            	$this->wconfig->set('config->admin->allow_per_page',$this->string_to_array_allow_per_page($this->input->post('admin_allow_per_page')));
            	$this->wconfig->set('config->user->allow_per_page',$this->string_to_array_allow_per_page($this->input->post('user_allow_per_page')));
            	$this->wconfig->set('config->path->images',$this->input->post('path_images'));
            	$this->wconfig->set('config->user->modal->time_on',$this->input->post('modal_time'));
            	$this->wconfig->set('config->user->modal->speed',$this->input->post('modal_speed'));


            	$this->wconfig->set('config->user->modal->active',$this->input->post('modal_active'));
            	$this->wconfig->set('config->user->modal->switch',$this->input->post('modal_switch'));
            	$this->wconfig->set('config->user->modal->switch_default',$this->input->post('modal_switch_default'));
            	if($this->wconfig->update('config_write','adverts')){            		$this->load->view('admins/status/update_ok', false);
            		//echo 'Информация сохранена';
            	}else{            		//echo 'Не удалось записать информацию';
            		$this->load->view('admins/status/update_error', false);
            	}
            }
            //$this->wconfig->convert( 'array', 'array_comments', 'config_write');

            //print_r($this->wconfig->logs());
            //print_r($this->wconfig->error());
    	}
	}
    /**
    *	Проверяет допустимые символы при вводе допустимых чисел серез знак ","
    *
    */
	public function check_allow_per_page($allow_per_page){    	if($arr = $this->string_to_array_allow_per_page($allow_per_page)){
    		foreach($arr as $key=>$value){    			if(!is_numeric($value)){    				$this->form_validation->set_message('check_allow_per_page', 'Поле "%s" должно содержать только цифры через знак ","');
    				return false;
    			}
    		}
    		return true;
    	}
    	$this->form_validation->set_message('check_allow_per_page', 'Поле "%s" должно содержать несколько значений через знак ","');
    	return false;
	}

	/**
	*	Возвращает массив преобразованный из строки разделенной ","
	*   @param string - строка из номеров разделенные знаком ","
	*   @return array - массив
	*/
	public function string_to_array_allow_per_page($allow_per_page){		if(strpos($allow_per_page,',')){			$arr = explode(',', $allow_per_page);
			foreach($arr as $key=>$value){				$res[$key] = trim($value);
			}
			return $res;
		}
		return false;
	}
}
