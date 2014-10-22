<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Lib_menus_types_mods extends CI_Driver
{
    private $prefix = 'mods';          // префикс модуля

    public $CIC;

    function __construct()
	{
        $this->CIC = & get_instance();
    }

    /**
    *
    *
    */
    function get_data_nodes($ids){
        foreach($ids as $id){
    		$arr_id[] = $id['name'];
    	}

    	$data = Modules::run('mods/mods/get_data_array', $arr_id);
    		//print_r($data_pages);
    		//exit;
    	return $data;
    }

    public function getDataOfType(){
        return Modules::run('mods/mods_api/listSelectPrefix');
    }

}