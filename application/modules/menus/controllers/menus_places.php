<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен

if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 * Класс Menus_places
 *
 */

class Menus_places extends Menus {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_menus_places';
    }

    function grid_render(){
		$this->grid_params();
        $path_wysiwyg = '/'.assets_wysiwyg();

		//-------------- Подключаем нужный визуальный редактор (WYSIWYG)
		echo '<script type="text/javascript" src="'.$path_wysiwyg.'fckeditor/fckeditor.js"></script>';
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
				//oFCKeditor.BasePath = "'.$path_wysiwyg.'fckeditor/" ;
                //oFCKeditor.Height = 300 ;
                //oFCKeditor.ToolbarSet = "BasicA";
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
				oFCKeditor.BasePath = "'.$path_wysiwyg.'fckeditor/" ;
                oFCKeditor.Height = 300 ;
                oFCKeditor.ToolbarSet = "BasicImg";
				oFCKeditor.ReplaceTextarea() ;
			}';

        echo '</script>';


	}

	/**
	* Проверка имени на дублирование
	*	@param $name string - имя места положения
	*	@param $id numeric - id которые исключаются из проверки (например для исключения самого себя в запросе, при операция редактирования)
	*
	*	@return boolean
	*/
	public function check_double_name($name, $id = false){
		if($id !== false){			$where = array('id !=' => $id);
		}
		$where['name'] = $name;

		$res = $this->MY_data(
		         //select
		         array('id'),
		         //where
		         $where
		);
		if($res) return $res;
		return false;
	}

	/**
	* Проверка псевдонима на дублирование
	*	@param $name string - псевдоним места положения
	*	@param $id numeric - id которые исключаются из проверки (например для исключения самого себя в запросе, при операция редактирования)
	*
	*	@return boolean
	*/
	public function check_double_alias($name, $id = false){
        if($id !== false){
			$where = array('id !=' => $id);
		}
		$where['alias'] = $name;

		$res = $this->MY_data(
		         //select
		         array('id'),
		         //where
		         $where
		);
		if($res) return $res;
		return false;
	}

	public function places_select(){		$res = $this->MY_data_array_one();
		if($res) return $res;
		return array();
	}

	/**
	* События на удаление объекта
	*/
	public function eventDelete($id){
	}

	/**
	*	Возвращает содержимое место положения по его псевдониму
	*
	*
	*/
	public function data_place_of_alias($alias){		$res = $this->MY_data_row(
			//select
			array('id', 'name', 'active', 'description', 'alias'),
			//where
			array('alias' => $alias)
		);
		if(isset($res)) return $res;
		return false;
	}

	/**
	* Возвращает массив id найденных узлов
	*	@param string - имя узла
	*	@param string - тип узла (по умолчанию page - страница)
	* @param string - имя места
	*
	* @return array - массив с id узлами
	*/
	public function get_id_nodes($name, $type = false, $place = false){
	    if($type !== false){
	    	$id_type = Modules::run('menus/menus_types/get_id_is_name', $type);
		  	$this->setting['condition_field']['type_id'] = (is_numeric($id_type)) ? $id_type : 1;
        }
	    if($place != false){	    	$place_id = $this->data_place_of_alias($place);
	    	if(!empty($place_id->id)) $this->setting['condition_field']['place_id'] = $place_id->id;
	    }

	    $all_nodes = $this->_run_command('get_id_nodes', $name);
			return $all_nodes;
	}
}