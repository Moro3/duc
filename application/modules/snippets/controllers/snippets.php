<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Класс Snippets - основной класс для работы со сниппетами
 *
 *
 */

class Snippets extends MY_Controller {

    //настройки модуля
    protected $setting;

    function __construct(){
        parent::__construct();
        $this->lang->load('Snippets');
        $this->load_config();
        $this->load->helper(array('typography','language'));

        $this->load_models();

        $this->table = 'grid_snippets';
	}

	function load_config(){
		if(empty($this->setting)){
			$this->config->load('setting', true);
			$this->setting = $this->config->item('setting');
		}
	}

	function load_models(){
        //print_r($this->setting);
    	$this->load->model('snippets_model');
    	$this->load->model('snippets_groups_model');

    }

    // Маршрутизация модуля
    function admin_index(){
        $buf = '';
        $this->admin_menu_view();
        //print_r(Modules::run('reviews/reviews_categories/listCategories'));
        // запускаем маршрут для модуля с именем группы admin (file: route), модуля duc, вернуть сгенерированное маршрутом
        $buf .= $this->router_modules->run('admin', 'snippets', true);

        echo $buf;

    }
    /*
     * Шаблон меню модуля по умолчанию
     */
    function admin_menu_view(){
        //$data['tree'] = $this->router_modules->tree_menu('admin');
        $data['tree'] = $this->control_uri->route->tree_menu('admin');
        $data['uri']['point'] = $this->load->module('snippets/snippets')->uri_point('admin');
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
		$buf .= $this->router_modules->run('user', 'snippets', true);

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
            case 'admin_ajax_snippets_delete_foto':
                 // Uri для удаления фото педагога: admin
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('pages_foto_delete', 'ajax', 'snippets');
             break;
            default:
                $uri = $this->control_uri->get_full_segment('pages');
                $uri .= '';
            break;
        }

        return $uri;
    }

    /**
	*  Проверка наличие сниппета (по ID)
	*  Если сниппет найден - вернет true
	*
	* @return boolean
	*/
    function isId($id){
    	if(empty($id) || !is_numeric($id)) return false;
    	if(!is_array($ids))	$ids = array($id);

    	$res = Modules::run('snippets/snippets/MY_data_array_row',
         	//select
         	array('id', 'name'// 'main.paid'

         	),
         	//where
         	array('id' => $ids)

         );
         if (is_array($res) && count($res) > 0) return true;
         return false;
    }

    /**
	*  Проверка наличие сниппетов в группе (по ID группы)
	*  Если сниппеты из данной группы найдены не задан id - вернет true
	*
	* @return boolean
	*/
    function isGroupId($ids){
    	if(empty($ids)) return true;
    	if(!is_array($ids))	$ids = array($ids);

    	$res = Modules::run('snippets/snippets/MY_data_array_row',
         	//select
         	array('id', 'name'// 'main.paid'

         	),
         	//where
         	array('group_id' => $ids)

         );
         if (is_array($res) && count($res) > 0) return true;
         return false;
    }

    /**
	*  Проверка наличие сниппетов в группе (по Имени группы)
	*  Если сниппеты из данной группы найдены или не задано имя  - вернет true
	*
	* @return boolean
	*
	*/
    function isGroupName($names){
    	if(empty($names)) return true;
    	if(!is_array($names))	$names = array($names);

    	$idsGroup = Modules::run('snippets/snippets_groups/MY_data_array_row',
         	//select
         	array('id', 'group_id'),
         	//where
         	array('name' => $names)

         );
         if (is_array($idsGroup) && count($idsGroup) > 0){
         	 return $this->isGroupId($idsGroup['group_id']);
         }
         return false;
    }

    /**
    *  Поиск имени в базе
    *  @param $name - имя сниппета
    *  @param $without_id - id которые не учитывать при поиске
    *
    *	@return boolean - true: имя найдено, false - имя отсутствует
    */
    function searchName($name, $without_id = false){
    	 if(empty($name)) return true;

    	 if($without_id !== false){
    	 	if( ! is_array($without_id)) $without_id = array($without_id);
    	 }else{
    	 	$without_id = array();
    	 }

    	 if(count($without_id) > 0){
         	$where = array(
         				'name' => $name,
         				'not' => array('id' => $without_id)
         	);
         }else{
         	$where = array(
         				'name' => $name
         	);
         }


    	 $res = Modules::run('snippets/snippets/MY_data_array_row',
         	//select
         	array('id', 'name'// 'main.paid'

         	),
         	//where
			$where
         );
         if (is_array($res) && count($res) > 0) return true;
         return false;
    }

    /**
    * Возвращает данные сниппета по его имени
    * @param string $name - имя сниппета
    *
    * @return array - данные
    */
    public function get_data_template($name){
         if(empty($name)) return false;
         $result = $this->MY_data_array_row(
           //select
           '*',
           //where
           array('name' => $name,
           		 'active' => 1
           )
         );

         if(is_array($result)){
         	$this->load->driver('replaced');
         	if(is_object($this->replaced->snip)){
         		$data_access = $this->replaced->snip->get_allow_value();
         	}else{
         		return false;
         	}
         	//print_r($data_access);
         	//exit;
         	foreach($result as $key=>$value){
         		if(in_array($key, $data_access)){
         			$data[$key] = $value;
         		}
         	}
         }
         if(isset($data)) return $data;
         return false;
    }

    /**
    * Шаблон подсказки существующих сниппетов для статичных страниц
    *
    * @return array - данные
    */
    public function tpl_tooltip_page(){
    	 $data = $this->MY_data_array(
           //select
           array('id', 'name', 'description', 'alias',
           		'group_id'
           ),
           //where
           array(
           		 'active' => 1
           ),
           //limit
           false,
           //sort
           array('sorter')
         );

         if(is_array($data) && count($data) > 0){
         	$result = $this->load->view('tooltip_page', array('data' => $data), true);
         }else{
         	$result = '';
         }

         return $result;
    }

}