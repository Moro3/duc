<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Класс Pages - основной класс для работы со страницами
 *
 *
 */

class Pages extends MY_Controller {

    //настройки модуля
    protected $setting;

    function __construct(){
        parent::__construct();
        $this->load->helper(array('form', 'url', 'assets'));
        $this->load->library('form_validation');
        $this->load->library('control_uri');
        $this->load->library('pagination');


        $this->lang->load('pages');
        $this->load->helper('language');
        $this->load->helper('text');

        //$this->config->load('index_request', TRUE);
        //$this->index_request = $this->config->item('index_request', 'index_request');

        //$this->config->load('menu', TRUE);
        //$this->get = $this->config->item('menu', 'menu');
        $this->config->load('setting', TRUE);
        $this->setting = $this->config->item('setting');

        //$this->load->library('datetimepicker');
        $this->load_config();
        $this->load_models();

        $this->table = 'pages';

    }

    function load_config(){
		$this->config->load('setting', true);
		$this->setting = $this->config->item('setting');
        //print_r($this->setting);
        //exit;
	}

	function load_models(){
    	$this->load->model('pages_contents_model');
    	$this->load->model('pages_headers_model');
    	$this->load->model('pages_seo_model');
    	//$this->load->model('pages_mod_model');
    }

    function index(){

    }

    // Маршрутизация модуля
    function admin_index(){
        $buf = '';

        //$this->assets->load_style('pages.css', 'pages');
        //$this->assets->load_script('jquery/jquery-ui/plugin/jquery-ui-timepicker-addon.js', false);
        //echo "<pre>";
        //print_r ($this->router_modules->tree_menu('admin'));
        //echo "</pre>";
        $this->admin_menu_view();
//        echo "!!";
        //$this->grid_render();
//        echo "<script>";
//        echo $this->grid->loader->render('grid_news');
//        echo "</script>";

        // запускаем маршрут для модуля с именем группы admin (file: route), модуля duc, вернуть сгенерированное маршрутом
        $buf .= $this->router_modules->run('admin', 'pages', true);

        echo $buf;
        //exit();
        //return $buf;
    }

    /*
     * Шаблон меню модуля по умолчанию
     */
    function admin_menu_view(){
        //$data['tree'] = $this->router_modules->tree_menu('admin');
        $data['tree'] = $this->control_uri->route->tree_menu('admin');
        $data['uri']['point'] = $this->load->module('pages/pages')->uri_point('admin');
        //$data['uri']['point'] = '/';

		if(is_array($data['tree'])){
			//echo "<pre>";
			//print_r($data['tree']);
			//echo "</pre>";
			echo "<div class=\"menu_module\">";
			$this->print_tree($data['tree'], 0, 0, array('uri' => $data['uri']));
			echo "</div>";
		}

        //$this->load->view('admin/menu_default_2', $data);
    }


	function print_tree($tree, $current_key = 0, $level = 0, $options = false){
        if(isset($tree[$current_key])){
        	$arr = array('data' => $tree[$current_key], 'current' => $current_key, 'level' =>  $level);
        	$data = array_merge($arr, $options);
        	$this->load->view('admin/menu_default_row', $data);

	        foreach($tree[$current_key] as $key=>$value){

				 //if($key ){

						if(isset($tree[$key]) && $value['path_link'] == 1 || $value['active_link'] == 1){
	                        $l = $level + 1;
							$this->print_tree($tree, $key, $l, $options);
						}
				 //}

			}
        }
	}

    /*
     *  точка старта запроса uri
     */
    function uri_point($name = ''){
        switch ($name){
            case 'admin':
                $uri = $this->control_uri->get_full_segment('admin','mod');
                $uri .= 'pages/';
            break;
            case 'user':
                $uri = $this->control_uri->get_full_segment('pages');
                $uri = 'pages/';
            break;
            case 'user_main':
                $uri = '/pages/';
            break;
            case 'admin_ajax':
                $uri = '/ajax/pages/admin/';
            break;
            case 'admin_ajax_pages_delete_foto':
                 // Uri для удаления фото педагога: admin
                 // (user_list_object_main)
                 $data['uri']['object'] = $this->router_modules->generate_link('pages_foto_delete', 'ajax', 'pages');
             break;
            default:
                $uri = $this->control_uri->get_full_segment('pages');
                $uri .= '';
            break;
        }

        return $uri;
    }

	function grid_admin_object(){
        $this->grid_render();
	}

