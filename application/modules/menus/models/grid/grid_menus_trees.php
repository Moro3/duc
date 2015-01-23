<?php
class grid_menus_trees extends jqGrid
{
    protected $do_count = false;

    protected function beforeInit(){
    	 $this->CI = &get_instance();
       $this->CI->load->driver('lib_menus_types');
    }

    protected function init()
    {
        
        $this->table = 'menus_trees';
        $this->config = $this->loader->get('config');

		    $this->params['places'] = Modules::run('menus/menus_places/places_select');
        $this->params['typesName'] = Modules::run('menus/menus_types/MY_data', array('id', 'name'));
        $this->params['types'] = Modules::run('menus/menus_types/types_select');
		    $this->params['pages'] = Modules::run('pages/pages_api/listPagesSelect');
        $this->params['pagesPrefix'] = Modules::run('pages/pages_api/listPagesSelectPrefix');
        $this->params['mods'] = Modules::run('mods/mods_api/listSelectPrefix');
        $this->params['images_files'] = Modules::run('menus/menus_images/listImages');
        $this->params['images_names'] = Modules::run('menus/menus_images/MY_data_array_one', 'id', array('name','file'));
        $this->params['nodes'] = Modules::run('menus/menus_trees/MY_data',         
          //select
          '*'
        );
        foreach($this->params['nodes'] as $node=>$items){
          $this->params['nodesOfTypes'][$node] = array('name' => $items->name, 'type' => $items->type_id);
        }
        //dd($this->params['nodes']);
        $this->params['nodes_data'] = $this->CI->lib_menus_types->get_data_nodes($this->params['nodesOfTypes']);
        //vd($this->params['images_names']);        
        
        /*
        $this->params['nodes_trees'] = Modules::run('menus/menus_api/get_trees_place_data', $_GET['place']);
        $this->params['nodes_list'][0] = 'корень';
        foreach($this->params['nodes_trees'] as $id_node=>$items){
          $this->params['nodes_list'][$id_node] = $items['data']['name'];
        }
        */
        //dd($this->params['nodes_trees']);
        
        #Set tree grid mode
        $this->treegrid = 'adjacency';

        $this->ExpandColumn = 'type_description';

        $this->level = intval($this->input('n_level', 0));
        $this->parent_id = intval($this->input('nodeid', 0));

        #Set condition for base level
        $this->where = array('object.parent_id=0');
        if(isset($_GET['group']) && is_numeric($_GET['group'])){
        	$from = ', menus_groups_places group';
        }else{
        	$from = '';
        }

        $this->query = "
            SELECT {fields}
            FROM menus_trees object, menus_places place, menus_types type ".$from."
            WHERE {where}             
        ";
        if(isset($_GET['place']) && is_numeric($_GET['place'])){
        	$this->where[] = 'place.id = '.$_GET['place'];
        }else{
        	$this->where[] = 'place.id = 0';
        }

        if(isset($_GET['group']) && is_numeric($_GET['group'])){
        	$this->where[] = 'group.id = '.$_GET['group'];
        	$this->where[] = 'group.place_id = place.id';
        }

        /*
        $this->query = "
            SELECT {fields}
            FROM menus_trees AS object
            WHERE {where} AND object.parent_id='{$this->parent_id}'
        ";
        */
        $this->where[] = 'object.place_id = place.id';
        $this->where[] = 'object.type_id = type.id';
        //$this->where[] = 'object.image_id = image.id';




        //WHERE content.id IS NULL  - выводит только пустые (не связанные) элементы в таблице headers

        $this->cols = array(
            'id'          => array('label' => lang('menus_id'),
                                   'db' => 'object.id',
                                   'width' => 50,
                                   'align' => 'center',
                                   'editable' => true,
                                   'editoptions'=>array('readonly'=>true),

            ),

            'type_id'    => array('label' => lang('menus_type'),
                                    'db' => 'object.type_id',
                                   // 'replace' => $this->params['types'],
                                   'width' => 150,
                                   'align' => 'left',
                                   'hidden' => true,
                                   'editable' => true,
                                   'edittype' => 'select',
                                   'editoptions' => array(                                                
                                                'value' => new jqGrid_Data_Value($this->params['types']),
                                                
                                   ),
                                   'editrules' => array(
                                                      'edithidden' => true,
                                                      'integer' => true,
                                                      'minValue' => 1,
                                                      'maxValue' => 1000,
                                   ),
            ),

            /*
            'type_content'    => array('label' => lang('menus_type'),                                   
                                  // 'replace' => $this->params['types'],
                                  'manual' => true,
                                  'width' => 150,
                                  'align' => 'left',
                                  'hidden' => true,
                                  'editable' => true,
                                  'edittype' => 'select',
                                  'editoptions' => array(                                                
                                                'value' => new jqGrid_Data_Value($this->params['pages']),
                                                
                                   ),
                                   
                                  'editrules' => array(
                                                      'edithidden' => true,
                                                      'integer' => true,
                                                      'minValue' => 1,
                                                      'maxValue' => 1000,
                                  ),
                                  'formoptions'=>array(
                                                'rowpos'=> '2',
                                                'colpos'=>1,
                                                'elmprefix' => 'Содержимое',
                                                'label' => 'Тип содержимого',
                                  ),
            ),
            */
            'node_name'    => array('label' => lang('menus_name'),
                                    'db' => 'object.name',
                                   'width' => 250,
                                   'align' => 'left',
                                   'hidden' => true,
                                   'editable' => true,
                                   'edittype' => 'select',
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 250,
                                                       'edithidden' => true,
                                   ),
                                   'editoptions' => array(//'size' => 100
                                   							//'value' => $this->params['pages'],
                                   							'value' => new jqGrid_Data_Value($this->params['pages']),
                                   ),
                                   'formoptions'=>array(
                                                'rowpos'=> '2',
                                                'colpos'=>1,
                                                'elmprefix' => 'Содержимое',
                                                'label' => 'Тип содержимого',
                                  ),
            ),
            

            'name'    => array('label' => lang('menus_name'),
                                    'db' => 'object.name',
                                   'width' => 250,
                                   'align' => 'left',
                                   //'replace' => $this->params['pagesPrefix'],
                                   //'hidden' => true,
                                   //'editable' => true,
                                   'edittype' => 'select',
                                   'encode' => false,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 250,
                                   ),
                                   'editoptions' => array(//'size' => 100
                                   							//'value' => $this->params['pages'],
                                   							'value' => new jqGrid_Data_Value($this->params['pages']),
                                   ),
                                   'search' => false,
                                   'formoptions' => array(//'baseLinkUrl' => '/ajaxs/',
                                   						//'addParam' => 'resource=pages/admin/pages~'

                                   ),
                                   //'formatter' => 'showlink',
            ),

