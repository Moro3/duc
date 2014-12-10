<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// ѕодключаем наследуемый класс если он не подгужен
if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 *  ласс Duc_events
 *  —обыти€ дл€ модул€
 */

class Menus_events extends Menus {

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
    * —обытие на удаление файла изображени€ меню
    *	@param string $file - им€ файла с изображением
    *
    */
    public function onTreesImagesDelete($file){
    	Modules::run('menus/menus_trees/resizeDelete', 'trees', false, $file);
    }

    /**
    * —обытие на удаление файлов ресайзов изображений меню
    *	@param string $name_resize - им€ ресайза (если не задано, удал€ютс€ из всех ресайзов)
    *	@param string $file - им€ файла с изображением (если не задано, удал€ютс€ все файлы из ресайза)
    *
    */
    public function onTreesResizeDelete($name_resize = false, $file = false){

    }

    /**
    * —обытие на удаление папки ресайза с изображени€ми меню
    *	@param string $name_resize - им€ ресайза (если не задано, удал€ютс€ все ресайзы)
    *
    *
    */
    public function onTreesResizeDirDelete($name_resize){

    }

    /**
    * —обытие при загрузке файла изображени€ меню
    *	@param string $file - им€ файла с изображением
    */
    public function onTreesImagesUpload($file){
    	Modules::run('menus/menus_images/resize', 'trees', false, $file);
    }



    /**
    * 	—обытие до удалении объекта меню
    *	@param nuveric $id - id объекта
    */
    public function onTreesDeleteBefore($id){

    }

    

}