	function grid(){
        $this->grid_params();
        // header("Content-type: text/json;charset=utf-8");

        if(isset($_POST['id']) && isset($_POST['oper'])){
            if(is_numeric($this->input->post('id')) && $this->input->post('oper') == 'edit'){
            	$this->grid->loader->oper($this->table, 'edit');
            }elseif(is_numeric($this->input->post('id')) && $this->input->post('oper') == 'del'){
            	$this->grid->loader->oper($this->table, 'del');
            }else{
            	$this->grid->loader->oper($this->table, 'add');
            }
        }else{
        	$this->grid->loader->output($this->table);
        }
    }

    function grid_render(){

        $this->grid_params();

//-------------- Подключаем нужный визуальный редактор (WYSIWYG)
		echo '<script type="text/javascript" src="/wysiwyg/fckeditor/fckeditor.js"></script>';
		//echo '<script type="text/javascript" src="/wysiwyg/tiny_mce/tiny_mce.js"></script>';
        //echo '<script type="text/javascript" src="/wysiwyg/ckeditor/ckeditor.js"></script>';



//-------------- Загрузка таблицы jQGrid
   		echo "\r\n<script>";
        	echo $this->grid->loader->render($this->table);
        echo "</script>\r\n";



//------------- Обработчики событий для полей с использованием FCKeditor -----------

		//для вывода полей с использованием FCKeditor
		echo '<script>';
		echo '$grid.bind(\'jqGridAddEditAfterShowForm\', function(event, $form)
			{

                //var oFCKeditor = new FCKeditor( "description" ) ;
				//oFCKeditor.BasePath = "/wysiwyg/fckeditor/" ;
                //oFCKeditor.Height = 300 ;
                //oFCKeditor.ToolbarSet = "Basic";
				//oFCKeditor.ReplaceTextarea() ;

				gridEditWysiwyg("description");
			});
		';