            'type_description'    => array('label' => lang('menus_type'),
                                    'db' => 'type.description',
                                    'replace' => $this->params['types'],
                                   'width' => 150,
                                   'align' => 'left',
                                   //'editable' => true,
                                   'edittype' => 'select',
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 250,
                                   ),
                                   'editoptions' => array(//'size' => 100,
                                                          //'value' => $this->params['places'],
                                                          'value' => new jqGrid_Data_Value($this->params['types']),
                                   ),
                                   'sortable' => false,
                                   'search' => false,
                                   'stype' => 'select',
                                   //'replace' => $this->params['categories'],
                                   'searchoptions' => array(
                                        				'value' => new jqGrid_Data_Value($this->params['types'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
            ),

            'type_name'    => array('label' => lang('menus_type'),
                                    'db' => 'type.name',
                                   // 'replace' => $this->params['types'],
                                   'width' => 150,
                                   'align' => 'left',
                                   'hidden' => true,
                                   'editable' => true,

            ),

            'image_upload'    => array('label' => lang('menus_image_upload'),
                                   'db' => 'object.image_id',
                                   'width' => 150,
                                   'align' => 'left',
                                   'hidden' => true,
                                   'editable' => false,
                                   'encode' => false,
                                   'edittype' => 'file',
                                   'editrules' => array(
                                                       'maxValue' => 100,
                                                       'edithidden' => true,
                                   ),
                                   'editoptions' => array('size' => 60),
                                   //'formoptions' => array('elmsuffix' => $this->button_delete_foto()),
            ),
            'image'    => array('label' => lang('menus_image'),
                                   'db' => 'object.image_id',
                                   'width' => 150,
                                   'align' => 'left',
                                   'editable' => false,
                                   'encode' => false,
                                   //'edittype' => 'file',
                                   'editrules' => array(
                                                       'maxValue' => 100,
                                   ),
                                   //'editoptions' => array('size' => 60),
                                   //'formoptions' => array('elmsuffix' => Modules::run('menus/menus_images/tplButtonDeleteImage')),
            ),
            'image_list'    => array('label' => lang('menus_image_list'),
                                   'db' => 'object.image_id',
                                   //'manual' => true,
                                   'replace' => $this->params['images_files'],
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
                                                         //'value' => array(0=>'нет') + $this->params['images_names'],
                                                         'value' => new jqGrid_Data_Value(array(0=>'нет') + $this->params['images_names']),

                                   ),
                                   //'formoptions' => array('elmsuffix' => $this->button_delete_foto()),
            ),

            'date'   => array('label' => lang('menus_date'),
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
                                   'search' => false,
            ),
            'parent_id' => array('label' => lang('menus_parent'),
                                  //'hidden' => true,
                        					'db' => 'object.parent_id',
                        					'editable' => true,
                        					//'edittype' => 'select',
                                  'editoptions' => array(//'size' => 100,
                                                          //'value' => $this->params['places'],
                                                          //'value' => new jqGrid_Data_Value($this->params['nodes_list']),
                                   ),
                                  'editrules' => array(
                                                     	//'edithidden' => true,
                                                     	'integer' => true,
                                                     	//'minValue' => 1,
                                                     	//'maxValue' => 1000,                                                     	
                                  ),
            ),

            'parent_u' => array('hidden' => true,
                					'db' => 'object.parent_id',
                					//'editable' => true,

            ),

            'place_name'    => array('label' => lang('menus_place'),
                                    'db' => 'place.name',
                                    'replace' => $this->params['places'],
                                   'width' => 150,
                                   'align' => 'left',
                                   //'hidden' => true,
                                   //'editable' => true,
                                   'edittype' => 'select',
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 250,
                                   ),
                                   'editoptions' => array(//'size' => 100,
                                                          //'value' => $this->params['places'],
                                                          //'value' => new jqGrid_Data_Value($this->params['places']),
                                   ),
                                   'sortable' => false,
                                   'search' => false,
                                   'stype' => 'select',
                                   //'replace' => $this->params['categories'],
                                   'searchoptions' => array(
                                        				'value' => new jqGrid_Data_Value($this->params['places'], 'All'),
                                        				//'value' => array('' => 'All') + $this->params['categories'],
                                        				//'onSelect'       => new jqGrid_Data_Raw('function(){$grid[0].triggerToolbar();}'),
                  				   ),
            ),



