<?php
class grid_menus_groups extends jqGrid
{
    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {
        $this->CI = &get_instance();
        $this->table = 'menus_groups';

        $this->params['places'] = Modules::run('menus/menus_places/MY_data_array_one', 'id', array('name', 'description'), false, 'sorter');

		$this->config = $this->loader->get('config');

        $this->query = "
            SELECT {fields}
            FROM menus_groups AS object
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
            'places'   => array('label' => lang('menus_places'),
                                    //'db' => 'object.id_category',
                                    'manual'=> true,
                                    //'hidden' => true,
                                    'width' => 100,
                                   'editable' => true,
                                   'stype' => 'checkbox',
                                   //'replace' => array('Check1','Check2','Check3','Check4'),

                                   //'value' => new jqGrid_Data_Value($this->params['places'], 'All'),
                                   'edittype' => "select",
                                   'editoptions' => array(
                                   						  //textarea
                                   						  
                                   						  //jquery multiselect
                                   						  'value' => new jqGrid_Data_Value($this->params['places']),
                                                //'value' => array(),
                                   						  'multiple' => true,
                                   						  //'list' => '1,3,5,6,7',
                                   						  //'selected' => array(1,3,5,6,7),
                                   						  'size' => 10,
                                   						  'class' => 'multiselect',
                                                //'dataUrl' => '/ajax/?resource=dataPlaceInGroup/ajax/menus~&',
                                                // dataUrl get value format: "FE:FedEx; IN:InTime; TN:TNT"                                                
                                                
                                                //'dataUrl' => '/grid/menus_groups/menus/grid/?&oper=get_select',
                                                /*
                                                'buildSelect' => "function(data) {
                                                     alert(data);
                                                     var response = jQuery.parseJSON(data.responseText);
                                                     var s = '<select>';
                                                     if (response && response.length) {
                                                         for (var i = 0, l=response.length; i<l ; i++) {
                                                             var ri = response[i];
                                                             s += '<option value=\"'+ri+'\">'+ri+'</option>';
                                                         }
                                                     }                                                      
                                                     return s + '</select>';
                                                }",
                                                */
                                   ),
                                   'editrules' => array(//'required' => true,
                                                     //'integer' => true,
                                                     'minValue' => 0,
                                                     'maxValue' => 100,
                                                     'edithidden' => true,
                                   ),
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
            'caption' => lang('menus_groups'),
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
        
        // places трокой через запятую        
        $r['places'] = implode(',',Modules::run('menus/menus_groups_places/MY_data_array_one',
        												'id',
        												'place_id',
        												//where
        												array('group_id' => $r['id']),                                
                                //order
                                'sorter'
        						          )
        );
        
        /*
        // places массивом
        $r['places'] = Modules::run('menus/menus_groups_places/MY_data_array_one',
                                'id',
                                'place_id',
                                //where
                                array('group_id' => $r['id']),                                
                                //order
                                'sorter'                              
        );
        */
        return $r;
    }

	protected function renderGridUrl(){
        return '/grid/menus_groups/menus/grid/';
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
    	if(Modules::run('menus/menus_groups/check_double_name', $data['name'], $id)){
    		throw new jqGrid_Exception('Такое имя уже используется, придумайте другое');
    	}
    	if(Modules::run('menus/menus_groups/check_double_alias', $data['alias'], $id)){
    		throw new jqGrid_Exception('Такой псевдоним уже используется, придумайте другой');
    	}
    	// редактирование мест положения
            if(isset($data['places'])){
            	$this->editPlaces($id, $data['places']);
            	unset($data['places']);
            }
    	parent::opEdit($id, $data);

    }

	/**
    *  Операция добавления
    *  @param array $data
    *  @return boolean
    */
    protected function opAdd($data) {
         if(Modules::run('menus/menus_groups/check_double_name', $data['name'])){
    		throw new jqGrid_Exception('Такое имя уже используется, придумайте другое');
    	}
    	if(Modules::run('menus/menus_groups/check_double_alias', $data['alias'])){
    		throw new jqGrid_Exception('Такой псевдоним уже используется, придумайте другой');
    	}
        $places = $data['places'];
        unset($data['places']);
        if($id = parent::opAdd($data)){
        // редактирование мест положения
            if(isset($places)){
            	$this->editPlaces($id, $places);

            }
        }
    }

	/**
    *  Операция удаления
    *  @param array $ins
    *  @return boolean
    */
    protected function opDel($id) {

       $this->delPlaces($id);
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
    * Редактирование параметров 
    * @param int - id group
    * @param string - строка id перечисленных через запятую
    */
    protected function editPlaces($id, $places){
    	if(!empty($id) && isset($places)){

                    
	                    //если находим разделитель 'запятая' в веденных данных
	                    //тогда переводим в массив значений
	                    //если разделителя нет - в массив с одним значением
	                    if(strpos($places,',') !== false){
	                    	$comm = explode(',',$places);
	                    }else{
	                    	$comm = array($places);
	                    }
	                    //редактируем concertmasters
	                    if( ! Modules::run('menus/menus_groups_places/write_places',$id, $comm)){
                            throw new jqGrid_Exception('Не удалось обновить "место расположение"');
	                    }
                   

     	}
    }

    /**
    * Удаление мест нахождений из данной группы
    *	@param $id - id group
    *
    */
    protected function delPlaces($id){
    	Modules::run('menus/menus_groups_places/delete_places',$id);
    }

    /**
    * Событие на удаление объекта
    *
    */
    protected function eventDelete($id){
    	if(Modules::run('menus/menus_groups_places/get_have_places_groups', $id)){
    		throw new jqGrid_Exception('Объект используется в связке с местом положения. Удалите сначало объект из мест положения.');
    	}

    }

    protected function opGetSelect()
    {      
      $this->response['select'] = Modules::run('menus/menus_groups/get_places_is_group');

    }
}
