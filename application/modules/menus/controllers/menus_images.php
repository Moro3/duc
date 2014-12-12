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

        //library for resize
        // gd2|imagemagick
        $this->lib_resize = 'gd2'; 
        
        //path for imagemagick library
        $this->path_imagemagick = 'Z:\usr\local\ImageMagick-6.8.4-Q16';

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
    		case 'id_tree':
    			return 'tree_id';
    		break;
    		case 'field_tree':
    			return 'nametree';
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

		$this->load->view('grid/formatter/asis', array('selector' => 'image'));
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
    		 						  'id', 'name', 'file',

    		 					),
    		 					//where
    		 					array(
    		 					      'id' => $arr_id,
    		 					),
    		 					//false,
    		 					false, //limit
    		 					''     //order

    	);
    	

        $res = Modules::run('menus/menus_images/MY_delete',
    		 					//where
    		 					array(
    		 						  'id' => $arr_id,
    		 					)
        );
        
        if($res){
           foreach($objects as $items){                
                $this->delete_image_object($items['file']);
            } 
        }
        
    }

    /**
    *	Удаление изображения у объекта
    *   @param string - имя файла
    */
    function delete_image_object($file_name){
    	if(!empty($file_name)){
	    	
            //проверка файла на условия
            if( ! $this->verifyFile($file_name)) {
                return false;
            }

            $resize_config = Modules::run('menus/menus_settings/get_config_resize', 'trees');
	    	$file_source = $this->setting['path']['root'].$resize_config['path'].$resize_config['dir'].'/'.$file_name;
	    	if(is_file($file_source)){
	    		if(unlink($file_source)){
	    		 	Modules::run('menus/menus_events/onTreesImagesDelete', $file_name);
	    		 	return true;
	    		}
	    	}
	    	return true;
	  	}
	  	return false;
    }

    /**
     * upload main file 
     * @param  numeric $id id for image
     * @return array     ['errors'] - errors, ['data'] - data load
     */
    function upload_file($id){
        if(empty($id)) return false;
		if(!isset($_FILES[Modules::run('menus/menus_images/formSelector', 'image_upload')])) return false;
		//echo 'массив загрузки из '.__METHOD__;
		//print_r($_FILES);
		//return $data;
    
        //dd(Modules::run('menus/menus_images/formSelector', 'image_upload'));
		if( ! $this->resizeDirCreate('trees')) return false;

			$resize_config = Modules::run('menus/menus_settings/get_config_resize', 'trees');
			//dd($resize_config);
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
				Modules::run('menus/menus_events/onTreesImagesUpload', $data['data']['file_name']);
			}
            return $data;
	}



	function delete_image(){

		if($this->input->post('id')){
			$id = $this->input->post('id');
			$row = Modules::run('menus/menus_images/MY_data_row',
			                    //select
			                    array('id', 'file'),
			                    //where
			                    array('id' => $id)

			);

			if($row->id == $id){
				if(!empty($row->img)){
					// ищем строки с таким же названием фото кроме текущего id
					$row_foto = Modules::run('menus/menus_images/MY_data_row',
			                    //select
			                    array('id', 'file'),
			                    //where
			                    array('file' => $row->file,
			                    		'id !=' => $row->id
			                    )
					);
					//если строк с таким же изображением не найдено, удаляем фото
				    if(empty($row_foto)) $this->delete_image_object($row->file);
					//удаляем запись фото из базы
							if(Modules::run('menus/menus_images/MY_update',
									//set
									array('file' => ''),
									//where
									array('id' => $row->id)
								)){
								echo 'Изображение удалено';
								//return true;
							}


				}else{
					echo 'Изображения нет';
				}
			}else{
				echo 'ID не найден';
			}
		}
		//return false;
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

    	//dd($config);
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

    /**
     * возвращает массив конфигурации библиотеки ресайза
     * @return array 
     */
    function getConfigResize(){
        $config['image_library'] = $this->lib_resize;
        if($this->lib_resize == 'imagemagick'){
            $config['library_path'] = $this->path_imagemagick;
        }
        return $config;
    }
    

    /**
    * Ресайз основного изображения
    *   @param string $name_group - имя группы конфига
    *   @param string $file - имя файла с изображением (если не задано, проверяются все файлы)
    *
    */
    function resizeLoading($name_group, $file = false){
        $resize_config = Modules::run('menus/menus_settings/get_config_resize', $name_group);
        //$path_original = $resize_config['path'].$resize_config['dir'];
        if(!empty($resize_config['dir'])){
            $dir = $resize_config['path'].$resize_config['dir'];
        }else{
            $this->MY_error(__CLASS__, __METHOD__, 'error', 'Не указаны параметры для папки ресайза '.$name);
            return false;
        }
        //print_r($resize);
        //exit;
                if(!empty($file)){
                    if(is_file($dir.'/'.$file)){
                        if(!empty($resize_config['x']) && !empty($resize_config['y'])){
                            $size = getimagesize($dir.'/'.$file);

                            if($size[0] > $resize_config['x'] || $size[1] > $resize_config['y']){                                    
                                
                                    $config = $this->getConfigResize();

                                    $config['source_image'] = $dir.'/'.$file;
                                    //$config['new_image'] = $dir.'/'.$file;
                                    //$config['create_thumb'] = TRUE;
                                    //$config['maintain_ratio'] = (isset($resize_config['maintain_ratio'])) ? $resize_config['maintain_ratio'] : TRUE;
                                    $config['width'] = $resize_config['x'];
                                    $config['height'] = $resize_config['y'];
                                    //var_dump($config);
                                    //exit;
                                    $this->load->library('image_lib');
                                    $this->image_lib->initialize($config);

                                    if( ! $this->image_lib->resize()){
                                        $errors = $this->image_lib->display_errors();
                                        $this->MY_error(__CLASS__, __METHOD__, 'error', 'Не удалось произвести ресайз загруженого изображения '.$file);
                                        $this->MY_error(__CLASS__, __METHOD__, 'error', $errors);
                                        //exit($errors);
                                        return false;
                                    }
                                    $this->image_lib->clear();
                                    //print_r($config);
                            }
                        }
                    }else{
                        $this->MY_error(__CLASS__, __METHOD__, 'error', 'Не найдено оригинальное изображение в '.$dir.'/'.$file);
                    }
                }else{
                    $map = directory_map($dir, 1);
                    if(is_array($map) && count($map) > 0){
                        foreach($map as $num=>$file){
                            if(is_numeric($num) && !empty($file)){
                                $this->resizeLoading($name_group, $file);
                            }
                        }
                    }
                }



        return true;
    }

    /**
    * Ресайз файлов изображений
    *   @param string $name_group - имя группы ресайза  
    *   @param string $name_resize - имя ресайза (если не задано, удаляются из всех ресайзов)
    *   @param string $file - имя файла с изображением (если не задано, удаляются все файлы из ресайза)
    *
    */
    function resize($name_group, $name_resize = false, $file = false){
        $resize_config = Modules::run('menus/menus_settings/get_config_resize', $name_group);
        $path_original = $resize_config['path'].$resize_config['dir'];
        $resize = Modules::run('menus/menus_settings/get_param_resize', $name_group);
        //print_r($resize);
        //exit;
        if(is_array($resize)){
            if(!empty($name_resize)){
                if(!isset($resize[$name_resize])) return false;
                $item = $resize[$name_resize];
                if(!empty($file)){
                    if(!empty($item['dir'])){
                            $dir = $item['path'].$item['dir'];

                            $config = $this->getConfigResize();

                            $config['source_image'] = $path_original.'/'.$file;
                            $config['new_image'] = $dir.'/'.$file;
                            //$config['create_thumb'] = TRUE;
                            $config['maintain_ratio'] = (isset($item['maintain_ratio'])) ? $item['maintain_ratio'] : TRUE;
                            $config['master_dim'] = (isset($item['master_dim'])) ? $item['master_dim'] : 'auto';

                            $config['width'] = $item['x'];
                            $config['height'] = $item['y'];

                            $config['x_axis'] = (isset($item['x_axis'])) ? $item['x_axis'] : '';
                            $config['y_axis'] = (isset($item['y_axis'])) ? $item['y_axis'] : '';

                            $this->load->library('image_lib');
                            $this->image_lib->initialize($config);
                            if(isset($item['type']) && $item['type'] !== 'resize'){
                                if($item['type'] == 'crop' || $item['type'] == 'c'){
                                    $config['x_axis'] = $item['x'];
                                    $config['y_axis'] = $item['y'];
                                    if( ! $this->image_lib->crop()){
                                        $errors = $this->image_lib->display_errors();
                                        $this->MY_error(__CLASS__, __METHOD__, 'error', 'Не удалось произвести ресайз для '.$name);
                                        $this->MY_error(__CLASS__, __METHOD__, 'error', $errors);
                                        //exit($errors);
                                        return false;
                                    }
                                }else{
                                    $this->MY_error(__CLASS__, __METHOD__, 'error', 'Не распознан тип ресайза ('.$item['type'].') для '.$name);
                                }
                            }else{
                                if( ! $this->image_lib->resize()){
                                    $errors = $this->image_lib->display_errors();
                                    $this->MY_error(__CLASS__, __METHOD__, 'error', 'Не удалось произвести ресайз для '.$name);
                                    $this->MY_error(__CLASS__, __METHOD__, 'error', $errors);
                                    //exit($errors);
                                    return false;
                                }
                            }
                            $this->image_lib->clear();
                            //print_r($config);

                        }else{
                            $this->MY_error(__CLASS__, __METHOD__, 'error', 'Не указаны параметры для папки ресайза '.$name);
                            return false;
                        }
                }else{
                    $map = directory_map($path_original, 1);
                    if(is_array($map) && count($map) > 0){
                        foreach($map as $num=>$file){
                            if(is_numeric($num) && !empty($file)){
                                $this->resize($name_group, $name_resize, $file);
                            }
                        }
                    }
                }

            }else{
                foreach($resize as $name=>$items){
                    $this->resize($name_group, $name, $file);
                }
            }
        }
        return true;
    }


    /**
    *  Удаление файлов изображений из ресайзов
    *   @param string $name_resize - имя ресайза (если не задано, удаляются из всех ресайзов)
    *   @param string $file - имя файла с изображением (если не задано, удаляются все файлы из ресайза)
    *
    */
    function resizeDelete($name_group, $name_resize = false, $file = false){
        $resize_config = Modules::run('menus/menus_settings/get_config_resize', $name_group);
        $path_original = $resize_config['path'].$resize_config['dir'];
        $resize = Modules::run('menus/menus_settings/get_param_resize', $name_group);
        //print_r($resize);
        //exit;
        if(is_array($resize)){
            if(!empty($name_resize)){
                if(!isset($resize[$name_resize])) return false;
                $item = $resize[$name_resize];
                if(!empty($item['dir']) && !empty($item['path'])){
                    $dir = $item['path'].$item['dir'];
                    if(!empty($file)){
                        if(unlink($this->setting['path']['root'].$dir.DIRECTORY_SEPARATOR.$file)){
                            Modules::run('menus/menus_events/onResizeDelete', $name_group, $name_resize, $file);
                        }else{
                            $this->MY_error(__CLASS__, __METHOD__, 'error', 'Не удалось удалить файл "'.$file.'" из ресайза "'.$name_resize.'"');
                            return false;
                        }
                    }else{
                        $map = directory_map($dir, 1);
                        if(is_array($map) && count($map) > 0){
                            foreach($map as $num=>$file){
                                if(is_numeric($num) && !empty($file)){
                                    $this->resizeDelete($name_group, $name_resize, $file);
                                }
                            }
                        }
                    }
                }
            }else{
                foreach($resize as $name=>$items){
                    $this->resizeDelete($name_group, $name, $file);
                }
            }
        }
        return true;
    }

    /**
    *  Удаление папки ресайза изображений
    *   @param string $name_resize - имя ресайза (если не задано, удаляются все ресайзы)
    */
    function resizeDirDelete($name_group, $name_resize = false){
        $resize_config = Modules::run('menus/menus_settings/get_config_resize', $name_group);
        $path_original = $resize_config['path'].$resize_config['dir'];
        $resize = Modules::run('menus/menus_settings/get_param_resize', $name_group);
        if(!empty($name_resize)){
            if(isset($resize[$name_resize])){
                $item = $resize[$name_resize];
                if(!empty($item['path']) && !empty($item['dir'])){
                    $dir = $item['path'].$item['dir'];
                    if($this->resizeDelete($name_group, $name_resize)){
                        rmdir($this->setting['path']['root'].$dir);
                    }
                }
            }
        }else{
            foreach($resize as $name=>$items){
                $this->resizeDirDelete($name_group, $name);
            }
        }
        return true;
    }

    
    /**
     * проверка файла на определенные условия
     * @param  string $file - имя файла
     * @return boolean       true - условия выполнены, false - НЕ выполнены
     */
    function verifyFile($file){
        if(empty($file)) return false;
        $res = $this->MY_data(
                //select
                array('file', 'id'),
                //where
                array('file' => $file)

            );
        if(is_array($res) && count($res) > 0) return false;

        return true;
    }
}