            /*
            'has_child' => array('hidden' => true,
                				//do we have any children?
                				'db' => '(SELECT id FROM menus_trees WHERE parent_id=object.id LIMIT 1)',
            ),
            */
            
        );
        if(!empty($_GET['place'])){
        	$prefix_place = $this->params['places'][$_GET['place']];
        }else{
        	$prefix_place = '';
        }
        $this->options = array(
            'sortname'  => 'sorter',
            'sortorder' => 'asc',
            'rowNum' => 20,
            'rownumbers' => true,
            'width' => 900,
            'height' => '100%',
            'autowidth' => true,
            'altRows' => true,
    		    'multiselect' => true, // множественный выбор (checkbox)
            'multiboxonly' => true,//
    		    'rowList'     => array(10, 20, 30, 50, 100),
            'caption' => lang('menus_trees'). ': '.$prefix_place,
            
            'treeGridModel' => 'adjacency',
            'ExpandColumn' => 'name',
            'ExpandColClick' => true,
            'viewrecords' => false,
            'treeGrid' => true,
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

        $r['date'] = date('Y/m/d H:i',$r['date']);
        //проверяем есть данные по узлу
        if(isset($this->params['nodes_data'][$r['id']]['data'])){
        	
          //если данный узел является страницей, заменяем название на ссылочную строку
          if($this->params['nodes_data'][$r['id']]['data']['type'] == 'page'){
              if(isset($this->params['pagesPrefix'][$this->params['nodes_data'][$r['id']]['data']['id']])){
                $r['name'] = $this->params['pagesPrefix'][$this->params['nodes_data'][$r['id']]['data']['id']];
              }else{
                $r['name'] = $this->params['nodes_data'][$r['id']]['data']['name'];
              }
          }else{
            $r['name'] = $this->params['nodes_data'][$r['id']]['data']['name'];
          }
          
          //dd($r['name']);
        }else{
          $r['name'] = 'не найдены данные для type_id='.$r['type_id'].'; id='.$r['id'];
        }

        if(isset($r['image_upload']) && isset($this->params['images_files'][$r['image_upload']])){
          $file = $this->params['images_files'][$r['image_upload']];
          $resize_config = Modules::run('menus/menus_settings/get_config_resize', 'trees');
          $resize_param = Modules::run('menus/menus_settings/get_param_resize', 'trees');
          $path = $resize_param['small']['path'].$resize_param['small']['dir'].'/'.$file;
          //echo $path;
          //заключаем картинку в блок с идентификатором над которым
          //будут производится действия с картинкой
          //$r['foto'] = '<div id="foto">';
          if(is_file($this->config['path']['root'].$path)){
            $r['image'] = '<img src="/'.$path.'"  />';
          }else{
            $r['image'] = '';
          }
          //$r['foto'] = '</div>';
        }
        //$r['name'] = $r['type_name'].': '.$r['name'];
        
		    #Fields required to build tree grid
        /*
        $r['level'] = $this->input('nodeid') ? ($this->level + 1) : 0;
        $r['parent'] = $r['parent_id'];
        $r['isLeaf'] = $r['has_child'] ? false : true;
        $r['expanded'] = false;
        */

        //$r['parent'] = $r['parent_id'];
        //$r['type_content'] = array('ad','rh','fw','dg');
        return $r;
    }

    protected function addRow($orig_row, $parent = 0, $level = 0)
    {
        #Set new condition for query builder

        $this->where = array('object.parent_id=' . intval($orig_row['id']));
        $this->where[] = 'object.type_id = type.id';
        if(isset($_GET['place']) && is_numeric($_GET['place'])){
        	$this->where[] = 'place.id = '.$_GET['place'];
        }

        if(isset($_GET['group']) && is_numeric($_GET['group'])){
        	$this->where[] = 'group.id = '.$_GET['group'];
        	$this->where[] = 'place.id = group.place_id';
        }


        #Get children of current node
        $query = $this->buildQueryRows($this->query);
        $result = $this->DB->query($query);

        #Add current node
        $orig_row['level'] = $level;
        $orig_row['parent'] = $parent ? $parent : null;
        $orig_row['isLeaf'] = $this->DB->rowCount($result) ? false : true;
        $orig_row['expanded'] = $this->input('expanded') ? true : false;
        $orig_row['expanded'] = ($level >= 2) ? false : true;
        $orig_row['loaded'] = true;

        parent::addRow($orig_row);

        #Add children nodes recursively
        while($r = $this->DB->fetch($result))
        {
            $this->addRow($r, $orig_row['id'], $level + 1);
        }
    }

    protected function renderPostData()
    {
        $p['expanded'] = $this->input('expanded') ? 1 : 0;
        return $p;
    }

	/**
     *  Редактирование полученых данных перед записью
     * @param array $data
     * @return type
     */
    protected function operData($data)
    {
        $data['name'] = $data['node_name'];
        $data['date'] = strtotime($data['date']);
        $data['sorter'] = (!empty($data['sorter'])) ? $data['sorter'] : 10;
        $data['parent'] = $this->input('parent') ? $this->input('parent') : 0;
        if(!empty($data['parent_id'])){
       		$data['parent'] = $data['parent_id'];
        }
        if(!isset($data['place_name'])){
        	$data['place_name'] = $_GET['place'];
        }

        //dd($this->params['images_files']);
        if(!empty($data['image_list']) && isset($this->params['images_files'][$data['image_list']])){
          $data['image_id'] = $data['image_list'];
        }else{
          $data['image_id'] = 0;
        }              

        return $data;
    }

    /**
    * 	редактирование объекта
    */
    protected function opEdit($id, $data) {
        if(empty($this->table))
    		{
    			throw new jqGrid_Exception('Table is not defined');
    		}
    		if(isset($id)){
            #Get editing row
            $result = $this->DB->query('SELECT * FROM '.$this->table.' WHERE id='.intval($id));
            $row = $this->DB->fetch($result);


            // загружаем файл и добавляем к массиву с данными
            /*
                if(isset($_FILES[Modules::run('menus/menus_images/formSelector', 'image_upload')])){
                  //throw new jqGrid_Exception('Было загружено изображение');
                  $data_upload = Modules::run('menus/menus_images/upload_file', $id, $upd);
                    //var_dump($data);
                    if($row['file'] != $data_upload['file']){
                    Modules::run('menus/menus_images/delete_image_object', $row['file']);
                    $data['file'] = $data_upload['file'];
                  }
                }else{

                  //throw new jqGrid_Exception('Изображение НЕ загружено');
                }
            */
            #Save
            return $this->DB->update($this->table,
                                array(
                                    'name' => $data['name'],
                                    'sorter' => $data['sorter'],
                                    'parent_id' => $data['parent'],
                                    'date' => $data['date'],
                                    'place_id' => $data['place_name'],
                                    //'date_create' => $data['date_create'],
                                    'type_id' => $data['type_id'],
                                    'image_id' => $data['image_id'],

                                    'date_update' => time(),
                                    'ip_update' => $_SERVER['REMOTE_ADDR'],
                                    ),
                                array('id' => $row['id']
                                    )
            );
  		  }
    }

     /**
    * 	Добавление объекта
    */

    protected function opAdd($data) {
        if(empty($this->table))
    		{
    			throw new jqGrid_Exception('Table is not defined');
    		}
        if(isset($data['date'])){            #Get editing row
            //print_r($data);
		    //exit;
            //echo "@@@@";
            #Save book name to books table
            $id = $this->DB->insert($this->table,
                                array('name' => $data['name'],
                                      'sorter' => $data['sorter'],
                                      'parent_id' => $data['parent'],
                                      'place_id' => $data['place_name'],
                                      'type_id' => $data['type_id'],
                                      'image_id' => $data['image_id'],
                                      'date' => $data['date'],
                                      'date_create' => time(),
                                      'date_update' => time(),
                                      'ip_create' => $_SERVER['REMOTE_ADDR'],
                                      'ip_update' => $_SERVER['REMOTE_ADDR'],
                                      ),
                                true
            );

        }else{
            throw new jqGrid_Exception('Запрос не выполнен, нет данных для добавления');
        }

    }


	protected function renderGridUrl(){
        $get = '';

        if(isset($_GET['group']) && is_numeric($_GET['group'])){
        	$get .= 'group='.$_GET['group'];
        }
        if(isset($_GET['place']) && is_numeric($_GET['place'])){
        	$get .= '&place='.$_GET['place'];
        }
        return '/grid/menus_trees/menus/grid/?'.$get;

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


}
