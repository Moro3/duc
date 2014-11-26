<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mods extends MY_Controller {

	function __construct()
	{
		parent::__construct();

	    $this->load->library('control_uri');
	    $this->load->library('scheme');

        $this->load->helper('language');

	    $this->lang->load('mods');

	    //$this->load->library('datetimepicker');
        $this->load_config();
        $this->load_models();

        $this->table = 'grid_mods';
        $this->MY_table = 'mods';

	}

	function index()
	{
    	echo "???????";
	}

	function load_config(){
		$this->config->load('setting', true);
		$this->setting = $this->config->item('setting');
	}

	function load_models(){
    	$this->load->model('mods_model');
    	$this->load->model('mods_type_model');
    	$this->load->model('mods_tpl_model');
    	$this->load->model('mods_route_model');
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
        $buf .= $this->router_modules->run('admin', 'mods', true);

        echo $buf;
        //exit();
        //return $buf;
    }

    /*
     * Шаблон меню модуля по умолчанию
     */
    function admin_menu_view(){
        $data['tree'] = $this->control_uri->route->tree_menu('admin');
        $data['uri']['point'] = $this->load->module('mods/mods')->uri_point('admin');

		if(is_array($data['tree'])){
			echo "<div class=\"menu_module\">";
			$this->print_tree($data['tree'], 0, 0, array('uri' => $data['uri']));
			echo "</div>";
		}
    }


	function print_tree($tree, $current_key = 0, $level = 0, $options = false){
        if(isset($tree[$current_key])){
        	$arr = array('data' => $tree[$current_key], 'current' => $current_key, 'level' =>  $level);
        	$data = array_merge($arr, $options);
        	$this->load->view('admins/navigation/menu_default_row', $data);

	        foreach($tree[$current_key] as $key=>$value){
						if(isset($tree[$key]) && $value['path_link'] == 1 || $value['active_link'] == 1){
	                        $l = $level + 1;
							$this->print_tree($tree, $key, $l, $options);
						}
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
                $uri .= 'mods/';
            break;
            case 'user':
                $uri = $this->control_uri->get_full_segment('pages');
                $uri = 'mods/';
            break;
            case 'user_main':
                $uri = '/mods/';
            break;
            case 'admin_ajax':
                $uri = '/ajax/mods/admin/';
            break;
            case 'admin_ajax_mods_delete_foto':
                 // Uri для удаления фото педагога: admin
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('mods_foto_delete', 'ajax', 'mods');
             break;
            default:
                $uri = $this->control_uri->get_full_segment('mods');
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
            if(is_numeric($this->input->post('id')) && $this->input->post('oper') == 'edit'){
            	$this->grid->loader->oper($this->table, 'edit');
            }elseif(is_numeric($this->input->post('id')) && $this->input->post('oper') == 'del'){
            	$this->grid->loader->oper($this->table, 'del');
            }else{
            	$this->grid->loader->oper($this->table, 'add');
            }
        }else{
        	$this->grid->loader->output($this->table);
        }
    }

    function grid_render(){

        $this->grid_params();

//-------------- Подключаем нужный визуальный редактор (WYSIWYG)
		echo '<script type="text/javascript" src="/wysiwyg/fckeditor/fckeditor.js"></script>';
		//echo '<script type="text/javascript" src="/wysiwyg/tiny_mce/tiny_mce.js"></script>';
        //echo '<script type="text/javascript" src="/wysiwyg/ckeditor/ckeditor.js"></script>';



//-------------- Загрузка таблицы jQGrid
   		echo "\r\n<script>";
        	echo $this->grid->loader->render($this->table);
        echo "</script>\r\n";



//------------- Обработчики событий для полей с использованием FCKeditor -----------

		//для вывода полей с использованием FCKeditor
		echo '<script>';
		echo '$grid.bind(\'jqGridAddEditAfterShowForm\', function(event, $form)
			{

                //var oFCKeditor = new FCKeditor( "description" ) ;
				//oFCKeditor.BasePath = "/wysiwyg/fckeditor/" ;
                //oFCKeditor.Height = 300 ;
                //oFCKeditor.ToolbarSet = "Basic";
				//oFCKeditor.ReplaceTextarea() ;

				gridEditWysiwyg("description");
			});
		';

        //echo '</script>';
        //echo "<script>";
		// Для записи изменений с использованием FCKeditor
        echo '$grid.bind(\'jqGridAddEditClickSubmit\', function(event, $form)
			{
				oEditor = FCKeditorAPI.GetInstance("description"); //получаем ссылку на объект "редактор"
				//description   = oEditor.GetXHTML("html");

				//text = oEditor.GetXHTML("html");
				return {
			     description: oEditor.GetHTML() //вызываем метод у объекта
			    };
			});
        ';
        echo 'function gridEditWysiwyg(field)
			{
                var oFCKeditor = new FCKeditor( field ) ;
				oFCKeditor.BasePath = "/wysiwyg/fckeditor/" ;
                oFCKeditor.Height = 300 ;
                oFCKeditor.ToolbarSet = "BasicC";
				oFCKeditor.ReplaceTextarea() ;
			}';

        echo '</script>';



    }

    function grid_params(){
        $get = '';
        if(isset($_GET['id']) && is_numeric($_GET['id'])){
        	$get = 'id='.$_GET['id'];
        }
        $path_to_tables = Modules::current_path().'models/grid/';
        $this->grid->loader->set("grid_path", $path_to_tables);
        //$this->grid->loader->set("images_path", assets_uploads().'images/lands/objects/');
        //$this->grid->loader->set("path_root", FCPATH);
        $this->grid->loader->set("config", $this->setting);
        $this->grid->loader->set('debug_output', true);
    }

	function segment_admin(){
	    $data['uri']['admin'] = $this->control_uri->get_full_segment('Admin');
	    return $data['uri']['admin'];
	}

	function segment_module(){
	    $data['uri']['mod'] = 'mod';
	    return $data['uri']['mod'];
	}

  function list_modules_menu(){

    $date_menu['modules'] = $this->scheme->modules_gp('modules');

    $date_menu['uri']['admin'] = $this->segment_admin();
	  $date_menu['uri']['mod'] = $this->segment_module();
    $date_menu['lang']['admin']['modules'] = $this->lang->line('admin_modules');
    //print_r($date['kodules']);
    //$this->load->view('list_modules_menu_views',$date_menu);
    $date_menu['title'] = "Загружен модуль МЕНЮ";
    $module = $this->control_uri->rsegment(3);
    $date_menu['menu']['select'] = $module;
    return $this->load->view('list_modules_menu_views', $date_menu, true);
	}

  function list_modules_menu2(){
	  $date_menu['kodules'] = $this->scheme->get_modules();
    $date_menu['uri']['admin'] = $this->segment_admin();
	  $date_menu['uri']['mod'] = $this->segment_module();
    $date_menu['lang']['admin']['modules'] = $this->lang->line('admin_modules');
    //print_r($date['kodules']);
    //$this->load->view('list_modules_menu_views',$date_menu);
    $date_menu['title'] = "Загружен модуль МЕНЮ";
    $module = $this->control_uri->rsegment(3);
    $date_menu['menu']['select'] = $module;
    return $this->load->view('list_modules_menu_views', $date_menu, true);
	}

	function list_modules_main(){
	  //$date_menu['kodules'] = $this->scheme->get_modules();
	  $date_menu['modules'] = $this->scheme->modules_gp('modules');
    $date_menu['uri']['admin'] = $this->segment_admin();
	  $date_menu['uri']['mod'] = $this->segment_module();
    $date_menu['lang']['admin']['modules'] = $this->lang->line('admin_modules');
    //$date_menu['lang']['admin']['modules'] = "123";
    //print_r($date_menu['modules']);
    //$this->load->view('list_modules_menu_views',$date_menu);
    $date_menu['title'] = "Загружен модуль МЕНЮ";

    $date_menu['set_site'] = $this->config->item('site');
    $date_menu['path']['img'] = "/".$this->config->item('tpl_path')."/".$this->config->item('tpl_name')."/img/";
    return $this->load->view('list_modules_main_views', $date_menu, true);
	}

    function list_config_menu(){

    }

    /**
    * Возвращает данные на основе id или name_tpl
    * @param string||numeric $name - id или uri страницы
    *
    * @return array - данные о странице
    */
    public function get_data_template($name){
        if( ! is_numeric($name)) {
         	$id = $this->id_is_name($name);
        }else{
        	$id = $name;
        }

		if(empty($id)) return false;
        $result = Modules::run('mods/mods_tpl/MY_data_row',
    		//select
    		array('*', //'main.id', 'main.name', 'main.mod_id',
    			'related' => array('mod'),
    		),
    		//where
    		array('id' => $id)
    	);

        //var_dump($result);
        //exit;
         //$result = $this->get_data_mod($id);

         if(is_object($result)){
         	$this->load->driver('replaced');
         	if(is_object($this->replaced->mod)){
         		$data_access = $this->replaced->mod->get_allow_value();
         	}else{
         		return false;
         	}
         	//print_r($data_access);
         	//exit;
         	foreach($result as $key=>$value){
         		if(in_array($key, $data_access)){
         			$data_page[$key] = $value;
         		}
         	}
         }else{
         	echo 'не массив';
         	exit;
         }
         if(isset($data_page)) return $data_page;
         return false;
    }

    /**
    * Определяет id по name
    *	@param string $name - имя шаблона
    *
    *	@return number - номер id или false, если страница не найдена
    */
    public function id_is_name($name){
    	//cho 'Uri: '.$uri.'<br/>';
    	//exit;
    	//$uri = trim($uri, '/');
    	$res = Modules::run('mods/mods_tpl/MY_data_row',
    		//select
    		array('id'),
    		//where
    		array('name' => $name)
    	);
    	if(isset($res->id)) return $res->id;
    	return false;
    }


    public function get_data_mod($id){
    	$res = Modules::run('mods/mods/MY_data',
    		//select
    		array('*'),
    		//where
    		array('id' => $id)
    	);
    	if(isset($res->id)) return $res->id;
    	return false;
    }
}






