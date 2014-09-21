<?php
class grid_snippets extends jqGrid
{
    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {
        //$this->CI = &get_instance();
        $this->table = 'snippets';

		$this->config = $this->loader->get('config');
        //$this->params['categories'] = Modules::run('reviews/reviews_categories/MY_data_array_one');
        $this->params['groups'] = Modules::run('snippets/snippets_groups/listGroups');

        /*
        $this->query = "
            SELECT {fields}
            FROM reviews AS object, reviews_categories AS category, reviews_ratings AS rating
            WHERE {where}
        ";
        $this->where[] = 'object.id_category = category.id';
        $this->where[] = 'object.id_rating = rating.id';
        */

        $this->query = "
            SELECT {fields}
            FROM snippets AS object
            LEFT JOIN snippets_groups grouper
            ON object.group_id = grouper.id
            WHERE {where}
        ";

        //WHERE content.id IS NULL  - выводит только пустые (не связанные) элементы в таблице headers

        $this->cols = array(
            'id'          => array('label' => lang('snippets_id'),
                                   'db' => 'object.id',
                                   'width' => 50,
                                   'align' => 'center',
                                   'editable' => true,
                                   "editoptions"=>array("readonly"=>true),
            ),

            'active'  => array('label' => lang('snippets_active'),
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
            'active_icon'  => array('label' => lang('snippets_active'),
                                   'manual'=> true,
                                   'width' => 80,
                                   'align' => 'center',
                                   //'editable' => true,
                                   'encode' => false,
            ),

            'name'    => array('label' => lang('snippets_name'),
                                    'db' => 'object.name',
                                   'width' => 150,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 250,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),

            'alias'    => array('label' => lang('snippets_alias'),
                                    'db' => 'object.alias',
                                   'width' => 150,
                                   'align' => 'left',
                                   'hidden' => true,
                                   'editable' => true,
                                   //'edittype' => "textarea",
                                   'editrules' => array(//'required' => true,
                                                       'maxValue' => 20000,
                                                       'edithidden' => true,
                                   ),
                                   'editoptions' => array('size' => 50),
            ),

            'group_id'   => array('label' => lang('snippets_group'),
                                 'db' => 'object.group_id',
                                 //'hidden' => true,
                                    'width' => 50,
                                   'editable' => true,
                                   //'stype' => 'select',
                                   'replace' => $this->params['groups'],

                                   'edittype' => "select",
                                   'editoptions' => array(
                                   							'value' => $this->params['groups'],
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
                                        				'value' => new jqGrid_Data_Value($this->params['groups'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
   			),

   			'date'   => array('label' => lang('snippets_date'),
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

            'sorter'  => array('label' => lang('snippets_sorter'),
                                   'db' => 'object.sorter',
                                   'width' => 50,
                                   'hidden' => true,
                                   'editable' => false,
                                   'encode' => false,
                                   'editrules' => array(
                                                     	'edithidden' => true,
                                                     	'integer' => true,
                                                     	'minValue' => 1,
                                                     	'maxValue' => 1000,
                                   ),
            ),

            'description'    => array('label' => lang('snippets_description'),
                                    'db' => 'object.description',
                                   'width' => 250,
                                   'align' => 'left',
                                   'hidden' => true,
                                   'editable' => true,
                                   //'edittype' => "textarea",
                                   'editrules' => array(//'required' => true,
                                                       'maxValue' => 250,
                                                       'edithidden' => true,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),
            'description_active'    => array('label' => lang('snippets_description'),
                                    'db' => 'object.description',
                                    'manual'=> true,
                                   'width' => 250,
                                   'align' => 'left',
                                   'encode' => false,
                                   //'editable' => true,
                                   //'edittype' => "textarea",
                                   'editrules' => array(//'required' => true,
                                                       'maxValue' => 250,
                                                       //'edithidden' => true,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),
            'content'    => array('label' => lang('snippets_content'),
                                    'db' => 'object.content',
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
            'content_active'    => array('label' => lang('snippets_content'),
                                    'db' => 'object.content',
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
            'caption' => lang('reviews'),
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
        if(!empty($r['description'])){        	//$r['description'] = character_limiter($r['description'], 20);
        }

        if(!empty($r['description'])){
            $r['description_active'] = character_limiter($r['description'], 80);
        }else{
            $r['description_active'] = '<img src="'.assets_img('admin/no.gif', false).'">';
        }

        if(!empty($r['content'])){
            $r['content_active'] = '<img src="'.assets_img('admin/yes.gif', false).'">';
        }else{
            $r['content_active'] = '<img src="'.assets_img('admin/no.gif', false).'">';
        }
        $r['date'] = date('Y/m/d H:i',$r['date']);
        /*
        if(isset($r['foto_upload'])){
        	$path = $this->config['path']['images'].$this->config['image_config']['dir'].'/'.$r['foto_upload'];
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
        */
        return $r;
    }

	protected function renderGridUrl(){
        $get = '';
        if(isset($_GET['id']) && is_numeric($_GET['id'])){
        	$get = 'id='.$_GET['id'];
        }
        return '/grid/'.$this->table.'/snippets/grid/?'.$get;
        //return '/grid/'.$this->table.'/reviews/grid/?'.$get;
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
        $data['group_id'] = (isset($data['group_id'])) ? $data['group_id'] : 1;

        $data['date'] = strtotime($data['date']);

        if(count($this->params['groups']) <= 0){        	throw new jqGrid_Exception('Не создано не одной группы, для добавления "снипета" добавьте хотя бы оду группу');
        }
        //throw new jqGrid_Exception('Ошибка в operData');
        return $data;
    }

    /**
    * 	редактирование объекта
    */
    protected function opEdit($id, $data) {
        //throw new jqGrid_Exception('Ошибка в написании URI. Адрес должен состоять из латинских букв, цифр и может содержать символы -_');
    		if(!empty($id) && is_numeric($id)){            	//$result = $this->DB->query('SELECT * FROM '.$this->table.' WHERE id='.intval($id));
            	//$row = $this->DB->fetch($result);

            	$row = Modules::run('snippets/snippets/MY_data_array_row',
		         	//select
		         	array('id', 'name'// 'main.paid'

		         	),
		         	//where
		         	array(
		         		'id' => intval($id)
		         	)
		        );
	            if($row['id'] !== $id){
	            	throw new jqGrid_Exception('Сниппет с id="'.$id.'" не найден в системе');
	            	return false;
	            }
	            $nameDouble = Modules::run('snippets/snippets/searchName', $data['name'], $row['id']);
	            if($nameDouble === true){
	            	throw new jqGrid_Exception('Сниппет с таким именем уже есть в системе!');
	            	return false;
	            }

            	$arr['active'] = $data['active'];
            	$arr['sorter'] = $data['sorter'];
            	$arr['group_id'] = $data['group_id'];

                $arr['name'] = $data['name'];
                $arr['description'] = $data['description'];
                $arr['content'] = $data['content'];
                $arr['alias'] = $data['alias'];

            	$arr['date'] = $data['date'];
            	$arr['date_update'] = time();
            	$arr['ip_update'] = $_SERVER['REMOTE_ADDR'];

            	$res = Modules::run('snippets/snippets/MY_update',
	            	//set
	            	$arr,
	            	//where
	            	//array('id' => array('encode' => $row['id']))
	            	array('id' => $row['id'])

	            );

    		}else{
            	throw new jqGrid_Exception('Запрос не выполнен, нет id');
        	}
            //$response = parent::opEdit($id, $upd);
            //cache::drop($id);                       // after oper
    		//$response['cache_dropped'] = 1;         // modify original response

    		return $res;
    }

	/**
    *  Операция добавления
    *  @param array $data
    *  @return boolean
    */
    protected function opAdd($data) {
        if(!empty($data['name'])){
            $nameDouble = Modules::run('snippets/snippets/searchName', $data['name']);
            if($nameDouble === true){
            	throw new jqGrid_Exception('Сниппет с таким именем уже есть в системе!');
            	return false;
            }

        		$arr['active'] = $data['active'];
            	$arr['sorter'] = $data['sorter'];
            	$arr['group_id'] = $data['group_id'];

                $arr['name'] = $data['name'];
                $arr['description'] = $data['description'];
                $arr['content'] = $data['content'];
                $arr['alias'] = $data['alias'];

            	$arr['date'] = $data['date'];
            	$arr['date_create'] = time();
            	$arr['date_update'] = time();
            	$arr['ip_create'] = $_SERVER['REMOTE_ADDR'];
            	$arr['ip_update'] = $_SERVER['REMOTE_ADDR'];

	            $id = Modules::run('snippets/snippets/MY_insert', $arr);

        }else{
        	throw new jqGrid_Exception('Не задано имя сниппета');
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
		//Проверка на наличие данного сниппета
		$isSnippet = Modules::run('snippets/snippets/isId', $id);
            if($isSnippet === false){
            	throw new jqGrid_Exception('Не найден сниппет с id="'.$id.'"');
            	return false;
            }

		Modules::run('snippets/snippets/MY_delete',
			//where
			array('id' => $id)
		);

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

}
