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
        $this->start_row_age = 21;


		$this->params['directions'] = Modules::run('duc/duc_directions/MY_data_array_one', 'id', 'name', false, 'name');
        $this->params['departments'] = Modules::run('duc/duc_departments/MY_data_array_one', 'id', 'name', false, 'name');
        $this->params['sections'] = Modules::run('duc/duc_sections/MY_data_array_one', 'id', 'name', false, 'name');
        $this->params['activities'] = Modules::run('duc/duc_activities/MY_data_array_one', 'id', 'name', false, 'name');
        $this->params['teachers'] = Modules::run('duc/duc_teachers/MY_data_array_one', 'id', array('surname', 'name', 'name2'), false, 'surname');
        $this->params['teachersData'] = Modules::run('duc/duc_teachers/MY_data_array', array('surname', 'name', 'name2', 'foto', 'id'), false, false, array('surname', 'name'));
        $this->params['groups'] = Modules::run('duc/duc_groups/listGroups');
        $this->params['groupsEscape'] = Modules::run('duc/duc_groups/listGroupsEscape');
        //$this->params['teachersData'] = Modules::run('duc/duc_teachers/selectTeachersWithImg');
        //$this->params['teachersData'] = Modules::run('duc/duc_teachers/selectTeachersSelect2');
        //var_dump($this->params['groups']);
        //exit;
        $this->params['schedulesGroups'] = Modules::run('duc/duc_schedules/MY_data_array_one', 'id_group', 'active', array('active' => 1));

        //$this->params['concertmasters'] = Modules::run('duc/duc_concertmasters/MY_data_array_one');
		$this->params['ages'] = Modules::run('duc/duc_settings/listAges');
        $this->params['sexs'] = Modules::run('duc/duc_settings/listSexs');

        $this->params['year_create'] = Modules::run('duc/duc_groups/listYearCreate');
        $this->params['period'] = Modules::run('duc/duc_groups/listPeriod');

        $this->query = "
            SELECT {fields}
            FROM duc_groups AS object
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
                                   'width' => 30,
                                   'align' => 'center',
                                   //'editable' => true,
                                   'encode' => false,
            ),
            'active_site'  => array('label' => lang('duc_active_site'),
                                   'db' => 'object.active_site',
                                   'width' => 50,
                                   'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'edittype' => "checkbox",
                                   'editrules' => array('required' => true,
                                                     'edithidden' => true,
                                   ),
                                   //'sorttype' => "int",
            ),
            'active_site_icon'  => array('label' => lang('duc_active_site'),
                                   'manual'=> true,
                                   'width' => 30,
                                   'align' => 'center',
                                   //'editable' => true,
                                   'encode' => false,
                                   //'sorttype' => array(0,1),
            ),
            'id_direction'   => array('label' => lang('duc_direction'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['directions'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => new jqGrid_Data_Value($this->params['directions']),
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
   			'id_section'   => array('label' => lang('duc_section'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   'replace' => $this->params['sections'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => new jqGrid_Data_Value($this->params['sections']),
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
                                        				'value' => new jqGrid_Data_Value($this->params['sections'], 'All'),
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
                                   							'value' => new jqGrid_Data_Value($this->params['activities']),
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

                                   'edittype' => "custom",
                                   'editoptions' => array(
                                   						'value' => new jqGrid_Data_Value($this->params['teachers']),
                                   						'teachers' => $this->params['teachers'],
                                                		'dataTeachers' => $this->params['teachersData'],
                                   						//'value' => new jqGrid_Data_Value($this->params['teachersData']),
                                   						//'custom_element' => new jqGrid_Data_Raw('myselect'),
                                                		
                                                		'custom_value' => new jqGrid_Data_Raw('function(elem, operation, value){
                                                			console.log("смотрим custom_value");
                                                			console.log("elem: ");
														    console.log(elem);
														    console.log(operation);
														    console.log(value);
														    var get_val = $(elem).value;
														    //var set_val = $(\'input\',elem).val(value);
														    console.log(\'get_val:\');
														    console.log(get_val);
														    //console.log(set_val);
														    //return $(elem).value;
														    if(operation === \'get\') {
														       //return $(elem).find("input").val();
														       return $(elem).val();
														       //return value;
														    } else if(operation === \'set\') {
														       //$(\'input\',elem).val(\'!-\'+value);
														       //return $(elem).find("input").val();
														       var select = $("select", $(elem));
														       //var select = $(elem).val();			       	
														       

														       console.log(\'select:\');
														       console.log(select);
														    	var first = select.value;
														    	return first;
														    }
                                                		}'),
                                                		'custom_element' => new jqGrid_Data_Raw('function(value, options){
                                                			console.log("смотрим custom_element");
                                                			console.log("value:");
                                                			console.log(value);
                                                			var elemStr = \'<select id="\'+options.id +\'" class="FormElement" role="select" name="\'+options.name +\'">\';
														  	if(options.dataTeachers && typeof(options.dataTeachers) == \'object\'){
														  		//alert(options.teachers);
														  		var teacher = false;
														  		var flagSelect = \'\';
														  		for(k in options.dataTeachers){
														  			if(options.teachers && typeof(options.teachers) == \'object\'){
															  			if(options.teachers[options.dataTeachers[k].id]){
															  				teacher = options.teachers[options.dataTeachers[k].id];	
															  			}else{
															  				teacher = false;
															  			}	  			 
															  		}
															  		
															  		if(value == teacher){
															  			flagSelect = \'selected\';
															  		}else{
															  			flagSelect = \'\';
															  		}
															  		
														  			//alert(value);		  	
																  	elemStr += \'<option role="option" value="\' + options.dataTeachers[k].id +
																  	\'" data-img="\'+options.dataTeachers[k].foto +\'" \'+ flagSelect +\'>\'+ options.teachers[options.dataTeachers[k].id] +
																	\'</option>\';
																	// return DOM element from jQuery object
																}
																
															}
															elemStr += \'</select>\';
															console.log(\'elemStr:\');
														    console.log($(elemStr)[0]);
															return elemStr;
                                                		}'),
                                                		
														//'custom_element' => new jqGrid_Data_Raw('myelem'),
														//'custom_value' => new jqGrid_Data_Raw('myvalue'),
                                                        //'data-img' => 'test.jpg',
                                                		//'style' => 'width:200px',
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
                                        				//'dataInit' => $this->initSelect2(),
                                   						'sopt' => 'eq',
                                        				'clearSearch' => false,
                                        				'value' => new jqGrid_Data_Value($this->params['teachers'], 'All'),
                                                		'dataTeachers' => $this->params['teachersData'],
                                        				//'value' => $this->params['teachers'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function() { $("#gs_id_teacher").select2(); }'),
                  				   	),

   			),
   			'dop_teachers'   => array('label' => lang('duc_dop_teachers'),
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
   			'concertmasters'   => array('label' => lang('duc_concertmasters'),
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
            'paid'  => array('label' => lang('duc_paid'),
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
            'price'    => array('label' => lang('duc_price'),
                                   'db' => 'object.price',
                                   'hidden' => true,
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => false,
                                                       'integer' => true,
                                                       'edithidden' => true,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),
            'free'  => array('label' => lang('duc_free'),
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
            'accept2'  => array('label' => lang('duc_accept2'),
                                   'db' => 'object.accept2',
                                   'width' => 50,
                                   //'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'edittype' => "checkbox",
                                   'editrules' => array('required' => true,
                                                     'edithidden' => true,
                                   ),
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
            'sex'   => array('label' => lang('duc_sex'),
                                    //'hidden' => true,
                                    'width' => 50,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['sexs'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['sexs'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['sexs'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
            'name'    => array('label' => lang('duc_name'),
                                    'db' => 'object.id',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'replace' => $this->params['groups'],
                                   //'datatype' => 'html',
                                   //'encode' => false,
                                   //'classes' => 'selecttt',
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
                                   
                                   
                                   'stype' => 'select',
                                   'searchoptions' => array(
                                        				//'dataInit' => $this->initSelect2(),
                                        				'value' => new jqGrid_Data_Value($this->params['groupsEscape'], 'All'),
                                        				'style' => 'text-align:left;',
                                                		//'dataTeachers' => $this->params['teachersData'],
                                        				//'value' => $this->params['groups'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function() { $("#gs_id_teacher").select2(); }'),
                  				   ),
                  				   
                  				   
            ),
            'programm'    => array('label' => lang('duc_programm'),

                                   'width' => 250,
                                   'align' => 'left',
                                   'hidden' => true,
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                                       'edithidden' => true,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),
            'year_create'   => array('label' => lang('duc_year_create'),
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
                                                     'maxValue' => 2100,
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
   			'period'   => array('label' => lang('duc_period'),
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
   			/*
   			'age_child'    => array('label' => lang('duc_age_child'),

                                   'width' => 100,
                                   'align' => 'left',
                                   'hidden' => true,
                                   'editable' => true,
                                   'editrules' => array(//'required' => true,
                                                       'maxValue' => 100,
                                                       'edithidden' => true,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),
            */
            'age_from'   => array('label' => lang('duc_from'),
                                    //'hidden' => true,
                                    'width' => 50,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['ages'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['ages'],
                                	),
                                   'editrules' => array('required' => true,
                                                     'integer' => true,
                                                     'minValue' => min($this->params['ages']),
                                                     'maxValue' => max($this->params['ages']),
                                                     'edithidden' => true,
                                   ),
                                   'stype' => 'select',
                                   //'replace' => $this->params['categories'],
                                   'searchoptions' => array(
                                        				'value' => new jqGrid_Data_Value($this->params['ages'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
                  				   'formoptions'=>array(//'rowpos'=>($this->start_row_age),
                  				   						//'colpos'=>1,
                  				                       'elmprefix' => lang('duc_from'),

                  				   ),
   			),
   			'age_to'   => array('label' => lang('duc_to'),
                                    //'hidden' => true,
                                    'width' => 50,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['ages'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['ages'],
                                	),
                                   'editrules' => array('required' => true,
                                                     'integer' => true,
                                                     'minValue' => min($this->params['ages']),
                                                     'maxValue' => max($this->params['ages']),
                                                     'edithidden' => true,
                                   ),
                                   'stype' => 'select',
                                   //'replace' => $this->params['categories'],
                                   'searchoptions' => array(
                                        				'value' => new jqGrid_Data_Value($this->params['ages'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
                  				   'formoptions'=>array('rowpos'=>($this->start_row_age),
                  				   						'colpos'=>1,
                  				                       'elmprefix' => lang('duc_to'),
                  				                       'label' => lang('duc_age'),
                  				   ),
   			),
            'school'    => array('label' => lang('duc_school'),

                                   'width' => 100,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array(
                                                       'integer' => true,
                                                       //'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),
            'uri_gallery'    => array('label' => lang('duc_uri_gallery'),

                                   'width' => 100,
                                   'align' => 'left',
                                   'hidden' => true,
                                   'editable' => true,
                                   'editrules' => array(
                                                       //'integer' => true,
                                                       'maxValue' => 250,
                                                       'edithidden' => true,
                                   ),
                                   'editoptions' => array('size' => 90),
                                   'formoptions'=>array(
                  				                       'elmprefix' => lang('duc_domain_gallery'),
                  				                       'elmsuffix' => '<div class="tooltip">'.lang('duc_tooltip_uri_gallery').'</div>',
                  				   ),
            ),
            'short_description'    => array('label' => lang('duc_short_description'),
                                    'db' => 'object.short_description',
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
            'description'    => array('label' => lang('duc_description'),
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
            'sortname'  => 'sorter',
            'sortorder' => 'asc',
            'rowNum' => 20,
            'rownumbers' => true,
            'width' => 900,
            'height' => '100%',
            'autowidth' => true,
            'altRows' => true,
            //'gridComplete'=> new jqGrid_Data_Raw('function(){$(this).jqGrid(\'extHighlight\');}'), //Чтобы отработала функция parseRow (ниже)
    		'multiselect' => true, // множественный выбор (checkbox)
    		'multiboxonly' => true,
    		'rowList'     => array(10, 20, 30, 50, 100),
            //'navkeys' => true,
            'caption' => lang($this->table),
			//'onSelectRow' => new jqGrid_Data_Raw('function(id){alert("Selected row: " +id);}'),
            // показывает кол-во всех записей
            'viewrecords' => true,
             
            //закрашиваем ячейки коллектива у которых есть расписание
            'afterInsertRow' => new jqGrid_Data_Raw('function($rowid, $rowdata, $rowelem)
									{
									    var id = $rowid;
									    var shedule = '.json_encode($this->params["schedulesGroups"]).';
									    //console.log(shedule);
                                        //console.info(id);
                                        //console.info(id in shedule);
                                        if(id in shedule){
									    	$("tr#" + id + " td[aria-describedby=grid_duc_groups_name]").css("background-color", "#9C9");
									    }
									}'
		    ),

        );
        #Set nav
        $this->nav = array(//'view' => true, 'viewtext' => 'Смотреть',
        					'add' => true, 'addtext' => 'Добавить',
        				  	'edit' => true, 'edittext' => 'Редактировать',
        				  	'del' => true, 'deltext' => 'Удалить',
        				  	'refresh' => true, 'refreshtext' => 'Обновить',
                  			'search' => true, 'searchtext' => "Поиск",  'searchtitle' => "Find records",
                  			//'alertcap' => "Внимание",  'alerttext' => "Пожалуйста, выберите ячейку",
        				  	#Set common excel export
			            	//'excel' => true,
			            	//'exceltext' => 'Excel',
			            	//'search' => true,

        				  	'prmAdd' => array('width' => 800, 'closeAfterAdd' => true),
    				      	'prmEdit' => array('width' => 800,
								//'height' => 600,
								'closeAfterEdit' => true,
								//'closeAfterAdd' => true,
	    						//'closeAfterEdit' => true,
	    						'recreateForm' => true,
								//'viewPagerButtons' => false, //скрывает кнопки навигации предыдущая, следующая
					          	'afterclickPgButtons' => "function() {}",
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
        if($r['active_site'] == 1){
            $r['active_site_icon'] = '<img src="'.assets_img('admin/button_green.gif', false).'">';

        }else{
            $r['active_site_icon'] = '<img src="'.assets_img('admin/button_red.gif', false).'">';
        }
        if($r['accept2'] == 1){
            $r['accept2_icon'] = '<img src="'.assets_img('admin/button_green.gif', false).'">';

        }else{
            $r['accept2_icon'] = '<img src="'.assets_img('admin/button_red.gif', false).'">';
        }
        $r['concertmasters'] = implode(',',Modules::run('duc/duc_concertmasters/MY_data_array_one',
        												'id',
        												'id_teacher',
        												//where
        												array('id_group' => $r['id'])

        												)
        							);
        $r['dop_teachers'] = implode(',',Modules::run('duc/duc_dop_teachers/MY_data_array_one',
        												'id',
        												'id_teacher',
        												//where
        												array('id_group' => $r['id'])

        							)
        );
        //var_dump($this->params['teachers']);
        //exit;
        
        
        $res = '<option';
        $res .= ' value='.$r['id_teacher'];
        if(!empty($this->params['teachersData'][$r['id_teacher']]['foto'])){
          $res .= ' data-img="'.$this->params['teachersData'][$r['id_teacher']]['foto'].'"';
        }
        $res .= '>';
        if(!empty($this->params['teachers'][$r['id_teacher']])){

          //$res .= $this->params['teachersData'][$r['id_teacher']]['foto'].$this->params['teachers'][$r['id_teacher']];

        }
        $res .= '</option>';
        //$r['id_teacher'] = $res;
        //var_dump($this->cols);

        if(isset($this->params['schedulesGroups']['id_group'])){
        	$this->cols['name']['classes'] = 'sel';
        }else{
        	$this->cols['name']['classes'] = 'desel';
        }
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
        if(isset( $data['active']) && $data['active'] == 'on' || $data['active'] == 1){
            $data['active'] = 1;
        }else{
            $data['active'] = 0;
        }
        if(isset( $data['active_site']) && $data['active_site'] == 'on' || $data['active_site'] == 1){
            $data['active_site'] = 1;
        }else{
            $data['active_site'] = 0;
        }
        $data['sorter'] = (!empty($data['sorter'])) ? $data['sorter'] : 10;

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
        if(isset( $data['accept2']) && $data['accept2'] == 'on' || $data['accept2'] == 1){
            $data['accept2'] = 1;
        }else{
            $data['accept2'] = 0;
        }
        if(isset($this->params['year_create'][$data['year_create']])){
        	$data['year_create'] = $this->params['year_create'][$data['year_create']];
        }else{
        	$data['year_create'] = '';
        }

		if($data['age_to'] < $data['age_from']) throw new jqGrid_Exception('Возраст детей "до" не может быть меньше "от"');
        //throw new jqGrid_Exception('Ошибка в operData');
        return $data;
    }

    /**
    * 	редактирование объекта
    */
    protected function opEdit($id, $data) {
        // редактирование concertmasters
            if(isset($data['concertmasters'])){
            	$this->editConcertmasters($id, $data['concertmasters']);
            	unset($data['concertmasters']);
            }

        // редактирование concertmasters
            if(isset($data['dop_teachers'])){
            	$this->editDopTeachers($id, $data['dop_teachers']);
            	unset($data['dop_teachers']);
            }

    	if(isset($id)){
            #Get editing row
            $result = $this->DB->query('SELECT * FROM '.$this->table.' WHERE id='.intval($id));
            $row = $this->DB->fetch($result);


            #Save
            $upd_data = array(
            		  'active' => $data['active'],
            		  'active_site' => $data['active_site'],
                	  'sorter' => $data['sorter'],
                      'id_direction' => $data['id_direction'],
                      'id_department' => $data['id_department'],
                      'id_activity' => $data['id_activity'],
                      'id_section' => $data['id_section'],
                      'id_teacher' => $data['id_teacher'],
                      'paid' => $data['paid'],
                      'accept2' => $data['accept2'],
                      'free' => $data['free'],
                      'name' => $data['name'],
                      'programm' => $data['programm'],
                      'year_create' => $data['year_create'],
                      'period' => $data['period'],
                      //'uri' => $data['uri'],
                      'uri_gallery' => $data['uri_gallery'],
                      //'age_child' => $data['age_child'],
                      'school' => $data['school'],
                      'sex' => $data['sex'],
                      'age_from' => $data['age_from'],
                      'age_to' => $data['age_to'],
                      'short_description' => $data['short_description'],
                      'description' => $data['description'],
                      'price' => $data['price'],

                      'date_update' => time(),
                      'ip_update' => $_SERVER['REMOTE_ADDR'],

            );

            #Save book name to books table
	            $response = $this->DB->update($this->table,
	                                $upd_data,
	                                array('id' => $row['id']
	                                      )
	                            );

            //unset($upd['name']);
        }else{
            throw new jqGrid_Exception('Запрос не выполнен, нет id');
        }

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
        	#Save
            $upd_data = array(
        		'active' => $data['active'],
        		'active_site' => $data['active_site'],
          	    'sorter' => $data['sorter'],
                'id_direction' => $data['id_direction'],
                'id_department' => $data['id_department'],
                'id_activity' => $data['id_activity'],
                'id_section' => $data['id_section'],
                'id_teacher' => $data['id_teacher'],
                'paid' => $data['paid'],
                'accept2' => $data['accept2'],
                'free' => $data['free'],
                'name' => $data['name'],
                'programm' => $data['programm'],
                'year_create' => $data['year_create'],
                'period' => $data['period'],
                //'uri' => $data['uri'],
                'uri_gallery' => $data['uri_gallery'],
                //'age_child' => $data['age_child'],
                'school' => $data['school'],
                'sex' => $data['sex'],
                'age_from' => $data['age_from'],
                'age_to' => $data['age_to'],
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'price' => $data['price'],

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
            // редактирование concertmasters
            if(isset($data['concertmasters'])){
            	$this->editConcertmasters($id, $data['concertmasters']);
            }

        }else{
        	throw new jqGrid_Exception('Не задано имя');
        }

        return $id;

    }

	/**
    * 	редактирование объекта
    */
    protected function opDel($id) {
       	//echo 'удалено '.$id;
       	if( ! Modules::run('duc/duc_groups/eventDelete', $id)){
			throw new jqGrid_Exception('Не удалось удалить объект');
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
        $res = Modules::run('duc/duc_groups/sorterId', $ids);
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

     /**
    * Редактирование параметров concertmasters
    * @param int - id group
    * @param string - строка id concertmasters перечисленных через запятую
    */
    protected function editDopTeachers($id, $dop_teachers){
    	if(!empty($id) && isset($dop_teachers)){

                    //if(!empty($upd['concertmasters'])){
	                    //если находим разделитель 'запятая' в веденных данных
	                    //тогда переводим в массив значений
	                    //если разделителя нет - в массив с одним значением
	                    if(strpos($dop_teachers,',') !== false){
	                    	$comm = explode(',',$dop_teachers);
	                    }else{
	                    	$comm = array($dop_teachers);
	                    }
	                    //редактируем concertmasters
	                    if( ! Modules::run('duc/duc_dop_teachers/write_teachers',$id, $comm)){
                            throw new jqGrid_Exception('Не удалось обновить дополнительного педагога');
	                    }
                    //}

     	}
    }

    protected function initSelect2($options=null)
    {
        $options = is_array($options) ? $options : array();

        return new jqGrid_Data_Raw('
        	function() { $("#gs_id_teacher").select2(); }
        ');
    }

}
