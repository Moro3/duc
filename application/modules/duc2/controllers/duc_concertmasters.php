<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ���������� ����������� ����� ���� �� �� ��������
if( ! class_exists('duc')){
  include_once ('duc.php');
}

/*
 * ����� Duc_concertmasters
 *
 */

class Duc_concertmasters extends Duc {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_duc_concertmasters';
    }


	 /**
    * ������ ����� ����������������(���������) ��� ����������
    * @param int - id_group
    * @param array - ������ � ������ id_teacher
    *
    * return boolean - true || false
    */
    function write_teachers($id_group, $id_teachers = array()){
    	if( ! isset($id_group)){
    		throw new jqGrid_Exception('�� ������� ������� ��� ��������������');
      		return false;
    	}
    	if( ! is_numeric($id_group)){
    		throw new jqGrid_Exception('�� ����� ������� ������� ��� ��������������');
      		return false;
    	}
    	//������� ������ �������
    	$rows = Modules::run('duc/duc_concertmasters/MY_data_array',
    	                    //select
    	                    array('id', 'id_teacher'
    	                    ),
    	                    //where
    						array('id_group' => intval($id_group)
    						)
    	);
    	//��� ������ teachers
    	$count_teachers = Modules::run('duc/duc_teachers/MY_data_array',
    										//select
    										array('id')
    	);

    	//�������� ������ teachers � �������
    	foreach($count_teachers as $k=>$v){
    		$arr_teachers[] = $v['id'];
    	}
    	//���� teachers ��� - ���������� ������ � ���������� false
    	if(!isset($arr_teachers) || count($arr_teachers) <= 0){
    		throw new jqGrid_Exception('�� ������ �������� �� �������');
      		return false;
    	}
        //�������� ������� ������ � ����������� �������
        //��������� ����� ������ �� ������� ��������
        if(is_array($rows) && count($rows) > 0){
	        foreach($rows as $key=>$value){
	        	$rows_id[] = $value['id_teacher'];
	        }
	    }else{
	    	$rows_id = array();
	    }
        // �������� ��������� ������ ���������������� � ����������� �������
        // � ��������� ������������ �������
        if(!is_array($id_teachers)){
        	$rows_in = array($id_teachers);
        }else{
        	$rows_in = array();
        	foreach($id_teachers as $item){
        		if(!empty($item)){
        			$rows_in[] = $item;
        		}
        	}
        }
        //���� ��������� ������ �� ������� � ������������ ����������������
        // ���������� ������ � ���������� false
        if(count($rows_in) > 0){
	        if( array_diff($rows_in, $arr_teachers)){
	        	throw new jqGrid_Exception('������ �������������� �������');
	               return false;
	    	}
    	}
        //��������� ������ ��� ����������

        $cols_add = array_diff($rows_in, $rows_id);
        $cols_add = array_unique($cols_add);
        //��������� ������ ��� ��������
        $cols_del = array_diff($rows_id, $rows_in);
        $cols_del = array_unique($cols_del);
        /*
        echo "���� ������:\r\n";
        print_r($rows_id);
        echo "������� ������:\r\n";
        print_r($rows_in);
        echo "����� ��������:\r\n";
        print_r($arr_teachers);
        echo "������ ��� ����������:\r\n";
        print_r($cols_add);
        echo "������ ��� ��������:\r\n";
        print_r($cols_del);
        exit;
        */
        // �������
        if(is_array($cols_del) && count($cols_del) >= 1){
        	$where = array('id_group' => $id_group,
        					'id_teacher' => $cols_del
        	);
        	if( ! Modules::run('duc/duc_concertmasters/MY_delete',
        	        			//where
        	        			$where
        			)
        	){
        		throw new jqGrid_Exception('�� ������� ������� ��������� � ������� "�������������"');
        		return false;
        	}
        }
        //���������
        if(is_array($cols_add) && count($cols_add) >= 1){
        	$set = array('id_group' => $id_group,
        	);

        	foreach($cols_add as $item){
        		$set['id_teacher'] = $item;
        		if( ! Modules::run('duc/duc_concertmasters/MY_insert', $set)){
         			throw new jqGrid_Exception('�� ������� �������� �������� � ������� "�������������"');
         			return false;
        		}
        	}
        }
        return true;

    }

     /**
    * �������� ��������� �� ������ ����������������
    * @param int - id group
    *
    *
    * return boolean - true || false
    */
    function delete_teachers($id_group){
    	if( ! isset($id_group)){
    		throw new jqGrid_Exception('�� ������� ������� ��� ��������');
      		return false;
    	}
    	if( ! is_numeric($id_group)){
    		throw new jqGrid_Exception('�� ����� ������� ������� ��� ��������');
      		return false;
    	}
    	// �������

        	$where = array('id_group' => $id_group
        	);
        	if( ! Modules::run('duc/duc_concertmasters/delete_teachers', $id_group)){
        		throw new jqGrid_Exception('�� ������� ������� �������� � ������� "�������������"');
        		return false;
        	}else{
        		return true;
        	}

    }
}
