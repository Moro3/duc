<?php (defined('BASEPATH')) OR exit('No direct script access allowed');


class Lib_menus_types_pages extends CI_Driver
{
    //private $prefix = 'pages';          // префикс модул€
    private $type = 'page';
    private $driver = 'pages';
    private $select;
    public $CIC;

    function __construct()
	{
    	$this->load_data();

        $this->CIC = & get_instance();

    }

    /**
    * «агрузка переменных по умолчанию
    *
    */
    function load_data(){
         $this->select = array(
            'name' => 'name',
            'link' => 'uri',
            'active' => 'active'
         );
    }

    /**
    *  ѕолучение данных
    *  @param - array: многомерный массив с даными, ключ - id узла дерева(name - им€ узла (идентификатор содержимого), type - идентификатор типа)
    *
    *
    */
    function get_data_nodes($ids){
    	foreach($ids as $id){
    		$arr_id[] = $id['name'];
    	}

    	//$data = Modules::run('pages/pages/get_data_pages_array', $arr_id);

    	//получаем данные страниц
      $data = $this->get_data_pages_array($arr_id);    		

      // получаем ID типа по его драйверу  
      $type_id = $this->get_id_is_driver($this->driver);
       
      //преобразуем данные в требуемый формат
      foreach($data as &$items){
       		//$active = ($items['active'] === 1 && $items->content[0]->active === 1) ? 1 : 0;
          $active = ($items['active'] == 1) ? 1 : 0;
       		$result[$items['id']] = array(
                          'id' => $items['id'],
       		                'type' => $this->type,
       		                'type_id' => $type_id,
                          'driver' => $this->driver,
       		                'name' => $items['content'][0]->name,
       		                'active' => $active,
       		                'link' => Modules::run('pages/pages/getFieldUri', $items['uri']),

                          // опциональные данные
                          'label' => $items['label'],
                          'img' => $items['icon']

       		);

      }

    	return $result;
    }

    /**
    * 	¬озвращает данные о страницах
    *
    */
    public function get_data_pages_array($ids){
        if( ! is_array($ids)) $ids = array($ids);
   		$res = Modules::run('pages/pages_headers/MY_data_array',
   			//select
   			array('main.id','main.active', 'main.label', 'main.uri', 'main.icon', 'main.text1', 'main.text2', 'main.img_fon', 'main.icon',
   				 //'seo.title', 'seo.description', 'seo.keywords', 'seo.h1',
   				 //'content.id', 'content.name', 'content.active', 'content.description',
   					'related' => array('content' => array(
   														'main.id', 'main.active', 'main.name', 'main.description',
   														//'seo.id', 'seo.title', 'seo.description', 'seo.keywords',
   														'related' => array('seo' => array('id', 'title', 'description', 'keywords', 'h1'))
   										),

   					),
   			),
   			//where
   			array('main.id' => $ids,
   				//'content.id_page_header' => $id,
   				//'seo.id_page_content' => array('encode' => 'content.id')

   			)

   		);
   		foreach($ids as $items){
   			if(isset($res[$items])) $result[$items] = $res[$items];
   		}
   		return $result;
    }

    public function getDataOfType(){
        return Modules::run('pages/pages_api/listPagesSelect');
    }

}