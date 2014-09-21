<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен

/***
   Внимание!!!
   Файл сохранен в кодировке cp1251 специально
   чтобы корректно происходило преобрахование строки кирилицы в латиницу в функции rus2lat

**/
if( ! class_exists('pages')){
  include_once ('pages.php');
}

/*
 * Класс Pages_headers
 *
 */

class Pages_headers extends Pages {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_pages_headers';
    }

    function grid_admin_object(){
        $script_image_dropdown = '
		$(document).ready(function(e) {
			try {
				$("#select_img select").msDropDown({childwith:650,width:650,
				  visibleRows:4, rowHeight:40
				});
			} catch(e) {
				alert(e.message);
			}
		});
		';

		 $style_image_combobox = '
           .ddcommon .ddTitle .ddTitleText img{height:30px}
           .ddcommon .ddChild li img{height:30px}
		   .dd .ddChild li img{height:30px}
		 ';
		 assets_style($style_image_combobox, false);


        //print_r($this->grid->loader->getInputData());

        echo "\r\n<script>";
        //echo "xhr.setRequestHeader('X-REQUESTED-WITH', 'XMLHttpRequest');";

        echo <<<label
        var opts =
		{
		    //'caption'	: 'File Uploading',
		    'editurl'   : null, //this is required for dataProxy effect
		    'dataProxy' : $.jgrid.ext.ajaxFormProxy //our charming dataProxy ^__^
		}
label;

		 echo "</script>\r\n";



        $this->tpl_js_images_edit();
        $this->grid_render();

        //====== Для поиска с диапазоном дат

        echo '<script>';
        echo "function dateRangePicker_onChange() {
	        var \$input = $('#gs_date');
	        var old_val = \$input.val();

	        setTimeout(function () {
	            if (\$input.val() == old_val) {
	                \$grid[0].triggerToolbar();
	            }
	        }, 50);
	    }";
    	echo '</script>';


        echo '<script>
$grid.bind("jqGridAddEditBeforeShowForm", function(event, $form)
{
	var row_id = $(this).getGridParam("selrow");
	var imghtml = $(this).getCell(row_id, "foto");
	//var fotolist = $(this).getCell(row_id, "foto_list");

	$("#foto").replaceWith("<div id=\"foto\">" + imghtml + "</div>");
	//$("#foto_list").replaceWith(fotolist);
	//$("#foto").append(imghtml);
    //$form.find("#foto_upload").before(imghtml);
});
</script>';

	}

	 /**
    *	шаблон обработки изображения на javascript
    * 	Для вывода через jqGrid в режиме редактирования
    *
    */
    function tpl_js_images_edit(){
    	 $data['uri']['point'] = $this->uri_point('user');
    	 $data['config'] = $this->setting;
    	 $this->load->view('admin/js/js_images_pages_edit', $data );
    }

    /**
    *	проверка uri на дублирование
    *
    */
	public function get_check_uri($uri, $ignore_id = false){
		$where = array('uri' => $uri);
		if(!empty($ignore_id)) $where['id !='] = $ignore_id;
    	$res = $this->MY_data_array_row(
    		//select
    		array('id', 'uri'),
    		//where
    		$where
    	);
    	if(!empty($res)) return $res;
    	return false;
	}

	/**
    *	генерация uri на основе названия
    *
    */
	public function generate_uri($name){
    	$name = iconv('utf-8','cp1251', $name);
    	$uri = $this->rus2lat($name);
        $uri = $this->get_correct_uri($uri);
        return $uri;
	}

	/**
    *	проверка uri
    *
    */
	public function check_uri($uri){
    	// проверяем uri главной страницы
    	if($uri === '/') return true;

    	//все остальные проверяем по шаблону
    	$uri = iconv('utf-8','cp1251', $uri);
    	//var_dump($uri);
        //exit;
    	$pattern = '|^[A-z0-9_-]+$|i';
    	if( preg_match($pattern, $uri)){        	return true;
    	}
    	return false;
 	}

	/**
	* Возвращает корректный uri
	* т.е. без повторений
	*/
	public function get_correct_uri($uri){		$i = 2;
        $urich = $uri;
        while($this->get_check_uri($urich)){
        	$urich = $uri.'_'.$i;
        	$i++;
        }
        return $urich;
	}


    /**
    * Полное удаление страницы
    * @param $id - numeric (id страницы pages_headers)
    *
    *	@return boolean
    */
    public function delete_page($id){    	$where = array('id' => $id);

    	$res = $this->MY_data(
    		//select
    		array(
    			'related' => array('content'),
    		),
    		//where
    		$where
    	);
    	//throw new jqGrid_Exception(print_r($res));
    	if(is_array($res)){    		foreach($res as $items){
    			if(isset($items->content)){    				foreach($items->content as $item){    					if(isset($item->id)){    						$res_content = Modules::run('pages/pages_contents/MY_data',
					    		//select
					    		array(
					    			'related' => array('seo'),
					    		),
					    		//where
					    		array('id' => $item->id)
					    	);
    					}
    				}
    			}
    		}
    	}

		if(isset($res_content) && is_array($res_content)){			foreach($res_content as $items_content){				if(isset($items_content->seo)){                	foreach($items_content->seo as $item){
    					if(isset($item->id)){
    						$res_seo = Modules::run('pages/pages_seo/MY_data',
					    		//select
					    		array('id'

					    		),
					    		//where
					    		array('id' => $item->id)
					    	);
    					}
    				}
				}
			}
		}
		//Удаление из таблицы pages_seo
		if(isset($res_seo)){			foreach($res_seo as $item){				if(isset($item->id)){					Modules::run('pages/pages_seo/MY_delete',
						//where
						array('id' => $item->id)
					);
				}
			}
		}
		//Удаление из таблицы pages_contents
		if(isset($res_content)){
			foreach($res_content as $item){
				if(isset($item->id)){
					Modules::run('pages/pages_contents/MY_delete',
						//where
						array('id' => $item->id)
					);
				}
			}
		}
		//Удаление из таблицы pages_headers
		if(isset($res)){
			foreach($res as $item){
				if(isset($item->id)){
					$return = Modules::run('pages/pages_headers/MY_delete',
						//where
						array('id' => $item->id)
					);
				}
			}
		}

		if($return) return true;
		return false;
		//$rel_head = $this->MY_related_has_many('content', $id);
    	//throw new jqGrid_Exception(print_r($res_seo));
    	//print_r($res);
    	//exit;
    }

    /**
    *	События на удаление страницы
    *	@param $id - id страницы
    *
    *	@return возвращает текст ошибки сообщения при срабатывания событий
    */
    public function eventDeletePage($id){
    	if(Modules::run('pages/pages_headers/delete_page', $id)){    		return true;
    	}
    	return false;
    }
}