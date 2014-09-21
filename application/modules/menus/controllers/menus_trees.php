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
		        'ExpandColumn':'type_description',

		        'viewrecords':false
		    };
		    ";
        	echo $this->grid->loader->render($this->table);

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