<?php
class grid_menus_images extends jqGrid
{
    protected function init()
    {
        $this->CI = &get_instance();
        $this->table = 'menus_images';

        //модели зависящие от данной таблицы
        //используется для целостности данных при удалении
        /*
        $this->field_dependent = array(	'groups' => array('id', 'name')

        );
        */        
        //$this->params['groups_sort'] = array(0=>'нет')+ $this->params['groups'];
        //natcasesort($this->params['groups_sort']);

        $this->config = $this->loader->get('config');

        $this->query = "
            SELECT {fields}
            FROM ".$this->table." AS object
            WHERE {where}
        ";


        $this->cols = array(
            'id'          => array('label' => lang('menus_id'),
                                   'db' => 'object.id',
                                   'width' => 50,
                                   'align' => 'center',
                                   'editable' => true,
                                   "editoptions"=>array("readonly"=>true),
            ),
            'active'  => array('label' => lang('menus_active'),
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
            'active_icon'  => array('label' => lang('menus_active'),
                                   'manual'=> true,
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
            'sorter'  => array('label' => lang('menus_sorter'),
                                   'db' => 'object.sorter',
                                   'width' => 50,
                                   //'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'number' => true,
                                                     	'minValue' => 1,
                                                     	'maxValue' => 1000,
                                   ),
                                   'search' => false,
            ),
            
            'image_upload'    => array('label' => lang('menus_image_upload'),
                                   'db' => 'object.file',
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
            'image'    => array('label' => lang('menus_image'),
                                   //'db' => 'object.file',
                                   'manual' => true,
                                   'width' => 150,
                                   'align' => 'left',
                                   //'hidden' => true,
                                   'editable' => false,
                                   'encode' => false,
                                   //'edittype' => 'file',
                                   'editrules' => array(
                                                       'maxValue' => 100,
                                   ),
                                   //'editoptions' => array('size' => 60),
                                   'search' => false,

            ),
            'name'    => array('label' => lang('menus_name'),
                                    'db' => 'object.name',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array(//'required' => true,
                                                       'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),
            'name_is_file'    => array('label' => lang('menus_name_is_namefile'),
                                    //'db' => 'object.name',
                                    'manual' => true,
                                    'width' => 250,
                                    'align' => 'left',
                                    'hidden' => true,
                                    'editable' => true,
                                    'edittype' => 'checkbox',
                                    'editrules' => array(//'required' => true,
                                                       'maxValue' => 100,
                                                       'edithidden' => true,
                                    ),
                                    'editoptions' => array('size' => 60),
            ),
            'info'    => array('label' => lang('menus_image_info'),
                        					'manual' => true,
                         					//'db' => 'object.name',
                                  'encode' => false,
                  				        'width' => 250,
                                  'align' => 'left',
                                  'editable' => false,
                                  'editrules' => array(//'required' => true,
                                                       'maxValue' => 100,

                                  ),
                                  'editoptions' => array('size' => 60),
                                  'search' => false,
            ),

        );
        $this->options = array(
            'sortname'  => 'sorter',
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
    					                   //'reloadAfterSubmit' => true,
                  						 //'beforeShowForm' => "function() {	fckeditor('description');}",
                   						 //'onclickSubmit' => "function() {	var oEditorText = fckeditorAPI.GetInstance('description');	return {description: oEditorText.GetHTML()};}",

    									),



        );

        $this->render_filter_toolbar = true;

    }


	protected function renderGridUrl(){
        return '/grid/'.$this->table.'/menus/grid/';
    }

    protected function parseRow(array $r)
    {
        if($r['active'] == 1){
            $r['active_icon'] = '<img src="'.assets_img('admin/button_green.gif', false).'">';

        }else{
            $r['active_icon'] = '<img src="'.assets_img('admin/button_red.gif', false).'">';
        }
        $resize_config = Modules::run('menus/menus_settings/get_config_resize', 'trees');
        $resize_param = Modules::run('menus/menus_settings/get_param_resize', 'trees');
        if(isset($r['image_upload'])){
        	$path = $resize_param['small']['path'].$resize_param['small']['dir'].'/'.$r['image_upload'];
        	$path_original = $resize_config['path'].$resize_config['dir'].'/'.$r['image_upload'];
        	//echo $path;
        	//заключаем картинку в блок с идентификатором над которым
        	//будут производится действия с картинкой
        	//$r['foto'] = '<div id="foto">';
        	if(is_file($this->config['path']['root'].$path)){
        		$r['image'] = '<img src="/'.$path.'" />';
        		if(is_file($this->config['path']['root'].$path_original)){
              $img_info = getimagesize($this->config['path']['root'].$path_original);
          		$r['info'] = 'Ширина: '.$img_info[0].' px<br />';
          		$r['info'] .= 'Высота: '.$img_info[1].' px<br />';
          		$r['info'] .= 'Размер: '.round(filesize($this->config['path']['root'].$path_original)/1024).' КБайт';
            }else{
              $r['info'] = 'Не найден оригинал файла';
            }
        	}else{
        		$r['image'] = 'файла нет';
        	}

          $r['name_is_file'] = '';
        	//$r['foto'] = '</div>';
        }


        return $r;
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
    * 	Добавление объекта
    */

    protected function opAdd($data) {
        if(empty($this->table))
    		{
    			throw new jqGrid_Exception('Table is not defined');
    		}        

    		if(!isset($_FILES[Modules::run('menus/menus_images/formSelector', 'image_upload')])){
    			throw new jqGrid_Exception('Изображение НЕ выбрано');
    		}
            #Save
            $upd_data = array(
            		  'active' => $data['active'],            		  
                	'sorter' => $data['sorter'],
                  'name' => $data['name'],

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

                	//throw new jqGrid_Exception('Было загружено изображение');
                	$data_file = Modules::run('menus/menus_images/upload_file', $id);
                	//dd($data_file);
                  if(isset($data_file['errors'])){
                    Modules::run('menus/menus_images/deleteObject', $id);
                    throw new jqGrid_Exception($data_file['errors']);
                  }
                  //var_dump($data);

                	if(!empty($data_file['data']['file_name'])){

  	                $upd_foto['file'] = $data_file['data']['file_name'];
  	                #Save book name to books table
  		            	$this->DB->update($this->table,
  		                                $upd_foto,
  	                                	array('id' => $id
  	                                      )
  	                            );
                	};

            }


        return;
    }

     /**
    *   редактирование объекта
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
                  'name' => $upd['name'],
                  //'img' => $upd['img'],

                  'date_update' => time(),
                  'ip_update' => $_SERVER['REMOTE_ADDR'],

            );
            //if(isset($upd['foto_upload']))
            // проверяем был ли обновлен файл и если да,

                // загружаем файл и добавляем к массиву с данными
                if(isset($_FILES[Modules::run('menus/menus_images/formSelector', 'image_upload')])){
                  //throw new jqGrid_Exception('Было загружено изображение');
                  $data = Modules::run('menus/menus_images/upload_file', $id);
                    //var_dump($data);
                  if(isset($data['data']['file_name']) && $row['file'] != $data['data']['file_name']){
                    Modules::run('menus/menus_images/delete_image_object', $row['file']);
                    $upd_data['file'] = $data['data']['file_name'];
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
    *  Операция удаления
    *  @param array $ins
    *  @return boolean
    */
    protected function opDel($id) {

        if(empty($this->table))
    		{
    			throw new jqGrid_Exception('Table is not defined');
    		}

    		#Delete single value
    		if(is_scalar($id))
    		{
    			Modules::run('menus/menus_images/deleteObject', $id);
    			//$this->DB->delete($this->table, array($this->primary_key => $id));
    		}
    		#Delete multiple value
    		else
    		{
    			$ids = array_map(array($this->DB, 'quote'), explode(',', $id));
    			Modules::run('menus/menus_images/deleteObject', $ids);

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
    *  Операция сортирования
    *  @param array $data
    *  @return boolean
    */
    protected function opSorter() {
    	if(isset($this->input['id'])){
            $ids = $this->input['id'];
        }else{
        	throw new jqGrid_Exception('Не указаны id объектов');
        }
        $res = Modules::run('menus/menus_images/sorterId', $ids);
        //print_r($res);
        //exit;
        if( ! is_array($res)) throw new jqGrid_Exception('Ошибка в запросе на данные сортировки');
        if(is_array($res)){
        	foreach($res as $id=>$sort){
        		$upd_data = array(
        		     'sorter' => $sort
        		);
        		$this->DB->update($this->table,
        						 $upd_data,
        						 array('id' => $id
	                             )
        		);
        	}
        }else{
        	 throw new jqGrid_Exception('Не совпадает кол-во данных в запросе на сортировку');
        }
        //print_r($res);
        //exit;
    }

}
