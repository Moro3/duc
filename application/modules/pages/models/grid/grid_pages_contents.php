<?php
class grid_pages_contents extends jqGrid
{
    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {

        $this->table = 'pages_contents';		

        $this->query = "
            SELECT {fields}
            FROM pages_contents AS object
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

        return $r;
    }

	protected function renderGridUrl(){
        return '/grid/pages_contents/pages/grid/';
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
        if(isset($this->params['year_create'][$data['year_create']])){
        	$data['year_create'] = $this->params['year_create'][$data['year_create']];
        }else{
        	$data['year_create'] = '';
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
