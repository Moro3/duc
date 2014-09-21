<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('duc')){
  include_once ('duc.php');
}

/*
 * Класс Duc_teachers
 *
 */

class Duc_teachers extends Duc {

    protected $js;

    function __construct(){
        parent::__construct();
        $this->table = 'grid_duc_teachers';

        $this->js['function']['foto_delete'] = 'f_delete';

    }

    //возвращает список опыта работы
    function listExperience(){
        $res[0] = 0;
        for($i=1; $i<=40; $i++){
         	$res[$i] = $i;
        }
        return $res;
    }

    //возвращает список опыта работы
    function selectTeachersWithImg(){

        $data = $this->MY_data(
          				//select
          				array('id', 'surname', 'name', 'name2', 'foto', 'active'),
          				//where
          				false,
          				//limit
          				false,
          				//order
          				array('surname', 'name', 'name2')
        );
        $res = '<select>';
        foreach($data as $items){        	$res .= '<option';
        	if(!empty($items->foto)){
        		$res .= ' data-img="'.$items->foto.'"';
        	}
        	$res .= '>';
        	if(!empty($items->id)){
        		$res .= $items->surname.' '.$items->name.' '.$items->name2;

        	}
        	$res .= '</option>';
        }
        $res .= '</select>';
        return $res;
    }

    public function formSelector($name){    	switch ($name){    		case 'foto_upload':
    			return 'foto_upload';
    		break;
    		case 'foto':
    			return 'foto';
    		break;
    		case 'foto_delete':
    			return 'foto_delete';
    		break;
    	};
    }

    function grid_render(){

   		parent::grid_render();
        $this->load->view('grid/navigator/active');
    }

	function grid_admin_object(){
        //print_r($this->grid->loader->getInputData());
        $this->load->view('grid/fileUpload');

        $this->tpl_js_images_edit();
        $this->grid_render();
        $this->load->view('grid/formatter/asis', array('selector' => 'foto'));

	}
	/**
    *  Шаблон вывода коллективов и педагогов
    *
    */
    function tpl_teachers_list(){
    	$data['teachers'] = Modules::run(	'duc/duc_teachers/MY_data_array',
    		 					//select
    		 					array('main.id', 'main.name', 'main.surname', 'main.name', 'main.name2', 'main.experience', 'main.description',// 'id_qualification', 'id_rank',
    		 					      'qualification.id', 'qualification.name',
    		 					      'rank.id', 'rank.name',
    		 					      'related' => array(
    		 					      					'concertmaster',
    		 					      					'groups',
    		 					      ),
    		 					),
    		 					//where
    		 					array(//'main.id_qualification' => array('encode' => 'qualification.id'),
    		 					 	  //'main.id_rank' => array('encode' => 'rank.id'),
    		 					 	  'main.active' => 1
    		 					),
    		 					//false,
    		 					false, //limit
    		 					''     //order

    	);
        $data['uri']['point'] =$this->uri_point('user');
        $data['uri']['groups_id'] = $this->get_uri_link('user_groups_id');
        $data['uri']['teachers_id'] = $this->get_uri_link('user_teachers_id');

        //$mdata['related'] = Modules::run('duc/duc_teachers/MY_related_has_many','groups', array(5,8,13));

		//$mdata['related'] = Modules::run('duc/duc_teachers/MY_related_has_many','concertmaster', array(5,8,13));

        //$mdata['concertmaster'] = Modules::run('duc/duc_concertmasters/MY_related_belongs_to','groups', 7);
        //$mdata['related'] = $this->MY_data_related('groups', 1);
        //$rows = Modules::run('duc/duc_teachers/search_objects_is_field', $id, $key_object);
        //echo '<pre>';
        //print_r($data['teachers']);
        //echo '</pre>';
    	//exit;
    	$this->load->view('user/teachers_list', $data );
    }

