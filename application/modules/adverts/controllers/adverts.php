<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Класс Adverts - основной класс для работы с объявлениями
 *
 *
 */

class Adverts extends MY_Controller {
      protected $index_request = array();
      protected $path;
      protected $img;
      protected $get;
      protected $setting;
      protected $lang_default;
      protected $lang_current;
      protected $status = array();
      protected $table;

      protected $data_cache;

    function __construct(){
        parent::__construct();
        $this->lang->load('adverts');
        $this->load->helper('language');

        $this->load_config();
        $this->load_models();
    }
    /**
    * Загрузка модели
    */
    function load_models(){
    	$this->load->model('adverts_model');
    }

	/**
	* Загрузка конфигурационных данных
	*
	*/
	function load_config(){
		$this->config->load('setting', true);
		//$this->setting = $this->config->item('setting');
		//$this->config->load('wconfig', true);
		//$this->setting = array_merge_assoc_recursive_distinct($this->config->item('setting'), $this->config->item('wconfig'));
		//$this->setting = array_merge_recursive($this->config->item('setting'), $this->config->item('wconfig'));
        $this->load->library('wconfig');
		//print_r($this->config->item('wconfig'));
		$wconfig = $this->wconfig->get('config_write', 'adverts');
		//$this->setting = $this->config->item('setting');
		$this->setting = array_merge_assoc_recursive_distinct($this->config->item('setting'), $wconfig['config']);
        //print_r($wconfig);
		//print_r($this->setting);
		$this->config->load('validation', true);
		$this->validation = $this->config->item('validation');
        //print_r($this->setting);
        //exit;
	}

	// Маршрутизация модуля
    function admin_index(){
        $buf = '';
        $this->admin_menu_view();
        // запускаем маршрут для модуля с именем группы admin (file: route), модуль, вернуть сгенерированное маршрутом
        $buf .= $this->router_modules->run('admin', 'adverts', true);

        echo $buf;
    }

    /*
     * Шаблон меню модуля по умолчанию
     */
    function admin_menu_view(){
        //$data['tree'] = $this->router_modules->tree_menu('admin');
        $data['tree'] = $this->control_uri->route->tree_menu('admin');
        $data['uri']['point'] = $this->load->module('adverts/adverts')->uri_point('admin');
        //$data['uri']['point'] = '/';

		if(is_array($data['tree'])){
			//echo "<pre>";
			//print_r($data['tree']);
			//echo "</pre>";
			echo "<div class=\"menu_module\">";
			$this->print_tree($data['tree'], 0, 0, array('uri' => $data['uri']));
			echo "</div>";
		}

        //$this->load->view('admin/menu_default_2', $data);
    }

	/**
	*
	*/
	function print_tree($tree, $current_key = 0, $level = 0, $options = false){
        if(isset($tree[$current_key])){
        	$arr = array('data' => $tree[$current_key], 'current' => $current_key, 'level' =>  $level);
        	$data = array_merge($arr, $options);
        	$this->load->view('admins/navigation/menu_default_row', $data);

	        foreach($tree[$current_key] as $key=>$value){

				 //if($key ){

						if(isset($tree[$key]) && $value['path_link'] == 1 || $value['active_link'] == 1){
	                        $l = $level + 1;
							$this->print_tree($tree, $key, $l, $options);
						}
				 //}

			}
        }
	}

    /*
     *  точка старта запроса uri
     */
    function uri_point($name = ''){
        switch ($name){
            case 'admin':
                $uri = $this->control_uri->get_full_segment('admin','mod');
                $uri .= 'adverts/';
            break;
            case 'user':
                $uri = $this->control_uri->get_full_segment('pages');
                $uri = 'adverts/';
            break;
            case 'user_main':
                $uri = '/adverts/';
            break;
            case 'admin_ajax':
                $uri = '/ajax/adverts/admin/';
            break;
            default:
                $uri = $this->control_uri->get_full_segment('pages');
                $uri .= '';
            break;
        }

        return $uri;
    }

