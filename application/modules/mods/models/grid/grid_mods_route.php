<?php
class grid_mods_route extends jqGrid
{
    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {
        $this->table = 'mods_route';

        $this->params['mods'] = Modules::run('mods/mods/MY_data_array_one', 'id', 'name');
        $this->params['mods_uri'] = Modules::run('mods/mods/MY_data_array_one', 'id', array('name','uri'), false, false,':     http://site/');

        $this->query = "
            SELECT {fields}
            FROM mods_route AS object, mods AS module
            WHERE {where}
        ";
        $this->where[] = 'object.mod_id = module.id';

        

        $this->cols = array(
            'id'          => array('label' => lang('mods_id'),
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
            'name'    => array('label' => lang('mods_route_name'),
                                'db' => 'object.name',
                                'width' => 250,
                                'align' => 'left',
                                'editable' => true,
                                'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                ),
                                'editoptions' => array('size' => 60),
            ),
            'alias'    => array('label' => lang('mods_route_alias'),
                                'db' => 'object.alias',
                                'width' => 250,
                                'align' => 'left',
                                'editable' => true,
                                'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                ),
                                'editoptions' => array('size' => 60),
            ),
            'mod_ids'    => array('label' => lang('mods_mod'),
                                  'db' => 'object.mod_id',
                                  'replace' => $this->params['mods'],
                                  'width' => 150,
                                  'align' => 'center',
                                  'hidden' => true,
                                  'editable' => false,
                                  'edittype' => 'select',
                                  'editoptions' => array(                                                
                                                'value' => new jqGrid_Data_Value($this->params['mods']),
                                                
                                  ),
                                  'editrules' => array(
                                                      'edithidden' => true,
                                                      'integer' => true,
                                                      'minValue' => 1,
                                                      'maxValue' => 1000,
                                  ),
                                  'stype' => 'select',
                                  'searchoptions' => array(                                                
                                                'value' => new jqGrid_Data_Value($this->params['mods'], 'All'),
                                                'style' => 'text-align:left;',                                                
                                                //'onSelect'       => new jqGrid_Data_Raw('function() { $("#gs_id_teacher").select2(); }'),
                                  ),
            ),
            'mod_id'    => array('label' => lang('mods_mod_uri'),
                                  'db' => 'object.mod_id',
                                  'replace' => $this->params['mods_uri'],
                                  
                                  'width' => 150,
                                  'align' => 'right',
                                  //'hidden' => true,
                                  'editable' => true,
                                  'edittype' => 'select',
                                  'editoptions' => array(                                                
                                                'value' => new jqGrid_Data_Value($this->params['mods_uri']),
                                                
                                  ),
                                  'editrules' => array(
                                                      //'edithidden' => true,
                                                      'integer' => true,
                                                      'minValue' => 1,
                                                      'maxValue' => 1000,
                                  ),
                                  'stype' => 'select',
                                  'searchoptions' => array(                                                
                                                'value' => new jqGrid_Data_Value($this->params['mods_uri'], 'All'),
                                                'style' => 'text-align:left;',                                                
                                                //'onSelect'       => new jqGrid_Data_Raw('function() { $("#gs_id_teacher").select2(); }'),
                                  ),
            ),
            'uri'    => array('label' => lang('mods_route_uri'),
                                    'db' => 'object.uri',
                                   'width' => 250,
                                   'align' => 'left',
                                   'editable' => true,
                                   'editrules' => array('required' => true,
                                                       'maxValue' => 100,
                                                       //'url' => true,
                                   ),
                                   'editoptions' => array('size' => 60),
                                   'formoptions'=>array(
                                                'rowpos'=> '6',
                                                'colpos'=> '1',
                                                'elmprefix' => '/',
                                                'label' => 'Uri',
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
            'img_upload'    => array('label' => lang('mods_route_icon_upload'),
                                   'db' => 'object.icon',
                                   'width' => 150,
                                   'align' => 'left',
                                   'hidden' => true,
                                   'editable' => true,
                                   'encode' => false,
                                   'edittype' => 'file',
                                   'editrules' => array(
                                                       'maxValue' => 100,
                                                       'edithidden' => true,
                                   ),
                                   'editoptions' => array('size' => 60),
                                   //'formoptions' => array('elmsuffix' => $this->button_delete_foto()),
            ),
            'img'    => array('label' => lang('mods_route_icon'),
                                   'db' => 'object.icon',
                                   'width' => 150,
                                   'align' => 'left',
                                   'editable' => true,
                                   'encode' => false,
                                   //'edittype' => 'file',
                                   'editrules' => array(
                                                       'maxValue' => 100,
                                   ),
                                   //'editoptions' => array('size' => 60),
                                   //'formoptions' => array('elmsuffix' => $this->button_delete_foto()),
            ),

        );
        $this->options = array(
            'sortname'  => 'name',
            'sortorder' => 'asc',
            'rowNum' => 20,
            'rownumbers' => true,
            'width' => 900,
            'height' => '100%',
            'autowidth' => true,
            'altRows' => true,
    		    //'multiselect' => true, // множественный выбор (checkbox)
    		    'rowList'     => array(10, 20, 30, 50, 100),
            'caption' => lang('mods_routes'),
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
        //$r['mod_uri'] .= '/';

        return $r;
    }

	protected function renderGridUrl(){
        return '/grid/mods_route/mods/grid/';
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

        if( ! Modules::run('mods/mods_route/validationUri', $data['uri'])){
          throw new jqGrid_Exception('Неверный формат URI');  
        }

        if( ! Modules::run('mods/mods_route/validationName', $data['name'])){
          throw new jqGrid_Exception('Неверный формат '.lang('mods_route_name'));  
        }

        if( ! Modules::run('mods/mods_route/validationAlias', $data['alias'])){
          throw new jqGrid_Exception('Неверный формат '.lang('mods_route_alias'));  
        }		
        
        return $data;
    }

    /**
    * 	редактирование объекта
    */
    protected function opEdit($id, $data) {
        
      
      if(isset($id)){         
      
          if( ! isset($data['uri'])){
            throw new jqGrid_Exception('Не установлен параметр uri');
          }

          if($matchesUri = Modules::run('mods/mods_route/matchesUri', $data['uri'], $id)){
            throw new jqGrid_Exception('Такой uri ('.$matchesUri.') уже есть. Придумайте другой.');
          }

          if($matchesName = Modules::run('mods/mods_route/matchesName', $data['name'], $id)){
            throw new jqGrid_Exception('Такое '.lang('mods_route_name').' ('.$matchesUri.') уже есть. Придумайте другое.');
          }

          if($matchesAlias = Modules::run('mods/mods_route/matchesAlias', $data['alias'], $id)){
            throw new jqGrid_Exception('Такое '.lang('mods_route_alias').' ('.$matchesAlias.') уже есть. Придумайте другое.');
          }

          #Get editing row
          $result = $this->DB->query('SELECT * FROM '.$this->table.' WHERE id='.intval($id));
          $row = $this->DB->fetch($result);

          #Save book name to books table
          $response = $this->DB->update($this->table,
                              array('name' => $data['name'],
                                    'alias' => $data['alias'],
                                    'active' => $data['active'],
                                    'sorter' => $data['sorter'],
                                    'uri' => $data['uri'],
                                    'mod_id' => $data['mod_id'],
                                    'description' => $data['description'],
                                    'date_create' => time(),
                                    'date_update' => time(),
                                    'ip_create' => $_SERVER['REMOTE_ADDR'],
                                    'ip_update' => $_SERVER['REMOTE_ADDR'],
                                    ),
                              array('id' => $row['id']
                                      )
          );

      }else{
          throw new jqGrid_Exception('Запрос не выполнен, не указан ID.');
      }
    	return $response;
    }

     /**
    *   Добавление объекта
    */
    protected function opAdd($data) {
      if(empty($this->table))
      {
        throw new jqGrid_Exception('Table is not defined');
      }
      
      if( ! isset($data['uri'])){
        throw new jqGrid_Exception('Не установлен параметр uri');
      }

      if($matchesUri = Modules::run('mods/mods_route/matchesUri', $data['uri'])){
        throw new jqGrid_Exception('Такой uri ('.$matchesUri.') уже есть. Придумайте другой.');
      }

      if($matchesName = Modules::run('mods/mods_route/matchesName', $data['name'])){
        throw new jqGrid_Exception('Такое '.lang('mods_route_name').' ('.$matchesUri.') уже есть. Придумайте другое.');
      }

      if($matchesAlias = Modules::run('mods/mods_route/matchesAlias', $data['alias'])){
        throw new jqGrid_Exception('Такое '.lang('mods_route_alias').' ('.$matchesAlias.') уже есть. Придумайте другое.');
      }

      if(isset($data['name'])){            #Get editing row
      
          #Save book name to books table
          $id = $this->DB->insert($this->table,
                              array('name' => $data['name'],
                                    'sorter' => $data['sorter'],
                                    'uri' => $data['uri'],
                                    'mod_id' => $data['mod_id'],
                                    'description' => $data['description'],
                                    
                                    'date_update' => time(),
                                    
                                    'ip_update' => $_SERVER['REMOTE_ADDR'],
                                    ),
                              true
          );

      }else{
          throw new jqGrid_Exception('Запрос не выполнен, нет данных для добавления');
      }

    }

     /**
    *   Добавление объекта
    */
    protected function opDel($id) {
      if(empty($this->table))
      {
        throw new jqGrid_Exception('Table is not defined');
      }

      #Get editing row
          $result = $this->DB->query('SELECT * FROM '.$this->table.' WHERE id='.intval($id));
          $row = $this->DB->fetch($result);
          if(isset($row['id'])) {
              Modules::run('mods/mods_route/MY_delete',
                          array('id' => $row['id'])

              );
          }
    }    

}
