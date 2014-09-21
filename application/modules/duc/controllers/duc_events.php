<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ���������� ����������� ����� ���� �� �� ��������
if( ! class_exists('duc')){
  include_once ('duc.php');
}

/*
 * ����� Duc_events
 *  ������� ��� ������
 */

class Duc_events extends Duc {

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
    * ������� �� �������� ����� ����������� ��������
    *	@param string $file - ��� ����� � ������������
    *
    */
    public function onTeachersImagesDelete($file){    	Modules::run('duc/duc_teachers/resizeDelete', 'teachers', false, $file);
    }

    /**
    * ������� �� �������� ������ �������� ����������� ��������
    *	@param string $name_resize - ��� ������� (���� �� ������, ��������� �� ���� ��������)
    *	@param string $file - ��� ����� � ������������ (���� �� ������, ��������� ��� ����� �� �������)
    *
    */
    public function onTeachersResizeDelete($name_resize = false, $file = false){

    }

    /**
    * ������� �� �������� ����� ������� � ������������� ���������
    *	@param string $name_resize - ��� ������� (���� �� ������, ��������� ��� �������)
    *
    *
    */
    public function onTeachersResizeDirDelete($name_resize){

    }

    /**
    * ������� ��� �������� ����� ����������� ��������
    *	@param string $file - ��� ����� � ������������
    */
    public function onTeachersImagesUpload($file){
    	Modules::run('duc/duc_teachers/resize', 'teachers', false, $file);
    }



    /**
    * 	������� �� �������� ������� ��������
    *	@param nuveric $id - id �������
    */
    public function onTeachersDeleteBefore($id){

    }

    /**
    * ������� �� �������� ����� ����������� ����������
    *	@param string $file - ��� ����� � ������������
    *
    */
    public function onGroupsImagesDelete($file){
    	Modules::run('duc/duc_photos/resizeDelete', 'groups', false, $file);
    }

    /**
    * ������� ��� �������� ����� ����������� ����������
    *	@param string $file - ��� ����� � ������������
    */
    public function onGroupsImagesUpload($file){
    	Modules::run('duc/duc_photos/resizeLoading', 'groups', $file);
    	Modules::run('duc/duc_photos/resize', 'groups', false, $file);
    }

}