    /**
    * 	Генерирует ссылку
    */
    function get_uri_link($name){
         $data['uri']['admin_point'] = $this->uri_point('admin');
         $data['uri']['user_point'] = $this->uri_point('user');
         $data['uri']['user_point_main'] = $this->uri_point('user_main');

         switch($name){
             case 'admin_adverts_list':
                 // Uri для списка объектов: admin
                 // (admin_adverts_list)
                 $data['uri']['object'] = $this->router_modules->generate_link('adverts_all', 'admin', 'adverts');
             break;
             case 'admin_adverts_filter':
                 // Uri для списка объектов: admin
                 // (admin_adverts_list)
                 $data['uri']['object'] = $this->router_modules->generate_link('adverts_filter', 'admin', 'adverts');
             break;
             case 'admin_adverts_order':
                 // Uri для списка объектов: admin
                 // (admin_adverts_list)
                 $data['uri']['object'] = $this->router_modules->generate_link('adverts_order', 'admin', 'adverts');
             break;
             case 'admin_adverts_pagination':
                 // Uri для списка объектов: admin
                 // (admin_adverts_list)
                 $data['uri']['object'] = $this->router_modules->generate_link('adverts_pagination', 'admin', 'adverts');
             break;
             case 'admin_adverts_add':
                 // Uri для списка объектов: admin
                 // (admin_list_object)
                 $data['uri']['object'] = $this->router_modules->generate_link('adverts_add', 'admin', 'adverts');
             break;
             case 'admin_adverts_copy':
                 // Uri для списка объектов: admin
                 // (admin_list_object)
                 $data['uri']['object'] = $this->router_modules->generate_link('adverts_copy', 'admin', 'adverts');
             break;
             case 'admin_adverts_id':
                 // Uri для списка объектов: admin
                 // (admin_list_object)
                 $data['uri']['object'] = $this->router_modules->generate_link('adverts_id', 'admin', 'adverts');
             break;
             case 'admin_adverts_setting':
                 // Uri для списка объектов: admin
                 // (admin_list_object)
                 $data['uri']['object'] = $this->router_modules->generate_link('adverts_setting', 'admin', 'adverts');
             break;
       	}
       	if(isset($data['uri']['object'])) return $data['uri']['object'];

        return false;
    }


    /**
    *  Шаблон вывода всех объявлений
    *
    */
    public function tpl_list(){    	$data['config'] = $this->setting;
        $data['uri']['point'] = $this->uri_point('admin');
        $data['uri']['adverts_list'] = $this->get_uri_link('admin_adverts_list');
        $data['uri']['adverts_id'] = $this->get_uri_link('admin_adverts_id');
        $data['uri']['adverts_copy'] = $this->get_uri_link('admin_adverts_copy');
        $data['uri']['adverts_delete'] = $this->get_uri_link('admin_adverts_id');

		$this->action_select();

		$index = $this->router_modules->get_index('adverts_filter', 'admin', 'adverts');

		$sort_by = $this->help_request->order->by($index['order'], $this->setting['admin']['allow_sorter'],'date');
		$sort_direct = $this->help_request->order->direct($index['order_direct'], $this->setting['admin']['allow_direct']);
        $res = $this->MY_data_array_row(
                                       //select
                                       'count(*) AS count',
                                       //where
                                       false
        );

		$pagination = $this->help_request->pagination->data($res['count'], $this->setting['admin']['per_page'], $index['page']);

		$limit = $pagination['per_page'].','.$pagination['prev_rows'];
        //var_dump($res);
        $data['objects'] = $this->MY_data_array(
                                       //select
                                       array('id', 'show_i', 'sort_i', 'vip', 'date_create', 'name', 'description'),
                                       //where
                                       false,
                                       //limit
                                       $limit,
                                       //order
                                       //false
                                       array($sort_by=>$sort_direct)
        );
        $data['order']['by'] = $sort_by;
        $data['order']['direct'] = $sort_direct;
        $this->tpl_admin_pagination($pagination);
        $this->load->view('admin/advert_list', $data);
    }
    /**
    *	Шаблон сортировки (административная часть)
	*	@param array - массив с параметрами сортировки
	*				   array('by' - имя поля сортировки
	*					     'direct' - направление сортировки (опцмонально)
	*					     'current_by' - текущее поле (опцмонально)
	*					     'current_direct' - текущее направление сортировки (опцмонально)
    */
	public function tpl_admin_order($order, $direct = false){
    	$data['config'] = $this->setting;
        $data['uri']['point'] = $this->uri_point('admin');
        $data['uri']['adverts_filter'] = $this->get_uri_link('admin_adverts_filter');
        $data['uri']['index_name'] = 'object';
        $data['index'] =  $this->router_modules->get_index('adverts_filter', 'admin', 'adverts');
        $data['order']['allow_direct'] = $this->setting['admin']['allow_direct'];
        $data['order']['by'] = $order;
        //print_r($data['index']);
        if($direct !== false){        	$data['order']['direct'] = $direct;
        }else{            if($data['index']['order_direct'] == $data['order']['allow_direct']['asc']){            	$data['order']['direct'] = $data['order']['allow_direct']['desc'];
            }else{            	$data['order']['direct'] = $data['order']['allow_direct']['asc'];
            }

        }


        $data['order']['current_by'] = $data['index']['order'];
        $data['order']['current_direct'] = $data['index']['order_direct'];

        $this->load->view('admins/navigation/order', $data);
	}

