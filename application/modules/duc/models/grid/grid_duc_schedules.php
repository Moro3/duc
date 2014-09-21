<?php
class grid_duc_schedules extends jqGrid
{

    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {
        $this->CI = &get_instance();
        $this->table = 'duc_schedules';
        $this->caption = lang('duc_schedules');

		$this->config = $this->loader->get('config');

        $this->params['groups'] = Modules::run('duc/duc_groups/listGroups');
        $this->params['groupsEscape'] = Modules::run('duc/duc_groups/listGroupsEscape');
        $this->params['groupsTeacherEscape'] = Modules::run('duc/duc_groups/listGroupsTeacherEscape');
        $this->params['teachers'] = Modules::run('duc/duc_teachers/MY_data_array_one', 'id', array('surname', 'name', 'name2'), false, 'surname');
        $this->params['teachersEscape'] = Modules::run('duc/duc_teachers/listTeachersEscape');
        $this->params['num_groups'] = Modules::run('duc/duc_numgroups/MY_data_array_one', 'id', 'name', false, 'sorter');
        $this->start_row_week = 7;

        //var_dump($this->params['teachersEscape']);
        //exit;
		//return;
        /*
        $this->query = "
            SELECT {fields}
            FROM duc_schedules AS object, duc_groups AS grouper, duc_teachers AS teacher
            WHERE {where}
        ";
        */

        $this->query = "
            SELECT {fields}
            FROM duc_schedules AS object
        	LEFT JOIN duc_groups grouper
            ON object.id_group = grouper.id
            LEFT JOIN duc_teachers teacher
            ON grouper.id_teacher = teacher.id
            WHERE {where}
        ";

        //$this->where[] = 'object.id_group = grouper.id';
        //$this->where[] = 'grouper.id_teacher = teacher.id';

        $this->cols = array(
            'id'          => array('label' => lang('duc_id'),
                                   'db' => 'object.id',
                                   'width' => 20,
                                   'align' => 'center',
                                   'editable' => true,
                                   "editoptions"=>array("readonly"=>true),
            ),


            'active'  => array('label' => lang('duc_active'),
                                   'db' => 'object.active',
                                   'width' => 30,
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
            'sorter'  => array('label' => lang('duc_sorter'),
                                   'db' => 'object.sorter',
                                   'width' => 30,
                                   'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'integer' => true,
                                                     	'minValue' => 1,
                                                     	'maxValue' => 1000,
                                   ),
            ),
            'teacher'   => array('label' => lang('duc_teacher'),
                                  'db' => 'teacher.id',
                                    //'hidden' => true,
                                    'width' => 50,
                                   //'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => array(0=>'нет') + $this->params['teachers'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							//'value' => array(0=>'нет') + $this->params['groups'],
                                   							'value' => new jqGrid_Data_Value($this->params['teachers']),
                                	),
                                   'editrules' => array('required' => true,
                                                     'integer' => true,
                                                     'minValue' => 1,
                                                     'maxValue' => 1000,
                                                     'edithidden' => true,
                                   ),
                                   'stype' => 'select',
                                   //'replace' => $this->params['categories'],
                                   'searchoptions' => array(
                                        				'value' => new jqGrid_Data_Value($this->params['teachersEscape'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
            'id_group'   => array('label' => lang('duc_group'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => array(0=>'нет') + $this->params['groups'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							//'value' => array(0=>'нет') + $this->params['groups'],
                                   							'value' => new jqGrid_Data_Value($this->params['groupsTeacherEscape']),
                                	),
                                   'editrules' => array('required' => true,
                                                     'integer' => true,
                                                     'minValue' => 1,
                                                     'maxValue' => 1000,
                                                     'edithidden' => true,
                                   ),
                                   'stype' => 'select',
                                   //'replace' => $this->params['categories'],
                                   'searchoptions' => array(
                                        				'value' => new jqGrid_Data_Value($this->params['groupsEscape'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),

   			'cabinet'   => array('label' => lang('duc_cabinet'),
                                   //'hidden' => true,
                                   'width' => 20,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array(//'required' => true,
                                                       'maxValue' => 10,
                                   ),
                                   'editoptions' => array('size' => 50),
   			),

   			'id_numgroup'   => array('label' => lang('duc_group_number'),

                                    //'hidden' => true,
                                    'width' => 50,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => array(0=>'нет') + $this->params['num_groups'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => new jqGrid_Data_Value($this->params['num_groups']),
                                	),
                                   'editrules' => array('required' => true,
                                                     'integer' => true,
                                                     'minValue' => 1,
                                                     'maxValue' => 1000,
                                                     'edithidden' => true,
                                   ),
                                   'stype' => 'select',
                                   //'replace' => $this->params['categories'],
                                   'searchoptions' => array(
                                        				'value' => new jqGrid_Data_Value($this->params['num_groups'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
            /*
   			'paid'   => array('label' => lang('duc_group_paid'),
                                  'db' => 'grouper.paid',
                                    //'hidden' => true,
                                    'width' => 30,
                                   //'editable' => true,
                                   'stype' => 'select',
                                   //'replace' => $this->params['categories'],
                                   'searchoptions' => array(
                                        				'value' => array('0' => 'Бесплатно', 'free' => 'Платно'),
                                        				//'value' => new jqGrid_Data_Value($this->params['groups'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
            */
   			'mon'   => array('label' => lang('duc_mon'),
                                    'db'    => "CONCAT(object.mon_from, '-', object.mon_to)", //function call
                                    'width' => 30,
                                    'editable' => false,
                                    'search' => false,

   			),
   			'mon_from'   => array('label' => lang('duc_mon'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_start_edu'),
                                            'timeText'      => lang('duc_mon_from'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 0,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'time' => true,
                                    ),
   			),
   			'mon_to'   => array('label' => lang('duc_mon'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_end_edu'),
                                            'timeText'      => lang('duc_mon_to'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 1,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'time' => true,
                                    ),
                                    'formoptions'=>array('rowpos'=> $this->start_row_week, 'colpos'=>1 ),
   			),
   			'tue'   => array('label' => lang('duc_tue'),
                                    'db'    => "CONCAT(object.tue_from, '-', object.tue_to)", //function call
                                    'width' => 30,
                                    'editable' => false,
                                    'search' => false,

   			),
   			'tue_from'   => array('label' => lang('duc_tue'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_start_edu'),
                                            'timeText'      => lang('duc_tue_from'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 0,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'time' => true,
                                    ),
                                    'formoptions'=>array('rowpos'=> ($this->start_row_week + 1), 'colpos'=>1 ),
   			),
   			'tue_to'   => array('label' => lang('duc_tue'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_end_edu'),
                                            'timeText'      => lang('duc_tue_to'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 1,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'time' => true,
                                    ),
                                    'formoptions'=>array('rowpos'=>($this->start_row_week + 1), 'colpos'=>1 ),
   			),
   			'wed'   => array('label' => lang('duc_wed'),
                                    'db'    => "CONCAT(object.wed_from, '-', object.wed_to)", //function call
                                    'width' => 30,
                                    'editable' => false,
                                    'search' => false,

   			),
   			'wed_from'   => array('label' => lang('duc_wed'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_start_edu'),
                                            'timeText'      => lang('duc_wed_from'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 0,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'time' => true,
                                    ),
                                    'formoptions'=>array('rowpos'=>($this->start_row_week + 2), 'colpos'=>1 ),
   			),
   			'wed_to'   => array('label' => lang('duc_wed'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_end_edu'),
                                            'timeText'      => lang('duc_wed_to'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 1,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'time' => true,
                                    ),
                                    'formoptions'=>array('rowpos'=>($this->start_row_week + 2), 'colpos'=>1 ),
   			),
   			'thur'   => array('label' => lang('duc_thur'),
                                    'db'    => "CONCAT(object.thur_from, '-', object.thur_to)", //function call
                                    'width' => 30,
                                    'editable' => false,
                                    'search' => false,

   			),
   			'thur_from'   => array('label' => lang('duc_thur'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_start_edu'),
                                            'timeText'      => lang('duc_thur_from'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 0,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'time' => true,
                                    ),
                                    'formoptions'=>array('rowpos'=>($this->start_row_week + 3), 'colpos'=>1 ),
   			),
   			'thur_to'   => array('label' => lang('duc_thur'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_end_edu'),
                                            'timeText'      => lang('duc_thur_to'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 1,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'time' => true,
                                    ),
                                    'formoptions'=>array('rowpos'=>($this->start_row_week + 3), 'colpos'=>1 ),
   			),
   			'fri'   => array('label' => lang('duc_fri'),
                                    'db'    => "CONCAT(object.fri_from, '-', object.fri_to)", //function call
                                    'width' => 30,
                                    'editable' => false,
                                    'search' => false,

   			),
   			'fri_from'   => array('label' => lang('duc_fri'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_start_edu'),
                                            'timeText'      => lang('duc_fri_from'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 0,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'time' => true,
                                    ),
                                    'formoptions'=>array('rowpos'=>($this->start_row_week + 4), 'colpos'=>1 ),
   			),
   			'fri_to'   => array('label' => lang('duc_fri'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_end_edu'),
                                            'timeText'      => lang('duc_fri_to'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 1,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'time' => true,
                                    ),
                                    'formoptions'=>array('rowpos'=>($this->start_row_week + 4), 'colpos'=>1 ),
   			),
   			'sat'   => array('label' => lang('duc_sat'),
                                    'db'    => "CONCAT(object.sat_from, '-', object.sat_to)", //function call
                                    'width' => 30,
                                    'editable' => false,
                                    'search' => false,

   			),
   			'sat_from'   => array('label' => lang('duc_sat'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_start_edu'),
                                            'timeText'      => lang('duc_sat_from'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 0,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'time' => true,
                                    ),
                                    'formoptions'=>array('rowpos'=>($this->start_row_week + 5), 'colpos'=>1 ),
   			),
   			'sat_to'   => array('label' => lang('duc_sat'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_end_edu'),
                                            'timeText'      => lang('duc_sat_to'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 1,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'time' => true,
                                    ),
                                    'formoptions'=>array('rowpos'=>($this->start_row_week + 5), 'colpos'=>1 ),
   			),
   			'sun'   => array('label' => lang('duc_sun'),
                                    'db'    => "CONCAT(object.sun_from, '-', object.sun_to)", //function call
                                    'width' => 30,
                                    'editable' => false,
                                    'search' => false,

   			),
   			'sun_from'   => array('label' => lang('duc_sun'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_start_edu'),
                                            'timeText'      => lang('duc_sun_from'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 0,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'time' => true,
                                    ),
                                    'formoptions'=>array('rowpos'=>($this->start_row_week + 6), 'colpos'=>1 ),
   			),
   			'sun_to'   => array('label' => lang('duc_sun'),
                                    'hidden' => true,
                                    'width' => 30,
                                   'editable' => true,
                                   'searchoptions' => array('dataInit' => $this->initTimepicker(array(
                                        // обновление данных в общем окне
                                        'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                                    ))),
                                    'search_op' => 'date',
                                    'editoptions' => array('dataInit' => $this->initTimepicker(array(
                                            'timeOnlyTitle' => lang('duc_time_end_edu'),
                                            'timeText'      => lang('duc_sun_to'),
                                            'hourText'      => 'Часы',
                                            'minuteText'    => 'Минуты',
                                            'secondText'    => 'Секунды',
                                            'currentText'   => 'Текущее',
                                            'closeText'     => 'Закрыть',
                                            //'showCurrentAtPos' => 1,
                                            'showButtonPanel'  => true,
                                            'showOtherMonths' => true,
                                            'addSliderAccess' => true,
                                            'sliderAccessArgs' => array('touchonly' => false),
                                            'showAnim'        => 'slideDown',
                                        )),
                                        'defaultValue' => date(''),
                                    ),
                                    'editrules' => array(
                                                     	'edithidden' => true,
                                                     	//'time' => true,
                                    ),
                                    'formoptions'=>array('rowpos'=>($this->start_row_week + 6), 'colpos'=>1 ),
   			),


        );
        $this->options = array(
            'sortname'  => 'id_group',
            'sortorder' => 'asc',
            'rowNum' => 20,
            'rownumbers' => true,
            'width' => 900,
            'height' => '100%',
            'autowidth' => true,
            'altRows' => true,
    		//'multiselect' => true, // множественный выбор (checkbox)
    		'rowList'     => array(10, 20, 30, 50, 100),
    		'caption' => $this->caption,

        );
        #Set nav
        $this->nav = array(//'view' => true, 'viewtext' => 'Смотреть',
        					'add' => true, 'addtext' => 'Добавить объект',
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

        //$r['foto_list'] = Modules::run('duc/duc_teachers/image_combobox');
        //$r['foto_list'] = '№'.$r['id'];
        return $r;
    }

	protected function renderGridUrl(){
        return '/grid/duc_schedules/duc/grid/';
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

        $arr_week = array('mon','tue','wed','thur','fri','sat','sun');
        foreach($arr_week as $day){
        	$data[$day.'_from'] = ($data[$day.'_from'] ==  $data[$day.'_to'] || $data[$day.'_from'] == '00:00') ? '' : $data[$day.'_from'];
        	$data[$day.'_to'] = ($data[$day.'_from'] ==  $data[$day.'_to'] || $data[$day.'_to'] == '00:00') ? '' : $data[$day.'_to'];
        	if(strtotime($data[$day.'_to']) <= strtotime($data[$day.'_from'])){
        		$data[$day.'_from'] = '';
        		$data[$day.'_to'] =	'';
        	}
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
                      'id_group' => $upd['id_group'],
                      'id_numgroup' => $upd['id_numgroup'],
                      'cabinet' => $upd['cabinet'],
                      'mon_from' => $upd['mon_from'],
                      'mon_to' => $upd['mon_to'],
                      'tue_from' => $upd['tue_from'],
                      'tue_to' => $upd['tue_to'],
                      'wed_from' => $upd['wed_from'],
                      'wed_to' => $upd['wed_to'],
                      'thur_from' => $upd['thur_from'],
                      'thur_to' => $upd['thur_to'],
                      'fri_from' => $upd['fri_from'],
                      'fri_to' => $upd['fri_to'],
                      'sat_from' => $upd['sat_from'],
                      'sat_to' => $upd['sat_to'],
                      'sun_from' => $upd['sun_from'],
                      'sun_to' => $upd['sun_to'],

                      'date_update' => time(),
                      'ip_update' => $_SERVER['REMOTE_ADDR'],

            );

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
			//echo 'Удалено';
			$this->DB->delete($this->table, array($this->primary_key => $id));
		}
		#Delete multiple value
		else
		{
			$ids = array_map(array($this->DB, 'quote'), explode(',', $id));
			//$this->DB->delete($this->table, $this->primary_key . ' IN (' . implode(',', $ids) . ')');
		}

    }

    protected function initTimepicker($options=null)
    {

        $options = is_array($options) ? $options : array();
//        $r = $this->parseRow();
//        $options['hour'] = date('H', $r['date_create']);
        return new jqGrid_Data_Raw('function(el){$(el).timepicker(' . jqGrid_Utils::jsonEncode($options) . ');}');
    }

}