    /**
    * Шаблон вывода коллектива по его id
    *	@param numeric - id коллектива
    *
    */
    function tpl_teachers_id($id){

    	if(empty($id) || !is_numeric($id)) return false;


        $data['teachers'] = Modules::run(	'duc/duc_teachers/MY_data_array',
    		 					//select
    		 					array(
    		 						  'id', 'name', 'surname', 'name', 'name2', 'experience', 'description', 'foto',
                                      //связь многие к одному
                                       'id_rank', 'id_qualification',
    		 					      //связи один ко многим
    		 					      'related' => array(
    		 					      				'groups' => array('name','id', 'active'),
    		 					      				'concertmaster' => array('id_group'),
    		 					      ),
    		 					),
    		 					//where
    		 					array(
                                      'active' => 1,
    		 					      'id' => $id,
    		 					),
    		 					//false,
    		 					false, //limit
    		 					''     //order

    	);

        //echo '<pre>';
        //print_r($data['teachers']);
        //echo '</pre>';
        $data['uri']['point'] = $this->uri_point('user');
        $data['uri']['teachers_list'] = $this->get_uri_link('user_teachers_list');
        $data['uri']['groups_id'] = $this->get_uri_link('user_groups_id');
        $data['uri']['schedules_teacher'] = $this->get_uri_link('user_sсhedules_teacher');
    	//exit;
    	$data['config'] = $this->setting;
    	$this->load->view('user/teachers_id', $data );
    }

    /**
    *	шаблон обработки изображения на javascript
    * 	Для вывода через jqGrid в режиме редактирования
    *
    */
    function tpl_js_images_edit(){    	 $data['uri']['point'] = $this->uri_point('user');
    	 $data['config'] = $this->setting;
    	 $data['js'] = $this->js;
    	 $data['grid'] = $this->table;
         $data['uri']['teachers_delete_foto'] = $this->get_uri_link('admin_ajax_teachers_delete_foto');
    	 $this->load->view('admin/js/js_images_teachers_edit', $data );
    }

    /**
	*  Возвращает найденный объекты по заданному полю и его значению
	*  @param mixed - значение поля
	*  @param string - имя поля для проверки (по умолчанию 'id')
	*
	*	@return array - массив объектов или false
	*/
    function search_objects_is_field($key_value, $model, $fields = 'id'){
    	if(empty($key_value)) throw new jqGrid_Exception('Значение проверки наличия объектов не найдено');

    	$res = Modules::run('duc/duc_teachers/MY_related_has_many', $model, $key_value, $fields);
        if(is_array($res)) return $res;

        if($res === false) return false;
        return null;
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

    	return 'teacher_'.$id_object.'_'.md5($name);
    }

    /**
    * Удаление объекта педагога по его id
    * @param numeric || array  $ids - id педагога
    */
    function deleteObject($ids){        if(!is_array($ids))  $ids = array($ids);
       	$teachers = Modules::run(	'duc/duc_teachers/MY_data_array',
    		 					//select
    		 					array(
    		 						  'id', 'name', 'surname', 'foto', 'name2',

    		 					),
    		 					//where
    		 					array(
    		 					      'id' => $ids,
    		 					),
    		 					//false,
    		 					false, //limit
    		 					''     //order

    	);
    	foreach($teachers as $items){    		Modules::run('duc/duc_events/onTeachersDeleteBefore', $items['id']);
    		$this->delete_image_object($items['foto']);
    	}

        $res = Modules::run('duc/duc_teachers/MY_delete',
    		 					//where
    		 					array(
    		 						  'id' => $ids,
    		 					)
        );

    }

    /**
    *	Удаление изображения у объекта
    *   @param string - имя файла
    */
    function delete_image_object($file_name){    	if(!empty($file_name)){
	    	$resize_config = Modules::run('duc/duc_settings/get_config_resize', 'teachers');
	    	$file_source = $this->setting['path']['root'].$resize_config['path'].$resize_config['dir'].'/'.$file_name;
	    	if(is_file($file_source)){
	    		if(unlink($file_source)){	    		 	Modules::run('duc/duc_events/onTeachersImagesDelete', $file_name);
	    		 	return true;
	    		}
	    	}
	    	return true;
	  	}
	  	return false;
    }

