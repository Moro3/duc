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
        $this->field_dependent = array('duc_groups' => 'id_teacher');

		$this->config = $this->loader->get('config');

        $this->params['qualifications'] = Modules::run('duc/duc_qualifications/MY_data_array_one');
        $this->params['ranks'] = Modules::run('duc/duc_ranks/MY_data_array_one');

        $this->params['experiences'] = range(0,40);


		//return;
        $this->query = "
            SELECT {fields}
            FROM duc_teachers AS object
            WHERE {where}
        ";


        $this->cols = array(
            'id'          => array('label' => 'ID',
                                   'db' => 'object.id',
                                   'width' => 50,
                                   'align' => 'center',
                                   'editable' => true,
                                   "editoptions"=>array("readonly"=>true),
            ),


            'show_i'  => array('label' => 'Показать',
                                   'db' => 'object.show_i',
                                   'width' => 50,
                                   'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'edittype' => "checkbox",
                                   'editrules' => array('required' => true,
                                                     'edithidden' => true,
                                   ),
            ),
            'show_i_icon'  => array('label' => 'Показать',
                                   'manual'=> true,
                                   'width' => 80,
                                   'align' => 'center',
                                   //'editable' => true,
                                   'encode' => false,
            ),
            'sort_i'  => array('label' => 'Порядок',
                                   'db' => 'object.sort_i',
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
            'foto_upload'    => array('label' => 'Фото',
                                   'db' => 'object.foto',
                                   'width' => 150,
                                   'align' => 'left',
                                   'editable' => true,
                                   'encode' => false,
                                   'edittype' => 'file',
                                   'editrules' => array(
                                                       'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
                                   'formoptions' => array('elmsuffix' => $this->button_delete_foto()),
            ),
            'surname'    => array('label' => 'Фамилия',
                                    'db' => 'object.surname',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),
            'name'    => array('label' => 'Имя',
                                    'db' => 'object.name',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),

            'name2'    => array('label' => 'Отчество',
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
            'experience'   => array('label' => 'Пед. опыт',
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
            'description'    => array('label' => 'Описание',
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
    		//'multiselect' => true, // множественный выбор (checkbox)
    		'rowList'     => array(10, 20, 30, 50, 100),
    		'caption' => 'Педагоги',

        );
        #Set nav
        $this->nav = array(//'view' => true, 'viewtext' => 'Смотреть',
        					'add' => true, 'addtext' => 'Добавить объект',
        				   'edit' => true, 'edittext' => 'Редактировать',
        				   'del' => true, 'deltext' => 'Удалить',
        				'prmAdd' => array('width' => 600, 'closeAfterAdd' => true),
    					'prmEdit' => array('width' => 720,
    									   'closeAfteredit' => true,
    					                   //'reloadAfterSubmit' => true,
                  						 //'beforeShowForm' => "function() {	fckeditor('description');}",
                   						 //'onclickSubmit' => "function() {	var oEditorText = fckeditorAPI.GetInstance('description');	return {description: oEditorText.GetHTML()};}",

    									),



        );

        $this->render_filter_toolbar = true;

    }

    protected function parseRow(array $r)
    {
        if($r['show_i'] == 1){
            $r['show_i_icon'] = '<img src="'.assets_img('admin/button_green.gif', false).'">';

        }else{
            $r['show_i_icon'] = '<img src="'.assets_img('admin/button_red.gif', false).'">';
        }
        if(isset($r['foto'])){        	$path = $this->config['path']['images_teachers'].$this->config['image_config']['dir'].'/'.$r['foto'];
        	//echo $path;
        	if(is_file($this->config['path']['root'].$path)){        		$r['foto'] = '<img src="/'.$path.'" height="60px" />';
        	}else{        		$r['foto'] = '';
        	}
        }
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
        if(isset( $data['show_i']) && $data['show_i'] == 'on' || $data['show_i'] == 1){
            $data['show_i'] = 1;
        }else{
            $data['show_i'] = 0;
        }
        $data['sort_i'] = (!empty($data['sort_i'])) ? $data['sort_i'] : 10;

		$data['surname'] = trim($data['surname']);
		$data['name'] = trim($data['name']);
		$data['name2'] = trim($data['name2']);
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
            		  'show_i' => $upd['show_i'],
                	  'sort_i' => $upd['sort_i'],
                      'id_qualification' => $upd['id_qualification'],
                      'id_rank' => $upd['id_rank'],
                      'surname' => $upd['surname'],
                      'name' => $upd['name'],
                      'name2' => $upd['name2'],
                      'experience' => $upd['experience'],
                      'description' => $upd['description'],

                      'date_update' => time(),
                      'ip_update' => $_SERVER['REMOTE_ADDR'],

            );

            // проверяем был ли обновлен файл и если да,
                // загружаем файл и добавляем к массиву с данными
                if(isset($_FILES['foto'])){
                	//throw new jqGrid_Exception('Было загружено изображение');
                	$data = Modules::run('duc/duc_teachers/upload_file', $id, $upd);
                    //var_dump($data);
                    if($row['foto'] != $data['foto']){
                		Modules::run('duc/duc_teachers/delete_image_object', $row['foto']);
                		$upd_data['foto'] = $data['foto'];
                	}
                }else{                	throw new jqGrid_Exception('Изображение НЕ загружено');
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
    *  Операция удаления
    *  @param array $ins
    *  @return boolean
    */
    protected function opDel($id) {

        if(empty($this->table))
		{
			throw new jqGrid_Exception('Table is not defined');
		}

        if($objs = $this->search_id_object($id, $this->field_dependent)){

        	$str_error = '';
        	foreach($objs as $key=>$value){
            	$obj_name = Modules::run('lands/lands_objects/data', $key);

            	if(is_array($obj_name)){
            		foreach($obj_name as $id_obj=>$value_obj){
            			if(!empty($value_obj['controller']) ){
            				//берем все найденные id из ключей найденного массива
            				$ids = array_keys($value);
            				$data_odj = Modules::run('lands/'.$value_obj['controller'].'/data_id', $ids);
            				//print_r($data_odj);
            				//$str_error .= '<br />"Объект: '.$value_obj['name'].', id='.$data_odj['id'].'"';
            			}
            			$str_error .= '<br />"Объект: '.$value_obj['name'].', id=('.implode(',',$ids).')"';
            		}
            	}

        	}
        	throw new jqGrid_Exception('Нельзя удалить строку, т.к. её параметр используется в объектах '.$str_error);

        }

		#Delete single value
		if(is_scalar($id))
		{
			echo 'Удалено';
			//$this->DB->delete($this->table, array($this->primary_key => $id));
		}
		#Delete multiple value
		else
		{
			$ids = array_map(array($this->DB, 'quote'), explode(',', $id));
			//$this->DB->delete($this->table, $this->primary_key . ' IN (' . implode(',', $ids) . ')');
		}

    }

    /**
	*  Возвращает найденные объекты по заданному id
	*  если они есть в зависимых таблицах
	*/
    protected function search_id_object($id, $key_object = 'id'){    	if(empty($key_object)) throw new jqGrid_Exception('Ключ проверки наличия объектов не найден');

		$rows = Modules::run('duc/duc_teachers/search_objects_is_field', $id, $key_object);
        if($rows !== false) return $rows;
		return false;
    }


	protected function button_delete_foto(){    	$str = '<input type="submit" name="foto_delete" value="Удалить" onclick="foto_delete()" />';
    	return $str;
	}

}
