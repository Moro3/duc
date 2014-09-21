<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Подключаем наследуемый класс если он не подгужен
if( ! class_exists('duc')){
  include_once ('duc.php');
}

/*
 * Класс Duc_directions
 *
 */

class Duc_durations extends Duc {

    function __construct(){
        parent::__construct();
        $this->table = 'grid_duc_durations';
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

//====== изменяет высоту заголовка таблицы (чтобы видно было более 1 строки в ячейке)==========
        echo "<style>";
             echo '
               .ui-jqgrid .ui-jqgrid-htable th div#jqgh_grid_duc_durations_week_job{
               	   height: 40px;
               	   margin-top: -5px;
               }
             ';
        echo "</style>";
		//$this->load->view('grid/before_multiselect');

//-------------- Загрузка таблицы jQGrid
   		echo "\r\n<script>";
        	echo $this->grid->loader->render($this->table);
        echo "</script>\r\n";

		$this->load->view('grid/formatter/multiselect',
								array('selector' => 'multiselect',
                                      'sortable' => true,
									  'searchable' => true
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
									  			'titleText' => lang('duc_age')
									  		),
									  		'duration_job' => array(
									  			'numberOfColumns' => 2,
									  			'titleText' => lang('duc_duration')
									  		),
									  )
								)
		);

        //$this->load->view('grid/FCKeditor');
        $this->load->view('grid/list_position');

    }


    //возвращает список продолжительности занятий (мин)
    function listJobs(){
         for($i=5; $i<=400; $i= $i+5){
         	$res[$i] = $i;
         }
         return $res;
    }

    //возвращает список продолжительности перемены
    function listBreaks(){
         for($i=0; $i<=80; $i= $i+5){
         	$res[$i] = $i;
         }
         return $res;
    }

    //возвращает список кл-ва занятий в неделю
    function listWeekJobs(){
         //$res = array_merge(array(1=>1), range(2,10));
         //$res = array(1=>1)+ range(2,10);
         for($i=1; $i<=10; $i++){         	$res[$i] = $i;
         }
         return $res;
    }

}
