<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ѕодключаем наследуемый класс если он не подгужен
if( ! class_exists('duc')){
  include_once ('duc.php');
}

/*
 *  ласс Duc_events
 *  —обыти€ дл€ модул€
 */

class Duc_events extends Duc {

    function __construct(){
        parent::__construct();

    }

    /**
    * —обытие на удаление файлов ресайзов изображений
    *	@param string $name_group - им€ группы ресайза
    *	@param string $name_resize - им€ ресайза (если не задано, удал€ютс€ из всех ресайзов)
    *	@param string $file - им€ файла с изображением (если не задано, удал€ютс€ все файлы из ресайза)
    *
    */
    public function onResizeDelete($name_group, $name_resize = false, $file = false){

    }

    /**
    * —обытие на удаление файла изображени€ педагога
    *	@param string $file - им€ файла с изображением
    *
    */
    public function onTeachersImagesDelete($file){    	Modules::run('duc/duc_teachers/resizeDelete', 'teachers', false, $file);
    }

    /**
    * —обытие на удаление файлов ресайзов изображений педагога
    *	@param string $name_resize - им€ ресайза (если не задано, удал€ютс€ из всех ресайзов)
    *	@param string $file - им€ файла с изображением (если не задано, удал€ютс€ все файлы из ресайза)
    *
    */
    public function onTeachersResizeDelete($name_resize = false, $file = false){

    }

    /**
    * —обытие на удаление папки ресайза с изображени€ми педагогов
    *	@param string $name_resize - им€ ресайза (если не задано, удал€ютс€ все ресайзы)
    *
    *
    */
    public function onTeachersResizeDirDelete($name_resize){

    }

    /**
    * —обытие при загрузке файла изображени€ педагога
    *	@param string $file - им€ файла с изображением
    */
    public function onTeachersImagesUpload($file){
    	Modules::run('duc/duc_teachers/resize', 'teachers', false, $file);
    }



    /**
    * 	—обытие до удалении объекта педагога
    *	@param nuveric $id - id объекта
    */
    public function onTeachersDeleteBefore($id){

    }

    /**
    * —обытие на удаление файла изображени€ коллектива
    *	@param string $file - им€ файла с изображением
    *
    */
    public function onGroupsImagesDelete($file){
    	Modules::run('duc/duc_photos/resizeDelete', 'groups', false, $file);
    }

    /**
    * —обытие при загрузке файла изображени€ коллектива
    *	@param string $file - им€ файла с изображением
    */
    public function onGroupsImagesUpload($file){
    	Modules::run('duc/duc_photos/resizeLoading', 'groups', $file);
    	Modules::run('duc/duc_photos/resize', 'groups', false, $file);
    }

}