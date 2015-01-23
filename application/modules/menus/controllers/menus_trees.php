<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен

if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 * Класс Menus_trees
 *
 */

class Menus_trees extends Menus {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_menus_trees';
        $this->MY_table = 'menus_trees';        
    }

    function grid_render(){

        $modal = '
        $(document).ready(function(){
        	$(".iframe").colorbox({iframe:true, width:"90%", height:"90%"});
        });
        ';
        $modal_func = '
        function opPageEdit(){
        	$(".iframe").colorbox({iframe:true, width:"90%", height:"90%"});
        };
        ';
        //echo "\r\n<script>";
        //echo $modal;
        //echo 'open_page_edit();';
        //echo "</script>\r\n";
        //assets_script($modal_func, false);
        //echo '<a class="iframe" href="/ajaxs/?resource=pages/admin/pages~arg=_search=true&nd=1371768949471&rows=20&page=1&sidx=id&sord=desc&id=36">Страницы</a>';
        $this->grid_params();

                
        //-------------- Загрузка таблицы jQGrid
   		echo "\r\n<script>";
        	
            echo "
        		var opts = {
    		        'treeGrid':true,
    		        'treeGridModel':'adjacency',
                    //'treedatatype': 'local',
    		        'ExpandColumn':'type_description',
                    'ExpandColClick': true,
                    
    		        'viewrecords':false
                };
		    ";
            
        	if($place = $this->input->get('place')){
                if(is_numeric($place)){
                    $res = Modules::run('menus/menus_places/MY_data',
                        //select
                        '*',
                        //where
                        array('id' => $place)
                    );
                    if(!empty($res)){
                       echo $this->grid->loader->render($this->table); 
                   }                    
                }
            }

        echo "</script>\r\n";

       

        //Действия после построения grid
        //активируем модальное окно для ссылки на страницу
		echo '<script>';
		echo '$grid.bind(\'jqGridGridComplete\', function(event, $form)
			{

		        $(".iframe").colorbox({iframe:true, width:"90%", height:"90%"});

			});
		';
        echo '</script>';

        //------ Загрузка скрипта для подгрузки списка данных меню в зависимости от типа
        //------ Подгружаем после прорисовки формы редактирования
        $images = Modules::run('menus/menus_images/MY_data',
            //select
            '*',
            //where
            false,
            //limit
            false,
            //order
            'sorter'
        );
        $resize = Modules::run('menus/menus_settings/get_param_resize', 'trees');
        $path_images = '/'.$resize['middle']['path'].$resize['middle']['dir'];
        foreach($images as $items){
            $dataImages[$items->id] = array(  'value' => $items->id,
                                                'image' => $items->file,
                                                'name' => $items->name,
                                                'path' => $path_images
                                            ); 
        }
        
        //echo "\r\n<script>";        
            //echo '$grid.bind("jqGridAddEditAfterShowForm", function(event, $form){';
                $selectImages = $this->load->view('form/selectImages',
                        array(
                                'event' => '#image_list',
                                'selector' => 'selImages',
                                'data' => $dataImages,
                                
                        ),
                        true
                );
                //echo js_escape($selectImages);
                echo $selectImages;
            //echo '})';    
        //echo '</script>';

        //------ Загрузка списка изображений
        //------ Подгружаем после прорисовки формы редактирования        
        echo "\r\n<script>";        
            echo '$grid.bind("jqGridAddEditAfterShowForm", function(event, $form){';                   
                        
                echo Modules::run('menus/menus_api/tplDataTypeAjax');

            echo '})';    
        echo '</script>';
        
         // возможность сортировки полей методом drag and drop
        $this->load->view('grid/sorter/sortRowsTree',
                         array(
                                'selector' => '.tr',
                                'grid' => $this->table,
                                'url' => '/grid/'.$this->MY_table.'/'.$this->MY_module.'/grid/'
                         )
        );
        //assets_script($sortrows, 'menus');
        //assets_script(Modules::run('menus/menus_result/tplDataTypeAjax'), 'menus');

	}

	/**
	*	Возвращает группы зависящие от мест положений
	*
	*/
	public function get_trees_have_places($ids){
		$res = $this->MY_data(
					//select
					array('id', 'place_id', 'name'),
					//where
					array('place_id' => $ids)
		);
		if($res) return $res;
		return false;
	}


}