	/**
	*	Шаблон пагинации(административная часть)
	*   @param array - массив с параметрами пагинации
	*	@param numeric - текущая страница
	*/
	public function tpl_admin_pagination($pagination){
        $data['config'] = $this->setting;
        $data['uri']['point'] = $this->uri_point('admin');
        $data['uri']['adverts_filter'] = $this->get_uri_link('admin_adverts_filter');
        $data['uri']['objects'] = $data['uri']['point'].$data['uri']['adverts_filter'];
        $data['uri']['index_name'] = 'object';
        $data['pagination'] = $pagination;
        $data['index'] = $index = $this->router_modules->get_index('adverts_filter', 'admin', 'adverts');
        $this->load->view('admins/navigation/pagination', $data);
	}

	public function tpl_user_order($name, $direct){
	}

	public function tpl_user_pagination($pagination){

	}

    /**
    *  Шаблон вывода объявления по id
    *
    */
    public function tpl_id($id){
    	//echo 'id='.$id;
    	$data['config'] = $this->setting;
    	$data['uri']['point'] = $this->uri_point('admin');
        $data['uri']['adverts_list'] = $this->get_uri_link('admin_adverts_list');
        $data['uri']['adverts_id'] = $this->get_uri_link('admin_adverts_id');

        $this->action_edit($id);

        $data['objects'] = $this->MY_data_array_row(
                                       //select
                                       array('id', 'show_i', 'sort_i', 'vip', 'date_create', 'name', 'description'),
                                       //where
                                       array('id' => $id)
        );

        $this->load->view('admin/advert_id', $data);
    }

    /**
    *  Шаблон вывода создания нового объявления
    *
    */
    public function tpl_add($id = false){

        $data['config'] = $this->setting;
        $data['uri']['point'] = $this->uri_point('admin');
        $data['uri']['adverts_list'] = $this->get_uri_link('admin_adverts_list');
        $data['uri']['adverts_id'] = $this->get_uri_link('admin_adverts_id');
        $data['uri']['adverts_add'] = $this->get_uri_link('admin_adverts_add');
        //если указан копирующий объект
        //получаем его данные
		if(is_numeric($id)){			$object = $this->MY_data_array_row(
                                       //select
                                       array('id', 'show_i', 'sort_i', 'vip', 'name', 'description'),
                                       //where
                                       array('id' => $id)

        	);
		}
		//если данные объекта получены
		//подставляем в POST запрос
		if(isset($object)){
			$_POST['adverts_copy'] = true;			$_POST['date'] = date('m/d/Y');

			$_POST['show'] = $object['show_i'];
			$_POST['sort'] = $object['sort_i'];
			$_POST['vip'] = $object['vip'];
			$_POST['name'] = $object['name'];
			$_POST['description'] = $object['description'];
			//print_r($object);
		}

    	if($this->action_add() == 'ok'){    		return;
    	}

       	$this->load->view('admin/advert_add', $data);

    }

    /**
	* Действия на редактирование объекта
	*
	*/
	function action_edit($id){
		if( ! is_numeric($id)) return false;

		if($this->input->post('adverts_edit')){
            // проверка на валидность данных
           	$validation = array(
           	                'id' => $this->validation['id'],
                            'show' => $this->validation['show_i'],
                            'sort' => $this->validation['sort_i'],
                            'vip' => $this->validation['vip'],
                            'date' => $this->validation['date_create'],
                            'name' => $this->validation['name'],
                            'description' => $this->validation['description'],
           	);
            $this->form_validation->set_rules($validation);
            $this->form_validation->set_error_delimiters('<div style="color:#ff0000;font-size:14px;">', '</div>');
            if ($this->form_validation->run($this)){
            	$date = strtotime($this->input->post($this->validation['date_create']['field']));
            	if($this->input->post($this->validation['show_i']['field'])){
            		$show = 1;
            	}else{
            		$show = 0;
            	}
            	if($this->input->post($this->validation['vip']['field'])){
            		$vip = 1;
            	}else{
            		$vip = 0;
            	}
            	$sort = $this->input->post($this->validation['sort_i']['field']);
            	if(empty($sort) || ! is_numeric($sort)){
            		$sort = 10;
            	}
            	$name = $this->input->post($this->validation['name']['field']);
            	$description = $this->input->post($this->validation['description']['field']);

				$set = array(
							'show_i' => $show,
							'sort_i' => $sort,
							'vip' => $vip,
                            'name' => $name,
                            'description' => $description,
                            'date_create' => $date,
                            'date_update' => time(),
                            'ip_update' => $_SERVER['REMOTE_ADDR'],
				);
				$where = array('id'=> $id);

				$data['objects'] = array($id);
				if($this->MY_update($set, $where)){					$this->load->view('admins/status/update_ok', $data);
				}else{					$this->load->view('admins/status/update_error', $data);
				}
			}
		};

	}

