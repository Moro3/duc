<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ���������� ����������� ����� ���� �� �� ��������

if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 * ����� Menus_types
 *
 */

class Menus_types extends Menus {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_menus_types';
    }

    function grid_render(){
		parent::grid_render();
		//------------- ����������� ������� ��� ����� � �������������� FCKeditor -----------

		//��� ������ ����� � �������������� FCKeditor
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
				oFCKeditor.BasePath = "/wysiwyg/fckeditor/" ;
                oFCKeditor.Height = 300 ;
                oFCKeditor.ToolbarSet = "BasicA";
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

	public function types_select(){		$res = $this->MY_data_array_one();
		if($res) return $res;
		return array();
	}

	public function get_id_is_name($name){    	$res = $this->get_data_is_name($name);
    	if(isset($res->id)) return $res->id;
    	return false;
	}

	/**
	* ��������� ������ � ���� �� ��� �����
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