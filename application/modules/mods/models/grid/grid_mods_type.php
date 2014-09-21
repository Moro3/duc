<?php
class grid_mods_type extends jqGrid
{
    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {
        $this->table = 'mods_type';

        $this->query = "
            SELECT {fields}
            FROM mods_type AS object
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
            'name'    => array('label' => lang('mods_type_name'),
                                    'db' => 'object.name',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),

            'year_create'   => array('label' => lang('mods_year_create'),
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
        return '/grid/mods/mods_type/grid/';
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
