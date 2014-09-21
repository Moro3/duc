<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('duc')){
  include_once ('duc.php');
}

/*
 * Класс Duc_groups
 *
 */

class Duc_groups extends Duc {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_duc_groups';
        $this->MY_table = 'duc_groups';
    }

    //возвращает список групп
    function listGroups(){
        $res = Modules::run('duc/duc_groups/MY_data_array_one',
         	//key
         	'main.id',
         	//select
         	array('main.name',// 'main.paid'

         	),
         	//where
         	false,
         	//order
         	'main.name',
         	//separator
         	' - '
        );
        foreach($res as $key=>$value){
        	//$new[$key] = str_replace('"','\'',$value);
            //$new[$key] = addslashes($value);
            //$new[$key] = htmlspecialchars($value);
            //$new[$key] = mysql_real_escape_string($value);
            $new[$key] = $value;
        }
        return $res;
    }

    //возвращает список групп
    function listGroupsEscape(){
        $res = $this->listGroups();
        foreach($res as $key=>$value){
            //$new[$key] = str_replace('"','\'',$value);
            //$new[$key] = addslashes($value);
            //$new[$key] = htmlspecialchars($value);
            $new[$key] = mysql_real_escape_string($value);
            //$new[$key] = $value;
        } 
        return $new;
    }

    //возвращает список групп
    function listGroupsTeacherEscape(){
        $res = Modules::run('duc/duc_groups/MY_data_array_one',
            //key
            'main.id',
            //select
            array('main.name', 'teacher.surname'// 'main.paid'

            ),
            //where
            false,
            //order
            'main.name',
            //separator
            ' - '
        );
        foreach($res as $key=>$value){
            //$new[$key] = str_replace('"','\'',$value);
            //$new[$key] = addslashes($value);
            //$new[$key] = htmlspecialchars($value);
            $new[$key] = mysql_real_escape_string($value);
            //$new[$key] = $value;
        }
        return $new;
    }

	function grid_render(){

        $schedulesGroups = Modules::run('duc/duc_schedules/MY_data_array_one', 'id_group', 'active', array('active' => 1));

   		$opt = '
   		var opts = {beforeShowForm: function($form)
        {
            var value = $form.find("#id").val();
            $form.find("#id").append("My fancy suffix for value: " + value);
        }};
        ';
   		parent::grid_render();
        
        // добавление кнопок навигации в toolbar
        $this->load->view('grid/navigator/button_bar',
                            array(
                                'id' => 'active_grid_duc_groups',
                                'oper' => 'active',
                                'caption' => 'Вкл.',
                                'title' => 'Включить выбранные записи',
                                'buttonicon' => 'ui-icon-play',
                                'confirm' => 'Включить выбранные записи?',
                                'field' => 'id'
                            )
        );
        $this->load->view('grid/navigator/button_bar',
                            array(
                                'id' => 'deactive_grid_duc_groups',
                                'oper' => 'deactive',
                                'caption' => 'Откл.',
                                'title' => 'Отключить выбранные записи',
                                'buttonicon' => 'ui-icon-stop',
                                'confirm' => 'Отключить выбранные записи?',
                                'field' => 'id'
                            )
        );

        // возможность сортировки полей методом drag and drop
        $this->load->view('grid/sorter/sortrows',
        				 array('grid' => $this->table,
        				 		'url' => '/grid/'.$this->MY_table.'/'.$this->MY_module.'/grid/'
        				 )
        );
        // изменение вида формы поля select
        
        /*
        $this->load->view('grid/formatter/select2',
        				 array('grid' => $this->table,
        				 		'url' => '/grid/'.$this->MY_table.'/'.$this->MY_module.'/grid/',
        				 		'selector' => '#id_teacher',
        				 		'width' => '350px',
                                //'formatSelection' => 'formatSelection',
                                //'formatResult' => 'formatResult'
        				 )
        );
        */

        
        $this->load->view('grid/formatter/select2',
                         array('grid' => $this->table,
                                'url' => '/grid/'.$this->MY_table.'/'.$this->MY_module.'/grid/',
                                'selector' => '#gs_id_teacher',
                                'width' => '300px',                                
                                'event' => 'jqGridGridComplete',
                                //'formatSelection' => '',
                                //'formatResult' => ''
                         )
        );

        
        $this->load->view('grid/formatter/select2',
                         array('grid' => $this->table,
                                'url' => '/grid/'.$this->MY_table.'/'.$this->MY_module.'/grid/',
                                'selector' => '#gs_name',
                                'width' => '350px',
                                'containerCss' => array('text-align' => 'left'),
                                'event' => 'jqGridGridComplete',
                                //'formatSelection' => '',
                                //'formatResult' => ''
                         )
        );
        
        

        // группировка заголовков
        $this->load->view('grid/group_header/colspan',
								array('grid' => $this->table,
                                      'useColSpanStyle' => true,
									  'column' => array(
									  		'year_education_from' => array(
									  			'numberOfColumns' => 2,
									  			'titleText' => lang('duc_year_education')
									  		),
									  		'age_from' => array(
									  			'numberOfColumns' => 2,
									  			'titleText' => lang('duc_age_child')
									  		),
									  		'duration_job' => array(
									  			'numberOfColumns' => 2,
									  			'titleText' => lang('duc_duration')
									  		),
									  )
								)
		);
    }

    //возвращает список годов создания
    function listYearCreate(){
        $res[] = '';
        for($i=1990; $i<=date('Y'); $i++){
         	$res[$i] = $i;
        }
        return $res;
    }
    //возвращает список сроков реализации
    function listPeriod(){
    	for($i=1; $i<=8; $i++){
         	$res[$i] = $i;
        }
        return $res;
    }

	/**
	*	Событие на удаление объекта (группы)
	*	переносено в контроллер duc_events
	*	@param numeric|array $id - id или массив id групп
	*
	*	@return boolean
	*/
	/*
	public function eventDelete($id){
        if( ! is_array($id)) $id = array($id);
        $res = Modules::run($this->MY_module.'/'.$this->MY_table.'/MY_delete',
    		 					//where
    		 					array('id' => $id,

    		 					)

    	);
    	if($res) return true;
    	return false;

	}
	*/

    /**
    *  Шаблон вывода коллективов и педагогов
    *
    */
    function tpl_groups_list(){
    	$data['groups'] = Modules::run(	$this->MY_module.'/'.$this->MY_table.'/MY_data_array',
    		 					//select
    		 					array('main.id', 'main.name', 'main.age_child','main.age_from','main.age_to', 'main.paid', 'main.school', 'main.id_teacher', 'main.id_direction', 'main.id_department', 'main.id_activity', 'main.id_section',
    		 						 'teacher.id', 'teacher.surname', 'teacher.name', 'teacher.name2', 'teacher.id',
    		 					      'activity.name',
    		 					      'direction.name',
    		 					      'section.name',
                                      'related' => array(
                                      		'concertmaster' => array('id_group','id_teacher','id'),
                                            'dop_teachers' => array('id_group','id_teacher','id'

                                            ),
                                      ),
    		 					),
    		 					//where
    		 					array('main.id_teacher' => array('encode' => 'teacher.id'),
    		 						  'main.active' => 1,
    		 					      'main.id_activity' => array('encode' =>'activity.id'),
    		 					      'teacher.active' => 1,
    		 					),
    		 					//limit
    		 					false,
    		 					//order
    		 					array('main.id_direction', 'main.id_section',  'main.name', 'main.sorter',

    		 					),
    		 					//group
    		 					array('main.name'
    		 					)
    	);
        $data['uri']['point'] =$this->uri_point('user');
        $data['uri']['groups_id'] = $this->get_uri_link('user_groups_id');
        $data['uri']['teachers_id'] = $this->get_uri_link('user_teachers_id');
    	//echo '<pre>';
    	//print_r($data['groups'][6]);
    	//echo '</pre>';
    	//exit;


        $this->load->view('user/groups_list', $data );
    }

    /**
    * Шаблон вывода коллектива по его id
    *	@param numeric - id коллектива
    *
    */
    function tpl_groups_id($id){

    	if(empty($id) || !is_numeric($id)) return false;

    	$data['groups'] = Modules::run(	$this->MY_module.'/'.$this->MY_table.'/MY_data_array',
    		 					//select
    		 					array('main.id', 'main.name', 'main.paid', 'main.price', 'main.free', 'main.programm', 'main.year_create', 'main.period', 'main.age_child','main.age_from','main.age_to', 'main.description','main.short_description', 'main.school',
    		 						 'teacher.id', 'teacher.surname', 'teacher.name', 'teacher.name2', 'teacher.id',
    		 						 'direction.id', 'direction.name',
    		 						 'department.id', 'department.name',
    		 						 'activity.id', 'activity.name',
    		 						 'related' => array(
                                      		'photos' => array('active','sorter','name', 'img'),
                                            'concertmaster' => array('id_group','id_teacher','id'),
                                      		'dop_teachers' => array('id_group','id_teacher','id'),
                                      ),
    		 					),
    		 					//where
    		 					array('main.id_teacher' => array('encode' => 'teacher.id'),
    		 					      'main.id_direction' => array('encode' => 'direction.id'),
    		 					      'main.id_department' => array('encode' => 'department.id'),
    		 					      'main.id_activity' => array('encode' => 'activity.id'),
    		 					      'main.active' => 1,
    		 					      'main.id' => $id,
    		 					      'teacher.active' => 1,
    		 					),
    		 					//false,
    		 					false, //limit
    		 					''     //order

    	);

        $data['uri']['point'] =$this->uri_point('user');
        $data['uri']['groups_list'] = $this->get_uri_link('user_groups_list');
        $data['uri']['schedules'] = $this->get_uri_link('user_schedules_list');
        $data['uri']['schedules_group'] = $this->get_uri_link('user_sсhedules_group');
        $data['uri']['schedules_groupname'] = $this->get_uri_link('user_sсhedules_groupname');

        $data['config'] = $this->setting;
    	//exit;
    	$this->load->view('user/groups_id', $data );
    }

    /**
    *  Шаблон вывода списка шаблонов для сайта mskobr
    *
    */
    function tpl_mskobr(){

    	$data['departments'] = Modules::run(	$this->MY_module.'/duc_departments/MY_data',
    		 					//select
    		 					array('main.id', 'main.show_i', 'main.name', 'main.description',

    		 					),
    		 					//where
    		 					false,
    		 					//limit
    		 					false,
    		 					//order
    		 					array(
    		 						'main.sort_i',
                                )
    	);

    	$data['directions'] = Modules::run(	$this->MY_module.'/duc_directions/MY_data',
    		 					//select
    		 					array('main.id', 'main.show_i', 'main.name', 'main.description',

    		 					),
    		 					//where
    		 					false,
    		 					//limit
    		 					false,
    		 					//order
    		 					array(
    		 						'main.sort_i',
                                )
    	);
    	$data['groups'] = Modules::run(	$this->MY_module.'/'.$this->MY_table.'/MY_data_array',
    		 					//select
    		 					array('main.id', 'main.name', 'main.paid', 'main.price', 'main.free', 'main.programm', 'main.year_create', 'main.period', 'main.age_child','main.age_from','main.age_to', 'main.school',
    		 						 'teacher.id', 'teacher.surname', 'teacher.name', 'teacher.name2', 'teacher.id',
    		 						 'direction.id', 'direction.name',
    		 						 'department.id', 'department.name',
    		 						 'activity.id', 'activity.name',
    		 						 'related' => array(
                                      		'photos' => array('active','sorter','name', 'img'),

                                      ),
    		 					),
    		 					//where
    		 					array('main.id_teacher' => array('encode' => 'teacher.id'),
    		 					      'main.id_direction' => array('encode' => 'direction.id'),
    		 					      'main.id_department' => array('encode' => 'department.id'),
    		 					      'main.id_activity' => array('encode' => 'activity.id'),
    		 					      'main.active' => 1,
    		 					),
    		 					//false,
    		 					false, //limit
    		 					''     //order

    	);

        $data['teacher'] = Modules::run(	$this->MY_module.'/duc_teachers/MY_data_array_one',
    		 					//key
    		 					'main.id',
    		 					//select
    		 					array('main.surname','main.name', 'rank.name', 'qualification.name',

    		 					),
    		 					//where
    		 					array(
    		 						'main.id_rank' => array('encode' => 'rank.id'),
    		 						'main.id_qualification' => array('encode' => 'qualification.id'),
    		 					),
    		 					//order
    		 					array(
    		 						'main.surname',
    		 						'main.name'
                                )
    	);

    	$data['groups2'] = Modules::run(	$this->MY_module.'/'.$this->MY_table.'/MY_data_array',
    		 					//select
    		 					array('id', 'name', 'teacher.name',

    		 					),
    		 					//where
    		 					false,
    		 					//false,
    		 					false, //limit
    		 					''     //order

    	);

    	$this->db->select('g.id, g.name, g.active');
    	$this->db->from('duc_groups g');
    	$join = array('g.id_teacher = t.id', 't.active = 1');
    	//$this->db->join('duc_teachers t', $join);
    	$this->db->join('duc_teachers t', 'g.id_teacher = t.id');
    	//$this->db->join('duc_teachers t', 't.active = 1');
    	$query = $this->db->get();
    	$res = $query->result();

        echo $this->db->last_query();
        echo '<pre>';
        //var_dump($res);
        $data['uri']['point'] =$this->uri_point('user');
        $data['uri']['groups_id'] = $this->get_uri_link('user_groups_id');
        $data['uri']['teachers_id'] = $this->get_uri_link('user_teachers_id');
        $data['uri']['mskobr'] = $this->get_uri_link('user_mskobr');
        $data['uri']['mskobr_department'] = $this->get_uri_link('user_mskobr_department');
        $data['uri']['mskobr_direction'] = $this->get_uri_link('user_mskobr_direction');
    	//echo '<pre>';
    	//print_r($data['groups'][6]);
    	//echo '</pre>';
    	//exit;
        $data['config'] = $this->setting;

        $this->load->view('user/mskobr', $data);

    }

    /**
    *  Шаблон вывода коллективов для сайта mskobr
    *
    */
    function tpl_mskobr_groups_list(){
    	$data['groups'] = Modules::run(	$this->MY_module.'/'.$this->MY_table.'/MY_data_array',
    		 					//select
    		 					array('main.id', 'main.name', 'main.age_child','main.age_from','main.age_to', 'main.paid', 'main.free', 'main.school', 'main.programm', 'main.year_create', 'main.period', 'main.short_description', 'main.description', 'main.id_teacher', 'main.id_direction', 'main.id_department', 'main.id_activity',
    		 						 'teacher.id', 'teacher.surname', 'teacher.name', 'teacher.name2', 'teacher.id',
    		 					      'activity.name',
    		 					      'department.name',
                                      'related' => array(
                                      		'concertmaster' => array('id_group','id_teacher','id'),
                                      		'dop_teachers' => array('id_group','id_teacher','id'),
                                            'teacher' => array('id', 'surname', 'name', 'name2', 'id_qualification', 'id_rank',
                                            					'related' => array(
                                            						'qualification', 'rank'
                                            					),
                                            ),
                                            'photos'
                                      ),
    		 					),
    		 					//where
    		 					array('main.id_teacher' => array('encode' => 'teacher.id'),
    		 						  'main.active' => 1,
    		 					      'main.id_activity' => array('encode' =>'activity.id'),
    		 					      //'main.free' => 1,
    		 					),
    		 					//limit
    		 					false,
    		 					//order
    		 					array(
    		 						'department.name',
    		 						'main.name'
                                )
    	);
        $data['uri']['point'] =$this->uri_point('user');
        $data['uri']['groups_id'] = $this->get_uri_link('user_groups_id');
        $data['uri']['teachers_id'] = $this->get_uri_link('user_teachers_id');
    	//echo '<pre>';
    	//print_r($data['groups'][6]);
    	//echo '</pre>';
    	//exit;
        $data['config'] = $this->setting;

        $html = $this->load->view('user/mskobr_groups_list', $data, true );

        echo '<textarea cols=100 rows= 10>';
        echo $html;
        echo '</textarea>';
        echo $html;
    }

    /**
    *  Шаблон вывода коллективов для сайта mskobr по направлениям
    *
    */
    function tpl_mskobr_groups_direction($id_direction, $paid = false){
    	if(empty($id_direction) || !is_numeric($id_direction)) return false;

    	if($paid == 'paid'){
    		$where['main.paid'] = 1;
    	}elseif($paid == 'free'){
    		$where['main.paid'] = 0;
    	}
    	$where['main.id_teacher'] = array('encode' => 'teacher.id');
		$where['main.active'] = 1;
		$where['main.id_activity'] = array('encode' =>'activity.id');
		$where['main.id_direction'] = $id_direction;
		$where['main.active_site'] = 1;
    	$data['groups'] = Modules::run(	$this->MY_module.'/'.$this->MY_table.'/MY_data_array',
    		 					//select
    		 					array('main.id', 'main.name', 'main.age_child','main.age_from','main.age_to', 'main.paid', 'main.free', 'main.school', 'main.programm', 'main.year_create', 'main.period', 'main.short_description', 'main.description',
    		 						 'main.id_teacher', 'main.id_direction', 'main.id_department', 'main.id_activity', 'main.id_section',
    		 						 'teacher.id', 'teacher.surname', 'teacher.name', 'teacher.name2', 'teacher.id',
    		 					      'activity.name',
    		 					      'department.name',
                                      'related' => array(
                                      		'concertmaster' => array('id_group','id_teacher','id'),
                                      		'dop_teachers' => array('id_group','id_teacher','id'),
                                            'teacher' => array('id', 'surname', 'name', 'name2', 'id_qualification', 'id_rank',
                                            					'related' => array(
                                            						'qualification', 'rank'
                                            					),
                                            ),


                                            'photos' => array(
                                                         'main.id', 'main.name', 'main.active', 'main.img', 'main.id_group',
                                                         //'grouper.name', 'grouper.id',
                                                         /*
                                                         'join' => array(

                                                                   'left' => array(
	                                                                   'duc_groups m2' => array(
	                                                                              'm2.name' =>  'grouper.name',
	                                                                   ),
	                                                                   'duc_photos m1' => array(
	                                                                              'm1.id' =>  'main.id',
	                                                                   ),



                                                                   ),
                                                         ),
                                                         */

                                            ),


                                      ),
    		 					),
    		 					//where
    		 					$where,
    		 					//limit
    		 					false,
    		 					//order
    		 					array(
    		 						'main.id_section',
    		 						'main.id_activity'
                                )

    	);

        $photos =
        $data['uri']['point'] =$this->uri_point('user');
        $data['uri']['groups_id'] = $this->get_uri_link('user_groups_id');
        $data['uri']['teachers_id'] = $this->get_uri_link('user_teachers_id');
    	//echo '<pre>';
    	//print_r($data['groups'][6]);
    	//echo '</pre>';
    	//exit;
        $data['config'] = $this->setting;
        $data['direction'] = Modules::run('duc/duc_directions/MY_data_row',
        							//select
        							array('id', 'name', 'description'),
        							//where
        							array('id' => $id_direction,
        								  'show_i' => 1
        							)
        );

        $html = $this->load->view('user/mskobr_groups_direction', array('data' => $data), true );

        echo '<textarea cols=100 rows= 10>';
        echo $html;
        echo '</textarea>';
        echo $html;
    }

    /**
    *  Шаблон вывода коллективов для сайта mskobr по отделам
    *
    */
    function tpl_mskobr_groups_department($id_department, $paid = false){
    	if(empty($id_department) || !is_numeric($id_department)) return false;

    	if($paid == 'paid'){
    		$where['main.paid'] = 1;
    	}elseif($paid == 'free'){
    		$where['main.paid'] = 0;
    	}
    	$where['main.id_teacher'] = array('encode' => 'teacher.id');
		$where['main.active'] = 1;
		$where['main.id_activity'] = array('encode' =>'activity.id');
		$where['main.id_department'] = $id_department;
    	$data['groups'] = Modules::run(	$this->MY_module.'/'.$this->MY_table.'/MY_data_array',
    		 					//select
    		 					array('main.id', 'main.name', 'main.age_child','main.age_from','main.age_to', 'main.paid', 'main.free', 'main.school', 'main.programm', 'main.year_create', 'main.period', 'main.short_description', 'main.description', 'main.id_teacher', 'main.id_direction', 'main.id_department', 'main.id_activity',
    		 						 'teacher.id', 'teacher.surname', 'teacher.name', 'teacher.name2', 'teacher.id',
    		 					      'activity.name',
    		 					      'department.name',
                                      'related' => array(
                                      		'concertmaster' => array('id_group','id_teacher','id'),
                                      		'dop_teachers' => array('id_group','id_teacher','id'),
                                            'teacher' => array('id', 'surname', 'name', 'name2', 'id_qualification', 'id_rank',
                                            					'related' => array(
                                            						'qualification', 'rank'
                                            					),
                                            ),
                                            'photos'
                                      ),
    		 					),
    		 					//where
    		 					$where,
    		 					//limit
    		 					false,
    		 					//order
    		 					array(
    		 						//'department.name',
    		 						//'main.name'
                                )
    	);
        $data['uri']['point'] =$this->uri_point('user');
        $data['uri']['groups_id'] = $this->get_uri_link('user_groups_id');
        $data['uri']['teachers_id'] = $this->get_uri_link('user_teachers_id');
    	//echo '<pre>';
    	//print_r($data['groups'][6]);
    	//echo '</pre>';
    	//exit;
        $data['config'] = $this->setting;
        $data['department'] = Modules::run('duc/duc_departments/MY_data_row',
        							//select
        							array('id', 'name', 'description'),
        							//where
        							array('id' => $id_department,
        								  'show_i' => 1
        							)
        );

        $html = $this->load->view('user/mskobr_groups_department', array('data' => $data), true );

        echo '<textarea cols=100 rows= 10>';
        echo $html;
        echo '</textarea>';
        echo $html;
    }

}
