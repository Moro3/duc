<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен

if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 * Класс Menus_types
 *
 */

class Menus_types extends Menus {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_menus_types';
    }

    function grid_render(){
		parent::grid_render();
		//------------- Обработчики событий для полей с использованием FCKeditor -----------

		//для вывода полей с использованием FCKeditor
		echo '<script>';
		echo '$grid.bind(\'jqGridAddEditAfterShowForm\', function(event, $form)
			{

                //var oFCKeditor = new FCKeditor( "description" ) ;
				//oFCKeditor.BasePath = "/wysiwyg/fckeditor/" ;
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
				oFCKeditor.BasePath = "/wysiwyg/fckeditor/" ;
                oFCKeditor.Height = 300 ;
                oFCKeditor.ToolbarSet = "BasicA";
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

	public function types_select(){		$res = $this->MY_data_array_one();
		if($res) return $res;
		return array();
	}

	public function get_id_is_name($name){    	$res = $this->get_data_is_name($name);
    	if(isset($res->id)) return $res->id;
    	return false;
	}

	/**
	* Получение данных о типе по его имени
	*
	*/
	public function get_data_is_name($name){		$res = $this->MY_data_row(
		    //select
			array('id', 'name', 'active', 'description', 'alias'),
			//where
			array('alias' => $name)
		);
		if(isset($res)) return $res;
		return false;
	}

}