<?php
class grid_duc_directions extends jqGrid
{
    protected function beforeInit(){
    	 $this->CI = &get_instance();
    }

    protected function init()
    {
        $this->CI = &get_instance();
        $this->table = 'duc_directions';

        $this->query = "
            SELECT {fields}
            FROM duc_directions AS object
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
                                   'editrules' => array(
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
                                   'height' => 30,
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
        return '/grid/duc_directions/duc/grid/';
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

        //throw new jqGrid_Exception('Ошибка в operData');
        return $data;
    }
}
