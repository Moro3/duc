<?php
class grid_duc_teachers extends jqGrid
{

    protected function beforeInit(){
    	 $this->CI = &get_instance();

    }

    protected function init()
    {
        $this->CI = &get_instance();
        $this->table = 'duc_teachers';
        //модели зависящие от данной таблицы
        //используется для целостности данных при удалении
        $this->field_dependent = array(	'groups' => array('id', 'name'),
        								'concertmaster' => array('id', 'id_group')
        );


		$this->config = $this->loader->get('config');

        $this->params['qualifications'] = Modules::run('duc/duc_qualifications/MY_data_array_one');
        $this->params['ranks'] = Modules::run('duc/duc_ranks/MY_data_array_one');
        $this->params['array_files'] = array(0=>'нет') + Modules::run('duc/duc_teachers/array_icon');

        $this->params['experiences'] = Modules::run('duc/duc_teachers/listExperience');;


		//return;
        $this->query = "
            SELECT {fields}
            FROM duc_teachers AS object
            WHERE {where}
        ";


        $this->cols = array(
            'id'          => array('label' => lang('duc_id'),
                                   'db' => 'object.id',
                                   'width' => 50,
                                   'align' => 'center',
                                   'editable' => true,
                                   "editoptions"=>array("readonly"=>true),
            ),


            'active'  => array('label' => lang('duc_active'),
                                   'db' => 'object.active',
                                   'width' => 50,
                                   'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'edittype' => "checkbox",
                                   'editrules' => array('required' => true,
                                                     'edithidden' => true,
                                   ),
            ),
            'active_icon'  => array('label' => lang('duc_active'),
                                   'manual'=> true,
                                   'width' => 80,
                                   'align' => 'center',
                                   //'editable' => true,
                                   'encode' => false,
            ),
            'sorter'  => array('label' => lang('duc_sorter'),
                                   'db' => 'object.sorter',
                                   'width' => 50,
                                   //'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'integer' => true,
                                                     	'minValue' => 1,
                                                     	'maxValue' => 1000,
                                   ),
            ),
            'foto_upload'    => array('label' => lang('duc_foto_upload'),
                                   'db' => 'object.foto',
                                   'width' => 150,
                                   'align' => 'left',
                                   'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'edittype' => 'file',
                                   'editrules' => array(
                                                       'maxValue' => 100,
                                                       'edithidden' => true,
                                   ),
                                   'editoptions' => array('size' => 60),
                                   //'formoptions' => array('elmsuffix' => $this->button_delete_foto()),
            ),
            'foto'    => array('label' => lang('duc_foto'),
                                   //'db' => 'object.foto',
                                   'width' => 150,
                                   'align' => 'left',
                                   'editable' => true,
                                   'encode' => false,
                                   //'edittype' => 'file',
                                   'editrules' => array(
                                                       'maxValue' => 100,
                                   ),
                                   //'editoptions' => array('size' => 60),
                                   'formoptions' => array('elmsuffix' => Modules::run('duc/duc_teachers/tplButtonDeleteImage')),
            ),
            'foto_list'    => array('label' => lang('duc_foto_list'),
                                   'db' => 'object.foto',
                                   //'manual' => true,
                                   'replace' => $this->params['array_files'],
                                   'width' => 150,
                                   'align' => 'left',
                                   'hidden' => true,
                                   'editable' => true,
                                   //'encode' => false,
                                   'edittype' => 'select',
                                   'editrules' => array(
                                                       'maxValue' => 100,
                                                       'edithidden' => true,
                                   ),
                                   'editoptions' => array(
                                                         //'value' => array(0=>'нет') + $this->params['array_files'],
                                                         'value' => $this->params['array_files'],

                                   ),
                                   //'formoptions' => array('elmsuffix' => $this->button_delete_foto()),
            ),
            'surname'    => array('label' => lang('duc_surname'),
                                    'db' => 'object.surname',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),
            'name'    => array('label' => lang('duc_name'),
                                    'db' => 'object.name',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),

            'name2'    => array('label' => lang('duc_lastname'),
                                    'db' => 'object.name2',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 5,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),
            'id_qualification'   => array('label' => lang('duc_qualification'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => array(0=>'нет') + $this->params['qualifications'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => array(0=>'нет') + $this->params['qualifications'],
                                	),
                                   'editrules' => array('required' => true,
                                                     'integer' => true,
                                                     'minValue' => 0,
                                                     'maxValue' => 1000,
                                                     'edithidden' => true,
                                   ),
                                   'stype' => 'select',
                                   //'replace' => $this->params['categories'],
                                   'searchoptions' => array(
                                        				'value' => new jqGrid_Data_Value($this->params['qualifications'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
   			'id_rank'   => array('label' => lang('duc_rank'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => array(0=>'нет') + $this->params['ranks'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => array(0=>'нет') + $this->params['ranks'],
                                	),
                                   'editrules' => array('required' => true,
                                                     'integer' => true,
                                                     'minValue' => 0,
                                                     'maxValue' => 1000,
                                                     'edithidden' => true,
                                   ),
                                   'stype' => 'select',
                                   //'replace' => $this->params['categories'],
                                   'searchoptions' => array(
                                        				'value' => new jqGrid_Data_Value($this->params['ranks'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
            'experience'   => array('label' => lang('duc_experience'),
                                    //'hidden' => true,
                                    'width' => 50,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['experiences'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['experiences'],
                                	),
                                   'editrules' => array('required' => true,
                                                     'integer' => true,
                                                     'minValue' => 0,
                                                     'maxValue' => 1000,
                                                     'edithidden' => true,
                                   ),
                                   'stype' => 'select',
                                   //'replace' => $this->params['categories'],
                                   'searchoptions' => array(
                                        				'value' => new jqGrid_Data_Value($this->params['experiences'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
            'description'    => array('label' => lang('duc_description'),
                                    'db' => 'object.description',
                                    //'hidden' => true,
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'edittype' => "textarea",
                                   'encode' => true,
                                   'editoptions' => array('rows' => 10,
                                   						 'cols' => 80,
                                   						 'maxValue' => 10000,
                                   	),

                                   'editrules' => array('edithidden' => true),
            ),

        );
        $this->options = array(
            'sortname'  => 'surname',
            'sortorder' => 'asc',
            'rowNum' => 20,
            'rownumbers' => true,
            'width' => 900,
            'height' => '100%',
            'autowidth' => true,
            'altRows' => true,
    		'multiselect' => true, // множественный выбор (checkbox)
    		'rowList'     => array(10, 20, 30, 50, 100),
    		'caption' => lang($this->table),

        );
        #Set nav
        $this->nav = array(//'view' => true, 'viewtext' => 'Смотреть',
        					'add' => true, 'addtext' => 'Добавить',
        				   'edit' => true, 'edittext' => 'Редактировать',
        				   'del' => true, 'deltext' => 'Удалить',
        				'prmAdd' => array('width' => 600, 'closeAfterAdd' => true),
    					'prmEdit' => array('width' => 720,
    									   'closeAfteredit' => true,
    									   'viewPagerButtons' => false, //скрывает кнопки навигации предыдущая, следующая

    					                   //'reloadAfterSubmit' => true,
                  						 //'beforeShowForm' => "function() {	fckeditor('description');}",
                   						 //'onclickSubmit' => "function() {	var oEditorText = fckeditorAPI.GetInstance('description');	return {description: oEditorText.GetHTML()};}",

    									),



        );

        $this->render_filter_toolbar = true;

    }

    protected function parseRow(array $r)
    {
        if($r['active'] == 1){
            $r['active_icon'] = '<img src="'.assets_img('admin/button_green.gif', false).'">';

        }else{
            $r['active_icon'] = '<img src="'.assets_img('admin/button_red.gif', false).'">';
        }
        if(isset($r['foto_upload'])){        	$resize_config = Modules::run('duc/duc_settings/get_config_resize', 'teachers');
        	$resize_param = Modules::run('duc/duc_settings/get_param_resize', 'teachers');
        	$path = $resize_config['path'].$resize_param['small']['dir'].'/'.$r['foto_upload'];
        	//echo $path;
        	//заключаем картинку в блок с идентификатором над которым
        	//будут производится действия с картинкой
        	//$r['foto'] = '<div id="foto">';
        	if(is_file($this->config['path']['root'].$path)){        		$r['foto'] = '<img src="/'.$path.'" height="60px" />';
        	}else{        		$r['foto'] = '';
        	}
        	//$r['foto'] = '</div>';
        }
        //$r['foto_list'] = Modules::run('duc/duc_teachers/image_combobox');
        //$r['foto_list'] = '№'.$r['id'];
        return $r;
    }

	protected function renderGridUrl(){
        return '/grid/duc_teachers/duc/grid/';
    }

    /**
     *  Редактирование полученых данных перед записью
     * @param array $data
     * @return type
     */
    protected function operData($data)
    {
        if(isset( $data['active']) && $data['active'] == 'on' || $data['active'] == 1){
            $data['active'] = 1;
        }else{
            $data['active'] = 0;
        }
        $data['sorter'] = (!empty($data['sorter'])) ? $data['sorter'] : 10;

		$data['surname'] = trim($data['surname']);
		$data['name'] = trim($data['name']);
		$data['name2'] = trim($data['name2']);
		if(!empty($data['foto_list']) && isset($this->params['array_files'][$data['foto_list']])){			$data['foto_list'] = $this->params['array_files'][$data['foto_list']];
		}else{			$data['foto_list'] = '';
		}
        //throw new jqGrid_Exception('Ошибка в operData');
        return $data;
    }

    /**
    * 	редактирование объекта
    */
    protected function opEdit($id, $upd) {
        if(empty($this->table))
		{
			throw new jqGrid_Exception('Table is not defined');
		}

        if(isset($id)){
            #Get editing row
            $result = $this->DB->query('SELECT * FROM '.$this->table.' WHERE id='.intval($id));
            $row = $this->DB->fetch($result);


            #Save
            $upd_data = array(
            		  'active' => $upd['active'],
                	  'sorter' => $upd['sorter'],
                      'id_qualification' => $upd['id_qualification'],
                      'id_rank' => $upd['id_rank'],
                      'surname' => $upd['surname'],
                      'name' => $upd['name'],
                      'name2' => $upd['name2'],
                      'experience' => $upd['experience'],
                      'description' => $upd['description'],
                      //'foto' => $upd['foto_list'],
                      'date_update' => time(),
                      'ip_update' => $_SERVER['REMOTE_ADDR'],

            );
            //if(isset($upd['foto_upload']))
            // проверяем был ли обновлен файл и если да,

                // загружаем файл и добавляем к массиву с данными
                if(isset($_FILES[Modules::run('duc/duc_teachers/formSelector', 'foto_upload')])){
                	//throw new jqGrid_Exception('Было загружено изображение');
                	$data = Modules::run('duc/duc_teachers/upload_file', $id, $upd);
                    //var_dump($data);
                    if($row['foto'] != $data['foto']){
                		Modules::run('duc/duc_teachers/delete_image_object', $row['foto']);
                		$upd_data['foto'] = $data['foto'];
                	}
                }else{
                	//throw new jqGrid_Exception('Изображение НЕ загружено');
                }

            #Save book name to books table
	            return $this->DB->update($this->table,
	                                $upd_data,
	                                array('id' => $row['id']
	                                      )
	                            );

            //unset($upd['name']);
        }else{
            throw new jqGrid_Exception('Запрос не выполнен, нет id');
        }

    }

    /**
    * 	Добавление объекта
    */
    protected function opAdd($data) {
        if(empty($this->table))
		{
			throw new jqGrid_Exception('Table is not defined');
		}

        if(isset($data['surname']) && isset($data['name'])){

            #Save
            $upd_data = array(
            		  'active' => $data['active'],
                	  'sorter' => $data['sorter'],
                      'id_qualification' => $data['id_qualification'],
                      'id_rank' => $data['id_rank'],
                      'surname' => $data['surname'],
                      'name' => $data['name'],
                      'name2' => $data['name2'],
                      'experience' => $data['experience'],
                      'description' => $data['description'],
                      //'foto' => $data['foto_list'],

                      'date_create' => time(),
            		'date_update' => time(),
            		'ip_create' => $_SERVER['REMOTE_ADDR'],
            		'ip_update' => $_SERVER['REMOTE_ADDR'],

            );
            #Save book name to books table
            $id = $this->DB->insert($this->table,
                                $upd_data,
                                true
            );
            if(is_numeric($id)){
                // загружаем файл и добавляем к массиву с данными
                if(isset($_FILES[Modules::run('duc/duc_teachers/formSelector', 'foto_upload')])){
                	//throw new jqGrid_Exception('Было загружено изображение');
                	$data = Modules::run('duc/duc_teachers/upload_file', $id, $data);
                    //var_dump($data);
                	if(!empty($data['foto'])){
	                	$upd_foto['foto'] = $data['foto'];
	                	#Save book name to books table
		            	return $this->DB->update($this->table,
		                                $upd_foto,
	                                	array('id' => $id
	                                      )
	                            );
                	};
                }else{

                	//throw new jqGrid_Exception('Изображение НЕ загружено');
                }
            }


            //unset($upd['name']);
        }else{
            throw new jqGrid_Exception('Запрос не выполнен, нет id');
        }

    }

    /**
    *  Операция удаления
    *  @param array $ins
    *  @return boolean
    */
    protected function opDel($id) {

        if(empty($this->table))
		{
			throw new jqGrid_Exception('Table is not defined');
		}

		$objs = $this->search_id_object($id, $this->field_dependent);

        if(is_array($objs)){
        	$str_error = '';
	        	//print_r($objs);
	        	//exit;
	        	if(isset($objs['groups'])){	        		$str_error .= 'таблица "groups" содержит (';
	        		foreach($objs['groups'] as $key=>$value){	        			$str_error .= $value->name;
	        		}
	        		$str_error .= ')';
	        	}
	        	if(isset($objs['concertmaster'])){
	        		$str_error .= 'таблица "concertmaster" содержит (';
	        		foreach($objs['concertmaster'] as $key=>$value){
	        			$str_error .= $value->groups->name;
	        		}
	        		$str_error .= ')';
	        	}

	        	throw new jqGrid_Exception('Нельзя удалить строку, т.к. её параметр используется в объектах:<br /> '.$str_error);
        }
        if($objs === false){        	throw new jqGrid_Exception('Ошибка при поиске зависимых объектов');
        }

		#Delete single value
		if(is_scalar($id))
		{
			//echo 'Удалено';
			Modules::run('duc/duc_teachers/deleteObject', $id);
			//$this->DB->delete($this->table, array($this->primary_key => $id));
		}
		#Delete multiple value
		else
		{
			$ids = array_map(array($this->DB, 'quote'), explode(',', $id));
			Modules::run('duc/duc_teachers/deleteObject', $ids);

			//$this->DB->delete($this->table, $this->primary_key . ' IN (' . implode(',', $ids) . ')');
		}

    }

    /**
    * 	активация объектов
    */
    protected function opActive() {

    	if(isset($this->input['id']) && is_array($this->input['id'])){
            $ids = $this->input['id'];
        }else{
        	throw new jqGrid_Exception('Не указаны id объектов');
        }
            #Get editing row
            //$result = $this->DB->query('SELECT * FROM '.$this->table.' WHERE id='.intval($id));
            //$row = $this->DB->fetch($result);

            #Save
            $upd_data = array(
            		  'active' => 1,

                      'date_update' => time(),
                      'ip_update' => $_SERVER['REMOTE_ADDR'],
            );

            $ids = array_map(array($this->DB, 'quote'), $ids);
			$response = $this->DB->update($this->table, $upd_data, $this->primary_key . ' IN (' . implode(',', $ids) . ')');
            /*
            #Save book name to books table
	            $response = $this->DB->update($this->table,
	                                $upd_data,
	                                array('id' => $ids
	                                      )
	                            );
	        */
	        return $response;
    }

    /**
    * 	ДЕактивация объектов
    */
    protected function opDeactive() {

    	if(isset($this->input['id']) && is_array($this->input['id'])){
            $ids = $this->input['id'];
        }else{
        	throw new jqGrid_Exception('Не указаны id объектов');
        }

            #Save
            $upd_data = array(
            		  'active' => 0,

                      'date_update' => time(),
                      'ip_update' => $_SERVER['REMOTE_ADDR'],
            );

            $ids = array_map(array($this->DB, 'quote'), $ids);
			$response = $this->DB->update($this->table, $upd_data, $this->primary_key . ' IN (' . implode(',', $ids) . ')');

	        return $response;
    }

    /**
	*  Возвращает найденные объекты по заданному id
	*  если они есть в зависимых таблицах
	*/
    protected function search_id_object($id, $dependent){    	if(empty($dependent)) throw new jqGrid_Exception('Не заданы связующие модели');
        if( ! is_array($dependent)) $dependent = array($dependent);
        foreach($dependent as $name=>$fields){
			$rows = Modules::run('duc/duc_teachers/search_objects_is_field', $id, $name, $fields);
			if(is_array($rows) && count($rows) > 0){				$res[$name] = $rows;
			}
			if($rows === false) return false;
		}
        if(isset($res)) return $res;
		return null;
    }


	protected function button_delete_foto(){    	$str = '<input type="submit" name="'.Modules::run('duc/duc_teachers/formSelector', 'foto_delete').'" value="Удалить с сервера" onclick="'.$this->jjss['function']['foto_delete'].'()" />';
    	//$str .= 'для удаления с этой странице с оставлением на сервере, воспользуйтесь списком ниже (выберите нет и сохраните)';
    	return $str;
	}

}