    //загрузка изображения
    function upload_file($id, $data){
        if(empty($id)) return $data;
			if( ! $this->resizeDirCreate('teachers')) return false;

			$resize_config = Modules::run('duc/duc_settings/get_config_resize', 'teachers');
			$config['upload_path'] = './'.$resize_config['path'].$resize_config['dir'];
			$config['allowed_types'] = $resize_config['allowed_types'];
			$config['max_size']	= $resize_config['max_size'];
			$config['max_width'] = $resize_config['max_width'];
			$config['max_height'] = $resize_config['max_height'];
           	$config['change_name'] = Modules::run('duc/duc_teachers/generate_file_name_object', $id);
            //$this->CI->output->set_header("X-Requested-With: XMLHttpRequest");
            //$this->CI->output->set_header("ci-cms: rules");
           	//header('X-Requested-With: XMLHttpRequest');
			$this->load->library('upload', $config);
            //print_r($config);
			if ( ! $this->upload->do_upload('foto_upload')){
				//$error = array('error' => $this->upload->display_errors());
                throw new jqGrid_Exception($this->upload->display_errors());
			}else{
				$files_data = $this->upload->data();
				Modules::run('duc/duc_events/onTeachersImagesUpload', $files_data['file_name']);
			}
            $data['foto'] = $files_data['file_name'];

            return $data;
	}



	function delete_foto(){
		if($this->input->post('id')){			$id = $this->input->post('id');
			$row = Modules::run('duc/duc_teachers/MY_data_row',
			                    //select
			                    array('id', 'foto'),
			                    //where
			                    array('id' => $id)

			);

			if($row->id == $id){				if(!empty($row->foto)){					// ищем строки с таким же названием фото кроме текущего id
					$row_foto = Modules::run('duc/duc_teachers/MY_data_row',
			                    //select
			                    array('id', 'foto'),
			                    //where
			                    array('foto' => $row->foto,
			                    		'id !=' => $row->id
			                    )
					);
					//если строк с таким же изображением не найдено, удаляем фото
				    if(empty($row_foto)) $this->delete_image_object($row->foto);
					//удаляем запись фото из базы
							if(Modules::run('duc/duc_teachers/MY_update',
									//set
									array('foto' => ''),
									//where
									array('id' => $row->id)
								)){								echo 'Фото удалено';
								//return true;
							}


				}else{					echo 'Фото нет';
				}
			}else{
				echo 'ID не найден';
			}
		}
		//return false;
	}

	/**
	* Массив из списка доступных файлов для иконок
	* Файлы считываються из назначенной директории
	*
	*/
	function array_icon(){		$resize_config = Modules::run('duc/duc_settings/get_config_resize', 'teachers');
		$path_icon = $this->setting['path']['root'].$resize_config['path'].$resize_config['dir'];
		$dh  = opendir($path_icon);
		//$files[0] = 'не выбрано';
		$files = array();
		$i = 1;
		if($dh){
			while (false !== ($filename = readdir($dh))) {
	            if(is_file($path_icon.$filename)){
			    	$files[$i] = $filename;
			    	$i++;
	            }
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
	function image_combobox(){		$resize_config = Modules::run('duc/duc_settings/get_config_resize', 'teachers');
		$path_icon = $resize_config['path'].$resize_config['dir'];
		$files = $this->array_icon();
		//$res = '<div id="select_img">';
		$res .= '<select class="select_img" style="width:400px" name="webmenu" id="webmenu">';
		$res .= '<option value="0" data-image="">не задано</option>';
		foreach($files as $key=>$value){			$res .= '<option value="'.$key.'" data-image="/'.$path_icon.$value.'">'.$value.'</option>';
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
		$resize_config = Modules::run('duc/duc_settings/get_config_resize', 'teachers');
		$path_icon = $resize_config['path'].$resize_config['dir'];
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


	/**
	*	Шаблон кнопки удаления изображения
	*
	*/
	function tplButtonDeleteImage(){		 $str = '<input type="submit" name="'.Modules::run('duc/duc_teachers/formSelector', 'foto_delete').'" value="Удалить с сервера" onclick="'.$this->js['function']['foto_delete'].'()" />';
    	//$str .= 'для удаления с этой странице с оставлением на сервере, воспользуйтесь списком ниже (выберите нет и сохраните)';
    	return $str;
	}
}