	/**
	*  Действия при добавлении
	*/
    function action_add(){    	$data['config'] = $this->setting;
        $data['uri']['point'] = $this->uri_point('admin');
        $data['uri']['adverts_list'] = $this->get_uri_link('admin_adverts_list');
        $data['uri']['adverts_id'] = $this->get_uri_link('admin_adverts_id');
        $data['uri']['adverts_add'] = $this->get_uri_link('admin_adverts_add');

     	$validation = array(
     	                   'date_create' => $this->validation['date_create'],
     	                   'show_i' => $this->validation['show_i'],
     	                   'sort_i' => $this->validation['sort_i'],
     	                   'name' => $this->validation['name'],
     	                   'description' => $this->validation['description'],
     	                   'vip' => $this->validation['vip'],
     	);

		//проверка при копировании объекта
     	if($this->input->post('adverts_copy')){     		$this->form_validation->set_rules($validation);
            $this->form_validation->set_error_delimiters('<div style="color:#ff0000;font-size:14px;">', '</div>');
            if ($this->form_validation->run($this)){
            }
     	}

     	if($this->input->post('add_adverts')){
            // проверка на валидность данных

            $this->form_validation->set_rules($validation);
            $this->form_validation->set_error_delimiters('<div style="color:#ff0000;font-size:14px;">', '</div>');
            if ($this->form_validation->run($this)){
            	$date = strtotime($this->input->post('date'));
            	if($this->input->post('show')){
            		$show = 1;
            	}else{
            		$show = 0;
            	}
            	$sort = $this->input->post('sort');
            	if(!empty($sort) || ! is_numeric($sort)){
            		$sort = 10;
            	}
            	$name = $this->input->post('name');
            	$description = $this->input->post('description');

				$set = array(
							'show_i' => $show,
							'sort_i' => $sort,
                            'name' => $name,
                            'description' => $description,
                            'date_create' => $date,
                            'date_update' => time(),
                            'ip_create' => $_SERVER['REMOTE_ADDR'],
                            'ip_update' => $_SERVER['REMOTE_ADDR'],
				);
            	$this->MY_insert($set);
            	$data['insert_id'] = $this->db->insert_id();

            	$data['uri']['object_id'] = $data['uri']['point'].uri_replace($data['uri']['adverts_id'],array($data['insert_id']),'object');
            	$data['uri']['object_add'] = $data['uri']['point'].$data['uri']['adverts_add'];
            	$this->load->view('admins/status/insert_add_ok', $data);
            	return 'ok';

       		}else{
       			$this->load->view('admins/status/insert_add_error', $data);
       			//echo  $data['errors'] = validation_errors();
       		}
       	}
    }

    /**
    * Действия при получении различных параметров POST
    *
    *
    */
	function action_select(){

        if($this->input->post('action_adverts')){
        	if($this->input->post('checkbox_array')){
            	switch($this->input->post('action_select'))
            	{
            		case 'on':
            			$this->MY_set_status('action', 'info', 'Выбрано действие на включение');
            			$this->action_on();
            		break;
            		case 'off':
            		    $this->MY_set_status('action', 'info', 'Выбрано действие на вЫключение');
            		    $this->action_off();
            		break;
            		case 'vip_on':
            			$this->MY_set_status('action', 'info', 'Выбрано действие на включение VIP');
            			$this->action_vip_on();
            		break;
            		case 'vip_off':
            		    $this->MY_set_status('action', 'info', 'Выбрано действие на вЫключение VIP');
            		    $this->action_vip_off();
            		break;
            		case 'delete':
            		    $this->MY_set_status('action', 'info', 'Выбрано действие на удаление');
            		    $this->action_delete();
            		break;
            		default:
            		    $this->MY_set_status('action', 'info', 'НЕ выбрано никакого действия');
            		break;
            	}
        	}else{
        		$this->MY_set_status('action', 'error', 'Не выбрано ни одного объекта');

        	}

        }
        //действие при создании копии объекта
        if($this->input->post('action_copy') || $this->input->post('action_copy.x') || $this->input->post('action_copy_x')){        	print_r($_POST);
        	//exit;
        }
	}

