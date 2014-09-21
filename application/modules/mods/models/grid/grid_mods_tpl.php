<?php
class grid_mods_tpl extends jqGrid
{

    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {
        $this->CI = &get_instance();
        $this->table = 'mods_tpl';
        $this->params['mods'] = Modules::run('mods/mods/MY_data_array_one', 'id', 'name', false, 'name');
        var_dump($this->params['mods']);
        //exit;
        if(!is_array($this->params['mods'])) $this->params['mods'] = array();

		//return;
        $this->query = "
            SELECT {fields}
            FROM mods_tpl AS object
            LEFT JOIN mods mods
            ON object.module_id = mods.id
            WHERE {where}
        ";


        $this->cols = array(
            'id'          => array('label' => lang('mods_id'),
                                   'db' => 'object.id',
                                   'width' => 50,
                                   'align' => 'center',
                                   'editable' => true,
                                   "editoptions"=>array("readonly"=>true),
            ),

            'active'  => array('label' => lang('mods_active'),
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
            'active_icon'  => array('label' => lang('mods_active'),
                                   'db' => 'object.active',
                                   'width' => 80,
                                   'align' => 'center',
                                   //'editable' => true,
                                   'encode' => false,
                                   'sortable' => false,
                                   'stype' => 'select',
                                   'searchoptions' => array(
                                        				'value' => new jqGrid_Data_Value(array('Откл','Вкл'), 'All'),
                                        				//'value' => array('' => 'All','Откл','Вкл'),
                                        				'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
            ),
             'name'    => array('label' => lang('mods_type_name'),
                                    'db' => 'object.name',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 250,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),

            'alias'    => array('label' => lang('mods_type_alias'),
                                    'db' => 'object.alias',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 250,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),
            'mods'   => array('label' => lang('mods_id'),
                                  'db' => 'object.id',
                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => array(0=>'нет') + $this->params['mods'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => array(0=>'нет') + $this->params['mods'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['mods'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
            'controller'    => array('label' => lang('mods_tpl_controller'),
                                    'db' => 'object.controller',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 30,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),
            'method'    => array('label' => lang('mods_tpl_method'),
                                    'db' => 'object.alias',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 30,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),
            'arg'    => array('label' => lang('mods_tpl_arg'),
                                    'db' => 'object.arg',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 30,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),
            'description'    => array('label' => lang('mods_description'),
                                    'db' => 'object.description',
                                   'width' => 250,
                                   'align' => 'left',
                                   'hidden' => true,
                                   'editable' => true,
                                   'edittype' => "textarea",
                                   'editrules' => array(//'required' => true,
                                                       'maxValue' => 20000,
                                                       'edithidden' => true,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),
            'description_active'    => array('label' => lang('mods_description'),
                                    'db' => 'object.description',
                                    'manual'=> true,
                                   'width' => 50,
                                   'align' => 'left',
                                   'encode' => false,
                                   //'editable' => true,
                                   'edittype' => "textarea",
                                   'editrules' => array(//'required' => true,
                                                       'maxValue' => 20000,
                                                       //'edithidden' => true,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),
        );
        $this->options = array(
            'sortname'  => 'name',
            'sortorder' => 'asc',
            'rowNum' => 20,
            'rownumbers' => true,
            'width' => 900,
            'height' => '100%',
            'autowidth' => true,
            'altRows' => true,
    		//'multiselect' => true, // множественный выбор (checkbox)
    		'rowList'     => array(10, 20, 30, 50, 100),
    		'caption' => lang('mods_tpl'),

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
        if($r['active'] == 1){
            $r['active_icon'] = '<img src="'.assets_img('admin/button_green.gif', false).'">';

        }else{
            $r['active_icon'] = '<img src="'.assets_img('admin/button_red.gif', false).'">';
        }

        if(!empty($r['description'])){
            $r['description_active'] = '<img src="'.assets_img('admin/yes.gif', false).'">';
        }else{
            $r['description_active'] = '<img src="'.assets_img('admin/no.gif', false).'">';
        }
        return $r;
    }

	protected function renderGridUrl(){
        return '/grid/mods/mods_tpl/grid/';
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
