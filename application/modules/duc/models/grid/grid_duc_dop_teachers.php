<?php
class grid_duc_dop_teachers extends jqGrid
{
    protected function init()
    {
        $this->CI = &get_instance();
        $this->table = 'duc_dop_teachers';

		$this->params['teachers'] = Modules::run('duc/duc_teachers/MY_data_array_one', 'id', array('surname', 'name', 'name2'), false, 'surname');
        $this->params['groups'] = Modules::run('duc/duc_groups/listGroups');

        $this->query = "
            SELECT {fields}
            FROM duc_dop_teachers AS object
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
            'group'          => array('label' => lang('duc_group'),
                                   'db' => 'group.name',
                                   'width' => 50,
                                   'align' => 'center',
                                   'editable' => true,
                                   "editoptions"=>array("readonly"=>true),
            ),
            'teacher'          => array('label' => lang('duc_teacher'),
                                   'db' => 'teacher.surname',
                                   'width' => 50,
                                   'align' => 'center',
                                   'editable' => true,
                                   "editoptions"=>array("readonly"=>true),
            ),

        );
        $this->options = array(
            'sortname'  => 'date_create',
            'sortorder' => 'desc',
            'rowNum' => 20,
            'rownumbers' => true,
            'width' => 900,
            'height' => '300',
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
        return '/grid/duc_dop_teachers/duc/grid/';
    }
}
