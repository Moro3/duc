<?php
/**
  jQgrid adjacency - вывод дерева целиком

*/

class grid_menus_trees extends jqGrid
{
    protected $do_count = false;

    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {
        $this->CI = &get_instance();
        $this->table = 'menus_trees';
        $this->config = $this->loader->get('config');

		$this->params['places'] = Modules::run('menus/menus_places/places_select');

		$this->params['pages'] = Modules::run('pages/pages/pages_select_prefix');

        #Set tree grid mode
        $this->treegrid = 'adjacency';

        #Set condition for base level
        $this->where = array('object.parent_id=0');

        $this->query = "
            SELECT {fields}
            FROM menus_trees object, menus_places place
            WHERE {where}
        ";
        if(isset($_GET['place']) && is_numeric($_GET['place'])){        	$this->where[] = 'place.id = '.$_GET['place'];
        }

        $this->where[] = 'object.place_id = place.id';


        $this->cols = array(
            'id'          => array('label' => lang('menus_id'),
                                   'db' => 'object.id',
                                   'width' => 50,
                                   'align' => 'center',
                                   'editable' => true,
                                   'editoptions'=>array('readonly'=>true),

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
            'parent_id' => array('hidden' => true,
                					'db' => 'object.parent_id',
                					'editable' => true,

            ),

            'node_name' => array('label' => lang('menus_name'),
                					'db' => 'object.name',
                					'width' => 55,
                					'editable' => true,
            ),


            'place_name'    => array('label' => lang('menus_place'),
                                    'db' => 'place.name',
                                    'replace' => $this->params['places'],
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'edittype' => 'select',
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 250,
                                   ),
                                   'editoptions' => array(//'size' => 100,
                                                          //'value' => $this->params['places'],
                                                          'value' => new jqGrid_Data_Value($this->params['places']),
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
            'caption' => lang('menus_trees'). ': '.$this->params['places'][$_GET['place']],
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

        //$r['date'] = date('Y/m/d H:i',$r['date']);

        return $r;
    }

    protected function addRow($orig_row, $parent = 0, $level = 0)
    {
		#Set new condition for query builder
        $this->where = array('object.parent_id=' . intval($orig_row['id']));
        if(isset($_GET['place']) && is_numeric($_GET['place'])){
        	$this->where[] = 'place.id = '.$_GET['place'];
        }
        #Get children of current node
        $query = $this->buildQueryRows($this->query);
        $result = $this->DB->query($query);

        #Add current node
        $orig_row['level'] = $level;
        $orig_row['parent'] = $parent ? $parent : null;
        $orig_row['isLeaf'] = $this->DB->rowCount($result) ? false : true;
        $orig_row['expanded'] = $this->input('expanded') ? true : false;
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
        //$data['name'] = $data['node_name'];
        //$data['date'] = strtotime($data['date']);
        //$data['sorter'] = (!empty($data['sorter'])) ? $data['sorter'] : 10;
        if(isset($data['parent_id']) && empty($data['parent'])){       		//$data['parent'] = $data['parent_id'];
        }        //if(empty($data['parent'])) $data['parent'] = 0;
        $data['parent'] = $this->input('parent') ? $this->input('parent') : 0;
        return $data;
    }


    /**
    * 	редактирование объекта
    */
    /*
    protected function opEdit($id, $data) {
        if(empty($this->table))
		{
			throw new jqGrid_Exception('Table is not defined');
		}
		if(isset($id)){
            #Get editing row
            $result = $this->DB->query('SELECT * FROM '.$this->table.' WHERE id='.intval($id));
            $row = $this->DB->fetch($result);


            #Save
            return $this->DB->update($this->table,
                                array(
                                	  'name' => $data['name'],
                                      'sorter' => $data['sorter'],
                                      'parent_id' => $data['parent'],
                                      'date' => $data['date'],
                                      //'date_create' => $data['date_create'],

                                      'date_update' => time(),
                                      'ip_update' => $_SERVER['REMOTE_ADDR'],
                                      ),
                                array('id' => $row['id']
                                      )
            );
  		}
    }
    */

     /**
    * 	Добавление объекта
    */
    /*
    protected function opAdd($data) {
        if(empty($this->table))
		{
			throw new jqGrid_Exception('Table is not defined');
		}
        if(isset($data['date'])){            #Get editing row
            print_r($data);
			exit;
            //echo "@@@@";
            #Save book name to books table
            $id = $this->DB->insert($this->table,
                                array('name' => $data['name'],
                                      'sorter' => $data['sorter'],
                                      //'parent_id' => $data['parent'],
                                      'place_id' => $data['place_name'],
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
    */

	protected function renderGridUrl(){
        $get = '';

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
