<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Класс Menus - основной класс для работы с иерархическим меню
 *
 *
 */

class Menus extends MY_Controller {

    //настройки модуля
    protected $setting;

    function __construct(){
        parent::__construct();

        $this->lang->load('menus');
        $this->load->helper('language');
        $this->load->helper('text');

		// Load the library "utree"
    	$this->load->library('utree');

        //$this->config->load('index_request', TRUE);
        //$this->index_request = $this->config->item('index_request', 'index_request');

        //$this->config->load('menu', TRUE);
        //$this->get = $this->config->item('menu', 'menu');
        $this->config->load('setting', TRUE);
        $this->setting = $this->config->item('setting');

        //$this->load->library('datetimepicker');
        $this->load_config();
        $this->load_models();

        $this->table = 'menus_trees';

    }

    function load_config(){
		$this->config->load('setting', true);
		$this->setting = $this->config->item('setting');
        //print_r($this->setting);
        //exit;
	}

	function load_models(){
    	$this->load->model('menus_trees_model');
    	$this->load->model('menus_places_model');
        $this->load->model('menus_groups_model');
        $this->load->model('menus_types_model');
        $this->load->model('menus_groups_places_model');
    }

    function index(){

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
        $buf .= $this->router_modules->run('admin', 'menus', true);

        echo $buf;
        //exit();
        //return $buf;
    }

