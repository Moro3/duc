<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ���������� ����������� ����� ���� �� �� ��������
if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 * ����� Duc_events
 *  ������� ��� ������
 */

class Menus_events extends Menus {

    function __construct(){
        parent::__construct();

    }

    /**
    * ������� �� �������� ������ �������� �����������
    *	@param string $name_group - ��� ������ �������
    *	@param string $name_resize - ��� ������� (���� �� ������, ��������� �� ���� ��������)
    *	@param string $file - ��� ����� � ������������ (���� �� ������, ��������� ��� ����� �� �������)
    *
    */
    public function onResizeDelete($name_group, $name_resize = false, $file = false){

    }

    /**
    * ������� �� �������� ����� ����������� ����
    *	@param string $file - ��� ����� � ������������
    *
    */
    public function onTreesImagesDelete($file){
    	Modules::run('menus/menus_trees/resizeDelete', 'trees', false, $file);
    }

    /**
    * ������� �� �������� ������ �������� ����������� ����
    *	@param string $name_resize - ��� ������� (���� �� ������, ��������� �� ���� ��������)
    *	@param string $file - ��� ����� � ������������ (���� �� ������, ��������� ��� ����� �� �������)
    *
    */
    public function onTreesResizeDelete($name_resize = false, $file = false){

    }

    /**
    * ������� �� �������� ����� ������� � ������������� ����
    *	@param string $name_resize - ��� ������� (���� �� ������, ��������� ��� �������)
    *
    *
    */
    public function onTreesResizeDirDelete($name_resize){

    }

    /**
    * ������� ��� �������� ����� ����������� ����
    *	@param string $file - ��� ����� � ������������
    */
    public function onTreesImagesUpload($file){
    	Modules::run('menus/menus_images/resize', 'trees', false, $file);
    }



    /**
    * 	������� �� �������� ������� ����
    *	@param nuveric $id - id �������
    */
    public function onTreesDeleteBefore($id){

    }

    

}