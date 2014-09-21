<?php
class grid_duc_ranks extends jqGrid
{
    protected function init()
    {
        $this->CI = &get_instance();
        $this->table = 'duc_ranks';

        $this->query = "
            SELECT {fields}
            FROM duc_ranks AS object
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
            'show_i'  => array('label' => 'Показать',

                                   'width' => 50,
                                   'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'edittype' => "checkbox",
                                   'editrules' => array('required' => true,
                                                     'edithidden' => true,
                                   ),
            ),
            'show_i_icon'  => array('label' => 'Показать',
                                   'manual'=> true,
                                   'width' => 80,
                                   'align' => 'center',
                                   //'editable' => true,
                                   'encode' => false,
            ),
            'sort_i'  => array('label' => 'Порядок',
                                   'db' => 'object.sort_i',
                                   'width' => 50,
                                   //'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'editrules' => array('required' => true,
                                                     	'edithidden' => true,
                                                     	'integer' => true,
                                                     	'minValue' => 1,
                                                     	'maxValue' => 1000,
                                   ),
            ),
            'name'    => array('label' => 'Наименование',
                                    'db' => 'object.name',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                   ),
                                   'editoptions' => array('size' => 60),
            ),
            'description'    => array('label' => 'Описание',
                                    'db' => 'object.description',
                                    //'hidden' => true,
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'edittype' => "textarea",
                                   'encode' => true,
                                   'editoptions' => array('rows' => 10,
                                   						 'cols' => 80,
                                   						 'maxValue' => 10000,
                                   	),

                                   'editrules' => array('edithidden' => true),
            ),
        );
        $this->options = array(
            'sortname'  => 'sort_i',
            'sortorder' => 'asc',
            'rowNum' => 20,
            'rownumbers' => true,
            'width' => 900,
            'height' => '300',
            'autowidth' => true,
            'altRows' => true,
    		//'multiselect' => true, // множественный выбор (checkbox)
    		'rowList'     => array(10, 20, 30, 50, 100),
            'caption' => lang($this->table),
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
        if($r['show_i'] == 1){
            $r['show_i_icon'] = '<img src="'.assets_img('admin/button_green.gif', false).'">';

        }else{
            $r['show_i_icon'] = '<img src="'.assets_img('admin/button_red.gif', false).'">';
        }

        return $r;
    }

	protected function renderGridUrl(){
        return '/grid/duc_ranks/duc/grid/';
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
        $data['sort_i'] = (isset($data['sort_i'])) ? $data['sort_i'] : 10;

        //throw new jqGrid_Exception('Ошибка в operData');
        return $data;
    }

     protected function opEdit($id, $upd) {
        if(isset($id) && isset($upd['name'])){
            #Get editing row
            $result = $this->DB->query('SELECT * FROM '.$this->table.' WHERE id='.intval($id));
            $row = $this->DB->fetch($result);

            #Save book name to books table
            return $this->DB->update($this->table,
                                array('show_i' => $upd['show_i'],
                                      'sort_i' => $upd['sort_i'],
                                      'name' => $upd['name'],
                                      'description' => $upd['description'],
                                      'date_update' => time(),
                                      'ip_update' => $_SERVER['REMOTE_ADDR'],
                                      ),
                                array('id' => $row['id']
                                      )
                            );
            //unset($upd['name']);
        }else{
            throw new jqGrid_Exception('Запрос не выполнен, нет id');
        }

    }

    /**
    *  Операция добавления
    *  @param array $data
    *  @return boolean
    */
    protected function opAdd($data) {

        if(isset($data['name'])){            #Get editing row
            //echo "@@@@";
            #Save book name to books table
            return $this->DB->insert($this->table,
                                array('show_i' => $data['show_i'],
                                      'sort_i' => $data['sort_i'],
                                      'name' => $data['name'],
                                      'description' => $data['description'],
                                      'date_create' => time(),
                                      'date_update' => time(),
                                      'ip_create' => $_SERVER['REMOTE_ADDR'],
                                      'ip_update' => $_SERVER['REMOTE_ADDR'],
                                      ),
                                true
                            );
            //unset($upd['name']);
        }else{
            throw new jqGrid_Exception('Запрос не выполнен, нет данных для добавления');
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

        if($objs = $this->search_id_object($id, 'id_rank')){

        	$str_error = '';
        	foreach($objs as $key=>$value){
            	$obj_name = Modules::run('duc/duc_teachears/data', $key);

            	if(is_array($obj_name)){
            		foreach($obj_name as $id_obj=>$value_obj){
            			if(!empty($value_obj['controller']) ){
            				//берем все найденные id из ключей найденного массива
            				$ids = array_keys($value);
            				$data_odj = Modules::run('duc/'.$value_obj['controller'].'/data_id', $ids);
            				//print_r($data_odj);
            				//$str_error .= '<br />"Объект: '.$value_obj['name'].', id='.$data_odj['id'].'"';
            			}
            			$str_error .= '<br />"Объект: '.$value_obj['name'].', id=('.implode(',',$ids).')"';
            		}
            	}

        	}
        	throw new jqGrid_Exception('Нельзя удалить строку, т.к. её параметр используется в объектах '.$str_error);

        }

		#Delete single value
		if(is_scalar($id))
		{
			$this->DB->delete($this->table, array($this->primary_key => $id));
		}
		#Delete multiple value
		else
		{
			$ids = array_map(array($this->DB, 'quote'), explode(',', $id));
			$this->DB->delete($this->table, $this->primary_key . ' IN (' . implode(',', $ids) . ')');
		}

    }

	/**
	*  Возвращает найденный объекты по заданному полю
	*/
    protected function search_id_object($id, $key_object = 'id'){
    	if(empty($key_object)) throw new jqGrid_Exception('Ключ проверки наличия объектов не найден');

        $rows = Modules::run('duc/duc_teachers/search_objects_is_field', $id, $key_object);
        if($rows !== false) return $rows;
		return false;
    }
}
