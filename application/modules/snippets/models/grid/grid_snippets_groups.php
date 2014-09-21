<?php
class grid_snippets_groups extends jqGrid
{
    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {

        $this->table = 'snippets_groups';

        $this->query = "
            SELECT {fields}
            FROM snippets_groups AS object
            WHERE {where}
        ";


        $this->cols = array(
            'id'          => array('label' => 'ID',
                                   'db' => 'object.id',
                                   'width' => 50,
                                   'align' => 'center',
                                   'editable' => true,
                                   'editoptions'=>array("readonly"=>true),
            ),
            'active'  => array('label' => lang('snippets_active'),
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
            'active_icon'  => array('label' => lang('snippets_active'),
                                   'manual'=> true,
                                   'width' => 30,
                                   'align' => 'center',
                                   //'editable' => true,
                                   'encode' => false,
            ),
            'sorter'  => array('label' => lang('snippets_sorter'),
                                   'db' => 'object.sorter',
                                   'width' => 30,
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
            'name'    => array('label' => lang('snippets_name'),
                                    'db' => 'object.name',
                                   'width' => 150,
                                   'align' => 'left',
                                   //'hidden' => true,
                                   'editable' => true,
                                   'edittype' => "text",
                                   'editrules' => array(//'required' => true,
                                                       'maxValue' => 200,
                                                       'edithidden' => true,
                                   ),
                                   'editoptions' => array('size' => 100),
            ),
            'description'    => array('label' => lang('snippets_description'),
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
                                   'editoptions' => array('cols' => 80, 'rows' => 5),
            ),
            'description_active'    => array('label' => lang('snippets_description'),
                                    'db' => 'object.description',
                                    'manual'=> true,
                                   'width' => 250,
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
            $r['description_active'] = character_limiter($r['description'], 120);
        }else{
            $r['description_active'] = '<img src="'.assets_img('admin/no.gif', false).'">';
        }
        return $r;
    }

	protected function renderGridUrl(){
        return '/grid/'.$this->table.'/snippets/grid/';
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
    protected function opEdit($id, $data) {
        if(!empty($id) && is_numeric($id)){
            //$result = $this->DB->query('SELECT * FROM '.$this->table.' WHERE id='.intval($id));
            //$row = $this->DB->fetch($result);
            $row = Modules::run('snippets/snippets_groups/MY_data_array_row',
	         	//select
	         	array('id', 'name'// 'main.paid'

	         	),
	         	//where
	         	array(
	         		'id' => intval($id)
	         	)
	        );
            if($row['id'] !== $id){            	throw new jqGrid_Exception('Такая группа уже есть в системе');
            	return false;
            }

            $nameDouble = Modules::run('snippets/snippets_groups/searchName', $data['name'], $row['id']);
            if($nameDouble === true){
            	throw new jqGrid_Exception('Группа с таким именем уже есть в системе!');
            	return false;
            }

            	$arr['active'] = $data['active'];
            	$arr['sorter'] = $data['sorter'];

                $arr['name'] = $data['name'];
                $arr['description'] = $data['description'];

            	$arr['date_update'] = time();
            	$arr['ip_update'] = $_SERVER['REMOTE_ADDR'];

            	#Save book name to books table
	            /*
	            $res = $this->DB->update($this->table,
	                                $arr,
	                                array('id' => $row['id']
	                                )
	            );
	            */
	            $res = Modules::run('snippets/snippets_groups/MY_update',
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
        	$nameDouble = Modules::run('snippets/snippets_groups/searchName', $data['name']);
            if($nameDouble === true){
            	throw new jqGrid_Exception('Группа с таким именем уже есть в системе!');
            	return false;
            }

        		$arr['active'] = $data['active'];
            	$arr['sorter'] = $data['sorter'];

                $arr['name'] = $data['name'];
                $arr['description'] = $data['description'];

            	$arr['date_create'] = time();
            	$arr['date_update'] = time();
            	$arr['ip_create'] = $_SERVER['REMOTE_ADDR'];
            	$arr['ip_update'] = $_SERVER['REMOTE_ADDR'];



	            /*
	            $id = $this->DB->insert($this->table,
	                                $arr,
	                                true
	            );
	            */
	            //throw new jqGrid_Exception('Добавление записи');
	            $id = Modules::run('snippets/snippets_groups/MY_insert', $arr);


        }else{
        	throw new jqGrid_Exception('Не задано имя');
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
        $isSnippet = Modules::run('snippets/snippets/isGroupId', $id);
            if($isSnippet === true){
            	throw new jqGrid_Exception('Есть сниппеты присутствующие в данной группе, удалите сначало группу из сниппетов!');
            	return false;
            }

		Modules::run('snippets/snippets_groups/MY_delete',
			//where
			array('id' => $id)
		);

    }

}
