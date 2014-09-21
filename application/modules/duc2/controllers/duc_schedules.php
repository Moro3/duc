<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('duc')){
  include_once ('duc.php');
}

/*
 * Класс Duc_directions
 *
 */

class Duc_schedules extends Duc {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_duc_schedules';
    }

    function grid_render(){

        //$this->load->view('admin/tpl2/script');
        //$this->load->view('admin/tpl2/style');
        //$this->admin_menu_view();
        $this->grid_params();

        //print_r($this->grid->loader->getInputData());
//-------------- Подключаем нужный визуальный редактор (WYSIWYG)

		//$this->load->view('grid/load_FCKeditor');
		$this->load->view('grid/load_WYSIWYG', array('name' => 'FCKeditor'));


		$this->load->view('grid/before_multiselect');

//-------------- Загрузка таблицы jQGrid
   		echo "\r\n<script>";
        	echo $this->grid->loader->render($this->table);
        echo "</script>\r\n";

		$this->load->view('grid/formatter/multiselect');
        //$this->load->view('grid/FCKeditor');
        $this->load->view('grid/list_position');

        if($this->input->post('_search')){        	if($this->input->post('id_group') && $this->input->post('id_numgroup')){        		$this->load->view('grid/sorter/sortrows',
        				 array('grid' => $this->table,
        				 		'url' => '/grid/'.$this->MY_table.'/'.$this->MY_module.'/grid/'
        				 )
        		);
        	}
        }

    }


    /**
    * Шаблон вывода расписания занятий
    *
    */
    public function tpl_schedules_list(){    	$data['data'] = $this->MY_data(    		        //select
    		        array('main.id', 'main.id_group', 'main.active',
    		        		 //'mon_from', 'mon_to', 'tue_from', 'tue_to', 'wed_from', 'wed_to', 'thur_from', 'thur_to', 'fri_from', 'fri_to', 'sat_from', 'sat_to', 'sun_from', 'sun_to',
    		        		 'main.mon_from', 'main.mon_to', 'main.tue_from', 'main.tue_to', 'main.wed_from', 'main.wed_to', 'main.thur_from', 'main.thur_to', 'main.fri_from', 'main.fri_to', 'main.sat_from', 'main.sat_to', 'main.sun_from', 'main.sun_to',
    		        		 'main.id_numgroup',
                          'teacher.id', 'teacher.surname', 'teacher.name', 'teacher.name2',
                          'groups.id', 'groups.name'
    		        ),
    		        //where
    		        array(
    		        		'main.active' => 1,
    		        		'groups.id' => array('encode' => 'main.id_group'),
    		        		'groups.id_teacher' => array('encode' => 'teacher.id'),
    		        		'groups.active' => 1
    		        ),
    		        //limit
    		        false,
    		        //order
    		        array('teacher.surname', 'groups.name', 'main.id_numgroup')
    	);

    	//echo '<pre>';
    	//print_r($object);
    	//echo '</pre>';
    	$data['uri']['point'] = $this->uri_point('user');
        $data['uri']['groups_id'] = $this->get_uri_link('user_groups_id');
        $data['uri']['teachers_id'] = $this->get_uri_link('user_teachers_id');
        $data['uri']['schedules'] = $this->get_uri_link('user_sсhedules');
        $data['uri']['schedules_department'] = $this->get_uri_link('user_schedules_department');

        $data['config'] = $this->setting;

    	$this->load->view('user/schedules_list', $data);
    }

    /**
    * Шаблон вывода расписания занятий по группе
    *
    */
    public function tpl_schedules_group($id_group){
    	if(!is_numeric($id_group)) return false;
    	$data['data'] = $this->MY_data(
    		        //select
    		        array('main.id', 'main.id_group', 'main.active',
    		        		 //'mon_from', 'mon_to', 'tue_from', 'tue_to', 'wed_from', 'wed_to', 'thur_from', 'thur_to', 'fri_from', 'fri_to', 'sat_from', 'sat_to', 'sun_from', 'sun_to',
    		        		 'main.mon_from', 'main.mon_to', 'main.tue_from', 'main.tue_to', 'main.wed_from', 'main.wed_to', 'main.thur_from', 'main.thur_to', 'main.fri_from', 'main.fri_to', 'main.sat_from', 'main.sat_to', 'main.sun_from', 'main.sun_to',
    		        		 'main.id_numgroup',
                          'teacher.id', 'teacher.surname', 'teacher.name', 'teacher.name2',
                          'groups.id', 'groups.name'
    		        ),
    		        //where
    		        array(
    		        		'main.active' => 1,
    		        		'groups.id' => array('encode' => 'main.id_group'),
    		        		'groups.id_teacher' => array('encode' => 'teacher.id'),
    		        		'main.id_group' => $id_group,
    		        		'groups.active' => 1,
    		        		'teacher.active' => 1,
    		        ),
    		        //limit
    		        false,
    		        //order
    		        array('teacher.surname', 'groups.name', 'main.id_numgroup')
    	);
        $data['group'] = Modules::run('duc/duc_groups/MY_data_row',
		                   //select
		                   array('id', 'name', 'active', 'programm', 'age_from', 'age_to', 'period', 'sex', 'school', 'price',
		                         'id_direction', 'id_department', 'id_activity',
		                         'paid', 'free'
		                   ),
		                   //where
		                   array('id' => $id_group)
		);
    	//echo '<pre>';
    	//print_r($object);
    	//echo '</pre>';
    	$data['uri']['point'] =$this->uri_point('user');
        $data['uri']['groups_id'] = $this->get_uri_link('user_groups_id');
        $data['uri']['teachers_id'] = $this->get_uri_link('user_teachers_id');
        $data['uri']['schedules'] = $this->get_uri_link('user_sсhedules');
        $data['uri']['schedules_department'] = $this->get_uri_link('user_schedules_department');
        $data['config'] = $this->setting;

    	$this->load->view('user/schedules_group', $data);
    }

    /**
    * Шаблон вывода расписания занятий по педагогу
    *
    */
    public function tpl_schedules_teacher($id_teacher){
    	if(!is_numeric($id_teacher)) return false;
    	$data['data'] = $this->MY_data(
    		        //select
    		        array('main.id', 'main.id_group', 'main.active',
    		        		 //'mon_from', 'mon_to', 'tue_from', 'tue_to', 'wed_from', 'wed_to', 'thur_from', 'thur_to', 'fri_from', 'fri_to', 'sat_from', 'sat_to', 'sun_from', 'sun_to',
    		        		 'main.mon_from', 'main.mon_to', 'main.tue_from', 'main.tue_to', 'main.wed_from', 'main.wed_to', 'main.thur_from', 'main.thur_to', 'main.fri_from', 'main.fri_to', 'main.sat_from', 'main.sat_to', 'main.sun_from', 'main.sun_to',
    		        		 'main.id_numgroup',
                          'teacher.id', 'teacher.surname', 'teacher.name', 'teacher.name2',
                          'groups.id', 'groups.name'
    		        ),
    		        //where
    		        array(
    		        		'main.active' => 1,
    		        		'groups.id' => array('encode' => 'main.id_group'),
    		        		'groups.id_teacher' => array('encode' => 'teacher.id'),
    		        		'teacher.id' => $id_teacher,
    		        		'groups.active' => 1,
    		        		'teacher.active' => 1
    		        ),
    		        //limit
    		        false,
    		        //order
    		        array('teacher.surname', 'groups.name', 'main.id_numgroup')
    	);

		$data['teacher'] = Modules::run('duc/duc_teachers/MY_data_row',
		                   //select
		                   array('id', 'surname', 'name', 'name2', 'active', 'foto'),
		                   //where
		                   array('id' => $id_teacher)
		);
    	//echo '<pre>';
    	//print_r($object);
    	//echo '</pre>';
    	$data['uri']['point'] =$this->uri_point('user');
        $data['uri']['groups_id'] = $this->get_uri_link('user_groups_id');
        $data['uri']['teachers_id'] = $this->get_uri_link('user_teachers_id');
        $data['uri']['schedules'] = $this->get_uri_link('user_sсhedules');
        $data['uri']['schedules_department'] = $this->get_uri_link('user_schedules_department');
        $data['config'] = $this->setting;

    	$this->load->view('user/schedules_teacher', $data);
    }

    /**
    * Шаблон вывода расписания занятий по отделам
    *
    */
    public function tpl_schedules_department($id_department){
    	if(!is_numeric($id_department)) return false;
    	$data['data'] = $this->MY_data(
    		        //select
    		        array('main.id', 'main.id_group', 'main.active',
    		        		 //'mon_from', 'mon_to', 'tue_from', 'tue_to', 'wed_from', 'wed_to', 'thur_from', 'thur_to', 'fri_from', 'fri_to', 'sat_from', 'sat_to', 'sun_from', 'sun_to',
    		        		 'main.mon_from', 'main.mon_to', 'main.tue_from', 'main.tue_to', 'main.wed_from', 'main.wed_to', 'main.thur_from', 'main.thur_to', 'main.fri_from', 'main.fri_to', 'main.sat_from', 'main.sat_to', 'main.sun_from', 'main.sun_to',
    		        		 'main.id_numgroup',
                          'teacher.id', 'teacher.surname', 'teacher.name', 'teacher.name2',
                          'groups.id', 'groups.name',
                          'department.id', 'department.name'
    		        ),
    		        //where
    		        array(
    		        		'main.active' => 1,
    		        		'groups.id' => array('encode' => 'main.id_group'),
    		        		'groups.id_teacher' => array('encode' => 'teacher.id'),
    		        		'department.id' => $id_department,
    		        ),
    		        //limit
    		        false,
    		        //order
    		        array('teacher.surname', 'groups.name', 'main.id_numgroup')
    	);

    	//echo '<pre>';
    	//print_r($object);
    	//echo '</pre>';
    	$data['uri']['point'] =$this->uri_point('user');
        $data['uri']['groups_id'] = $this->get_uri_link('user_groups_id');
        $data['uri']['teachers_id'] = $this->get_uri_link('user_teachers_id');
        $data['uri']['schedules'] = $this->get_uri_link('user_sсhedules');
        $data['uri']['schedules_department'] = $this->get_uri_link('user_schedules_department');
        $data['config'] = $this->setting;

    	$this->load->view('user/schedules_list', $data);
    }

}
