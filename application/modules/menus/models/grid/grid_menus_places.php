<?php
class grid_menus_places extends jqGrid
{
    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {
        $this->CI = &get_instance();
        $this->table = 'menus_places';


		$this->config = $this->loader->get('config');

        $this->query = "
            SELECT {fields}
            FROM menus_places AS object
            WHERE {where}
        ";


        //WHERE content.id IS NULL  - выводит только пустые (не связанные) элементы в таблице headers

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
            ),

            'sorter'  => array('label' => lang('menus_sorter'),
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

            'name'    => array('label' => lang('menus_name'),
                                    'db' => 'object.name',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 250,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),

            'alias'    => array('label' => lang('menus_alias'),
                                    'db' => 'object.alias',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 250,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),

            'description'    => array('label' => lang('menus_description'),
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
            'description_active'    => array('label' => lang('menus_description'),
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
            'sortname'  => 'sorter',
            'sortorder' => 'asc',
            'rowNum' => 20,
            'rownumbers' => true,
            'width' => 900,
            'height' => '100%',
            'autowidth' => true,
            'altRows' => true,
    		//'multiselect' => true, // множественный выбор (checkbox)
    		'rowList'     => array(10, 20, 30, 50, 100),
            'caption' => lang('menus_places'),
        );
        #Set nav
        $this->nav = array(//'view' => true, 'viewtext' => 'Смотреть',
        					'add' => true, 'addtext' => 'Добавить объект',
        				   'edit' => true, 'edittext' => 'Редактировать',
        				   'del' => true, 'deltext' => 'Удалить',
        				'prmAdd' => array('width' => 800, 'closeAfterAdd' => true),
    					'prmEdit' => array('width' => 900,
    										//'height' => 600,
    									   'closeAfterEdit' => true,
    									   'viewPagerButtons' => false, //скрывает кнопки навигации предыдущая, следующая,
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
        return '/grid/menus_places/menus/grid/';
    }

    /**
     *  Редактирование полученых данных перед записью
     * @param array $data
     * @return type
     */
    protected function operData($data)
    {
        $data['sorter'] = (!empty($data['sorter'])) ? $data['sorter'] : 10;
        if(isset( $data['active']) && $data['active'] == 'on' || $data['active'] == 1){
            $data['active'] = 1;
        }else{
            $data['active'] = 0;
        }
        return $data;
    }

    /**
    * 	редактирование объекта
    */
    protected function opEdit($id, $data) {
    	if(Modules::run('menus/menus_places/check_double_name', $data['name'], $id)){    		throw new jqGrid_Exception('Такое имя уже используется, придумайте другое');
    	}
    	if(Modules::run('menus/menus_places/check_double_alias', $data['alias'], $id)){
    		throw new jqGrid_Exception('Такой псевдоним уже используется, придумайте другой');
    	}
    	parent::opEdit($id, $data);

    }

	/**
    *  Операция добавления
    *  @param array $data
    *  @return boolean
    */
    protected function opAdd($data) {
         if(Modules::run('menus/menus_places/check_double_name', $data['name'])){
    		throw new jqGrid_Exception('Такое имя уже используется, придумайте другое');
    	}
    	if(Modules::run('menus/menus_places/check_double_alias', $data['alias'])){
    		throw new jqGrid_Exception('Такой псевдоним уже используется, придумайте другой');
    	}
        parent::opAdd($data);
    }

	/**
    *  Операция удаления
    *  @param array $ins
    *  @return boolean
    */
    protected function opDel($id) {
       $this->eventDelete($id);
       parent::opDel($id);

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

    /**
    * Событие на удаление объекта
    *
    */
    protected function eventDelete($id){    	if($groups = Modules::run('menus/menus_groups_places/get_groups_have_places', $id)){    		$err_group = '';
    		foreach($groups as $group){    			$err_group .= $group->groups->name.', ';
    		}
    		throw new jqGrid_Exception('Объект используется в связке с группами. Удалите сначало объект из групп: '.$err_group);
    	}
        if($trees = Modules::run('menus/menus_trees/get_trees_have_places', $id)){
    		$err_tree = '';
    		foreach($trees as $tree){
    			$err_tree .= $tree->name.', ';
    		}

    		throw new jqGrid_Exception('Объект используется в связке с меню. Удалите сначало объекты из данных меню.');
    	}

    }
}
