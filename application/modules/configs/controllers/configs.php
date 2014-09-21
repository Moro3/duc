<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Класс Configs - основной класс для работы с конфигурационными файлами
 *
 *
 */

class Configs extends MY_Controller {

    //настройки модуля
    protected $setting;

    function __construct(){
        parent::__construct();
        $this->lang->load('configs');
        $this->load_config();
        $this->load->helper('typography');

        $this->load_models();

        $this->table = 'grid_configs';
	}

	function load_config(){
		if(empty($this->setting)){			$this->config->load('setting', true);
			$this->setting = $this->config->item('setting');
		}
	}

	function load_models(){
        //print_r($this->setting);
    	//$this->load->model('configs_model');


    }

    // Маршрутизация модуля
    function admin_index(){
        $buf = '';
        $this->admin_menu_view();
        //print_r(Modules::run('reviews/reviews_categories/listCategories'));
        // запускаем маршрут для модуля с именем группы admin (file: route), модуля duc, вернуть сгенерированное маршрутом
        $buf .= $this->router_modules->run('admin', 'configs', true);

        echo $buf;

    }
    /*
     * Шаблон меню модуля по умолчанию
     */
    function admin_menu_view(){
        //$data['tree'] = $this->router_modules->tree_menu('admin');
        $data['tree'] = $this->control_uri->route->tree_menu('admin');
        $data['uri']['point'] = $this->load->module('configs/configs')->uri_point('admin');
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

	function grid_admin_object(){
        $this->grid_render();
	}

	function grid(){
        //echo '@';
        //exit;
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
				gridEditWysiwyg("content");
			});
		';

        //echo '</script>';
        //echo "<script>";
		// Для записи изменений с использованием FCKeditor
        echo '$grid.bind(\'jqGridAddEditClickSubmit\', function(event, $form)
			{
				oEditor = FCKeditorAPI.GetInstance("content"); //получаем ссылку на объект "редактор"
				//text = oEditor.GetXHTML("html");
				return {
			     	content: oEditor.GetHTML(), //вызываем метод у объекта
			    };
			});
        ';
        echo 'function gridEditWysiwyg(field)
			{
                var oFCKeditor = new FCKeditor( field ) ;
				oFCKeditor.BasePath = "/wysiwyg/fckeditor/" ;
                //oFCKeditor.Height = 300;
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

    /**
	* Маршрут модуля для пользователя
	*
	*/
	function route(){

		$buf = '';
		$buf .= $this->router_modules->run('user', 'configs', true);

        echo $buf;
	}
    /*
     *  точка старта запроса uri
     */
    function uri_point($name = ''){
        switch ($name){
            case 'admin':
                $uri = $this->control_uri->get_full_segment('admin','mod');
                $uri .= trim($this->setting['uri'], '\/').'/';
            break;
            case 'admin_ajax':
                $uri = '/ajax/'.trim($this->setting['uri'], '\/').'/admin/';
            break;
            default:
                $uri = $this->control_uri->get_full_segment('pages');
                $uri .= '';
            break;
        }

        return $uri;
    }


    /**
    * Шаблон подсказки существующих конфигурационных настроек для статичных страниц
    *
    * @return array - данные
    */
    public function tpl_tooltip_page(){    	 $config_array = $this->config->item('site');

    	 foreach($config_array as $name=>$value){    	 	$data[] = array('name' => $name,
    	 					'content' => $value
    	 	);
    	 }

         if(is_array($data) && count($data) > 0){         	$result = $this->load->view('tooltip_page', array('data' => $data), true);
         }else{         	$result = '';
         }

         return $result;
    }

}