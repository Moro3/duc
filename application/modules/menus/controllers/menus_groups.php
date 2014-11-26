<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ���������� ����������� ����� ���� �� �� ��������

if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 * ����� Menus_groups
 *
 */

class Menus_groups extends Menus {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_menus_groups';
    }

    function grid_render(){
		$this->grid_params();
        $path_wysiwyg = '/'.assets_wysiwyg();

		//-------------- ���������� ������ ���������� �������� (WYSIWYG)
		echo '<script type="text/javascript" src="'.$path_wysiwyg.'fckeditor/fckeditor.js"></script>';
		//echo '<script type="text/javascript" src="/wysiwyg/tiny_mce/tiny_mce.js"></script>';
        //echo '<script type="text/javascript" src="/wysiwyg/ckeditor/ckeditor.js"></script>';

        /*
        echo "<script>";
        echo 'function gridEditMultiselect(field)
			{
                $(".ui-"+field).remove();
                //var option = $("."+field).html();
                //$("."+field).empty().append(option);
                //$("."+field).show();
                $("."+field).show().multiselect(
                {
                	sortable: true,
                	searchable: true
                });

			}';
        echo '</script>';
        */


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
                //oFCKeditor.ToolbarSet = "BasicA";
				oFCKeditor.ReplaceTextarea() ;
			}';

        echo '</script>';

        
        // ���������� ������� ��� ����� multiselect
        
        $this->load->view('grid/formatter/multiselect',
                            array(
                            	'selector' => 'multiselect',                                
                                'sortable' => 'true',
                                'searchable' => 'true',                                
                            )
        );
        
       
        //------------- ���������� ������� ��� ����� multiselect
       	
       	//echo "<script>";
		//echo '$grid.bind("jqGridAddEditAfterShowForm", function(event, $form)
		//	{
		//		//$.localise(\'ui-multiselect\', {/*language: \'ru\',*/ path: \'/{assets}jquery-ui/plugins/multiselect-master/js/locale/\'});
		//		//$(".multiselect").multiselect({sortable: false, searchable: true});
		//		gridEditMultiselect("multiselect");
		//	});
		//';
        //echo '</script>';
        
	}

	/**
	* �������� ����� �� ������������
	*	@param $name string - ��� ����� ���������
	*	@param $id numeric - id ������� ����������� �� �������� (�������� ��� ���������� ������ ���� � �������, ��� �������� ��������������)
	*
	*	@return boolean
	*/
	public function check_double_name($name, $id = false){

		if($id !== false){
			$where = array('id !=' => $id);
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

	public function places_select(){
		$res = $this->MY_data_array_one();
		if($res) return $res;
		return array();
	}

    /**
	* ���������� ������ id ��������� ����� � ����������� �� ����� ������
	*	@param string - ��� ����
	*	@param string - ��� ���� (�� ��������� page - ��������)
	* @param string - ��� ������
	*
	* @return array - ������ � id ������
	*/
	public function get_id_nodes($name, $type = false, $group = false){
		if($type !== false){
			$id_type = Modules::run('menus/menus_types/get_id_is_name', $type);
		  	$this->setting['condition_field']['type_id'] = (is_numeric($id_type)) ? $id_type : 1;
        }
	    if($group !== false){
	    	//�������� ������ �� ������ � ������
	    	$place_ids = $this->get_id_places_is_group($group);
            //���� ���� ������ ������� � ������� ��� �������
	    	if(is_array($place_ids)){
	    		//��������������� id ����
	    		foreach($place_ids as $items){
	    			$ids[] = $items->place_id;
	    		}
	    		$this->setting['condition_field']['place_id'] = $ids;
	   		}
	    }
	    $all_nodes = $this->_run_command('get_id_nodes', $name);
			return $all_nodes;
	}

  	public function get_id_places_is_group($group){
        return Modules::run('menus/menus/get_places_of_group', $group);
  	}

  	

  	public function get_places_is_group(){
        $group = 'top';
        $places = $this->get_id_places_is_group($group);
        
        $selResult = '<select>';
        $sResult = '';
        if(is_array($places) && count($places) > 0){
        	foreach($places as $items){
        		$selResult .= '<option role="option" value='.$items->id.'>'.$items->place_id;
        		//$selResult .= '</option>';
        		
        	}
        	//$sResult .= '"';
        	$sResult .= join(';', array_map(function($x, $y){return $y->id.':'.$y->place_id.'';}, array_keys($places), array_values($places)));
        	//$sResult .= '"';
        }
        //$selResult .= '</select>';
        //dd($sResult);
        return $selResult;
  	}

}