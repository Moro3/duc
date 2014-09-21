<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Класс Duc - основной класс для работы с недвижимостью
 *
 *
 */

class Duc extends MY_Controller {
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
        $this->load->helper(array('form', 'url', 'assets'));
        $this->load->library('form_validation');
        $this->load->library('control_uri');
        $this->load->library('pagination');


        $this->lang->load('duc');
        $this->load->helper('language');
        $this->load->helper('text');
        $this->load->helper('directory');

        //$this->config->load('index_request', TRUE);
        //$this->index_request = $this->config->item('index_request', 'index_request');

        $this->config->load('db', TRUE);

        //$this->config->load('menu', TRUE);
        //$this->get = $this->config->item('menu', 'menu');
        $this->config->load('setting', TRUE);
        //print_r($this->config->item('setting'));
        $this->setting = $this->config->item('setting');


        if(is_array($this->index_request)){
            foreach($this->index_request as $key=>$value){
                $this->control_uri->guri($key)->set_index($value);
            }
        }
        //$this->load->library('datetimepicker');
        $this->load_config();
        $this->load_models();

        $this->table = 'duc_groups';
        $this->MY_table = 'duc_groups';
        $this->MY_module = 'duc';

    }

    /*
     * возвращает значения индексов запросов
     */
    function get_index($name){
        if(isset($this->index_request[$name]) && is_array($this->index_request[$name])){
            foreach($this->index_request[$name] as $key=>$value){
                $data[$key] = $this->control_uri->guri($name)->value_segment($key);
            }
        }
        if(isset($data)) return $data;

        return false;
    }

    // Маршрутизация модуля
    function admin_index(){
        $buf = '';

        //$this->assets->load_style('pages.css', 'pages');
        //$this->assets->load_script('jquery/jquery-ui/plugin/jquery-ui-timepicker-addon.js', false);
        //echo "<pre>";
        //print_r ($this->router_modules->tree_menu('admin'));
        //echo "</pre>";
        $this->admin_menu_view();
//        echo "!!";
        //$this->grid_render();
//        echo "<script>";
//        echo $this->grid->loader->render('grid_news');
//        echo "</script>";

        // запускаем маршрут для модуля с именем группы admin (file: route), модуля duc, вернуть сгенерированное маршрутом
        $buf .= $this->router_modules->run('admin', 'duc', true);

        echo $buf;
        //exit();
        //return $buf;
    }

    function user_index(){
        //echo '@';
        //exit;
        $this->assets->style->load('list_objects_public');
        $this->assets->style->load('id_objects_public');

        $this->assets->img->load('fon_notice.gif');
        $buf = '';
        $buf .= $this->router_modules->run('user', 'duc', true);

        echo $buf;
    }

    function load_models(){
    	$this->load->model('duc_activities_model');
    	$this->load->model('duc_sections_model');
    	$this->load->model('duc_concertmasters_model');
    	$this->load->model('duc_dop_teachers_model');
    	$this->load->model('duc_departments_model');
    	$this->load->model('duc_directions_model');
    	$this->load->model('duc_groups_model');
    	$this->load->model('duc_photos_model');
    	$this->load->model('duc_qualifications_model');
    	$this->load->model('duc_ranks_model');
    	$this->load->model('duc_teachers_model');
        $this->load->model('duc_addresses_model');
        $this->load->model('duc_durations_model');
        $this->load->model('duc_schedules_model');
        $this->load->model('duc_numgroups_model');
    }

	function load_config(){
		$this->config->load('setting', true);
		$this->setting = $this->config->item('setting');

		$this->setting = Modules::run('duc/duc_settings/getConfig');

  		//print_r($this->wconfig->logs());
  		print_r($this->wconfig->error());
        //print_r($this->setting);
        //exit;
	}

    function index(){

    }

    /*
     * Шаблон меню модуля по умолчанию
     */
    function admin_menu_view(){
        //$data['tree'] = $this->router_modules->tree_menu('admin');
        $data['tree'] = $this->control_uri->route->tree_menu('admin');
        $data['uri']['point'] = $this->load->module('duc/duc')->uri_point('admin');
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


	function print_tree($tree, $current_key = 0, $level = 0, $options = false){
        if(isset($tree[$current_key])){
        	$arr = array('data' => $tree[$current_key], 'current' => $current_key, 'level' =>  $level);
        	$data = array_merge($arr, $options);
        	$this->load->view('admin/menu_default_row', $data);

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
                $uri .= 'duc/';
            break;
            case 'user':
                $uri = $this->control_uri->get_full_segment('pages');
                $uri = 'duc/';
            break;
            case 'user_main':
                $uri = '/duc/';
            break;
            case 'admin_ajax':
                $uri = '/ajax/duc/admin/';
            break;
            default:
                $uri = $this->control_uri->get_full_segment('pages');
                $uri .= '';
            break;
        }

        return $uri;
    }

	function grid_admin_object(){
        $this->grid_render();
	}

	function grid(){
        $this->grid_params();
        // header("Content-type: text/json;charset=utf-8");

        if(isset($_POST['id']) && isset($_POST['oper'])){
            if($this->input->post('oper') == 'edit'){
            	$this->grid->loader->oper($this->table, 'edit');
            }elseif($this->input->post('oper') == 'del'){
            	$this->grid->loader->oper($this->table, 'del');
            }elseif($this->input->post('oper') == 'add'){
            	$this->grid->loader->oper($this->table, 'add');
            }elseif($this->input->post('oper') == 'active'){
            	$this->grid->loader->oper($this->table, 'active');
            }elseif($this->input->post('oper') == 'deactive'){
            	$this->grid->loader->oper($this->table, 'deactive');
            }elseif($this->input->post('oper') == 'sorter'){
            	$this->grid->loader->oper($this->table, 'sorter');
            }
        }else{
        	$this->grid->loader->output($this->table);
        }
    }

    function grid_render(){

        //$this->load->view('admin/tpl2/script');
        //$this->load->view('admin/tpl2/style');
        //$this->admin_menu_view();
        $this->grid_params();

        //print_r($this->grid->loader->getInputData());
//-------------- Подключаем нужный визуальный редактор (WYSIWYG)

		//$this->load->view('grid/load_FCKeditor');
		$this->load->view('grid/load_WYSIWYG', array('name' => 'FCKeditor'));



//-------------- Загрузка таблицы jQGrid
   		
        echo "\r\n<script>";
        	echo $this->grid->loader->render($this->table);
        echo "</script>\r\n";
        
		$this->load->view('grid/formatter/multiselect',
								array('selector' => 'multiselect',
                                      'sortable' => true,
									  'searchable' => true
								)
		);
        $this->load->view('grid/FCKeditor');
        //$this->load->view('grid/sorter/sortrows');
        $this->load->view('grid/list_position');

    }

    function grid_params(){
        $path_to_tables = Modules::current_path().'models/grid/';
        $this->grid->loader->set("grid_path", $path_to_tables);
        //$this->grid->loader->set("images_path", assets_uploads().'images/lands/objects/');
        //$this->grid->loader->set("path_root", FCPATH);
        $this->grid->loader->set("config", $this->setting);
        $this->grid->loader->set('debug_output', true);
    }


    function event_object($event, $id_name_object, $id_object){
    	switch($event){
    		case 'edit':

    			break;
    		case 'add':

    			break;
    		case 'del':
    			Modules::run('duc/duc_photos/delete_images_object_id', $id);
    			Modules::run('duc/duc_objects_communications/delete_objects',$id);
    			break;

    		default:

    	}
    }


    /**
    * 	Генерирует ссылку
    */
    function get_uri_link($name){
         $data['uri']['admin_point'] = $this->uri_point('admin');
         $data['uri']['user_point'] = $this->uri_point('user');
         $data['uri']['user_point_main'] = $this->uri_point('user_main');

         switch($name){
             case 'admin_list_object':
                 // Uri для списка объектов: admin
                 // (admin_list_object)
                 $index = $this->load->module('lands/lands')->get_index('object');
                 $config = array('menu' => $index['menu'],'action' => $index['action'], 'page' => '?', 'per_page' => $index['per_page'], 'lang' => $index['lang'], 'order' => $index['order'], 'order_direct' => $index['order_direct'], 'filter' => $index['filter']);
                 $data['uri']['object'] = $this->control_uri->guri('object')->get_uri($config);
                 $uri = $data['uri']['admin_point'].$data['uri']['object'];
             break;
             case 'user_list_object_main':
                 // Uri для списка объектов на главной: user
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('objects_category', 'user', 'duc');
             break;
             case 'user_groups_id':
                 // Uri для списка объектов на главной: user
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('groups_id', 'user', 'duc');
             break;
             case 'user_sсhedules':
                 // Uri для списка расписания: user
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('schedules_list', 'user', 'duc');
             break;
             case 'user_sсhedules_group':
                 // Uri для расписания по группам: user
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('schedules_group', 'user', 'duc');
             break;
             case 'user_sсhedules_groupname':
                 // Uri для расписания по группам с учетом групп с таким же названием: user
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('schedules_groupname', 'user', 'duc');
             break;
             case 'user_sсhedules_teacher':
                 // Uri для расписания по педагогам: user
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('schedules_teacher', 'user', 'duc');
             break;
             case 'user_sсhedules_department':
                 // Uri для расписания по отделам: user
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('schedules_department', 'user', 'duc');
             break;
             case 'user_groups_list':
                 // Uri для списка объектов на главной: user
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('groups_list', 'user', 'duc');
             break;
             case 'user_teachers_id':
                 // Uri для списка объектов на главной: user
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('teachers_id', 'user', 'duc');
             break;
             case 'user_teachers_list':
                 // Uri для списка объектов на главной: user
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('teachers_list', 'user', 'duc');
             break;
             case 'admin_ajax_teachers_delete_foto':
                 // Uri для удаления фото педагога: admin
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('teachers_foto_delete', 'ajax', 'duc');
             break;
             case 'user_mskobr':
                 // Uri для списка объектов для сайта mskobr: user
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('mskobr', 'user', 'duc');
             break;
             case 'user_mskobr_department':
                 // Uri для списка объектов для сайта mskobr: user
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('mskobr_department_paid', 'user', 'duc');
             break;
             case 'user_mskobr_direction':
                 // Uri для списка объектов для сайта mskobr: user
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('mskobr_direction_paid', 'user', 'duc');
             break;
       	}
       	if(isset($data['uri']['object'])) return $data['uri']['object'];

        return false;
    }

    	/**
	* создает папки для ресайзов изображений если их нет
	*
	*/
	function resizeDirCreate($name){
    	$config = Modules::run('duc/duc_settings/get_config_resize', $name);

    	if($config == false){
    		$this->MY_error(__CLASS__, __METHOD__, 'error', 'Не указаны настройки для папки оригинальных ресайзов');
    		return false;
    	}

    	$dir = $config['path'].$config['dir'];

		if( ! create_dir($dir)){
			$this->MY_error(__CLASS__, __METHOD__, 'fatal', 'Не удалось создать папку "'.$dir.'" для хранения изображений ');
   			return false;
		}

		$resize = Modules::run('duc/duc_settings/get_param_resize', $name);
		//print_r($resize);
		//exit;
		if(is_array($resize)){
			foreach($resize as $name=>$item){


						if(!empty($item['dir'])){
		                	$dir = $item['path'].$item['dir'];
							if( ! create_dir($dir)){
								$this->MY_error(__CLASS__, __METHOD__, 'fatal', 'Не удалось создать папку "'.$dir.'" для хранения изображений ');
					   			return false;
							}
						}else{
							$this->MY_error(__CLASS__, __METHOD__, 'error', 'Не указаны параметры для папки ресайза '.$name);
							return false;
						}


			}
		};
        return true;

	}

	/**
    * Ресайз файлов изображений
    *	@param string $name_resize - имя ресайза (если не задано, удаляются из всех ресайзов)
    *	@param string $file - имя файла с изображением (если не задано, удаляются все файлы из ресайза)
    *
    */
	function resize($name_group, $name_resize = false, $file = false){
		$resize_config = Modules::run('duc/duc_settings/get_config_resize', $name_group);
		$path_original = $resize_config['path'].$resize_config['dir'];
		$resize = Modules::run('duc/duc_settings/get_param_resize', $name_group);
		//print_r($resize);
		//exit;
		if(is_array($resize)){
			if(!empty($name_resize)){
				if(!isset($resize[$name_resize])) return false;
				$item = $resize[$name_resize];
				if(!empty($file)){
					if(!empty($item['dir'])){
		                	$dir = $item['path'].$item['dir'];

							$config['image_library'] = 'gd2';
							//$config['image_library'] = 'imagemagick';
							//$config['library_path'] = 'Z:\usr\local\ImageMagick-6.8.4-Q16';

							$config['source_image'] = $path_original.'/'.$file;
							$config['new_image'] = $dir.'/'.$file;
							//$config['create_thumb'] = TRUE;
							$config['maintain_ratio'] = (isset($item['maintain_ratio'])) ? $item['maintain_ratio'] : TRUE;
							$config['master_dim'] = (isset($item['master_dim'])) ? $item['master_dim'] : 'auto';

							$config['width'] = $item['x'];
							$config['height'] = $item['y'];

                            $config['x_axis'] = (isset($item['x_axis'])) ? $item['x_axis'] : '';
							$config['y_axis'] = (isset($item['y_axis'])) ? $item['y_axis'] : '';

							$this->load->library('image_lib');
							$this->image_lib->initialize($config);
                            if(isset($item['type']) && $item['type'] !== 'resize'){
                            	if($item['type'] == 'crop' || $item['type'] == 'c'){
                            		$config['x_axis'] = $item['x'];
									$config['y_axis'] = $item['y'];
                            		if( ! $this->image_lib->crop()){
										$errors = $this->image_lib->display_errors();
										$this->MY_error(__CLASS__, __METHOD__, 'error', 'Не удалось произвести ресайз для '.$name);
										$this->MY_error(__CLASS__, __METHOD__, 'error', $errors);
										//exit($errors);
										return false;
									}
                            	}else{
                            		$this->MY_error(__CLASS__, __METHOD__, 'error', 'Не распознан тип ресайза ('.$item['type'].') для '.$name);
                            	}
                            }else{
								if( ! $this->image_lib->resize()){
									$errors = $this->image_lib->display_errors();
									$this->MY_error(__CLASS__, __METHOD__, 'error', 'Не удалось произвести ресайз для '.$name);
									$this->MY_error(__CLASS__, __METHOD__, 'error', $errors);
									//exit($errors);
									return false;
								}
							}
							$this->image_lib->clear();
				            //print_r($config);

						}else{
							$this->MY_error(__CLASS__, __METHOD__, 'error', 'Не указаны параметры для папки ресайза '.$name);
							return false;
						}
				}else{
					$map = directory_map($path_original, 1);
					if(is_array($map) && count($map) > 0){
						foreach($map as $num=>$file){
							if(is_numeric($num) && !empty($file)){
								$this->resize($name_group, $name_resize, $file);
							}
						}
					}
				}

			}else{
				foreach($resize as $name=>$items){
					$this->resize($name_group, $name, $file);
				}
			}
		}
		return true;
	}

	/**
    * Ресайз основного изображения
    *	@param string $name_group - имя группы конфига
    *	@param string $file - имя файла с изображением (если не задано, проверяются все файлы)
    *
    */
	function resizeLoading($name_group, $file = false){
		$resize_config = Modules::run('duc/duc_settings/get_config_resize', $name_group);
		//$path_original = $resize_config['path'].$resize_config['dir'];
		if(!empty($resize_config['dir'])){
			$dir = $resize_config['path'].$resize_config['dir'];
		}else{
			$this->MY_error(__CLASS__, __METHOD__, 'error', 'Не указаны параметры для папки ресайза '.$name);
			return false;
		}
		//print_r($resize);
		//exit;
				if(!empty($file)){
					if(is_file($dir.'/'.$file)){
						if(!empty($resize_config['x']) && !empty($resize_config['y'])){
							$size = getimagesize($dir.'/'.$file);

							if($size[0] > $resize_config['x'] || $size[1] > $resize_config['y']){
									$config['image_library'] = 'gd2';
									//$config['image_library'] = 'imagemagick';
									//$config['library_path'] = 'Z:\usr\local\ImageMagick-6.8.4-Q16';


									$config['source_image'] = $dir.'/'.$file;
									//$config['new_image'] = $dir.'/'.$file;
									//$config['create_thumb'] = TRUE;
									//$config['maintain_ratio'] = (isset($resize_config['maintain_ratio'])) ? $resize_config['maintain_ratio'] : TRUE;
									$config['width'] = $resize_config['x'];
									$config['height'] = $resize_config['y'];
                                    //var_dump($config);
                                    //exit;
									$this->load->library('image_lib');
									$this->image_lib->initialize($config);

									if( ! $this->image_lib->resize()){
										$errors = $this->image_lib->display_errors();
										$this->MY_error(__CLASS__, __METHOD__, 'error', 'Не удалось произвести ресайз загруженого изображения '.$file);
										$this->MY_error(__CLASS__, __METHOD__, 'error', $errors);
										//exit($errors);
										return false;
									}
									$this->image_lib->clear();
						            //print_r($config);
		                    }
	                    }
                    }else{
                    	$this->MY_error(__CLASS__, __METHOD__, 'error', 'Не найдено оригинальное изображение в '.$dir.'/'.$file);
                    }
				}else{
					$map = directory_map($dir, 1);
					if(is_array($map) && count($map) > 0){
						foreach($map as $num=>$file){
							if(is_numeric($num) && !empty($file)){
								$this->resizeLoading($name_group, $file);
							}
						}
					}
				}



		return true;
	}

	/**
    *  Удаление файлов изображений из ресайзов
    *	@param string $name_resize - имя ресайза (если не задано, удаляются из всех ресайзов)
    *	@param string $file - имя файла с изображением (если не задано, удаляются все файлы из ресайза)
    *
    */
	function resizeDelete($name_group, $name_resize = false, $file = false){
		$resize_config = Modules::run('duc/duc_settings/get_config_resize', $name_group);
		$path_original = $resize_config['path'].$resize_config['dir'];
		$resize = Modules::run('duc/duc_settings/get_param_resize', $name_group);
		//print_r($resize);
		//exit;
		if(is_array($resize)){
			if(!empty($name_resize)){
				if(!isset($resize[$name_resize])) return false;
				$item = $resize[$name_resize];
				if(!empty($item['dir']) && !empty($item['path'])){
					$dir = $item['path'].$item['dir'];
					if(!empty($file)){
			            if(unlink($this->setting['path']['root'].$dir.DIRECTORY_SEPARATOR.$file)){
			            	Modules::run('duc/duc_events/onResizeDelete', $name_group, $name_resize, $file);
			            }else{
			            	$this->MY_error(__CLASS__, __METHOD__, 'error', 'Не удалось удалить файл "'.$file.'" из ресайза "'.$name_resize.'"');
							return false;
			            }
			        }else{
			        	$map = directory_map($dir, 1);
						if(is_array($map) && count($map) > 0){
							foreach($map as $num=>$file){
								if(is_numeric($num) && !empty($file)){
									$this->resizeDelete($name_group, $name_resize, $file);
								}
							}
						}
			        }
		        }
		    }else{
		    	foreach($resize as $name=>$items){
					$this->resizeDelete($name_group, $name, $file);
				}
		    }
		}
		return true;
	}

    /**
    *  Удаление папки ресайза изображений
    *	@param string $name_resize - имя ресайза (если не задано, удаляются все ресайзы)
    */
	function resizeDirDelete($name_group, $name_resize = false){
    	$resize_config = Modules::run('duc/duc_settings/get_config_resize', $name_group);
		$path_original = $resize_config['path'].$resize_config['dir'];
		$resize = Modules::run('duc/duc_settings/get_param_resize', $name_group);
    	if(!empty($name_resize)){
			if(isset($resize[$name_resize])){
				$item = $resize[$name_resize];
				if(!empty($item['path']) && !empty($item['dir'])){
					$dir = $item['path'].$item['dir'];
					if($this->resizeDelete($name_group, $name_resize)){
						rmdir($this->setting['path']['root'].$dir);
					}
				}
			}
    	}else{
    		foreach($resize as $name=>$items){
				$this->resizeDirDelete($name_group, $name);
			}
    	}
    	return true;
	}

}
