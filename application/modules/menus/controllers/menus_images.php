<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('menus')){
  include_once ('menus.php');
}

/*
 * Класс Menus_images
 *
 */

class Menus_images extends Menus {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_menus_images';
        $this->MY_table = 'menus_images';

    }

    
    //возвращает список групп
    function listImages(){
        $res = Modules::run('menus/menus_images/MY_data_array_one',
            //key
            'main.id',
            //select
            array('main.file',// 'main.paid'

            ),
            //where
            false,
            //order
            'main.file',
            //separator
            ' - '
        );
        
        return $res;
    }


    /**
    * Возвращает имена форм
    * Для сопоставления при вызовов в разных приложениях (js, css, view)
    *
    */
    public function formSelector($name){
    	switch ($name){
    		case 'image_upload':
    			return 'image_upload';
    		break;
    		case 'image':
    			return 'image';
    		break;
    		case 'image_delete':
    			return 'image_delete';
    		break;
    		case 'file_upload':
    			return 'file_upload';
    		break;
    		case 'id_group':
    			return 'group';
    		break;
    		case 'field_group':
    			return 'namegroup';
    		break;
    		case 'id_nameIsFile':
    			return 'thenameIsFile';
    		break;
    		case 'field_nameIsFile':
    		    return 'nameIsFile';
    		break;
    	};
    }

    function grid_render(){
        $this->grid_params();

        $this->load->view('grid/fileUpload');

        //-------------- Загрузка таблицы jQGrid
   		echo "\r\n<script>";
        	echo $this->grid->loader->render($this->table);
        echo "</script>\r\n";

		$this->load->view('grid/formatter/asis', array('selector' => 'img'));
		//$this->load->view('grid/formatter/asis', array('selector' => 'sorter'));
        $this->load->view('grid/navigator/active');
        $this->load->view('grid/sorter/sortrows',
        				 array('grid' => $this->table,
        				 		'url' => '/grid/'.$this->MY_table.'/'.$this->MY_module.'/grid/'
        				 )
        );

        $this->load->view('grid/formatter/multiselect',
								array('selector' => 'multiselect',
                                      'sortable' => true,
									  'searchable' => true
								)
		);

    }

    /**
	*  Генерация имени файла для фото объектов
	*
	*  @param numeric - id объекта
	*
	*  return string - строка md5
	*/
    function generate_file_name_object($id_object){
    	$name = $id_object.mktime();

    	return 'menus_'.$id_object.'_'.md5($name);
    }

    /**
    * Удаление объекта photos по его id
    * @param numeric || array  $ids - id педагога
    */
    function deleteObject($ids){

        if( ! is_array($ids)){
    		$ids = explode(',', $ids);
    	}
    	foreach($ids as $item){
    		if(is_numeric($item)){
    			$arr_id[] =	$item;
    		}else{
    			return false;
    		}
    	}
       	$objects = Modules::run(	'menus/menus_images/MY_data_array',
    		 					//select
    		 					array(
    		 						  'id', 'name', 'img',

    		 					),
    		 					//where
    		 					array(
    		 					      'id' => $arr_id,
    		 					),
    		 					//false,
    		 					false, //limit
    		 					''     //order

    	);
    	foreach($objects as $items){
    		//Modules::run('duc/duc_events/onTeachersImagesDelete', $items['foto']);
    		$this->delete_image_object($items['img']);
    	}

        $res = Modules::run('menus/menus_images/MY_delete',
    		 					//where
    		 					array(
    		 						  'id' => $arr_id,
    		 					)
        );

    }

    /**
    *	Удаление изображения у объекта
    *   @param string - имя файла
    */
    function delete_image_object($file_name){
    	if(!empty($file_name)){
	    	$resize_config = Modules::run('menus/menus_settings/get_config_resize', 'groups');
	    	$file_source = $this->setting['path']['root'].$resize_config['path'].$resize_config['dir'].'/'.$file_name;
	    	if(is_file($file_source)){
	    		if(unlink($file_source)){
	    		 	Modules::run('menus/menus_events/onGroupsImagesDelete', $file_name);
	    		 	return true;
	    		}
	    	}
	    	return true;
	  	}
	  	return false;
    }

    //загрузка изображения
    function upload_file($id){
        if(empty($id)) return false;
		if(!isset($_FILES[Modules::run('menus/menus_images/formSelector', 'image_upload')])) return false;
		//echo 'массив загрузки из '.__METHOD__;
		//print_r($_FILES);
		//return $data;
		if( ! $this->resizeDirCreate('groups')) return false;

			$resize_config = Modules::run('menus/menus_settings/get_config_resize', 'groups');
			//print_r($resize_config);
			$config['upload_path'] = './'.$resize_config['path'].$resize_config['dir'];
			$config['allowed_types'] = $resize_config['allowed_types'];
			$config['max_size']	= $resize_config['max_size'];
			$config['max_width'] = $resize_config['max_width'];
			$config['max_height'] = $resize_config['max_height'];
           	$config['change_name'] = Modules::run('menus/menus_images/generate_file_name_object', $id);
           	$config['remove_spaces'] = false;
            //$this->CI->output->set_header("X-Requested-With: XMLHttpRequest");
            //$this->CI->output->set_header("ci-cms: rules");
           	//header('X-Requested-With: XMLHttpRequest');
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
            //print_r($config);
			if ( ! $this->upload->do_upload(Modules::run('menus/menus_images/formSelector', 'image_upload'))){
				//$error = array('error' => $this->upload->display_errors());
                $data['errors'] = $this->upload->display_errors();
			}else{
				$data['data'] = $this->upload->data();
				Modules::run('menus/menus_events/onGroupsImagesUpload', $data['data']['file_name']);
			}
            return $data;
	}



	function delete_foto(){

		if($this->input->post('id')){
			$id = $this->input->post('id');
			$row = Modules::run('menus/menus_images/MY_data_row',
			                    //select
			                    array('id', 'img'),
			                    //where
			                    array('id' => $id)

			);

			if($row->id == $id){
				if(!empty($row->img)){
					// ищем строки с таким же названием фото кроме текущего id
					$row_foto = Modules::run('menus/menus_images/MY_data_row',
			                    //select
			                    array('id', 'img'),
			                    //where
			                    array('img' => $row->img,
			                    		'id !=' => $row->id
			                    )
					);
					//если строк с таким же изображением не найдено, удаляем фото
				    if(empty($row_foto)) $this->delete_image_object($row->img);
					//удаляем запись фото из базы
							if(Modules::run('menus/menus_images/MY_update',
									//set
									array('img' => ''),
									//where
									array('id' => $row->id)
								)){
								echo 'Фото удалено';
								//return true;
							}


				}else{
					echo 'Фото нет';
				}
			}else{
				echo 'ID не найден';
			}
		}
		//return false;
	}

    /**
    * Шаблон для пакетной обработки фотографий
    *
    *
    */
    public function tpl_package(){
    	$this->load->view('admin/photos_package_Uploadify');
    }

    /**
    * Пакетная обработка фотографий
    *
    *
    */
    public function action_package(){
        $group = $this->input->post(Modules::run('menus/menus_images/formSelector', 'field_group'));
        if(empty($group) || !is_numeric($group)){
        	return 'Не выбрана группа';
        }
        	$res_sorter = $this->MY_data_array_row(
        	                 //select
        	                 array('max(sorter) AS max_sorter'),
        	                 //where
        	                 array('id_group' => $group)
        	);
        	$max_sorter = (!empty($res_sorter['max_sorter'])) ? $res_sorter['max_sorter'] + 10 : 10;
        	#Save
            $upd_data = array(
            		  'active' => 1,
            		  'id_group' => $group,
                	  'sorter' => $max_sorter,
                      //'name' => $upd['name'],
                      'date_create' => time(),
            		'date_update' => time(),
            		'ip_create' => $_SERVER['REMOTE_ADDR'],
            		'ip_update' => $_SERVER['REMOTE_ADDR'],

            );
            $id = $this->MY_insert($upd_data);

			if(is_numeric($id)){
				$data = Modules::run('menus/menus_images/upload_file', $id);
                if(isset($data['errors'])){
                	return $data['errors'];
                }
    				if(!empty($data['data']['file_name'])){
	                	$upd_foto['img'] = $data['data']['file_name'];
	                	$ckfile = $this->input->post(Modules::run('menus/menus_images/formSelector', 'field_nameIsFile'));
	                	if($ckfile == '1'){

	                		$upd_foto['name'] = (!empty($data['data']['orig_name'])) ? substr_replace($data['data']['orig_name'], '', -strlen($data['data']['file_ext'])) : '';
	                	}
	                	#Save book name to books table
		            	$this->MY_update(
		                                $upd_foto,
	                                	array('id' => $id
	                                      )
	                            );
                	};
			}
			echo '<script>';
			echo 'alert('.$ckfile.')';
			echo '</script>';


    }

    /**
    * Производит действия по ресайзу изображений
    *
    *
    */
    function action_resize(){

    	if($action = $this->input->post('action')){
    		$group = $this->input->post('group');
    		$resize = $this->input->post('resize');

    		switch($action){
    			case 'clear':
    			    if(!empty($group) && !empty($resize)) {
    			    	if($this->resizeDelete($group, $resize)){
    			    		$this->load->view('admin/resizeDeleteOk', array('group'=>$group, 'resize' => $resize));
    			    	}else{
    			    		$this->load->view('admin/resizeDeleteError', array(
    			    														'group'=>$group,
    			    														'resize' => $resize,
    			    														'error' => 'Не удалось удалить файлы изображений'
    			    													)
    			    		);
    			    	}
    			    }else{
    			    	$this->load->view('admin/resizeDeleteError', array(
    			    													'group'=>$group,
    			    													'resize' => $resize,
    			    													'error' => 'Не указаны параметры ресайза'
    			    												)
    			    	);
    			    }

    			break;
    			case 'rebuild':
    			    if(!empty($group) && !empty($resize)) {
    			    	if($this->resize($group, $resize)){
    			    		$this->load->view('admin/resizeRebuildOk', array('group'=>$group, 'resize' => $resize));
    			    	}else{
    			    		$this->load->view('admin/resizeRebuildError', array(
    			    														'group'=>$group,
    			    														'resize' => $resize,
    			    														'error' => 'Не удалось пострить изображения'
    			    													)
    			    		);
    			    	}
    			    }else{
    			    	$this->load->view('admin/resizeRebuildError', array(
    			    													'group'=>$group,
    			    													'resize' => $resize,
    			    													'error' => 'Не указаны параметры ресайза'
    			    												)
    			    	);
    			    }
    			break;
    			case 'rebuildOrig':
    			    if(!empty($group)) {
    			    	if($this->resizeLoading($group)){
    			    		$this->load->view('admin/resizeOk', array('message'=> 'Изображения успешно перестроены для группы ['.$group.']'));
    			    	}else{
    			    		$this->load->view('admin/resizeError', array(
    			    														'error' => 'Не удалось пострить оригинал изображений для ['.$group.']'
    			    													)
    			    		);
    			    	}
    			    }else{
    			    	$this->load->view('admin/resizeError', array(
    			    													'error' => 'Не указаны параметры ресайза '
    			    												)
    			    	);
    			    }
    			break;
    			default:
    				$this->load->view('admin/resizeError', array(
    			    											'error' => 'Выбранное действие для обработки не верно'
    			    											)
    			    );
    		}
    	}else{
    		$this->load->view('admin/resizeError', array(
	    											'error' => 'Не выбраны действия для обработки'
	    										)
		    );
    	}

    }

    /**
	* создает папки для ресайзов изображений если их нет
	*
	*/
	function resizeDirCreate($name){
    	$config = Modules::run('menus/menus_settings/get_config_resize', $name);

    	if($config == false){
    		$this->MY_error(__CLASS__, __METHOD__, 'error', 'Не указаны настройки для папки оригинальных ресайзов');
    		return false;
    	}

    	$dir = $config['path'].$config['dir'];

		if( ! create_dir($dir)){
			$this->MY_error(__CLASS__, __METHOD__, 'fatal', 'Не удалось создать папку "'.$dir.'" для хранения изображений ');
   			return false;
		}

		$resize = Modules::run('menus/menus_settings/get_param_resize', $name);
		//print_r($resize);
		//exit;
		if(is_array($resize)){
			foreach($resize as $name=>$item){


						if(!empty($item['dir'])){
		                	$dir = $item['path'].$item['dir'];
							if( ! create_dir($dir)){
								$this->MY_error(__CLASS__, __METHOD__, 'fatal', 'Не удалось создать папку "'.$dir.'" для хранения изображений ');
					   			return false;
							}
						}else{
							$this->MY_error(__CLASS__, __METHOD__, 'error', 'Не указаны параметры для папки ресайза '.$name);
							return false;
						}


			}
		};
        return true;

	}


}
