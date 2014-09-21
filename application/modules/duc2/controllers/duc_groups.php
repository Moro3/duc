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
         	array('main.name', 'teacher.surname',// 'main.paid'

         	),
         	//where
         	array('main.id_teacher' => array('encode' => 'teacher.id')),
         	//order
         	'main.name',
         	//separator
         	' - '
         );
         foreach($res as $key=>$value){         	$new[$key] = str_replace('"','\'',$value);
         	//$new[$key] = str_replace('\'','',$value);
         }
         return $new;
    }

	function grid_render(){

   		parent::grid_render();
        $this->load->view('grid/navigator/active');
        $this->load->view('grid/sorter/sortrows',
        				 array('grid' => $this->table,
        				 		'url' => '/grid/'.$this->MY_table.'/'.$this->MY_module.'/grid/'
        				 )
        );
        $this->load->view('grid/formatter/select2',
        				 array('grid' => $this->table,
        				 		'url' => '/grid/'.$this->MY_table.'/'.$this->MY_module.'/grid/',
        				 		//'selector' => 'gs_id_teacher',
        				 )
        );
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
		переносено в контроллер duc_events
	*	@param numeric|array $id - id или массив id групп
	*
	*	@return boolean
	*/
	/*
	public function eventDelete($id){        if( ! is_array($id)) $id = array($id);        $res = Modules::run($this->MY_module.'/'.$this->MY_table.'/MY_delete',
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
    function tpl_groups_list(){    	$data['groups'] = Modules::run(	$this->MY_module.'/'.$this->MY_table.'/MY_data_array',
    		 					//select
    		 					array('main.id', 'main.name', 'main.age_child','main.age_from','main.age_to', 'main.paid', 'main.school', 'main.id_teacher', 'main.id_direction', 'main.id_department', 'main.id_activity', 'main.id_section',
    		 						 'teacher.id', 'teacher.surname', 'teacher.name', 'teacher.name2', 'teacher.id',
    		 					      'activity.name',
    		 					      'direction.name',
    		 					      'section.name',
                                      'related' => array(
                                      		'concertmaster' => array('id_group','id_teacher','id'),

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
    		 					array('main.id_direction', 'main.id_section', 'main.name',

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
    		 					array('main.surname','main.name','main.name2', 'rank.name', 'qualification.name',
                                      'join' => array(
                                                  'left' => array(
                                                            'qualification' => array(
                                                                               'qualification.id' => 'main.id_qualification'
                                                            ),
                                                            'rank' => array(
                                                                               'rank.id' => 'main.id_rank'
                                                            ),
                                                  ),
                                     )
    		 					),
    		 					//where
    		 					array(
    		 						//'main.id_rank' => array('encode' => 'rank.id'),
    		 						//'main.id_qualification' => array('encode' => 'qualification.id'),
    		 					),
    		 					//order
    		 					array(
    		 						'main.surname',
    		 						'main.name'
                                ),
                                //separator
                                array(
                                   //глобальные параметры для всех полей
                                   '!' => array(
                                   			'separator' => ' ', //общий разделитель для всех полей
                                   			'prefix' => '',  //общий префикс для всех полей
                                   			'suffix' => '',  //общий суфикс для всех полей
                                   			'rowprefix' => '', //префик для всей строки
                                   			'rowsuffix' => '', //суфик для всей строки
                                   ),
                                   'rank.name' => array('prefix' => '<b>Категория:</b> ',
                                                        'suffix' => ',',
                                   ),
                                   'qualification.name' => '<b>Квалификация:</b> ',
                                )
    	);
        echo $this->db->last_query();
    	$data['teacher2'] = Modules::run(	$this->MY_module.'/duc_teachers/MY_data_array',
    		 					//select
    		 					array('main.id', 'main.surname', 'main.name', 'main.name2', 'qualification.id', 'qualification.name', 'rank.name',
    		 					    //'qualification.description LEFT JOIN rank ON main.id_rank = rank.id LEFT JOIN qualification ON main.id_qualification = qualification.id'
                                     'join' => array(
                                                  'left' => array(
                                                            'qualification' => array(
                                                                               'qualification.id' => 'main.id_qualification'
                                                            ),
                                                            'rank' => array(
                                                                               'rank.id' => 'main.id_rank'
                                                            ),
                                                  ),
                                     )
    		 					),
    		 					//where
    		 					false,
    		 					//false,
    		 					false, //limit
    		 					''     //order

    	);

    	/*
    	$this->db->select('t.id, t.surname, t.name, t.name2, q.id as q_id, q2.name as q_name');
		$this->db->from('duc_teachers t');
		$this->db->from('duc_qualifications q');
		$this->db->join('duc_qualifications q2', 'q2.id = t.id_qualification', 'left');
		$query = $this->db->get();
        */


        echo '<pre>';
        var_dump($data['teacher']);
        //var_dump($query->result());

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
    	$data['groups'] = Modules::run(	$this->MY_module.'/'.$this->MY_table.'/MY_data_array',
    		 					//select
    		 					array('main.id', 'main.name', 'main.age_child','main.age_from','main.age_to', 'main.paid', 'main.free', 'main.school', 'main.programm', 'main.year_create', 'main.period', 'main.short_description', 'main.description', 'main.id_teacher', 'main.id_direction', 'main.id_department', 'main.id_activity',
    		 						 'teacher.id', 'teacher.surname', 'teacher.name', 'teacher.name2', 'teacher.id',
    		 					      'activity.name',
    		 					      'department.name',
                                      'related' => array(
                                      		'concertmaster' => array('id_group','id_teacher','id'),
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
