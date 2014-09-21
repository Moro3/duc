<?php
class grid_mods extends jqGrid
{
    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {
        $this->CI = &get_instance();
        $this->table = 'mods';


		$this->config = $this->loader->get('config');
        /*
        $this->query = "
            SELECT {fields}
            FROM pages_headers AS object, pages_contents AS content, pages_seo AS seo
            WHERE {where}
        ";
        $this->where[] = 'object.id = content.id_page_header';
        $this->where[] = 'content.id = seo.id_page_content';
        */
        $this->query = "
            SELECT {fields}
            FROM mods AS object
            LEFT JOIN mods_type type
            ON object.type_id = type.id
            WHERE {where}
        ";
        //WHERE content.id IS NULL  - выводит только пустые (не связанные) элементы в таблице headers

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

            'date'   => array('label' => lang('mods_date'),
                                   'db' => 'object.date',
                                    'width' => 140,
                                   'editable' => true,

                                   'searchoptions' => array('dataInit' => $this->initDatepicker(array(
                                        'dateFormat'     => 'yy/mm/dd',
                                        'changeMonth'    => true,
                                        'changeYear'     => true,
                                        'minDate'        => '1950/01/01',
                                        'maxDate'        => date('Y/m/d'),
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    /*
                                    'searchoptions' => array('dataInit' => $this->initDateRangePicker(array(
					                    //'earliestDate' => '2011/01/01',
					                    //'latestDate' => '2011/06/10',
					                    'dateFormat' => 'yy/mm/dd',
					                    'onChange' => new jqGrid_Data_Raw('dateRangePicker_onChange'),
					                    'presetRanges' => array(
					                        array('text' => 'January 2011', 'dateStart' => '2011/01/01', 'dateEnd' => '2011/02/01'),
					                        array('text' => 'February 2011', 'dateStart' => '2011/02/01', 'dateEnd' => '2011/03/01'),
					                    ),
					                    'datepickerOptions' => array(
					                        'changeMonth' => true,
					                        'dateFormat' => 'yy/mm/dd',
					                        'minDate' => '2011/01/01',
					                        'maxDate' => '2011/06/10',
					                    ),
					                ))),
                                    */
                                    //'search_op' => 'date_range',

                                    'editoptions' => array('dataInit' => $this->initDatetimepicker(array(
                                            'dateFormat'     => 'yy/mm/dd',
                                            'separator'      => ' ',
                                            'timeFormat'     => 'hh:mm',
                                            //'hour'           => 0,
                                            //'minute'         => 15,
                                            'timeOnlyTitle' => 'Выберите время',
                                            'timeText'      => 'Время',
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            'numberOfMonths' => array(1,3),
                                            //'showCurrentAtPos' => 1,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            //'minDate'        => '1950-01-01',
                                            'maxDate'        => date('Y/m/d H:i'),
                                            //'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                            'changeMonth'     => true,
                                            'changeYear'      => true,

                                            //'showOn'          => "button",
                                            //'buttonImage'     => "client/calendar.gif",
                                            //'buttonImageOnly' => true,
                                            'firstDay'        => '1',
                                            'monthNamesShort' => array("Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"),
                                            'dayNamesMin'     => array("Вс","Пн","Вт","Ср","Чт","Пт","Сб"),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date('Y/m/d H:i'),

                                    ),
                //'formoptions' => array('elmsuffix' => '<input type="submit" value="Моя кнопка"/>'),
                                   //"formatter"=>"date",
                                    ///"formatoptions"=>array("srcformat"=>"Y-m-d H:i:s","newformat"=>"m/d/Y"),

            ),
            'sorter'  => array('label' => lang('mods_sorter'),
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
            'sorter_type'  => array('label' => lang('mods_sorter'),
                                   'db' => 'type.sorter',
                                   'width' => 50,
                                   'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'editrules' => array(
                                                     	//'edithidden' => true,
                                                     	'integer' => true,
                                                     	'minValue' => 1,
                                                     	'maxValue' => 1000,
                                   ),
            ),
            'active_type'  => array('label' => lang('mods_active'),
                                   'db' => 'type.active',
                                   'width' => 50,
                                   'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'edittype' => "checkbox",
                                   'editrules' => array('required' => true,
                                                     //'edithidden' => true,
                                   ),
            ),

            'name'    => array('label' => lang('mods_name'),
                                    'db' => 'object.name',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 250,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),

            'alias'    => array('label' => lang('mods_alias'),
                                    'db' => 'object.alias',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 250,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),

            'uri'    => array('label' => lang('mods_uri'),
                                    'db' => 'object.uri',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array(//'required' => true,
                                                       'maxValue' => 100,
                                                       //'url' => true,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),
            'uri_start'    => array('label' => lang('mods_uri_start'),
                                    'db' => 'object.uri_start',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array(//'required' => true,
                                                       'maxValue' => 100,
                                                       //'url' => true,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),
            'start_method'    => array('label' => lang('mods_start_method'),
                                    'db' => 'object.start_method',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array(//'required' => true,
                                                       'maxValue' => 100,
                                                       //'url' => true,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),
            'start_controller'    => array('label' => lang('mods_start_controller'),
                                    'db' => 'object.start_controller',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array(//'required' => true,
                                                       'maxValue' => 100,
                                                       //'url' => true,
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
            'short_description'    => array('label' => lang('mods_short_description'),
                                    'db' => 'object.short_description',
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
            'short_description_active'    => array('label' => lang('mods_short_description'),
                                    'db' => 'object.short_description',
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
            'icon'    => array('label' => lang('pages_icon'),
                                   'db' => 'object.icon',
                                   //'manual' => true,
                                   //'replace' => $this->params['array_img_fon'],
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
                                                         //'value' => $this->params['array_img_fon'],
                                                         //'value' => new jqGrid_Data_Value($this->params['array_img_fon']),

                                   ),
                                   //'formoptions' => array('elmsuffix' => $this->button_delete_foto()),
            ),


        );
        $this->options = array(
            'sortname'  => 'id',
            'sortorder' => 'desc',
            'rowNum' => 20,
            'rownumbers' => true,
            'width' => 900,
            'height' => '100%',
            'autowidth' => true,
            'altRows' => true,
    		//'multiselect' => true, // множественный выбор (checkbox)
    		'rowList'     => array(10, 20, 30, 50, 100),
            'caption' => lang('pages_headers'),
        );
        #Set nav
        $this->nav = array(//'view' => true, 'viewtext' => 'Смотреть',
        					'add' => true, 'addtext' => 'Добавить объект',
        				   'edit' => true, 'edittext' => 'Редактировать',
        				   'del' => true, 'deltext' => 'Удалить',
        				'prmAdd' => array('width' => 800, 'closeAfterAdd' => true),
    					'prmEdit' => array('width' => 900,
    										//'height' => 600,
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
        $r['date'] = date('Y/m/d H:i',$r['date']);
        if(isset($r['foto_upload'])){
        	$path = $this->config['path']['icons'].$this->config['image_config']['dir'].'/'.$r['foto_upload'];
        	//echo $path;
        	//заключаем картинку в блок с идентификатором над которым
        	//будут производится действия с картинкой
        	//$r['foto'] = '<div id="foto">';
        	if(is_file($this->config['path']['root'].$path)){
        		$r['foto'] = '<img src="/'.$path.'" height="60px" />';
        		$r['foto'] .= $this->button_delete_foto();
        	}else{
        		$r['foto'] = '';
        	}
        	//$r['foto'] = '</div>';
        }
        return $r;
    }

	protected function renderGridUrl(){
        $get = '';
        if(isset($_GET['id']) && is_numeric($_GET['id'])){
        	$get = 'id='.$_GET['id'];
        }

        return '/grid/mods/mods/grid/?'.$get;
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
        $data['date'] = strtotime($data['date']);
        if(empty($data['uri'])){        	$data['uri'] = Modules::run('mods/mods/generate_uri', $data['name']);
        }else{        	//$this->isset_uri($data['uri']);

        }

        if(!empty($data['foto_list']) && isset($this->params['array_files'][$data['foto_list']])){
			$data['foto_list'] = $this->params['array_files'][$data['foto_list']];
		}else{
			$data['foto_list'] = '';
		}
		if(!empty($data['img_fon']) && isset($this->params['array_img_fon'][$data['img_fon']])){
			$data['img_fon'] = $this->params['array_img_fon'][$data['img_fon']];
		}else{
			$data['img_fon'] = '';
		}
        //throw new jqGrid_Exception('Ошибка в operData');
        return $data;
    }

    /**
    * 	редактирование объекта
    */
    protected function opEdit($id, $data) {
        //throw new jqGrid_Exception('Ошибка в написании URI. Адрес должен состоять из латинских букв, цифр и может содержать символы -_');
    		if(!empty($id) && is_numeric($id)){            	$result = $this->DB->query('SELECT * FROM '.$this->table.' WHERE id='.intval($id));
            	$row = $this->DB->fetch($result);

            	$arr_header['active'] = $data['active'];
            	$arr_header['label'] = $data['label'];
            	$arr_header['sorter'] = $data['sorter'];
            	if($this->isset_uri($data['uri'], $row['id'])){            		$arr_header['uri'] = Modules::run('mods/mods/get_correct_uri', $data['uri']);
            	}else{
            		$arr_header['uri'] = $data['uri'];
            	}
            	if( ! Modules::run('mods/mods/check_uri', $arr_header['uri'])){
            		throw new jqGrid_Exception('Ошибка в написании URI. Адрес должен состоять из латинских букв, цифр и может содержать символы -_');
        		}
        		$arr_header['uri_start'] = (!empty($data['uri_start'])) ? $data['uri_start'] : null;
            	$arr_header['date'] = $data['date'];
            	$arr_header['date_update'] = time();
            	$arr_header['ip_update'] = $_SERVER['REMOTE_ADDR'];
            	$arr_header['icon'] = $data['foto_list'];



                $name_file_load = $this->upload_image();
                if($name_file_load) $arr_header['icon'] = $name_file_load;
            	#Save book name to books table
	            $this->DB->update($this->table,
	                                $arr_header,
	                                array('id' => $row['id']
	                                )
	            );
	            if(!empty($row['id']) && is_numeric($row['id'])){
	            	$result = $this->DB->query('SELECT * FROM `pages_contents` WHERE id_page_header='.intval($row['id']));
            		$row_content = $this->DB->fetch($result);

	            	//$arr_content['id_page_header'] = $row_content['id'];
	            	$arr_content['active'] = $data['active_content'];
            		$arr_content['sorter'] = $data['sorter_content'];
            		$arr_content['name'] = $data['name'];
            		$arr_content['description'] = $data['description'];
            		//$arr_content['date_create'] = $data['date_create'];
            		$arr_content['date_update'] = time();
            		//$arr_content['ip_create'] = $_SERVER['REMOTE_ADDR'];
            		$arr_content['ip_update'] = $_SERVER['REMOTE_ADDR'];

            		$this->DB->update('pages_contents',
	                                $arr_content,
	                                array('id' => $row_content['id']
	                                )
	            	);
	            }else{
	            	throw new jqGrid_Exception('Не удалось записать в таблицу content, т.к. нет id page');
	            }
	            if(!empty($row_content['id']) && is_numeric($row_content['id'])){
	            	$result = $this->DB->query('SELECT * FROM `pages_seo` WHERE id_page_content='.intval($row_content['id']));
            		$row_seo = $this->DB->fetch($result);

	            	//$arr_seo['id_page_content'] = $id_content;
	            	$arr_seo['title'] = $data['seo_title'];
            		$arr_seo['description'] = $data['seo_description'];
            		$arr_seo['keywords'] = $data['seo_keywords'];
            		$arr_seo['h1'] = $data['seo_h1'];
            		//$arr_seo['date_create'] = $data['date_create'];
            		$arr_seo['date_update'] = time();
            		//$arr_seo['ip_create'] = $_SERVER['REMOTE_ADDR'];
            		$arr_seo['ip_update'] = $_SERVER['REMOTE_ADDR'];

            		return $this->DB->update('pages_seo',
	                                $arr_seo,
	                                array('id' => $row_seo['id']
	                                )
	            	);


	            }else{
	            	throw new jqGrid_Exception('Не удалось записать в таблицу seo, т.к. нет id content');
	            }
    		}else{
            	throw new jqGrid_Exception('Запрос не выполнен, нет id');
        	}
            //$response = parent::opEdit($id, $upd);
            //cache::drop($id);                       // after oper
    		//$response['cache_dropped'] = 1;         // modify original response

    		return $response;
    }

	/**
    *  Операция добавления
    *  @param array $data
    *  @return boolean
    */
    protected function opAdd($data) {
        if(!empty($data['name'])){
        	$this->isset_uri($data['uri']);
        	$data['uri'] = Modules::run('pages/pages_headers/get_correct_uri', $data['uri']);
        		$arr_header['active'] = $data['active'];
        		$arr_header['label'] = $data['label'];
            	$arr_header['sorter'] = $data['sorter'];
            	$arr_header['uri'] = $data['uri'];

            	$arr_header['uri_old'] = (!empty($data['uri_old'])) ? $data['uri_old'] : null;
            	$arr_header['text1'] = $data['text1'];
            	$arr_header['text2'] = $data['text2'];
            	$arr_header['date'] = $data['date'];
            	$arr_header['date_create'] = time();
            	$arr_header['date_update'] = time();
            	$arr_header['ip_create'] = $_SERVER['REMOTE_ADDR'];
            	$arr_header['ip_update'] = $_SERVER['REMOTE_ADDR'];
            	$arr_header['icon'] = $data['foto_list'];
            	$arr_header['img_fon'] = $data['img_fon'];
            	$name_file_load = $this->upload_image();
                if($name_file_load) $arr_header['icon'] = $name_file_load;

            	#Save book name to books table
	            $id = $this->DB->insert($this->table,
	                                $arr_header,
	                                true
	            );

	            if(!empty($id) && is_numeric($id)){	            	$arr_content['id_page_header'] = $id;
	            	$arr_content['active'] = $data['active_content'];
            		$arr_content['sorter'] = $data['sorter_content'];
            		$arr_content['name'] = $data['name'];
            		$arr_content['description'] = $data['description'];

            		$arr_content['date_create'] = time();
            		$arr_content['date_update'] = time();
            		$arr_content['ip_create'] = $_SERVER['REMOTE_ADDR'];
            		$arr_content['ip_update'] = $_SERVER['REMOTE_ADDR'];

            		$id_content = $this->DB->insert('pages_contents',
	                                $arr_content,
	                                true
	            	);
	            }else{	            	throw new jqGrid_Exception('Не удалось записать в таблицу content, т.к. нет id page');
	            }
	            if(!empty($id_content) && is_numeric($id_content)){	            	$arr_seo['id_page_content'] = $id_content;
	            	$arr_seo['title'] = $data['seo_title'];
            		$arr_seo['description'] = $data['seo_description'];
            		$arr_seo['keywords'] = $data['seo_keywords'];
            		$arr_seo['h1'] = $data['seo_h1'];
            		$arr_seo['date_create'] = time();
            		$arr_seo['date_update'] = time();
            		$arr_seo['ip_create'] = $_SERVER['REMOTE_ADDR'];
            		$arr_seo['ip_update'] = $_SERVER['REMOTE_ADDR'];

            		$id_content = $this->DB->insert('pages_seo',
	                                $arr_seo,
	                                true
	            	);
	            }else{
	            	throw new jqGrid_Exception('Не удалось записать в таблицу seo, т.к. нет id content');
	            }


        }else{
        	throw new jqGrid_Exception('Не задано имя');
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
		if($res = Modules::run('menus/menus/get_nodes_of_name', $id, 'page')){			$str = '(';
			foreach($res as $items){				$str .=	$items->id.',';
			}
			$str .= ')';
			throw new jqGrid_Exception('Нельзя удалить страницу, пока она содержится в пунктах меню: '.$str);
		}
		if( ! Modules::run('pages/pages_headers/eventDeletePage', $id)){			throw new jqGrid_Exception('Не удалось удалить страницу');
		}

    }

	protected function upload_image(){		 // проверяем был ли обновлен файл и если да,
	                // загружаем файл и добавляем к массиву с данными
	                if(isset($_FILES['foto_upload'])){
	                	$name = $_FILES['foto_upload']['name'];
	                	//throw new jqGrid_Exception('Было загружено изображение');
	                	$data_load = Modules::run('pages/pages_headers/upload_file', $name);
	                    //var_dump($data);
	                }
		if(isset($data_load['file_name'])) return $data_load['file_name'];
		return false;
	}

    protected function initDatepicker($options=null)
    {
        $options = is_array($options) ? $options : array();

        return new jqGrid_Data_Raw('function(el){$(el).datepicker(' . jqGrid_Utils::jsonEncode($options) . ');}');
    }

    protected function initTimepicker($options=null)
    {

        $options = is_array($options) ? $options : array();
//        $r = $this->parseRow();
//        $options['hour'] = date('H', $r['date_create']);
        return new jqGrid_Data_Raw('function(el){$(el).timepicker(' . jqGrid_Utils::jsonEncode($options) . ');}');
    }

    protected function initDatetimepicker($options=null)
    {
        $options = is_array($options) ? $options : array();

        return new jqGrid_Data_Raw('function(el){$(el).datetimepicker(' . jqGrid_Utils::jsonEncode($options) . ');}');
    }

	protected function initDateRangePicker($options = null)
    {
        $options = is_array($options) ? $options : array();

        return new jqGrid_Data_Raw('function(el){$(el).daterangepicker(' . jqGrid_Utils::jsonEncode($options) . ');}');
    }

    protected function searchOpDateRange($c, $val)
    {
        //--------------
        // Date range
        //--------------

        if(strpos($val, ' / ') !== false)
        {
            list($start, $end) = explode(' / ', $val, 2);

            $start = strtotime(trim($start));
            $end = strtotime(trim($end));

            if(!$start or !$end)
            {
                throw new jqGrid_Exception('Invalid date format');
            }

            #Stap dates if start is bigger than end
            if($start > $end)
            {
                list($start, $end) = array($end, $start);
            }

            $start = date('yy/mm/dd', $start);
            $end = date('yy/mm/dd', $end);

            return $c['db'] . " BETWEEN '$start' AND '$end'";
        }

        //------------
        // Single date
        //------------

        $val = strtotime(trim($val));

        if(!$val)
        {
            throw new jqGrid_Exception('Invalid date format');
        }

        $val = date('yy/mm/dd', $val);

        return "DATE({$c['db']}) = '$val'";
    }

    protected function searchOpDate($c, $val)
    {    	$start = strtotime(trim($val));
    	$end = $start + 86400;

    	return $c['db'] . " BETWEEN '$start' AND '$end'";
    }

    protected function button_delete_foto(){
    	$str = '<input type="submit" name="foto_delete" value="Удалить" onclick="foto_delete()" />';
    	return $str;
	}

    /**
    * Возвращает проверенный uri
    */
    protected function isset_uri($uri, $ignore_id = false){    	if(Modules::run('pages/pages_headers/get_check_uri', $uri, $ignore_id)){    		throw new jqGrid_Exception('Такой URI уже используется, придумайте другой или оставьте строку пустой тогда URI сгенерируется автоматически');
    		return true;
    	}else{            return false;
    	}

    }
}
