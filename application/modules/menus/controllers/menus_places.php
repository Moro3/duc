<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ���������� ����������� ����� ���� �� �� ��������

if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 * ����� Menus_places
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

		//-------------- ���������� ������ ���������� �������� (WYSIWYG)
		echo '<script type="text/javascript" src="'.$path_wysiwyg.'fckeditor/fckeditor.js"></script>';
		//echo '<script type="text/javascript" src="/wysiwyg/tiny_mce/tiny_mce.js"></script>';
        //echo '<script type="text/javascript" src="/wysiwyg/ckeditor/ckeditor.js"></script>';

        //-------------- �������� ������� jQGrid
   		echo "\r\n<script>";
        	echo $this->grid->loader->render($this->table);
        echo "</script>\r\n";


		//------------- ����������� ������� ��� ����� � �������������� FCKeditor -----------

		//��� ������ ����� � �������������� FCKeditor
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
		// ��� ������ ��������� � �������������� FCKeditor
        echo '$grid.bind(\'jqGridAddEditClickSubmit\', function(event, $form)
			{
				oEditor = FCKeditorAPI.GetInstance("description"); //�������� ������ �� ������ "��������"
				//description   = oEditor.GetXHTML("html");

				//text = oEditor.GetXHTML("html");
				return {
			     description: oEditor.GetHTML() //�������� ����� � �������
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
	* �������� ����� �� ������������
	*	@param $name string - ��� ����� ���������
	*	@param $id numeric - id ������� ����������� �� �������� (�������� ��� ���������� ������ ���� � �������, ��� �������� ��������������)
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
	* �������� ���������� �� ������������
	*	@param $name string - ��������� ����� ���������
	*	@param $id numeric - id ������� ����������� �� �������� (�������� ��� ���������� ������ ���� � �������, ��� �������� ��������������)
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
	* ������� �� �������� �������
	*/
	public function eventDelete($id){
	}

	/**
	*	���������� ���������� ����� ��������� �� ��� ����������
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
	* ���������� ������ id ��������� �����
	*	@param string - ��� ����
	*	@param string - ��� ���� (�� ��������� page - ��������)
	* @param string - ��� �����
	*
	* @return array - ������ � id ������
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