	/**
	* Действия на включение выбранных объектов
	*
	*/
	function action_on(){
		$ids = $this->input->post('checkbox_array');
		if(is_array($ids)){
			foreach($ids as $id){
				if(is_numeric($id)){
     				$id_where[] = $id;
				}
			}
			$where['id'] = ($id_where) ? $id_where : false;
            $set = array('show_i' => 1,
                         'date_update' => time(),
                         'ip_update' => $this->input->ip_address(),
            );

			$data['objects'] = $id_where;
			if($this->MY_update($set, $where)){            	$this->load->view('admins/status/update_ok', $data);
			}else{				$this->load->view('admins/status/update_error', $data);
			}

		}
	}

	/**
	* Действия на вЫключение выбранных объектов
	*
	*/
	function action_off(){
		$ids = $this->input->post('checkbox_array');
		if(is_array($ids)){
			foreach($ids as $id){
				if(is_numeric($id)){
     				$id_where[] = $id;
				}
			}
			$where['id'] = ($id_where) ? $id_where : false;
            $set = array('show_i' => 0,
                         'date_update' => time(),
                         'ip_update' => $this->input->ip_address(),
            );

			$data['objects'] = $id_where;
			if($this->MY_update($set, $where)){
            	$this->load->view('admins/status/update_ok', $data);
			}else{
				$this->load->view('admins/status/update_error', $data);
			}

		}
	}

	/**
	* Действия на включение в VIP выбранных объектов
	*
	*/
	function action_vip_on(){
		$ids = $this->input->post('checkbox_array');
		if(is_array($ids)){
			foreach($ids as $id){
				if(is_numeric($id)){
     				$id_where[] = $id;
				}
			}
			$where['id'] = ($id_where) ? $id_where : false;
            $set = array('vip' => 1,
                         'date_update' => time(),
                         'ip_update' => $this->input->ip_address(),
            );

			$data['objects'] = $id_where;
			if($this->MY_update($set, $where)){
            	$this->load->view('admins/status/update_ok', $data);
			}else{
				$this->load->view('admins/status/update_error', $data);
			}

		}
	}

	/**
	* Действия на вЫключение из VIP выбранных объектов
	*
	*/
	function action_vip_off(){
		$ids = $this->input->post('checkbox_array');
		if(is_array($ids)){
			foreach($ids as $id){
				if(is_numeric($id)){
     				$id_where[] = $id;
				}
			}
			$where['id'] = ($id_where) ? $id_where : false;
            $set = array('vip' => 0,
                         'date_update' => time(),
                         'ip_update' => $this->input->ip_address(),
            );

			$data['objects'] = $id_where;
			if($this->MY_update($set, $where)){
            	$this->load->view('admins/status/update_ok', $data);
			}else{
				$this->load->view('admins/status/update_error', $data);
			}

		}
	}

	/**
	* Действия на удаление выбранных объектов
	*
	*/
	function action_delete(){
		$ids = $this->input->post('checkbox_array');
		if(is_array($ids)){
			foreach($ids as $id){
				if(is_numeric($id)){
     				$id_where[] = $id;
				}
			}
			$where['id'] = ($id_where) ? $id_where : false;

			$data['objects'] = $id_where;
			if($this->MY_delete($where)){
            	$this->load->view('admins/status/delete_ok', $data);
			}else{
				$this->load->view('admins/status/delete_error', $data);
			}

		}
	}

	/**
	*  Вывод объявлений на главной странице
	*
	*/
	function tpl_user_main(){        $data['objects'] = $this->MY_data_array(
 		                               //select
 		                               array(),
 		                               //where
 		                               array('show_i' => 1),
 		                               //limit
 		                               false,
 		                               //order_by
 		                               array('sort_i' => 'asc')
 		);

 		$this->load->view('user/main', $data);
	}

	/**
	*  Вывод объявлений на в модальном окне
	*
	*/
	function tpl_user_modal(){

 		$data['objects'] = $this->MY_data_array(
 		                               //select
 		                               array(),
 		                               //where
 		                               array('show_i' => 1),
 		                               //limit
 		                               false,
 		                               //order_by
 		                               array('vip' => 'desc', 'sort_i' => 'asc')
 		);

		$data['config'] = $this->setting;
 		$this->load->view('user/modal', $data);

	}

	/**
	*  Вывод кнопки на сайте
	*
	*/
	function tpl_user_switch(){

		$data['config'] = $this->setting;
 		$this->load->view('user/switch', $data);
	}

	function test($a, $b, $c){		echo 'a='.$a.'<br>';
		echo 'b='.$b.'<br>';
        echo 'c='.$c.'<br>';
	}
}