<?php
class grid_duc_durations extends jqGrid
{

    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {
        $this->CI = &get_instance();
        $this->table = 'duc_durations';
        $this->caption = 'Продолжительность занятий';
        $this->start_row_year = 5;
        $this->start_row_age = 6;
        $this->start_row_duration = 7;

		$this->config = $this->loader->get('config');

        $this->params['groups'] = Modules::run('duc/duc_groups/listGroups');
        $this->params['groupsEscape'] = Modules::run('duc/duc_groups/listGroupsEscape');
        //print_r($this->params['groups']);
        //exit;
        //$this->params['groups'] = array();
        $this->params['years'] = Modules::run('duc/duc_settings/listYears');
        $this->params['ages'] = Modules::run('duc/duc_settings/listAges');
        $this->params['jobs'] = Modules::run('duc/duc_durations/listJobs');
        $this->params['breaks'] = Modules::run('duc/duc_durations/listBreaks');
        $this->params['week_jobs'] = Modules::run('duc/duc_durations/listWeekJobs');


		//return;
        $this->query = "
            SELECT {fields}
            FROM duc_durations AS object
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


            'active'  => array('label' => 'Показать',
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
            'active_icon'  => array('label' => 'Показать',
                                   'manual'=> true,
                                   'width' => 80,
                                   'align' => 'center',
                                   //'editable' => true,
                                   'encode' => false,
            ),
            'sorter'  => array('label' => 'Порядок',
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
            'id_group'   => array('label' => lang('duc_group'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => array(0=>'нет') + $this->params['groups'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => new jqGrid_Data_Value($this->params['groupsEscape']),
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
                                        				'value' => new jqGrid_Data_Value($this->params['groupsEscape'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),
   			'year_education_from'   => array('label' => lang('duc_from'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['years'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['years'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['years'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
                  				   'formoptions'=>array('rowpos'=>($this->start_row_year),
                  				   						'colpos'=>1,
                  				                       'elmprefix' => lang('duc_from')
                  				   ),

   			),
   			'year_education_to'   => array('label' => lang('duc_to'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['years'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['years'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['years'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['years'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
                  				   'formoptions'=>array('rowpos'=>($this->start_row_year),
                  				   						'colpos'=>1,
                  				                       'elmprefix' => lang('duc_to')
                  				   ),
   			),
   			'age_from'   => array('label' => lang('duc_from'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['ages'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['ages'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['ages'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['ages'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
                  				   'formoptions'=>array('rowpos'=>($this->start_row_age),
                  				   						'colpos'=>1,
                  				                       'elmprefix' => lang('duc_from')
                  				   ),
   			),
   			'age_to'   => array('label' => lang('duc_to'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['ages'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['ages'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['ages'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['ages'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
                  				   'formoptions'=>array('rowpos'=>($this->start_row_age),
                  				   						'colpos'=>1,
                  				                       'elmprefix' => lang('duc_to')
                  				   ),
   			),
   			'duration_job'   => array('label' => lang('duc_job'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['jobs'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['jobs'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['jobs'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['jobs'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
                  				   'formoptions'=>array('rowpos'=>($this->start_row_duration),
                  				   						'colpos'=>1,
                  				                       'elmprefix' => lang('duc_job')
                  				   ),
   			),
   			'duration_break'   => array('label' => lang('duc_break'),

                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['breaks'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['breaks'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['breaks'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['break'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
                  				   'formoptions'=>array('rowpos'=>($this->start_row_duration),
                  				   						'colpos'=>1,
                  				                       'elmprefix' => lang('duc_break')
                  				   ),
   			),
   			'week_job'   => array(//'label' => lang('duc_week_job'),
                                   'label' => 'Количество<br>занятий<br>в неделю',
                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['week_jobs'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['week_jobs'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['week_jobs'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['week_jobs'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),

        );
        $this->options = array(
            'sortname'  => 'id_group',
            'sortorder' => 'asc',
            'rowNum' => 10,
            'rownumbers' => true,
            //'width' => 900,
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
        				'prmAdd' => array('width' => 800, 'closeAfterAdd' => true),
    					'prmEdit' => array('width' => 800,
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
        return '/grid/duc_durations/duc/grid/';
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
            		  'active' => $upd['active'],
                	  'sorter' => $upd['sorter'],
                      'id_group' => $upd['id_group'],
                      'year_education_from' => $upd['year_education_from'],
                      'year_education_to' => $upd['year_education_to'],
                      'age_from' => $upd['age_from'],
                      'age_to' => $upd['age_to'],
                      'duration_job' => $upd['duration_job'],
                      'duration_break' => $upd['duration_break'],
                      'week_job' => $upd['week_job'],
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


    protected function opTruncate($id){
    	throw new jqGrid_Exception('операция Truncate');
    }

}
