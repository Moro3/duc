<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Lib_menus_types_mods extends CI_Driver
{
    private $type = 'mod';
    private $prefix = 'mods';          // ������� ������
    private $driver = 'mods';

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

    	// �������� ������ �������
        $data = $this->get_data_array($arr_id);
    	
        // �������� ID ���� �� ��� ��������  
        $type_id = $this->get_id_is_driver($this->driver);
       
        //����������� ������ � ��������� ������
        foreach($data as &$items){
            //$active = ($items['active'] === 1 && $items->content[0]->active === 1) ? 1 : 0;
            $active = ($items['active'] == 1) ? 1 : 0;
            $result[$items['id']] = array(
                            'id' => $items['id'],                            
                            'type' => $this->type,
                            'type_id' => $type_id,
                            'driver' => $this->driver,
                            'name' => $items['name'],
                            'active' => $active,
                            'link' => '/'.trim($items['uri'],'\/').'/',

                            // ������������ ������
                            'short_description' => $items['short_description'],
                            'description' => $items['description']
            );

        }

    	return $result;
    }

    /**
    *   ���������� ������ � ���������
    *
    */
    public function get_data_array($ids){
        if( ! is_array($ids)) $ids = array($ids);
        $res = Modules::run('mods/mods/MY_data_array',
            //select
            '*',
            //where
            array('id' => $ids)

        );
        foreach($ids as $items){
            if(isset($res[$items])) $result[$items] = $res[$items];
        }
        return $result;
    }

    public function getDataOfType(){
        return Modules::run('mods/mods_api/listSelectPrefix');
    }

}