        //echo '</script>';
        //echo "<script>";
		// Для записи изменений с использованием FCKeditor
        echo '$grid.bind(\'jqGridAddEditClickSubmit\', function(event, $form)
			{
				oEditor = FCKeditorAPI.GetInstance("description"); //получаем ссылку на объект "редактор"
				//description   = oEditor.GetXHTML("html");

				//text = oEditor.GetXHTML("html");
				return {
			     description: oEditor.GetHTML() //вызываем метод у объекта
			    };
			});
        ';
        echo 'function gridEditWysiwyg(field)
			{
                var oFCKeditor = new FCKeditor( field ) ;
				oFCKeditor.BasePath = "/wysiwyg/fckeditor/" ;
                oFCKeditor.Height = 300 ;
                oFCKeditor.ToolbarSet = "BasicC";
				oFCKeditor.ReplaceTextarea() ;
			}';

        echo '</script>';



    }

    function grid_params(){
        $get = '';
        if(isset($_GET['id']) && is_numeric($_GET['id'])){        	$get = 'id='.$_GET['id'];
        }
        $path_to_tables = Modules::current_path().'models/grid/';
        $this->grid->loader->set("grid_path", $path_to_tables);
        //$this->grid->loader->set("images_path", assets_uploads().'images/lands/objects/');
        //$this->grid->loader->set("path_root", FCPATH);
        $this->grid->loader->set("config", $this->setting);
        $this->grid->loader->set('debug_output', true);
    }

    /**
	*  Генерация имени файла для фото объектов
	*
	*  @param numeric - id объекта
	*
	*  return string - строка md5
	*/
    function generate_file_name_object($file_name){
    	//$file_name = $file_name.mktime();
        //echo $file_name;

        //echo $file;
        //echo '<br>'.$ext;
        //exit;
        $file_name = iconv('utf-8','cp1251', $file_name);

        $file_name = Modules::run('pages/pages_headers/rus2lat', $file_name, 'low');

        $info = pathinfo($file_name);
		$ext = ".".$info['extension']; // искомое расширение
		$p = strrpos($file_name, '.');
    	if ($p > 0) $file = substr($file_name, 0, $p);
    	else $file = $file_name;

        //$file_name = $this->rus2lat($file_name, 'low');
        $name =  'page_'.$file;
        $dir_source = $this->setting['path']['root'].$this->setting['path']['icons'].$this->setting['image_config']['dir'].'/';
        $i = 2;
        $name_tmp = $name;
        while(is_file($dir_source.$name_tmp.$ext)){        	$name_tmp = $name.'_'.$i;
        	$i++;
        }
    	//echo $name_tmp;
    	//exit;
    	return $name_tmp.$ext;
    }

    /**
    *	Удаление изображения c диска
    *   @param string - имя файла
    */
    function delete_image_object($file_name){
    	if(!empty($file_name)){
	    	$file_source = $this->setting['path']['root'].$this->setting['path']['icons'].$this->setting['image_config']['dir'].'/'.$file_name;
	    	if(is_file($file_source)){
	    		 if(unlink($file_source)) return true;
	    	}
	    	return true;
	  	}
	  	return false;
    }

    //загрузка изображения
    function upload_file($name){
        if(empty($name)) return false;
			$config['upload_path'] = $this->setting['path']['root'].$this->setting['path']['icons'].$this->setting['image_config']['dir'];
			$config['allowed_types'] = $this->setting['image_config']['allowed_types'];
			$config['max_size']	= $this->setting['image_config']['max_size'];
			$config['max_width'] = $this->setting['image_config']['max_width'];
			$config['max_height'] = $this->setting['image_config']['max_height'];
           	$config['change_name'] = Modules::run('pages/pages/generate_file_name_object', $name);
            //$this->CI->output->set_header("X-Requested-With: XMLHttpRequest");
            //$this->CI->output->set_header("ci-cms: rules");
           	//header('X-Requested-With: XMLHttpRequest');
			//echo $config['change_name'];
			$this->load->library('upload', $config);
            //print_r($config);
			if ( ! $this->upload->do_upload('foto_upload')){
				//$error = array('error' => $this->upload->display_errors());
                throw new jqGrid_Exception($this->upload->display_errors());
			}else{
				$files_data = $this->upload->data();
				//Modules::run('duc/duc_teacher/images_resize_object', $files_data['file_name']);
				//Modules::run('lands/lands_photos/delete_images_original_object', $files_data['file_name']);

			}
            //$data['icon'] = $files_data['file_name'];

            return $files_data;
	}

	/**
	* Удаление изображения POST запросом по ID странице
	*
	*/
	function delete_foto(){

		if($this->input->post('id')){
			$id = $this->input->post('id');
			$row = Modules::run('pages/pages_headers/MY_data_row',
			                    //select
			                    array('id', 'icon'),
			                    //where
			                    array('id' => $id)

			);

			if($row->id == $id){
				if(!empty($row->icon)){
					// ищем строки с таким же названием фото кроме текущего id
					$row_foto = Modules::run('pages/pages_headers/MY_data_row',
			                    //select
			                    array('id', 'icon'),
			                    //where
			                    array('icon' => $row->icon,
			                    		'id !=' => $row->id
			                    )
					);
					//если строк с таким же изображением не найдено, удаляем фото
				    if(empty($row_foto)) $this->delete_image_object($row->icon);
					//удаляем запись фото из базы
							if(Modules::run('pages/pages_headers/MY_update',
									//set
									array('icon' => ''),
									//where
									array('id' => $row->id)
								)){
								echo 'Изображение удалено';
								//return true;
							}


				}else{
					echo 'Изображение нет';
				}
			}else{
				echo 'ID не найден';
			}
		}
		//return false;
	}

	/**
	*  Ресайз фото для объектов
	*  @param string - имя файла
	*
	*/
    function images_resize_object($file_name){
    	if(!empty($file_name)){
	    	$file_source = $this->setting['path']['root'].$this->setting['path']['icons'].$this->setting['image_config']['dir'].'/'.$file_name;
	    	if(is_file($file_source)){
		    	//echo 'путь источника: '.$file_source.'<br />';
		    	$this->load->library('image_lib');
		    	$config['image_library'] = 'imagemagick';
		    	//$config['quality'] = '90%';
		    	//$config['library_path'] = 'Z:\usr\local\php5\ext';
		    	//$config['library_path'] = 'Z:\usr\local\ImageMagick-6.8.0-Q16';
		    	//$config['library_path'] = 'Z:\usr\local\ImageMagick-6.5.1-Q16';

		    	//---64-bit
		    	$config['library_path'] = 'Z:\usr\local\ImageMagick-6.8.4-Q16';
		    	//--- хостинг SWEB
		    	//$config['library_path'] = '/usr/bin/convert';
				$config['source_image'] = $file_source;
				//$config['new_image'] = $this->setting['path']['root'].$this->setting['path']['images'].$value['dir'].'/'.$file_name;
					//echo 'путь новый: '.$config['new_image'].'<br />';
                $size = getimagesize($file_source);

                if($size[0] > $this->setting['image_config']['x'] || $size[1] > $this->setting['image_config']['y']){
					$config['width'] = $this->setting['image_config']['x'];
					$config['height'] = $this->setting['image_config']['y'];
				}else{
					$config['width'] = $size[0];
					$config['height'] = $size[1];
				}
					// уменьшаем оригинал до требуемого в настройках
					$config['maintain_ratio'] = TRUE;
					//$config['width'] = $this->setting['image_config']['x'];
					//$config['height'] = $this->setting['image_config']['y'];
                    $this->image_lib->initialize($config);
					if ( ! $this->image_lib->resize())
					{
					    throw new jqGrid_Exception($this->image_lib->display_errors());
					    echo $this->image_lib->display_errors();
					    exit;
					}
					$this->image_lib->clear();
					$size = getimagesize($file_source);


				//$config['image_library'] = 'imagemagick';
				//$config['library_path'] = '/usr/bin/convert';
				//$config['library_path'] = 'Z:\usr\local\ImageMagick-6.8.0-Q16';
				//$config['source_image'] = $file_source;

		        // делаем ресайз остальных
		    	foreach($this->setting['images'] as $key=>$value){
					$config['new_image'] = $this->setting['path']['root'].$this->setting['path']['icons'].$value['dir'].'/'.$file_name;
					if($size[0] > $value['x'] || $size[1] > $value['y']){
						//echo 'путь новый: '.$config['new_image'].'<br />';
						$config['maintain_ratio'] = TRUE;
						$config['width'] = $value['x'];
						$config['height'] = $value['y'];
	                    $this->image_lib->initialize($config);
						if ( ! $this->image_lib->resize())
						{
						    throw new jqGrid_Exception($this->image_lib->display_errors());
						    echo $this->image_lib->display_errors();
						    exit;
						}
						$this->image_lib->clear();
					}else{
						copy($config['source_image'],$config['new_image']);
					}
		    	}
	    	}else{
            	throw new jqGrid_Exception('Не найден оригинал файла: '.$file_name);
	    	}
    	}else{
    		throw new jqGrid_Exception('Имя файла пустое: '.$file_name);
    	}
    }

	/**
	* Массив из списка доступных файлов для иконок
	* Файлы считываются из назначенной директории
	*
	*/
	function array_icon(){
		$path_icon = $this->setting['path']['icons'].$this->setting['image_config']['dir'].'/';
		$dh  = opendir($path_icon);
		//$files[0] = 'не выбрано';
		$files = array();
		$i = 1;
		while (false !== ($filename = readdir($dh))) {
            if(is_file($path_icon.$filename)){
		    	$files[$i] = $filename;
		    	$i++;
            }
		}
        return $files;
	}

	/**
	* Массив из списка доступных файлов для фона в шапке
	* Файлы считываются из назначенной директории
	*
	*/
	function array_img_fon(){
		//$path_icon = $this->setting['path']['img_fon'];
		$path_img = $this->assets->get_assets_path_source(false, 'img').'fon_header/';
		$dh  = opendir($path_img);
		//$files[0] = 'не выбрано';
		$files = array();
		$i = 1;
		while (false !== ($filename = readdir($dh))) {
            if(is_file($path_img.$filename)){
		    	$files[$i] = $filename;
		    	$i++;
            }
		}
        return $files;
	}

	/**
	*  Возвращает список файлов списком HTML
	*  для представлении их через плагин jquery combobox
	*  Плагин позволяет видеть изображения в списке
	*
	*/
	function image_combobox(){
		$path_icon = $this->setting['path']['images_teachers'].$this->setting['image_config']['dir'].'/';
		$files = $this->array_icon();
		//$res = '<div id="select_img">';
		$res .= '<select class="select_img" style="width:400px" name="webmenu" id="webmenu">';
		$res .= '<option value="0" data-image="">не задано</option>';
		foreach($files as $key=>$value){
			$res .= '<option value="'.$key.'" data-image="/'.$path_icon.$value.'">'.$value.'</option>';
		}
		$res .= '</select>';
		//$res .= '</div>';
        return $res;
	}

	/**
	*  Возвращает список файлов списком HTML
	*  для представлении их через плагин jquery selectmenu
	*  Плагин позволяет видеть изображения в списке
	*
	*/
	function image_selectmenu(){
		$path_icon = $this->setting['path']['images_teachers'].$this->setting['image_config']['dir'].'/';
		$files = $this->array_icon();
		$res = '<div id="select_img"><fieldset>';
		$res .= '<label for="peopleA">Select a Person:</label>';
		$res .= '<select name="peopleA" id="peopleA">';
		foreach($files as $key=>$value){
			$res .= '<option value="'.$key.'" class="avatar" title="/'.$path_icon.$value.'">'.$value.'</option>';
		}
		$res .= '</select></fieldset</div>';

        $res = '
		<fieldset>
			<label for="peopleA">Select a Person:</label>
			<select name="peopleA" id="peopleA">
				<option value="1" class="avatar" title="http://www.gravatar.com/avatar/b3e04a46e85ad3e165d66f5d927eb609?d=monsterid&r=g&s=16">John Resig</option>
				<option value="2" class="avatar" title="http://www.gravatar.com/avatar/e42b1e5c7cfd2be0933e696e292a4d5f?d=monsterid&r=g&s=16">Tauren Mills</option>
				<option value="3" class="avatar" title="http://www.gravatar.com/avatar/bdeaec11dd663f26fa58ced0eb7facc8?d=monsterid&r=g&s=16">Jane Doe</option>
			</select>
		</fieldset>
		';
        return $res;
	}

	// Disclaimer: Скрипт не сохраняет регистр! Кириллица принудительно переводится в нижний.
	// Это связано с необходимостью корректной транслитерации двуязычных названий страниц.
	// Использованная локале-независимая функция UpLow($s)
    /**
    *  @param string - строка для конвертации
    *  @param string - low || up - перевод в нижний или в верхний регистр
    */
	protected function UpLow($string,$registr='low'){
		  $upper = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯABCDEFGHIJKLMNOPQRSTUVWXYZ';
		  //$upper = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
		  $lower = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяabcdefghijklmnopqrstuvwxyz';
		  //$lower = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
		  if($registr == 'up') $string = strtr($string,$lower,$upper);
		  if($registr == 'low') $string = strtr($string,$upper,$lower);
		  return $string;
	} //function UpLow(&$string,$registr='up')

	public function rus2lat($s, $registr = 'low') { // Функция обратимой перекодировки кириллицы в транслит.
		  // Сначала всё переводим в верхний регистр, причём не с помощью глючной strtoupper
		  $s = $this->UpLow($s, $registr);
		  //а потом только кириллицу в нижний

		  $s=str_replace("ЫА","YHA",$s);
		  $s=str_replace("ЫО","YHO",$s);
		  $s=str_replace("ЫУ","YHU",$s);
		  $s=str_replace("Ё","YO",$s);
		  $s=str_replace("Ж","ZH",$s);
		  $rus = "АБВГДЕЗИЙКЛМНОПРСТУФХЦ";
		  $lat = "ABVGDEZIJKLMNOPRSTUFXC";
		  $s = strtr($s, $rus, $lat);
		  $s=str_replace("Ч","CH",$s);
		  $s=str_replace("Ш","SH",$s);
		  $s=str_replace("Щ","SHH",$s);
		  $s=str_replace("Ъ","QH",$s);
		  $s=str_replace("Ы","Y",$s);
		  $s=str_replace("Ь","Q",$s);
		  $s=str_replace("Э","EH",$s);
		  $s=str_replace("Ю","YU",$s);
		  $s=str_replace("Я","YA",$s);

		  $s=str_replace("ыа","yha",$s);
		  $s=str_replace("ыо","yho",$s);
		  $s=str_replace("ыу","yhu",$s);
		  $s=str_replace("ё","yo",$s);
		  $s=str_replace("ж","zh",$s);
		  $rus = "абвгдезийклмнопрстуфхц";
		  $lat = "abvgdezijklmnoprstufxc";
		  $s = strtr($s, $rus, $lat);
		  $s=str_replace("ч","ch",$s);
		  $s=str_replace("ш","sh",$s);
		  $s=str_replace("щ","shh",$s);
		  $s=str_replace("ъ","qh",$s);
		  $s=str_replace("ы","y",$s);
		  $s=str_replace("ь","q",$s);
		  $s=str_replace("э","eh",$s);
		  $s=str_replace("ю","yu",$s);
		  $s=str_replace("я","ya",$s);

		  $s=str_replace(" ","_",$s); // сохраняем пробел от перехода в %20
		  $s=str_replace(",",".h",$s); // сохраняем запятую
		  //$s=str_replace('"',"&quot;",$s); // сохраняем кавычки
		  //$s=rawurlencode($s); // Разрешённые символы URL - латинские буквы, точка, минус и подчёркивание
		  $pattern = '%[^A-z0-9_.-]+%is';
		  $s = preg_replace($pattern, '', $s);

		  return $s;
	} // function rus2lat($s)


	/**
	* 	Возвращает данные страниц
	*
	*/
	public function get_pages($arr){
	}

	public function get_all_id(){

	}

    public function pages_select(){    	$res = Modules::run('pages/pages_headers/MY_data',
    		//select
    		array('id',
    			  'related' => array('content' => array('id','name')),
    			  'uri'
    		)
    	);
    	foreach($res as $items){    		$replace = array('"','\'');
    		$name = str_replace($replace, ' ', $items->content[0]->name);
    		$result[$items->id] = '<b>'.htmlentities($name, ENT_QUOTES,'UTF-8'). '</b> : '. htmlspecialchars($items->uri) . ' : <i style=\'grey\'>id=' . $items->id . '</i>';
    		//$result[$items->id] = $items->{content}[0]->name;
    	}

		if($result) return $result;
		return array();
    }

    public function pages_select_prefix(){
    	//$prefix = '[page]';
    	$res = $this->pages_select();
    	//return $res;
    	foreach($res as $key=>$item){
    		//$name = htmlspecialchars($item);
    		$res_pr[$key] = '<a class=\'iframe\' href=/ajaxs/?resource=pages/admin/pages~&id='.$key.'>'.$item.'</a>';
    		//$res_pr[$key] = '<a id=\'iframe cboxElement\' href=/ajaxs/?resource=pages/admin/pages~&id='.$key.'>'.$item.'</a>';

    	}
    	if($res_pr) return $res_pr;
		return array();
    }

    /**
    * Определяет id страницы по uri
    *	@param string $uri - строка uri
    *
    *	@return number - номер id или false, если страница не найдена
    */
    public function id_is_uri($uri){    	//cho 'Uri: '.$uri.'<br/>';
    	//exit;
    	//$uri = trim($uri, '/');
    	$res = Modules::run('pages/pages_headers/MY_data_row',
    		//select
    		array('id'),
    		//where
    		array('uri' => $uri)
    	);
    	if(isset($res->id)) return $res->id;
    	return false;
    }

    /**
    * Возвращает валидный uri, т.е. uri без слешей "/"
    * если будет передан uri из цепочек "first/too/free", то вернёт последний существующий uri
    * Например есть путь "page1/page2/filters"
    * если существует страницы page1 и page2, но не существует filters, то вернет id page2
    * если уже самый первый uri несуществует, то вернет false
    * Данный способ служит для отслеживания правильной страницы в случае сохранения цепочки uri в иерархии
    *
    */
    public function id_is_valid_uri($uri){        $uri = $this->get_uri_valid($uri);
        if($uri === false) return false;
    	if($uri == '/') return $this->id_is_uri($uri);
    	$arr_uri = explode('/', $uri);
    	$end_uri = end($arr_uri);
    	$res = $this->id_is_uri($end_uri);
    	if(isset($res)) return $res;
    	return false;
    }

    /**
    * Возвращает id страницы по uri_old (старый адрес страницы)
    *	@param string $uri - строка uri
    *
    *	@return number - номер id или false, если страница не найдена
    */
    public function id_is_uri_old($uri){    	$uri = trim($uri, '/');
    	$res = Modules::run('pages/pages_headers/MY_data_row',
    		//select
    		array('id'),
    		//where
    		array('uri_old' => $uri)
    	);
    	if(isset($res->id)){    		return $res->id;
    	}
    	return false;
    }

	/**
	* Возвращает валидный uri с учетом цепочек uri,
	*   а также проверяет наличие завершающего слеша в запросе - это требуется для исключения дублей страниц без слеша
	*	если по пути встретился несуществующий uri, концовка отбрасывается
	*	Пример uri "page1/page2/page3/page4"
	*	page1, page2, page4 - существуют  page3 - не существует
	*	будет возвращено "page1/page2"
	*	Данный метод НЕ проверяет правильность цепочки, а только проверяет наличие каждого uri
	*	Если требуется проверять правильность цепочки иерархического меню, проверяйте по полученному uri отдельными средствами
	*
	*/
	public function get_uri_valid($uri_full){        //проверка только главной страницы
        if($uri_full == '/'){
    		if($this->id_is_uri($uri_full)) return $uri_full;
    	}
    	$uri = trim($uri_full, '/');
    	$arr_uri = explode('/', $uri);
    	foreach($arr_uri as $item){
    		if($uri_check = $this->id_is_uri($item)){
    			$path_uri[] = $item;
    		}else{
    			break;
    		}
    	}
    	if(isset($path_uri)){    		//return $path_uri;
    		$res = implode('/', $path_uri);
    	}

    	if(isset($res)){    		//делаем проверку наличие завершающего слеша для страницы
    		$pos = strpos($uri_full, $res.'/');
    		if($pos !== false){

    			return $res;
    		}
    	}
    	return false;
	}

	/**
	* Возвращает валидный массив id страниц с учетом цепочек uri
	*	если по пути встретился несуществующий uri, концовка отбрасывается
	*	Пример uri "page1/page2/page3/page4"
	*	page1, page2, page4 - существуют  page3 - не существует
	*	будет возвращено array(0=>{id page1}, 1=>{id_page2})
	*	Данный метод НЕ проверяет правильность цепочки, а только проверяет наличие каждого uri
	*	Если требуется проверять правильность цепочки иерархического меню, проверяйте по полученному uri отдельными средствами
	*
	*/
	public function get_ids_valid($uri){
        //проверка только главной страницы
        if($uri == '/'){
    		if($ids = $this->id_is_uri($uri)) return array($ids);
    	}
    	$uri = trim($uri, '/');
    	$arr_uri = explode('/', $uri);
    	foreach($arr_uri as $item){
    		if($id_check = $this->id_is_uri($item)){
    			$ids[] = $id_check;
    		}else{
    			break;
    		}
    	}
    	if(isset($ids)){
    		return $ids;
    	}

    	return false;
	}


    public function get_pages_menus(){    	return Modules::run('menus/menus_trees/get_nodes');
    }

    /**
    * 	Возвращает информацию о странице
    *
    */
    public function get_data_page($id){   		if( ! is_numeric($id)) return false;
   		$res = Modules::run('pages/pages_headers/MY_data_array_row',   			//select
   			array('main.id','main.active', 'main.label', 'main.uri', 'main.icon', 'main.text1', 'main.text2', 'main.img_fon',
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
   			array('main.id' => $id,
   				//'content.id_page_header' => $id,
   				//'seo.id_page_content' => array('encode' => 'content.id')

   			)

   		);
   		return $res;
    }

    /**
    * 	Возвращает информацию о страницах
    *
    */
    public function get_data_pages($ids){
        if( ! is_array($ids)) $ids = array($ids);
   		$res = Modules::run('pages/pages_headers/MY_data',
   			//select
   			array('main.id','main.active', 'main.label', 'main.uri', 'main.icon', 'main.text1', 'main.text2', 'main.img_fon',
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
   		return $res;
    }

    /**
    * 	Возвращает информацию о страницах
    *
    */
    public function get_data_pages_array($ids){
        if( ! is_array($ids)) $ids = array($ids);
   		$res = Modules::run('pages/pages_headers/MY_data_array',
   			//select
   			array('main.id','main.active', 'main.label', 'main.uri', 'main.icon', 'main.text1', 'main.text2', 'main.img_fon',
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
   		foreach($ids as $items){   			if(isset($res[$items])) $result[$items] = $res[$items];
   		}
   		return $result;
    }

    /**
    * Возвращает данные страницы на основе id или uri
    * @param string||numeric $name - id или uri страницы
    *
    * @return array - данные о странице
    */
    public function get_data_template($id){
         if( ! is_numeric($id)) $id = $this->id_is_uri($id);

         if(empty($id)) return false;
         $result = $this->get_data_page($id);

         if(is_array($result)){         	$this->load->driver('replaced');
         	if(is_object($this->replaced->page)){
         		$data_access = $this->replaced->page->get_allow_value();
         	}else{         		return false;
         	}
         	//print_r($data_access);
         	//exit;
         	foreach($result as $key=>$value){         		if(in_array($key, $data_access)){         			$data_page[$key] = $value;
         		}else{         			foreach($result['content'][0] as $field_content=>$value_content){         				if(in_array($field_content, $data_access)){
         					if(!isset($result[$field_content])){         						$data_page[$field_content] = $value_content;
         					}
         					$data_page['content.'.$field_content] = $value_content;
         				}
         			}
         		}
         	}
         }
         if(isset($data_page)) return $data_page;
         return false;
    }

    public function getFieldUri($uri){    	if($uri == '/'){    		return '/';
    	}
    	if(is_string($uri)){    		return '/'.trim($uri, '\/').'/';
    	}
    	return '/';
    }


    /**
	* Breadcrumbs - текущий путь до страницы (хлебные крошки)
	* @param array - массив параметры для формирования пути
	*				num	 - ключ в массиве порядковый номер ветки следования пути
	*				sub_num - порядковый номер для ссылке в данной ветке (можно строить дополнительное меню при наведении мыши на данной ветви пути)
	*                	[num][sub_num] = array(	'name' - название
	*                					'link' - ссылка
	*                					'current_path' - boolean (относится ли ссылка к текущему пути)
	*                					'last'  - boolean (является ли ссылка последней в пути)
	*                           	)
	*/
  function breadcrumbs($id, $name_group = false, $name_menu = false){
        if($name_menu !== false){
        	$ids_pages = $this->get_path($id, $name_menu);
        }else{        	$ids_pages = $this->get_path_group($id, $name_group);
        }


        $pages = $this->get_data_pages_array($ids_pages);
       //echo '<pre>';

        //print_r($id);
        //print_r($ids_pages);
        //print_r($pages);
        //echo '</pre>';
        //exit;
        if(is_array($pages)){

        	if($this->bufer->pages['uri'] != '/'){
	        	$data = array(
	        	 			'name' => 'Главная',
	        	 			'link' => $this->getFieldUri('/'),
	        	 			'current_path' => true
	        	);
	        	Modules::run('breadcrumbs/breadcrumbs/add', $data, 'pages');
        	}
        	foreach($pages as $key=>$items){                if($items['active'] == 1){
	                $data = array(
	                	    'name' => $items['content'][0]->name,
	        	 			'link' => $this->getFieldUri($items['uri']),
	        	 			'current_path' => true
	                );
	                Modules::run('breadcrumbs/breadcrumbs/add', $data, 'pages');
                }
        	}

        }
  }

  /**
	* Breadcrumbs - текущий путь до страницы (хлебные крошки) через известный узел
	*	рекомендуется использовать когда точно уже известен узел в меню
	* @param integer $node - узел в меню
	*
	*	@return string - строка шаблона (html)
	*/
  function breadcrumbs_node($node){
        if(!empty($node)) {
        	$arr_node = Modules::run('menus/menus/get_path', $node, 'page');
        	foreach($arr_node as $key=>$value){
        		$ids_pages[] = $value['name'];
        	}
        }else{
        	return false;
        }
        $pages = $this->get_data_pages_array($ids_pages);

        if(is_array($pages)){

        	$data = array(
        	 			'name' => 'Главная',
        	 			'link' => $this->getFieldUri('/'),
        	 			'current_path' => true
        	);
        	Modules::run('breadcrumbs/breadcrumbs/add', $data, 'pages');
        	//print_r($pages);
        	//exit;
        	foreach($pages as $key=>$items){
                if($items['active'] == 1){
                	$data = array(
                	    'name' => $items['content'][0]->name,
        	 			'link' => $this->getFieldUri($items['uri']),
        	 			'current_path' => true
                	);
                	Modules::run('breadcrumbs/breadcrumbs/add', $data, 'pages');
                }
        	}

        }

  }

	/**
	* Возвращает путь страницы относительно указанного меню
	* @param numeric - id страницы
	* @param name - имя меню
	*
	* @retutn array - массив в последовательности следования страниц в меню
	*					ключ - номер чередования
	*					значение - все параметры страницы
	*/
	function get_path($id_page, $name_menu = false){
	    //получаем узлы данной id страницы
        $array = Modules::run('menus/menus_places/get_id_nodes', $id_page, 'page', $name_menu);
        /*
		$data = Modules::run('menus/menus_places/get_data_nodes', $nodes);
        foreach($data as $key=>$value){        	$array[] = $value->name;
        }
        */
	  	if(isset($array)) return $array;
	  	return false;
	}

	/**
	* Возвращает путь страницы относительно указанного меню
	* @param numeric - id страницы
	* @param name - имя меню
	*
	* @retutn array - массив в последовательности следования страниц в меню
	*					ключ - номер чередования
	*					значение - все параметры страницы
	*/
	function get_path_group($id_page, $group = false){
	    //получаем узлы для данной группы
        $nodes = Modules::run('menus/menus_groups/get_id_nodes', $id_page, 'page',  $group);

        //если есть узлы, берем первый
        if(is_array($nodes)){        	$node = array_shift($nodes);
        }else{        	$node = false;
        }
        //если есть узел в меню, вычисляем весь его путь
        if(!empty($node)) {        	$arr_node = Modules::run('menus/menus/get_path', $node, 'page');

        	foreach($arr_node as $key=>$value){
        		$array[] = $value['name'];
        	}
        }else{        	$array[] = $id_page;
        }

	  	if(isset($array)) return $array;
	  	return false;
	}
}