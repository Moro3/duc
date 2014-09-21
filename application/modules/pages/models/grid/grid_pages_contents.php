<?php
class grid_duc_groups extends jqGrid
{
    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {
        $this->CI = &get_instance();
        $this->table = 'duc_groups';

		$this->params['directions'] = Modules::run('duc/duc_directions/MY_data_array_one');
        $this->params['departments'] = Modules::run('duc/duc_departments/MY_data_array_one');
        $this->params['activities'] = Modules::run('duc/duc_activities/MY_data_array_one', 'id', 'name', false, 'name');
        $this->params['teachers'] = Modules::run('duc/duc_teachers/MY_data_array_one', 'id', array('surname', 'name', 'name2'), false, 'surname');
        //$this->params['concertmasters'] = Modules::run('duc/duc_concertmasters/MY_data_array_one');

        $this->params['year_create'][] = '';
        $this->params['year_create'] += range(1990,date('Y'));
        $this->params['period'] = range(1,8);

        $this->query = "
            SELECT {fields}
            FROM duc_groups AS object
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
            'id_direction'   => array('label' => lang('duc_direction'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['directions'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['directions'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['directions'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
   			'id_department'   => array('label' => lang('duc_department'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['departments'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['departments'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['departments'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
   			'id_activity'   => array('label' => lang('duc_activity'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   'replace' => $this->params['activities'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['activities'],
                                	),
                                   'editrules' => array('required' => true,
                                                     'integer' => true,
                                                     'minValue' => 0,
                                                     'maxValue' => 1000,
                                                     'edithidden' => true,
                                   ),
                                   'stype' => 'text',
                                   //'replace' => $this->params['categories'],
                                   'searchoptions' => array(
                                        				//'value' => new jqGrid_Data_Value($this->params['activities'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
   			'id_teacher'   => array('label' => lang('duc_teacher'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['teachers'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['teachers'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['teachers'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
   			'concertmasters'   => array('label' => 'Концертмейстеры',
                                    //'db' => 'object.id_category',
                                    'manual'=> true,
                                    'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   'stype' => 'checkbox',
                                   //'replace' => array('Check1','Check2','Check3','Check4'),

                                   //'value' => new jqGrid_Data_Value($this->params['directions'], 'All'),
                                   'edittype' => "select",
                                   'editoptions' => array(
                                   						  //textarea
                                   						  //'value' => new jqGrid_Data_Value($this->params['teachers'], 'НЕТ'),
                                   						  //jquery multiselect
                                   						  'value' => new jqGrid_Data_Value($this->params['teachers']),
                                   						  'multiple' => true,
                                   						  //'list' => '1,3,5,6,7',
                                   						  //'selected' => array(1,3,5,6,7),
                                   						  'size' => 10,
                                   						  'class' => 'multiselect',

                                                          //'dataUrl' => '/grid/lands_permits/lands/data_select'
                                   ),
                                   'editrules' => array(//'required' => true,
                                                     //'integer' => true,
                                                     'minValue' => 0,
                                                     'maxValue' => 100,
                                                     'edithidden' => true,
                                   ),
            ),
            'paid'  => array('label' => 'Платно',
                                   'db' => 'object.paid',
                                   'width' => 50,
                                   //'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'edittype' => "checkbox",
                                   'editrules' => array('required' => true,
                                                     'edithidden' => true,
                                   ),
            ),
            'free'  => array('label' => 'Бесплатно',
                                   'db' => 'object.free',
                                   'width' => 50,
                                   //'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'edittype' => "checkbox",
                                   'editrules' => array('required' => true,
                                                     'edithidden' => true,
                                   ),
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
            'name'    => array('label' => 'Название',
                                    'db' => 'object.name',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),
            'programm'    => array('label' => 'Образовательные программы',

                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),
            'year_create'   => array('label' => 'Год создания',
                                    //'hidden' => true,
                                    'width' => 50,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['year_create'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['year_create'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['year_create'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
   			'period'   => array('label' => 'Срок реализации',
                                    //'hidden' => true,
                                    'width' => 50,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['period'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['period'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['period'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
   			'age_child'    => array('label' => 'Возраст детей',

                                   'width' => 100,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),
            'school'    => array('label' => 'На базе школы',

                                   'width' => 100,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array(
                                                       'integer' => true,
                                                       //'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),
            'description'    => array('label' => 'Описание',
                                    'db' => 'object.description',
                                    'hidden' => true,
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'edittype' => "textarea",
                                   'encode' => true,
                                   'editoptions' => array('rows' => 10,
                                   						 'cols' => 80,
                                   						 'maxValue' => 50000,

                                   	),

                                   'editrules' => array('edithidden' => true),
            ),

        );
        $this->options = array(
            'sortname'  => 'date_create',
            'sortorder' => 'asc',
            'rowNum' => 20,
            'rownumbers' => true,
            'width' => 900,
            'height' => '100%',
            'autowidth' => true,
            'altRows' => true,
    		//'multiselect' => true, // множественный выбор (checkbox)
    		'rowList'     => array(10, 20, 30, 50, 100),

        );
        #Set nav
        $this->nav = array(//'view' => true, 'viewtext' => 'Смотреть',
        					'add' => true, 'addtext' => 'Добавить объект',
        				   'edit' => true, 'edittext' => 'Редактировать',
        				   'del' => true, 'deltext' => 'Удалить',
        				'prmAdd' => array('width' => 800, 'closeAfterAdd' => true),
    					'prmEdit' => array('width' => 800,
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
        if($r['show_i'] == 1){
            $r['show_i_icon'] = '<img src="'.assets_img('admin/button_green.gif', false).'">';

        }else{
            $r['show_i_icon'] = '<img src="'.assets_img('admin/button_red.gif', false).'">';
        }
        $r['concertmasters'] = implode(',',Modules::run('duc/duc_concertmasters/MY_data_array_one',
        												'id',
        												'id_teacher',
        												//where
        												array('id_group' => $r['id'])

        												)
        							);

        return $r;
    }

	protected function renderGridUrl(){
        return '/grid/duc_groups/duc/grid/';
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

		if(isset( $data['paid']) && $data['paid'] == 'on' || $data['paid'] == 1){
            $data['paid'] = 1;
        }else{
            $data['paid'] = 0;
        }
        if(isset( $data['free']) && $data['free'] == 'on' || $data['free'] == 1){
            $data['free'] = 1;
        }else{
            $data['free'] = 0;
        }
        if(isset($this->params['year_create'][$data['year_create']])){        	$data['year_create'] = $this->params['year_create'][$data['year_create']];
        }else{        	$data['year_create'] = '';
        }

        //throw new jqGrid_Exception('Ошибка в operData');
        return $data;
    }

    /**
    * 	редактирование объекта
    */
    protected function opEdit($id, $upd) {
        // редактирование коммуникаций
            if(isset($upd['concertmasters'])){
            	$this->editConcertmasters($id, $upd['concertmasters']);
            	unset($upd['concertmasters']);
            }
            $response = parent::opEdit($id, $upd);
            //cache::drop($id);                       // after oper
    		$response['cache_dropped'] = 1;         // modify original response

    		return $response;
    }

     /**
    * Редактирование параметров concertmasters
    * @param int - id group
    * @param string - строка id concertmasters перечисленных через запятую
    */
    protected function editConcertmasters($id, $concertmasters){
    	if(!empty($id) && isset($concertmasters)){

                    //if(!empty($upd['concertmasters'])){
	                    //если находим разделитель 'запятая' в веденных данных
	                    //тогда переводим в массив значений
	                    //если разделителя нет - в массив с одним значением
	                    if(strpos($concertmasters,',') !== false){
	                    	$comm = explode(',',$concertmasters);
	                    }else{
	                    	$comm = array($concertmasters);
	                    }
	                    //редактируем concertmasters
	                    if( ! Modules::run('duc/duc_concertmasters/write_teachers',$id, $comm)){
                            throw new jqGrid_Exception('Не удалось обновить концертмейстеров');
	                    }
                    //}

     	}
    }

}