    /*
     * Шаблон меню модуля по умолчанию
     */
    function admin_menu_view(){
        //$data['tree'] = $this->router_modules->tree_menu('admin');
        $data['tree'] = $this->control_uri->route->tree_menu('admin');
        $data['uri']['point'] = $this->load->module('menus/menus')->uri_point('admin');
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
	* Вывод шаблона меню на основе древовидной структуры
	*	@param $tree - дерево меню
	*	@param $current_key - текущее меню
	*	@param $level - уровень в меню
	*	@param $options - дополнительные опции для меню
	*
	*   Содержание дерева:
	*                 name - имя меню
	*                 link - ссылка на раздел
	*                 active_link - активная ли ссылка
	*                 path_link - путь до ссылки
	*
	*
	*/
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

							if($value['link'] == $this->router_modules->generate_link('menus', 'admin', 'menus')){
			                  	echo Modules::run('menus/menus/menus_places');
			                }
						}
				 //}

			}
        }
	}

	public function menus_places(){		$data['uri']['point'] = $this->load->module('menus/menus')->uri_point('admin');
		$data['uri']['link'] = $data['uri']['point'].$this->router_modules->generate_link('menus', 'admin', 'menus');
		$data['level'] = 1;
		$data['objects'] = Modules::run('menus/menus_places/MY_data',
			//select
			array('id', 'name', 'alias', 'sorter')
		);
		//var_dump($data['objects']);
		if(isset($_GET['place']) && is_numeric($_GET['place'])){
        	$data['active_id'] = $_GET['place'];
        }else{        	$data['active_id'] = false;
        }
        if(isset($_GET['group']) && is_numeric($_GET['group'])){
        	$data['group_id'] = $_GET['group'];
        }else{
        	$data['group_id'] = false;
        }
		$this->load->view('admin/menu_places', $data);
	}

	public function menus_groups(){
		$data['uri']['point'] = $this->load->module('menus/menus')->uri_point('admin');
		$data['uri']['link'] = $data['uri']['point'].$this->router_modules->generate_link('menus', 'admin', 'menus');
		$data['level'] = 1;
		$data['objects'] = Modules::run('menus/menus_groups_places/MY_data',
			//select
			array('id', 'name', 'alias', 'sorter')
		);
		//var_dump($data['objects']);
		if(isset($_GET['group']) && is_numeric($_GET['group'])){
        	$data['active_id'] = $_GET['group'];
        }else{
        	$data['active_id'] = false;
        }
		$this->load->view('admin/menu_groups', $data);
	}

    /*
     *  точка старта запроса uri
     */
    function uri_point($name = ''){
        switch ($name){
            case 'admin':
                $uri = $this->control_uri->get_full_segment('admin','mod');
                $uri .= 'menus/';
            break;
            case 'user':
                $uri = $this->control_uri->get_full_segment('pages');
                $uri = 'menus/';
            break;
            case 'user_main':
                $uri = '/menus/';
            break;
            case 'admin_ajax':
                $uri = '/ajax/menus/admin/';
            break;
            default:
                $uri = $this->control_uri->get_full_segment('menus');
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



    }

    function grid_params(){
        $path_to_tables = Modules::current_path().'models/grid/';
        $this->grid->loader->set("grid_path", $path_to_tables);
        //$this->grid->loader->set("images_path", assets_uploads().'images/lands/objects/');
        //$this->grid->loader->set("path_root", FCPATH);
        $this->grid->loader->set("config", $this->setting);
        $this->grid->loader->set('debug_output', true);
    }


    /**
    * Установка параметров для манипуляции с библиотекой Utree
    *
    */
	private function _set_param(){
    $this->setting['table'] = "menus_trees";
    $this->setting['id_field'] = "id";
    $this->setting['name_field'] = "name";
    $this->setting['parent_field'] = "parent_id";
    $this->setting['order_field'] = "sorter"; // опционально

    $this->setting['method'] = "arjacency";

    // метод используемой сортировки
    // значения: arj - метод списки смежноти, num - номерационная сортировка (обычная)
    // если не указано, сортировка будет выполнена в случайном порядке
    // использовать только если указано поле 'order_field' !!!
    $this->setting['method_order'] = "arj";

    //дополнительные поля для извлечения
    $this->setting['select_field'] = array('type_id', 'date');

    // дополнительные условия при извлечении данных
    /*
    $this->setting['condition_field'] = array(
                                           //'type_id' => '1',
                                           //'place_id' => $this->place,
                                          );
   */

  }

  /**
  *	Установка устовий для выборки из таблиц
  *
  */
  private function _set_where($params = array(), $value = false){  		if(is_array($params) && count($params) > 0){  			foreach($params as $key=>$items){  				if(is_string($key)){  					$this->setting['condition_field'][$key] = $items;
  				}
  			}
  		}
  }

  /**
  * Запуск команды на выполнение действия с узлами
  * через библиотеку utree
  *
  */
  protected function _run_command($action, $arg = null){

    $this->_set_param();
    //echo $this->setting['condition_field']['menu_place_id'];
    $this->utree->set_param($this->setting);
    $this->setting = array();
    return $this->utree->run_command($action, $arg);
  }

  /**
  * Возвращает узлы в виде родительского древа $nodes[parent][node]
  *  если узел корневой parent = 0
  *
  *	@return array - массив узлов
  */
  public function get_nodes(){  		$all_nodes = $this->_run_command('get_tree_parent');
  		return $all_nodes;
  }

  /**
  *	Возвращает путь к узлу
  *	@param numeric $node - id узла
  *	@param string $type - тип узла (по умолчанию page)
  *
  *	@return array - массив с узлами
  */
  public function get_path($node, $type = false){
  		if($type !== false){
  			$id_type = Modules::run('menus/menus_types/get_id_is_name', $type);
  			$this->setting['condition_field']['type_id'] = (is_numeric($id_type)) ? $id_type : 1;
  		}
  		$all_nodes = $this->_run_command('get_path', $node);
  		//echo $this->db->last_query();
  		//print_r($all_nodes);
  		//exit;
  		return $all_nodes;
  }

  /**
  * Возвращает массив id найденных узлов
  *	@param string - имя узла
  *	@param string - тип узла (по умолчанию page - страница)
  *
  * @return array - массив с id узлами
  */
  public function get_id_nodes($name, $type = false){      	if($type !== false){
      		$id_type = Modules::run('menus/menus_types/get_id_is_name', $type);
  			$this->setting['condition_field']['type_id'] = (is_numeric($id_type)) ? $id_type : 1;
  		}

      $all_nodes = $this->_run_command('get_id_nodes', $name);
  		return $all_nodes;
  }

  /**
    * Возвращает места содержащие в группе
    *
    *
    */
    public function get_places_of_group($alias){
    	$id_group = Modules::run('menus/menus_groups/MY_data_row',
    		//select
    		array('id'),
    		//where
    		array('alias' => $alias)
    	);
    	if(isset($id_group->id)){
	    	$res = Modules::run('menus/menus_groups_places/MY_data',
	    	  	//select
	    	  	array('id', 'place_id', 'sorter',
	    	  	),
	    	  	//where
	    	  	array('group_id' => $id_group->id)
	    	);
    	}
    	if(isset($res)){    		uasort($res, array($this, '_order_by_places'));
    		return $res;
    	}
    	return false;
    }


    private function _order_by_places($a,$b){    	if(is_object($a->places)){
    		if($a->places->sorter == $b->places->sorter) return 0;
    		return ($a->places->sorter > $b->places->sorter) ? 1 : -1;
        }
        if(is_array($a['places'])){
    		if($a['places']->sorter == $b['places']->sorter) return 0;
    		return ($a['places']->sorter > $b['places']->sorter) ? 1 : -1;
    	}
    }

    private function _order_by($a,$b){
    	if(is_object($a)){
    		if($a->sorter == $b->sorter) return 0;
    		return ($a->sorter > $b->sorter) ? 1 : -1;
        }
        if(is_array($a)){
    		if($a['sorter'] == $b['sorter']) return 0;
    		return ($a['sorter'] > $b['sorter']) ? 1 : -1;
    	}
    }

    /**
    * Возвращает меню содержащие в place
    * @param $alias - псевдоним места положения
    *
    *	@return array - массив с меню
    */
    public function get_menu_of_place($alias, $type = false){    	$id_place = Modules::run('menus/menus_places/MY_data_row',
    		//select
    		array('id', 'alias'),
    		//where
    		array('alias' => $alias)
    	);
    	//print_r($id_place);
    	if($id_place === false) return false;
    	$this->setting['condition_field']['place_id'] = $id_place->id;

    	if($type !== false){
    		$id_type = Modules::run('menus/menus_types/get_id_is_name', $type);
  			$this->setting['condition_field']['type_id'] = (is_numeric($id_type)) ? $id_type : 1;
        }
      	$all_nodes = $this->get_nodes();
  		return $all_nodes;
    }

    /**
    *	Возвращает дерево меню
    *	Формат array[alias place][parent id][id][data node]
    *   @param $alias - псевдоним место положения
    *
    */
    public function get_trees_place($alias){
		$places = Modules::run('menus/menus/get_places_of_group', $alias);

		foreach($places as $key_1=>$items){
			$res = Modules::run('menus/menus/get_menu_of_place', $items->places->alias);
            //echo '<br><b>Source = '.__FILE__.' : Line = '.__LINE__.'</b><br>';
            //echo '<pre>';
            //var_dump($res);
            //echo '</pre>';

            if(is_array($res)){
	            foreach($res as &$item){	            	uasort($item, array($this, '_order_by'));
	            }
                $nodes[$items->places->alias] = $res;
            }

		}

		if(isset($nodes)) return $nodes;
		return false;

    }

    /**
    *	Возвращает узлы которые содержат указанные имена
    *	@param $name - имя узла
    *	@param $type - тип узла (по умолчанию считается страницей)
    *
    *	@return array - массив из узлов или false
    */
    public function get_nodes_of_name($name, $type = false){    	if($type !== false){
	    	$type = Modules::run('menus/menus_types/MY_data_row',
	    		//select
	    		array('id'),
	    		//where
	    		array('name' => $type)
	    	);
	    	if( ! isset($type->id)) return false;
	    	$where_type = array('type_id' => $type->id);
    	}

    	if( ! $where_type) $where_type = '';

    	$res = Modules::run('menus/menus_trees/MY_data',
    		//select
    		array('id', 'parent_id'),
    		//where
    		array('name' => $name,
    			  $where_type
    		)
    	);
    	if($res) return $res;
    	return false;
    }

	public function get_data_node($node){
		$res = Modules::run('menus/menus_trees/MY_data_row',
					//select
					array('id', 'parent_id', 'place_id', 'name'),
					//where
					array('id' => $node)
		);
		if($res) return $res;
		return false;
	}

    public function get_data_nodes($nodes){
		$res = Modules::run('menus/menus_trees/MY_data',
					//select
					array('id', 'parent_id', 'place_id', 'name'),
					//where
					array('id' => $nodes)
		);
		if($res) return $res;
		return false;
	}
}