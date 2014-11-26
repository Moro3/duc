<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ���������� ����������� ����� ���� �� �� ��������


if( ! class_exists('mods')){
  include_once ('mods.php');
}

/*
 * ����� Mods_route
 *
 */

class Mods_route extends Mods {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_mods_route';
        $this->MY_table = 'mods_route';
    }

    /**
     * �������� �� ��� ��������� �������� ���� "uri"
     * @param  string  $uri          ������ ��� ��������
     * @param  array $exception_id   ������������� id
     * @return boolean               true - ������� �����������, false - ����������� �� �������
     */
    function matchesUri($uri, $exception_id = false){
    	if(empty($uri)) return true;
     	
    	$where['uri'] = $uri;
    	if($exception_id !== false){
    		if( ! is_array($exception_id)) $exception_id = array($exception_id);
    		$where['not'] = array('id' => $exception_id);
    	} 
    	
    	$res = $this->MY_data(
    		//select
    		array('id', 'uri'),
    		//where
    		$where
    	);

    	//dd($res);
    	if(is_array($res) && count($res) > 0){
    		return true;
    	}else{
    		return false;
    	}
    }

    /**
     * �������� �� ��� ��������� �������� ���� "name"
     * @param  string  $str          ������ ��� ��������
     * @param  array $exception_id   ������������� id
     * @return boolean               true - ������� �����������, false - ����������� �� �������
     */
    function matchesName($str, $exception_id = false){
        if(empty($str)) return true;
        
        $where['name'] = $str;
        if($exception_id !== false){
            if( ! is_array($exception_id)) $exception_id = array($exception_id);
            $where['not'] = array('id' => $exception_id);
        } 
        
        $res = $this->MY_data(
            //select
            array('id', 'name'),
            //where
            $where
        );
        
        if(is_array($res) && count($res) > 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * �������� �� ��� ��������� �������� ���� "alias"
     * @param  string  $str          ������ ��� ��������
     * @param  array $exception_id   ������������� id
     * @return boolean               true - ������� �����������, false - ����������� �� �������
     */
    function matchesAlias($str, $exception_id = false){
        if(empty($str)) return true;
        
        $where['alias'] = $str;
        if($exception_id !== false){
            if( ! is_array($exception_id)) $exception_id = array($exception_id);
            $where['not'] = array('id' => $exception_id);
        } 
        
        $res = $this->MY_data(
            //select
            array('id', 'alias'),
            //where
            $where
        );
        
        if(is_array($res) && count($res) > 0){
            return true;
        }else{
            return false;
        }
    }


    /**
     * �������� �� ���������� ���� "uri"
     * @param  string $str ������ uri
     * @return boolean      true - ��������� ��������, false - ������ ���������
     */
    function validationUri($str){
        if( $this->form_validation->uri($str)){
            return true;              
        }
        return false;
    }

    /**
     * �������� �� ���������� ���� "name"
     * @param  string $str ������ name
     * @return boolean      true - ��������� ��������, false - ������ ���������
     */
    function validationName($str){
        if( $this->form_validation->alpha_space_ru($str)){
            return true;              
        }
        return false;
    }

    /**
     * �������� �� ���������� ���� "alias"
     * @param  string $str ������ alias
     * @return boolean      true - ��������� ��������, false - ������ ���������
     */
    function validationAlias($str){
        if( $this->form_validation->alpha_dash($str)){
            return true;              
        }
        return false;
    }

}
