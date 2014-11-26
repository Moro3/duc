<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ���������� ����������� ����� ���� �� �� ��������
if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 * ����� Menus_groups_places
 *
 */

class Menus_groups_places extends Menus {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_menus_groups_places';
    }


	 /**
    * ������ ����� ���� � ������
    * @param int - id_group
    * @param array - ������ � ������ id_place
    *
    * return boolean - true || false
    */
    function write_places($id_group, $id_places = array()){
    	if( ! isset($id_group)){
    		throw new jqGrid_Exception('�� ������� ������� ��� ��������������');
      		return false;
    	}
    	if( ! is_numeric($id_group)){
    		throw new jqGrid_Exception('�� ����� ������� ������� ��� ��������������');
      		return false;
    	}
    	//������� ������ �������
    	$rows = Modules::run('menus/menus_groups_places/MY_data_array',
    	                    //select
    	                    array('id', 'place_id'
    	                    ),
    	                    //where
    						array('group_id' => intval($id_group)
    						)
    	);
    	//��� ������ places
    	$count_places = Modules::run('menus/menus_places/MY_data_array',
    										//select
    										array('id')
    	);

    	//�������� ������ places � �������
    	foreach($count_places as $k=>$v){
    		$arr_places[] = $v['id'];
    	}
    	//���� places ��� - ���������� ������ � ���������� false
    	if(!isset($arr_places) || count($arr_places) <= 0){
    		throw new jqGrid_Exception('�� ������ ����� ��������� �� �������');
      		return false;
    	}
        //�������� ������� ������ � ����������� �������
        //��������� ����� ������ �� ������� ��������
        if(is_array($rows) && count($rows) > 0){
	        foreach($rows as $key=>$value){
	        	$rows_id[] = $value['place_id'];
	        }
	    }else{
	    	$rows_id = array();
	    }
        // �������� ��������� ������ ���� � ����������� �������
        // � ��������� ������������ �������
        if(!is_array($id_places)){
        	$rows_in = array($id_places);
        }else{
        	$rows_in = array();
        	foreach($id_places as $item){
        		if(!empty($item)){
        			$rows_in[] = $item;
        		}
        	}
        }
        //���� ��������� ������ �� ������� � ������������ ������
        // ���������� ������ � ���������� false
        if(count($rows_in) > 0){
	        if( array_diff($rows_in, $arr_places)){
	        	throw new jqGrid_Exception('������� �������������� ����� ���������');
	               return false;
	    	}
    	}
        
        /* 
        //==== ������ � ������ ������� ��������� � ������������ ====

        //��������� ������ ��� ����������
        $cols_add = array_diff($rows_in, $rows_id);
        $cols_add = array_unique($cols_add);
        //��������� ������ ��� ��������
        $cols_del = array_diff($rows_id, $rows_in);
        $cols_del = array_unique($cols_del);
        */
        
        //==== ������ ��� ������ ������� ��������� � ������������ ====
        //==== ����������� ������������������ ��������� � ��� ����� ����������� �������� ������ ��� ����������
        $cols_add = $rows_in;
        $cols_del = $rows_id;

        /*
        dd(array("Data be:" => $rows_id,
                "Data input:" => $rows_in,
                "Data for add:" => $cols_add,
                "Data for delete:" => $cols_del,
            )
        );
        */
        
        // �������
        if(is_array($cols_del) && count($cols_del) >= 1){
        	$where = array('group_id' => $id_group,
        					'place_id' => $cols_del
        	);
        	if( ! Modules::run('menus/menus_groups_places/MY_delete',
        	        			//where
        	        			$where
        			)
        	){
        		throw new jqGrid_Exception('�� ������� ������� ����� ��������� � ������� "menus_groups_places"');
        		return false;
        	}
        }
        //���������
        if(is_array($cols_add) && count($cols_add) >= 1){
        	$set = array('group_id' => $id_group,
        	);

        	foreach($cols_add as $key=>$item){
        		$set['place_id'] = $item;
                //��������� ���� � �������� ����������
                $set['sorter'] = $key;
        		if( ! Modules::run('menus/menus_groups_places/MY_insert', $set)){
         			throw new jqGrid_Exception('�� ������� �������� "����� ���������" � ������� "menus_groups_places"');
         			return false;
        		}
        	}
        }
        return true;

    }

     /**
    * �������� ����� ��������� �� ������ �����
    * @param int - id group
    *
    *
    * return boolean - true || false
    */
    public function delete_places($id_group){
    	if( ! isset($id_group)){
    		throw new jqGrid_Exception('�� ������� ������� ��� ��������');
      		return false;
    	}
    	if( ! is_numeric($id_group)){
    		throw new jqGrid_Exception('�� ����� ������� ������� ��� ��������');
      		return false;
    	}
    	// �������

        	$where = array('group_id' => $id_group
        	);
        	if( ! Modules::run('menus/menus_groups_places/MY_delete', $where)){
        		throw new jqGrid_Exception('�� ������� ������� "����� ���������" � ������� "�����"');
        		return false;
        	}else{
        		return true;
        	}

    }

    /**
	*	���������� ������ ��������� �� ���� ���������
	*
	*/
	public function get_groups_have_places($ids){
		$res = $this->MY_data(
					//select
					array('id', 'group_id'),
					//where
					array('place_id' => $ids)
		);
		if($res) return $res;
		return false;
	}

	/**
	*	���������� ����� ��������� ��������� �� �����
	*
	*/
	public function get_have_places_groups($ids){
		$res = $this->MY_data(
					//select
					array('id', 'place_id'),
					//where
					array('group_id' => $ids)
		);
		if($res) return $res;
		return false;
	}
}
